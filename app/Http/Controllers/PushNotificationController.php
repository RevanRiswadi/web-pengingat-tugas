<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use App\Services\PushNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    protected PushNotificationService $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function getPublicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => $this->pushService->getPublicKey(),
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'p256dh'   => 'required|string',
            'auth'     => 'required|string',
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'p256dh' => $validated['p256dh'],
                'auth'   => $validated['auth'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil terdaftar untuk notifikasi pengingat!',
            'data'    => $subscription,
        ]);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        PushSubscription::where('endpoint', $validated['endpoint'])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Langganan notifikasi berhasil dihapus.',
        ]);
    }

    public function sendTest(Request $request): JsonResponse
    {
        $endpoint = $request->input('endpoint');
        $targetSubs = null;

        if ($endpoint) {
            $targetSubs = PushSubscription::where('endpoint', $endpoint)->get();
        }

        $res = $this->pushService->sendNotification(
            '🔔 Tes Notifikasi TaskMind Berhasil!',
            'HP kamu sekarang sudah terhubung dengan pengingat tugas. Kamu akan menerima notifikasi otomatis bila tugas mendekati deadline.',
            '/tasks',
            $targetSubs
        );

        return response()->json($res);
    }

    public function sendReminders(Request $request)
    {
        $res = $this->pushService->sendDeadlineReminders();

        if ($request->wantsJson()) {
            return response()->json($res);
        }

        if ($res['sent'] > 0) {
            return redirect()->route('tasks.index')->with('success', "Notifikasi pengingat berhasil dikirim ke {$res['sent']} perangkat!");
        }

        $msg = $res['message'] ?? 'Tidak ada pengingat yang perlu dikirim saat ini.';
        return redirect()->route('tasks.index')->with('success', $msg);
    }
}
