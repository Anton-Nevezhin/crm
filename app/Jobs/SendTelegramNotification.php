<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendTelegramNotification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $chatId;
    protected $token;

    public function __construct($message, $chatId)
    {
        $this->message = $message;
        $this->chatId = $chatId;
        $this->token = env('TELEGRAM_BOT_TOKEN');
    }

    public function handle(): void
    {
        Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $this->message,
            'parse_mode' => 'HTML',
        ]);
    }
}