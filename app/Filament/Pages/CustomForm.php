<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class CustomForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.custom-form';

    protected ?string $heading = 'Form';

    protected static ?string $title = 'Form';


    public function form(Form $form): Form 
    {
        // REQIRED FORM ELEMENTS: REPEATER, GROUP
        return $form->schema([
            Repeater::make("inputs")->schema([
                TextInput::make("name")->placeholder("عنوان")->required(),
                TextInput::make("")->placeholder("تعداد")->required()->numeric(),
                Select::make("")->placeholder("محصول")->options(["انتخاب اول", "انتخاب دوم", "انتخاب سوم"]),
                TextInput::make("")->placeholder("عدد")->required()->numeric(),
                TextInput::make("")->placeholder("عدد")->required()->numeric()->prefix("%")->minValue(1)->maxValue(100),
                TextInput::make("")->placeholder("عدد")->required()->numeric()->prefix("%")->minValue(1)->maxValue(100),
                TextInput::make("")->placeholder("عدد")->required()->numeric()->prefix("%")->minValue(1)->maxValue(100),
                TextInput::make("")->placeholder("عدد")->required()->numeric()->prefix("%")->minValue(1)->maxValue(100),
            ]),
        ]);
    }
}
