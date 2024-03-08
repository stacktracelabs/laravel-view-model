<?php


namespace StackTrace\ViewModel;


class ArrayViewModel extends ViewModel
{
    public function __construct(
        protected array $value
    ) {}

    public function toView(): array
    {
        return $this->value;
    }
}
