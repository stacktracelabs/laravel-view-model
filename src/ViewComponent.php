<?php


namespace StackTrace\ViewModel;


use Illuminate\Support\Arr;

class ViewComponent extends ViewModel
{
    protected ?Format $format = null;

    public function __construct(
        protected string $name,
        protected array $props = []
    ) { }

    /**
     * Set component props.
     */
    public function with(string|array $key, mixed $value = null): static
    {
        if (is_array($key)) {
            $this->props = array_merge($this->props, $key);
        } else {
            Arr::set($this->props, $key, $value);
        }

        return $this;
    }

    /**
     * Retrieve value of the property.
     */
    public function prop(string $name, mixed $default = null): mixed
    {
        return Arr::get($this->props, $name, $default);
    }

    /**
     * Retrieve all component properties.
     */
    public function getProps(): array
    {
        return $this->props;
    }

    public function toView(): array
    {
        return [
            'name' => $this->name,
            'props' => (object) $this->props,
        ];
    }
}
