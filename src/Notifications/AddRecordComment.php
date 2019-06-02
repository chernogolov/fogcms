<?php

namespace Chernogolov\Fogcms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddRecordComment extends Notification implements ShouldQueue
{
    use Queueable;

    public $reg_id;
    public $ticket;
    public $sender;
    public $channels = ['database'];

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket, $sender = '', $channels = [])
    {
        //
        $this->ticket = $ticket;
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
            ->subject('Новое сообщение в заявке #' .$this->ticket->Number)
            ->line("Вы получили ответ на обращение #".$this->ticket->Number)
            ->action('ПОСМОТРЕТЬ', route('view-ticket', ['rid' => $this->ticket->id]))
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
            'theme' => 'Новый ответ на обращение №' . $this->ticket->Number,
            'message' => "Вы получили ответ на обращение.",
            'action' => route('view-ticket', ['rid' => $this->ticket->id]),
        ];
    }
}
