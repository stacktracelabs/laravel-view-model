<?php


namespace StackTrace\ViewModel;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use function collect;

abstract class ViewModel implements Arrayable, \JsonSerializable
{
    /**
     * The format used for the properties.
     */
    protected static Format $defaultFormat = Format::CamelCase;

    /**
     * The property key format for this view model.
     */
    protected ?Format $format = null;

    /**
     * Format data to view.
     */
    public abstract function toView(): array;

    /**
     * Set the one format for json and view responses.
     */
    public static function alwaysFormatWith(?Format $format): void
    {
        static::$defaultFormat = $format;
    }

    /**
     * Set property key format for this view model.
     */
    public function formatWith(?Format $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return $this->prepareValue($this->toView(), $this->format ?: static::$defaultFormat);
    }

    public function toArray()
    {
        return $this->prepareValue($this->toView(), $this->format ?: static::$defaultFormat);
    }

    protected function formatKey(string $key, ?Format $case): string
    {
        return match ($case) {
            Format::CamelCase => Str::camel($key),
            Format::SnakeCase => Str::snake($key),
            default => $key,
        };
    }

    protected function prepareValue(mixed $value, ?Format $case): mixed
    {
        if (is_array($value)) {
            // Convert associative array
            if (Arr::isAssoc($value)) {
                return collect($value)
                    ->mapWithKeys(fn ($val, $key) => [$this->formatKey($key, $case) => $this->prepareValue($val, $case)])
                    ->all();
            } else {
                // Convert regular array
                return collect($value)->map(fn ($val) => $this->prepareValue($val, $case))->all();
            }
        } else if ($value instanceof Collection) {
            // Convert collection
            return $value->map(fn ($val) => $this->prepareValue($val, $case))->all();
        } else if ($value instanceof ViewModel) {
            // Convert another view model
            return $this->prepareValue($value->toView(), $case);
        }

        return $value;
    }

    public static function make(): static
    {
        return new static(...func_get_args());
    }

    public static function collect(array|Collection $items): Collection
    {
        return Collection::wrap($items)->mapInto(static::class)->values();
    }
}
