<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreInvoiceResource\Pages;
use App\Models\PreInvoice;
use App\Models\Product;
use App\Models\ProjectRequest;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Tables\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class PreInvoiceResource extends Resource
{
    protected static ?string $model = PreInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-cash';

    protected static ?string $navigationGroup = 'Dashboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make()
                ->view('forms.components.custom-wizard')
                ->schema([
                    Step::make('general')
                        ->description('general data description')
                        ->visible(fn (PreInvoice $record = null) => (!empty($record) && $record->client_id == auth()->id()) ? false : true)
                        ->schema([
                            Group::make()->schema([
                                Select::make('client_id')
                                    ->label('Select Your Client')
                                    ->options(ProjectRequest::where('status', ProjectRequest::STATUS_PREINVOICE)->where('freelancer_id', auth()->id())->with('project')->get()->pluck('project.user.name', 'project.user.id'))
                                    ->required()
                                    ->searchable(),
                                TextInput::make('title')
                                ->required(),
                                Select::make('currency_unit')
                                    ->options([
                                        'dolloar' => '$',
                                        'euro' => '€',
                                    ])
                                    ->required(),
                                Radio::make('has_tax')
                                    ->label('')
                                    ->options([
                                        'true' => 'With Tax',
                                        'false' => 'Without Tax',
                                    ])
                                    ->default('true')
                                    ->required(),
                                DateTimePicker::make('start_date')->label('Start date/time')->withoutSeconds(),
                                DateTimePicker::make('end_date')->label('End date/time')->withoutSeconds(),
                                TextInput::make('fax')->tel(),
                                TextInput::make('tel')->tel(),
                                TextInput::make('address'),
                            ])->columns(2), // End Group
                    ]), // End Step
                    
                    Step::make('division')
                        ->view('forms.components.custom-step2')
                        ->description('division description')
                        ->schema([
                            Repeater::make('data')
                                ->label('Division / edit')
                                ->view('filament::components.custom-repeater')
                                ->schema([
                                    Group::make()->schema([
                                        TextInput::make('division_title')->required(),
                                    ])->columns(4),
                                    // 2 steps repeater
                                    Repeater::make('details')
                                        ->view('filament::components.custom-repeater')
                                        ->label('Section')
                                        ->schema([
                                            Group::make()->schema([
                                                TextInput::make('detail_title')
                                                ->label('title')
                                                ->required(),
                                            ])->columns(4),
                                            Section::make('Table Section')
                                            ->view('forms.components.section-custom')
                                            ->heading('Section / Edit')
                                            ->schema([
                                                // 3 steps repeater
                                                Repeater::make('table_details')
                                                    ->view('filament::components.custom-repeater')
                                                    ->label('')
                                                    ->schema([
                                                        TextInput::make('section_count')
                                                        ->numeric()
                                                        ->minValue(1),
                                                        TextInput::make('section_title'),
                                                        Select::make('section_unit')
                                                            ->options([
                                                                'hour' => 'Hour',
                                                                'package' => 'Package',
                                                            ]),
                                                        TextInput::make('section_unit_count')
                                                            ->numeric()
                                                            ->minValue(1),
                                                        TextInput::make('section_tax')
                                                            ->numeric()
                                                            ->placeholder('%')
                                                            ->minValue(1),
                                                        TextInput::make('section_profit')
                                                            ->numeric()
                                                            ->placeholder('%')
                                                            ->minValue(1),
                                                        TextInput::make('section_unit_price'),
                                                        Placeholder::make("total_row")
                                                        ->label("Total Row Without Tax")
                                                        ->content(function ($get, $set) {
                                                            $calculate = $get('unit_count') * $get('unit_price');
                                                            // $set('total_material_row', $calculate);
                                                            return $calculate;
                                                        }),
                                                        Placeholder::make("total_row_tax")
                                                        ->label("Total Row With Tax")
                                                        ->content(function ($get, $set) {
                                                            $calculate = ($get('unit_count') * $get('unit_price')) + intval($get('tax'));
                                                            $set('total_row_tax_hidden', $calculate);
                                                            return $calculate;
                                                        }),
                                                        Hidden::make('total_row_tax_hidden'),
                                                        Placeholder::make("total_row_profit")
                                                        ->label("Total Row Profit")
                                                        ->content(function ($get, $set) {
                                                            $calculate = (intval($get('total_row_tax_hidden')) * intval($get('profit'))) / 100;
                                                            $set('total_row_profit_hidden', $calculate);
                                                            return $calculate;
                                                        }),
                                                        Hidden::make('total_row_profit_hidden'),
                                                        Section::make('Select Product')
                                                        ->collapsible()
                                                        ->collapsed()
                                                        ->schema([
                                                            TableRepeater::make('products')
                                                            ->withoutHeader(true)
                                                            ->collapsible(true)
                                                            ->collapsed(true)
                                                            ->view('forms.components.calculator-table-repeater')
                                                            ->label('Products')
                                                            ->schema([
                                                                Select::make('product')
                                                                ->label('Search on Products')
                                                                ->allowHtml()
                                                                ->searchable()
                                                                ->getSearchResultsUsing(function (string $search) {
                                                                        $products = Product::where('title', 'like', "%{$search}%")->orWhere('ean', $search)->limit(50)->get();

                                                                        // $products = Product::search($search)->take(5)->get()->map(function ($product) {
                                                                        //     return [
                                                                        //         'id' => $product->id,
                                                                        //         'title' => $product->title,
                                                                        //         'url' => $product->url,
                                                                        //         'image' => $product->images[0]
                                                                        //     ];
                                                                        // });
                                                                    
                                                                        return $products->mapWithKeys(function ($product) {
                                                                            return [$product->getKey() => static::getCleanOptionString($product)];
                                                                        })->toArray();
                                                                })
                                                                ->getOptionLabelUsing(function ($value, $set): string {
                                                                    $product = Product::find($value);
                                                                    $set('product_title', $product->title);
                                                                    return static::getCleanOptionString($product);
                                                                }),
                                                                Hidden::make('product_title'),
                                                                TextInput::make('product_count')
                                                                ->placeholder('count')
                                                                ->label('')
                                                                ->numeric(),
                                                                TextInput::make('product_unit')
                                                                    ->label('')
                                                                    ->placeholder('unit')
                                                                    ->datalist([
                                                                        'kg',
                                                                        'g',
                                                                    ]),
                                                                TextInput::make('product_unit_count')
                                                                ->placeholder('unit count')
                                                                ->label('')
                                                                ->numeric(),
                                                                TextInput::make('product_vat')
                                                                    ->label('')
                                                                    ->placeholder('vat %')
                                                                    ->numeric(),
                                                                TextInput::make('product_price')
                                                                    ->label('')
                                                                    ->placeholder('€')
                                                                    ->numeric(),
                                                                    Placeholder::make("total_product_row")
                                                                    ->label("ex vat")
                                                                    ->content(function ($get, $set) {
                                                                        $calculate = $get('count') * $get('price');
                                                                        $set('total_product_row', $calculate);
                                                                        return $calculate;
                                                                    }),
                                                                    Hidden::make('total_product_row'),
                                                                    Placeholder::make("total_product_row_vat")
                                                                    ->label("inc vat")
                                                                    ->content(function ($get, $set) {
                                                                        $calculate = ($get('count') * $get('price')) + $get('vat');
                                                                        $set('total_product_row_vat', $calculate);
                                                                        return $calculate;
                                                                    }),
                                                                    Hidden::make('total_product_row_vat'),
                                                            ])->createItemButtonLabel('+')->collapsible(),
                                                            Placeholder::make("total_products")
                                                            ->view('forms.components.calculator-placeholder')
                                                            ->label("Total Products:")
                                                            ->content(function ($get, $set) {
                                                                $without_vat = collect($get('products'))
                                                                    ->pluck('total_product_row')
                                                                    ->sum();
                                                                $with_vat = collect($get('products'))
                                                                    ->pluck('total_product_row_vat')
                                                                    ->sum();
                                                                $set('total_products', $without_vat);
                                                                $set('total_products_vat', $with_vat);
                                                                return "ex vat " . $without_vat . " € <br> " . "inc vat " . $with_vat . " €";
                                                            }),
                                                            Hidden::make('total_products_vat'),
                                                            Hidden::make('total_products'),
                                                                    ]),
                                                    ])
                                                    ->createItemButtonLabel('Add Section Row')
                                                    ->columns(7)
                                                    // end repeater        
                                            ])->reactive(), // End Section
                                        ])
                                        ->createItemButtonLabel('Add Section')
                                        ->columns(1),
                                    // end repeater

                                    Group::make()->schema([
                                        Textarea::make('data_description')->rows(2),
                                    ])->columns(3),
                                    Section::make('total')->schema([
                                        Placeholder::make("total_details")
                                                            ->view('forms.components.calculator-placeholder')
                                                            ->label("Total Details:")
                                                            ->hint('removeBorder')
                                                            ->content(function ($get, $set) {
                                                                $without_vat = collect($get('products'))
                                                                    ->pluck('total_product_row')
                                                                    ->sum();
                                                                $with_vat = collect($get('products'))
                                                                    ->pluck('total_product_row_vat')
                                                                    ->sum();
                                                                $profits = collect($get('table_details'))
                                                                    ->pluck('total_row_profit_hidden')
                                                                    ->sum();
                                                                $set('total_products', $without_vat);
                                                                $set('total_products_vat', $with_vat);
                                                                return "ex vat " . $without_vat . " € <br> " . "inc vat " . $with_vat . " € <br> " . "Profits " . $profits . " €";
                                                            }),
                                                            Hidden::make('total_products'),
                                                            Hidden::make('total_products_vat'),
                                    ]),
                                ])
                                ->createItemButtonLabel('Add Division'), // End main Repeater
                        ]), // End Step

                        // Step::make('description')
                        //     ->description("file or description")
                        //     ->visible(fn (PreInvoice $record = null) => (!empty($record) && $record->client_id == auth()->id()) ? false : true)
                        //     ->schema([
                        //         Section::make('Pre Invoice File or Description (optional)')->schema([
                        //             SpatieMediaLibraryFileUpload::make('pre_invoice_file')->collection('pre_invoice_file')->columnSpan(1),
                        //             Textarea::make('description')->rows(2)->columnSpan(1),
            
                        //             Placeholder::make("total_all")
                        //                                     ->view('forms.components.calculator-placeholder')
                        //                                     ->label("Totals:")
                        //                                     ->content(function ($get, $set) {
                        //                                         $without_vat = collect($get('products'))
                        //                                             ->pluck('total_product_row')
                        //                                             ->sum();
                        //                                         $with_vat = collect($get('products'))
                        //                                             ->pluck('total_product_row_vat')
                        //                                             ->sum();
                        //                                         $set('total_products', $without_vat);
                        //                                         $set('total_products_vat', $with_vat);
                        //                                         return "ex vat " . $without_vat . " € <br> " . "inc vat " . $with_vat . " €";
                        //                                     }),
                        //                                     Hidden::make('total_products'),
                        //                                     Hidden::make('total_products_vat'),
                        //         ])->columns(3), // End Section
                        //     ]), // End Step
                        
                        // Step::make('project')
                        //     ->view('forms.components.custom-step')
                        //     ->description("complete this step after accept")
                        //     ->visible(fn (PreInvoice $record = null) => (!empty($record) && $record->client_id == auth()->id()) ? false : true)
                        //     ->schema([
                        //         Group::make()->schema([
                        //             TextInput::make('project_title'),
                        //             TextInput::make('project_budget'),
                        //             Select::make('project_size')
                        //                 ->options([
                        //                     'small' => 'Small',
                        //                     'medium' => 'Medium',
                        //                     'large' => 'Large',
                        //                 ]),
                        //             Radio::make('project_type')
                        //                 ->inline()
                        //                 ->options([
                        //                     'governmental' => 'Governmental',
                        //                     'corporate' => 'Corporate',
                        //                     'private' => 'Private'
                        //                 ]),
                        //             SpatieMediaLibraryFileUpload::make('main_project_image')->collection('main_project_image')->columnSpan(1),
                        //             SpatieMediaLibraryFileUpload::make('document_project_images')
                        //                 ->collection('document_project_images')
                        //                 ->multiple()
                        //                 ->columnSpan(1),
                        //             DateTimePicker::make('project_start_date')->label('Start date/time')->withoutSeconds(),
                        //             DateTimePicker::make('project_end_date')->label('Start date/time')->withoutSeconds(),
                        //         ])->columns(2), // End Group
                        //     ]), // End Step

                ]), // End Wizard Steps
            ])->columns(1); // End Schema
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('description'),
                TextColumn::make('start_date')->since(),
                TextColumn::make('end_date')->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Export PDF')
                ->label('')
                ->size('lg')
                ->icon('heroicon-o-printer')
                ->url(fn (PreInvoice $record): string => route('preinvoice.export.pdf', $record->id)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreInvoices::route('/'),
            'create' => Pages\CreatePreInvoice::route('/create'),
            'edit' => Pages\EditPreInvoice::route('/{record}/edit'),
        ];
    }
    
    public static function getCleanOptionString(Model $model): string
    {
        return \Stevebauman\Purify\Facades\Purify::clean(
                view('filament::components.select-product-result')
                    ->with('title', $model?->title)
                    ->with('price', $model?->price)
                    ->with('image', $model?->images[0])
                    ->render()
        );
    }
}
