<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        // Log le message en attendant la config email
        Log::info('Message de contact reçu', [
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // TODO: Envoyer par email quand le serveur est hébergé
        // Mail::to('contact@kaayjangalma.sn')->send(new ContactMail($request->all()));

        return back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les 24h.');
    }
}