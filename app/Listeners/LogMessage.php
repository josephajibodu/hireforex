<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogMessage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSending|MessageSent $event): void
    {
        $message = $event->message;
        $eventType = $event instanceof MessageSending ? 'sending' : 'sent';

        Log::info("Email {$eventType}:", [
            'subject' => $message->getSubject(),
            'to' => array_map(fn($to) => $to->getAddress(), array_values($message->getTo() ?? [])),
            'cc' => array_map(fn($cc) => $cc->getAddress(), array_values($message->getCc() ?? [])),
            'bcc' => array_map(fn($bcc) => $bcc->getAddress(), array_values($message->getBcc() ?? [])),
            'headers' => $message->getHeaders()->toArray(),
        ]);
    }
}
