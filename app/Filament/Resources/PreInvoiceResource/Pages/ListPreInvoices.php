<?php

namespace App\Filament\Resources\PreInvoiceResource\Pages;

use App\Filament\Resources\PreInvoiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreInvoices extends ListRecords
{
    protected static string $resource = PreInvoiceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
