<?php

namespace App\Filament\Resources\Holidays\Pages;

use App\Filament\Resources\Holidays\HolidayResource;
use App\Models\Holiday;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;
    protected string $view = 'filament.resources.holidays.pages.list-holidays';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncHolidays')
                ->label('Tarik Tanggal Merah ' . date('Y'))
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('info')
                ->action(function () {
                    try {
                        $year = date('Y');
                        $response = Http::timeout(10)->get("https://api-hari-libur.vercel.app/api?year={$year}");
                        if ($response->successful()) {
                            $data = $response->json('data');
                            $count = 0;
                            foreach ($data as $item) {
                                $created = Holiday::firstOrCreate(
                                    ['tanggal' => $item['date']],
                                    ['keterangan' => $item['description']]
                                );
                                if ($created->wasRecentlyCreated) {
                                    $count++;
                                }
                            }
                            
                            // Generate semua Hari Minggu di tahun tersebut
                            $startDate = Carbon::create($year, 1, 1);
                            $endDate = Carbon::create($year, 12, 31);
                            $sundayCount = 0;
                            
                            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                                if ($date->isSunday()) {
                                    $createdSunday = Holiday::firstOrCreate(
                                        ['tanggal' => $date->format('Y-m-d')],
                                        ['keterangan' => 'Libur Akhir Pekan (Minggu)']
                                    );
                                    if ($createdSunday->wasRecentlyCreated) {
                                        $sundayCount++;
                                    }
                                }
                            }
                            
                            Notification::make()
                                ->title('Sinkronisasi Sukses')
                                ->body("Berhasil menarik {$count} libur nasional & {$sundayCount} hari Minggu.")
                                ->success()
                                ->send();
                        } else {
                            throw new \Exception("Server kalender sibuk.");
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Sinkronisasi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make(),
        ];
    }
}
