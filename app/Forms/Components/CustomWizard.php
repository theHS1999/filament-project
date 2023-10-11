<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class CustomWizard extends Component
{
    protected string $view = 'forms.components.custom-wizard';

    public static function make(): static
    {
        return new static();
    }
}
