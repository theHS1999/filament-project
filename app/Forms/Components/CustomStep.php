<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class CustomStep extends Component
{
    protected string $view = 'forms.components.custom-step';

    public static function make(): static
    {
        return new static();
    }
}
