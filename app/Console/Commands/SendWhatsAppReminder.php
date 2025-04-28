<?php

namespace App\Console\Commands;

use App\Models\PregnantCheckD;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Services\WhatsAppService;
use App\Models\InseminationNatural;
use App\Models\InseminationArtificial;
use App\Models\TreatmentScheduleIndividu;

class SendWhatsAppReminder extends Command
{
    protected $whatsAppService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-whats-app-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send WhatsApp reminders 30 days, 7 days, 3 days, and 1 day before schedule_date';

    public function __construct(WhatsAppService $whatsAppService)
    {
        parent::__construct();
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $datesToCheck = [
            Carbon::now()->addDays(30)->toDateString(),
            Carbon::now()->addDays(7)->toDateString(),
            Carbon::now()->addDays(3)->toDateString(),
            Carbon::now()->addDay()->toDateString(),
        ];

        $treatmentScheduleIndividu = TreatmentScheduleIndividu::whereIn('schedule_date', $datesToCheck)->get();

        foreach ($treatmentScheduleIndividu as $item) {
            $message = "Pengingat: Perawatan '{$item->treatment_name}' untuk ternak dengan nomor eartag : '{$item->livestock->eartag_number}', dijadwalkan pada tanggal {$item->schedule_date}. Mohon persiapkan dengan baik.";

            $this->whatsAppService->sendMessage($item->livestock->farm->owner->phone_number, $message);
        }

        $inseminationNatural = InseminationNatural::whereIn('cycle_date', $datesToCheck)->get();

        foreach ($inseminationNatural as $item) {
            $message = "Pengingat: Estimasi oestrus untuk ternak dengan nomor eartag: '{$item->reproductionCycle->livestock->eartag_number}' diperkirakan akan terjadi pada tanggal {$item->cycle_date}. Mohon persiapkan langkah-langkah yang diperlukan.";

            $this->whatsAppService->sendMessage($item->reproductionCycle->livestock->farm->owner->phone_number, $message);
        }

        $inseminationArtificial = InseminationArtificial::whereIn('cycle_date', $datesToCheck)->get();

        foreach ($inseminationArtificial as $item) {
            $message = "Pengingat: Estimasi oestrus untuk ternak dengan nomor eartag: '{$item->reproductionCycle->livestock->eartag_number}' diperkirakan akan terjadi pada tanggal {$item->cycle_date}. Mohon persiapkan langkah-langkah yang diperlukan.";

            $this->whatsAppService->sendMessage($item->reproductionCycle->livestock->farm->owner->phone_number, $message);
        }

        $pregnantCheck = PregnantCheckD::whereIn('estimated_birth_date', $datesToCheck)->get();

        foreach ($pregnantCheck as $item) {
            $message = "Pengingat: Ternak dengan nomor eartag: '{$item->reproductionCycle->livestock->eartag_number}' diperkirakan akan melahirkan pada tanggal {$item->estimated_birth_date}. Mohon persiapkan kebutuhan dan lingkungan yang sesuai untuk proses kelahiran.";

            $this->whatsAppService->sendMessage($item->reproductionCycle->livestock->farm->owner->phone_number, $message);
        }
    }
}
