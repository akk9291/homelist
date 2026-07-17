<?php

namespace App\Filament\Resources\CustomFieldGroupResource\Pages;

use App\Filament\Resources\CustomFieldGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomFieldGroups extends ListRecords
{
    protected static string $resource = CustomFieldGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
