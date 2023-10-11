<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Component;

class SectionCustom extends Component
{
    protected string $view = 'forms.components.section-custom';

    public static function make(): static
    {
        return new static();
    }
}
