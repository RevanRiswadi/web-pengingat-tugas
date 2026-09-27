<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushNotificationService
{
    protected ?WebPush $webPush = null;

    public function __construct()
    {
        $publicKey = env('VAPID_PUBLIC_KEY');
        $privateKey = env('VAPID_PRIVATE_KEY');
        $subject = env('VAPID_SUBJECT', 'mailto:admin@taskmind.app');

        if ($publicKey && $privateKey) {
            $this->webPush = new WebPush([
                'VAPID' => [
                    'subject' => $subject,
                    'publicKey' => $publicKey,
                    'privateKey' => $privateKey,
                ],
            ]);
        }
    }

    public function getPublicKey(): ?string
    {
        return env('VAPID_PUBLIC_KEY');
    }

    /**
     * Send a notification payload to one or all subscribers.
     */
    public function sendNotification(string $title, string $body, string $url = '/tasks', $targetSubscriptions = null): array
    {
        if (!$this->webPush) {
            return ['success' => false, 'sent' => 0, 'message' => 'VAPID keys not configured'];
        }

        $subscriptions = $targetSubscriptions ?: PushSubscription::all();

        if ($subscriptions->isEmpty()) {
            return ['success' => false, 'sent' => 0, 'message' => 'Belum ada perangkat terdaftar untuk notifikasi push.'];
        }

        $payload = json_encode([
            'title' => $title,
            'body'  => $body,
            'url'   => url($url),
            'tag'   => 'taskmind-' . time(),
        ]);

        foreach ($subscriptions as $sub) {
            $subscriptionObj = Subscription::create([
                'endpoint'  => $sub->endpoint,
                'publicKey' => $sub->p256dh,
                'authToken' => $sub->auth,
            ]);

            $this->webPush->queueNotification($subscriptionObj, $payload);
        }

        $sentCount = 0;
        $failedCount = 0;
        $expiredEndpoints = [];

        foreach ($this->webPush->flush() as $report) {
            $endpoint = (string) $report->getEndpoint();
            if ($report->isSuccess()) {
                $sentCount++;
            } else {
                $failedCount++;
                Log::warning("Push notification failed for {$endpoint}: " . $report->getReason());
                if ($report->isSubscriptionExpired()) {
                    $expiredEndpoints[] = $endpoint;
                }
            }
        }

        if (!empty($expiredEndpoints)) {
            PushSubscription::whereIn('endpoint', $expiredEndpoints)->delete();
        }

        return [
            'success' => true,
            'sent'    => $sentCount,
            'failed'  => $failedCount,
            'total'   => $subscriptions->count(),
        ];
    }

    /**
     * Scan tasks and send reminders for overdue tasks or tasks approaching deadline.
     */
    public function sendDeadlineReminders(): array
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $twoDaysLater = Carbon::today()->addDays(2);

        $pendingTasks = Task::where(function ($query) {
            $query->whereNull('status')
                  ->orWhere('status', '!=', 'selesai');
        })
        ->orderBy('tenggat_waktu', 'asc')
        ->get();

        $urgentTasks = [];
        $overdueTasks = [];

        foreach ($pendingTasks as $task) {
            $deadline = Carbon::parse($task->tenggat_waktu)->startOfDay();

            if ($deadline->lt($today)) {
                $overdueTasks[] = $task;
            } elseif ($deadline->lte($twoDaysLater)) {
                $urgentTasks[] = $task;
            }
        }

        $allNotifTasks = array_merge($overdueTasks, $urgentTasks);

        if (empty($allNotifTasks)) {
            return [
                'success' => true,
                'sent'    => 0,
                'message' => 'Tidak ada tugas yang menumpuk atau mepet tenggat waktunya saat ini.',
            ];
        }

        $count = count($allNotifTasks);
        $first = $allNotifTasks[0];
        $firstDeadline = Carbon::parse($first->tenggat_waktu)->startOfDay();

        if ($firstDeadline->lt($today)) {
            $statusText = "sudah lewat tenggat!";
        } elseif ($firstDeadline->eq($today)) {
            $statusText = "deadline HARI INI!";
        } else {
            $statusText = "deadline BESOK!";
        }

        $title = "⏰ Pengingat Tugas: {$count} Tugas Perlu Diselesaikan!";
        $body = "{$first->mata_pelajaran} - {$first->judul_tugas} ({$statusText})";

        if ($count > 1) {
            $body .= " dan " . ($count - 1) . " tugas lainnya menunggu diselesaikan.";
        }

        $result = $this->sendNotification($title, $body, '/tasks');
        $result['task_count'] = $count;
        $result['urgent_count'] = count($urgentTasks);
        $result['overdue_count'] = count($overdueTasks);

        return $result;
    }
}
