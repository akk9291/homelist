<?php

namespace App\Filament\Resources\CustomFieldResource\Pages;

use App\Filament\Resources\CustomFieldResource;
use App\Services\CustomFieldService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;

class ListCustomFields extends ListRecords
{
    protected static string $resource = CustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // JSON Export Action
            Actions\Action::make('export_json')
                ->label('Export JSON')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (CustomFieldService $service) {
                    $json = $service->exportToJson();
                    return response()->streamDownload(
                        fn () => print($json),
                        'custom_fields_' . date('Ymd_His') . '.json',
                        ['Content-Type' => 'application/json']
                    );
                }),

            // CSV Export Action
            Actions\Action::make('export_csv')
                ->label('Export CSV')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (CustomFieldService $service) {
                    $csv = $service->exportToCsv();
                    return response()->streamDownload(
                        fn () => print($csv),
                        'custom_fields_' . date('Ymd_His') . '.csv',
                        ['Content-Type' => 'text/csv']
                    );
                }),

            // JSON Import Action
            Actions\Action::make('import_json')
                ->label('Import JSON')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Choose JSON File')
                        ->required()
                        ->disk('public')
                        ->visibility('public'),
                ])
                ->action(function (array $data, CustomFieldService $service) {
                    try {
                        $json = Storage::disk('public')->get($data['file']);
                        $count = $service->importFromJson($json);
                        
                        // Clean up uploaded file
                        Storage::disk('public')->delete($data['file']);

                        Notification::make()
                            ->title('Import Completed')
                            ->body("Successfully imported {$count} custom fields and groups from JSON.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // CSV Import Action
            Actions\Action::make('import_csv')
                ->label('Import CSV')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('Choose CSV File')
                        ->required()
                        ->disk('public')
                        ->visibility('public'),
                ])
                ->action(function (array $data, CustomFieldService $service) {
                    try {
                        $csv = Storage::disk('public')->get($data['file']);
                        $count = $service->importFromCsv($csv);

                        // Clean up uploaded file
                        Storage::disk('public')->delete($data['file']);

                        Notification::make()
                            ->title('Import Completed')
                            ->body("Successfully imported {$count} custom fields and groups from CSV.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import Failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
