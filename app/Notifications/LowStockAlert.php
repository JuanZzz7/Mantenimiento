<?php

namespace App\Notifications;

use App\Models\Spare;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    public function __construct(public Spare $spare)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('⚠️ Alerta de Stock Bajo: ' . $this->spare->name)
            ->line("El repuesto \"{$this->spare->name}\" ({$this->spare->code}) ha alcanzado un nivel de stock crítico.")
            ->line("Stock Actual: {$this->spare->stock}")
            ->line("Stock Mínimo: {$this->spare->stock_min}")
            ->action('Gestionar Inventario', route('spares.index', ['search' => $this->spare->code]))
            ->line('Por favor, realice la reposición de este activo lo antes posible.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'spare_id'  => $this->spare->id,
            'code'      => $this->spare->code,
            'message'   => "⚠️ Stock bajo: {$this->spare->name} ({$this->spare->stock} restantes)",
            'type'      => 'stock_alert',
        ];
    }
}
