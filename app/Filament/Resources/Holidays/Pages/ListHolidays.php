<?php

namespace App\Filament\Resources\Holidays\Pages;

use App\Filament\Resources\Holidays\HolidayResource;
use App\Models\Holiday;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;
    protected string $view = 'filament.resources.holidays.pages.list-holidays';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncHolidays')
                ->label('Tarik Hari Minggu ' . date('Y'))
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('info')
                ->action(function () {
                    try {
                        $year = date('Y');

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
                            ->body("Berhasil menambahkan {$sundayCount} hari Minggu pada tahun {$year}.")
                            ->success()
                            ->send();
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

    public function deleteHoliday(int $holidayId): void
    {
        $holiday = Holiday::query()->find($holidayId);

        if (!$holiday) {
            Notification::make()
                ->title('Data tidak ditemukan')
                ->danger()
                ->send();
            return;
        }

        $holiday->delete();

        Notification::make()
            ->title('Data libur dihapus')
            ->success()
            ->send();
    }
}
