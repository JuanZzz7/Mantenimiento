<?php

namespace App\Notifications;

use App\Models\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkOrderAssigned extends Notification
{
    use Queueable;

    public function __construct(public WorkOrder $workOrder) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nueva Orden Asignada: {$this->workOrder->code}")
            ->greeting("Hola, {$notifiable->name}!")
            ->line("Se te ha asignado la orden de trabajo **{$this->workOrder->code}**.")
            ->line("**Activo:** {$this->workOrder->asset->name}")
            ->line("**Prioridad:** {$this->workOrder->priority_label}")
            ->line("**Descripción:** {$this->workOrder->description}")
            ->action('Ver Orden', url("/work-orders/{$this->workOrder->id}"))
            ->line('Por favor atiende esta orden a la brevedad posible.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'work_order_id'   => $this->workOrder->id,
            'work_order_code' => $this->workOrder->code,
            'asset_name'      => $this->workOrder->asset->name ?? '',
            'priority'        => $this->workOrder->priority,
            'message'         => "Nueva orden {$this->workOrder->code} asignada",
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
