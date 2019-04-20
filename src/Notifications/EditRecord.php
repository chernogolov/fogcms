<?php

namespace Chernogolov\Fogcms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EditRecord extends Notification implements ShouldQueue
{
    use Queueable;

    public $reg_id;
    public $ticket_id;
    public $sender;
    public $channels = ['database'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket_id, $sender = '', $channels = [])
    {
        //
        $this->ticket_id = $ticket_id;
        $this->sender = $sender;
        foreach($channels as $channel)
            in_array($channel, ['mail']) ? $this->channels[] = $channel : null;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Уведомление')
            ->from('noreply@2gkh.ru', $this->sender)
            ->subject('Статус обращения #' .$this->ticket_id . ' поменялся')
            ->line("Статус обращения #".$this->ticket_id . ' поменялся')
            ->action('ПОСМОТРЕТЬ', route('view-ticket', ['rid' => $this->ticket_id]))
            ->line('Это сообщение сформировано автоматически, пожалуйста не отвечайте на него.');
    }

    /**
     * Получить представление уведомления в виде массива.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'theme' => 'Обращение №' . $this->ticket_id,
            'message' => "Статус обращения поменялся.",
            'action' => route('view-ticket', ['rid' => $this->ticket_id]),
        ];
    }
}
