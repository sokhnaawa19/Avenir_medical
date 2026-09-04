<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create($request->validated());

        $this->notifyTeam($message);

        return redirect()
            ->route('contact')
            ->with('success', 'Merci ! Votre message a bien été envoyé, nous vous répondrons très vite.');
    }

    private function notifyTeam(ContactMessage $message): void
    {
        $recipient = (string) setting('email');

        if (blank($recipient)) {
            return;
        }

        try {
            $body = "Nouveau message depuis le site\n\n"
                ."Nom : {$message->name}\n"
                ."Téléphone : {$message->phone}\n"
                ."Email : {$message->email}\n\n"
                .$message->message;

            Mail::raw($body, function ($mail) use ($recipient): void {
                $mail->to($recipient)->subject('Nouveau message depuis le site');
            });
        } catch (\Throwable $exception) {
            Log::warning('Impossible d’envoyer l’email de contact : '.$exception->getMessage());
        }
    }
}
