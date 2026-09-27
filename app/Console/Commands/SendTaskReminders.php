<?php

namespace App\Console\Commands;

use App\Services\PushNotificationService;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi push otomatis untuk tugas yang belum selesai atau mepet deadline';

    /**
     * Execute the console command.
     */
    public function handle(PushNotificationService $pushService): int
    {
        $this->info('Memeriksa tugas yang mendekati deadline...');

        $res = $pushService->sendDeadlineReminders();

        if ($res['success']) {
            $sent = $res['sent'] ?? 0;
            $this->info("Selesai! {$sent} notifikasi berhasil terkirim.");
            if (isset($res['message'])) {
                $this->line($res['message']);
            }
            return Command::SUCCESS;
        }

        $this->error('Gagal mengirim notifikasi: ' . ($res['message'] ?? 'Unknown error'));
        return Command::FAILURE;
    }
}
