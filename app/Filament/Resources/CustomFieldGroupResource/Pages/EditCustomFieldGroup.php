<?php

namespace App\Filament\Resources\CustomFieldGroupResource\Pages;

use App\Filament\Resources\CustomFieldGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomFieldGroup extends EditRecord
{
    protected static string $resource = CustomFieldGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
