<?php

use StackTrace\ViewModel\ArrayViewModel;
use StackTrace\ViewModel\Format;
use StackTrace\ViewModel\ViewModel;

function model(array $model): ViewModel {
    return ArrayViewModel::make($model);
}

it('should render array view moddel', function () {
    $model = model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]);

    expect($model->toView())->toMatchArray([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]);
});

it('should convert to camel case by default', function () {
    expect(model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]))->toArray()->toMatchArray([
        'firstName' => 'Peter',
        'lastName' => 'Stovka',
    ]);
});

it('should convert to snake case', function () {
    expect(model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ])->formatWith(Format::SnakeCase))->toArray()->toMatchArray([
        'first_name' => 'Peter',
        'last_name' => 'Stovka',
    ]);
});

it('should preserve case', function () {
    expect(model([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ])->formatWith(Format::Preserve))->toArray()->toMatchArray([
        'first_name' => 'Peter',
        'lastName' => 'Stovka',
    ]);
});
