<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class CustomStep2 extends Component
{
    protected string $view = 'forms.components.custom-step2';

    public static function make(): static
    {
        return new static();
    }
}
