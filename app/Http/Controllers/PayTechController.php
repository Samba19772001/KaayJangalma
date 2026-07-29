<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayTechController extends Controller
{
    // ─── IPN (notification instantanée de PayTech) ────────────────
    public function ipn(Request $request)
    {
        Log::info('PayTech IPN reçu', $request->all());

        // Vérifier la signature
        $apiKey    = config('services.paytech.api_key');
        $apiSecret = config('services.paytech.api_secret');

        $token = hash('sha256', $apiKey . $apiSecret);

        if ($request->token !== $token) {
            Log::warning('PayTech IPN : token invalide');
            return response('Token invalide', 403);
        }

        // Récupérer les données
        $refCommand  = $request->ref_command;
        $customField = json_decode($request->custom_field, true);

        // Trouver l'abonnement via ref_command
        $subscription = Subscription::where('payment_reference', $refCommand)->first();

        if (!$subscription) {
            Log::warning('PayTech IPN : abonnement introuvable', ['ref' => $refCommand]);
            return response('OK', 200);
        }

        // Activer l'abonnement
        $subscription->update([
            'status'               => 'active',
            'payment_confirmed_at' => now(),
            'payment_note'         => 'Paiement confirmé via PayTech',
        ]);

        // Activer le premium du professeur
        $subscription->teacher->update(['is_premium' => true]);

        // Notification au professeur
        Notification::create([
            'user_id' => $subscription->teacher->user_id,
            'type'    => 'payment_confirmed',
            'data'    => json_encode([
                'message' => 'Votre paiement a été confirmé ! Abonnement Premium activé.',
                'plan'    => $subscription->plan,
                'ends_at' => $subscription->ends_at->format('d/m/Y'),
            ]),
        ]);

        return response('OK', 200);
    }

    // ─── Succès ───────────────────────────────────────────────────
    public function success(Request $request)
    {
        return redirect()->route('teacher.subscription')
                         ->with('success', 'Paiement effectué avec succès ! Votre abonnement Premium sera activé dans quelques instants.');
    }

    // ─── Annulation ───────────────────────────────────────────────
    public function cancel(Request $request)
    {
        return redirect()->route('teacher.subscription')
                         ->with('error', 'Paiement annulé. Vous pouvez réessayer à tout moment.');
    }
}