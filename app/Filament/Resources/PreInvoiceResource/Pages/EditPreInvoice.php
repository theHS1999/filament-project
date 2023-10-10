<?php

namespace App\Filament\Resources\PreInvoiceResource\Pages;

use App\Filament\Resources\PreInvoiceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreInvoice extends EditRecord
{
    protected static string $resource = PreInvoiceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
