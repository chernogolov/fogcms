<?php

namespace Chernogolov\Fogcms\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AddRecord extends Notification implements ShouldQueue
{
    use Queueable;

    public $reg_id;
    public $ticket_id;
    public $sender;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reg_id, $ticket_id, $sender = '')
    {
        //
        $this->reg_id = $reg_id;
        $this->ticket_id = $ticket_id;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->greeting('Новая заявка')
            ->from('support@sau1.ru', $this->sender)
            ->subject('Новая заявка #' .$this->ticket_id)
            ->line("Вы получили новую заявку #".$this->ticket_id)
            ->action('ПОСМОТРЕТЬ', route('view_record', ['id' => $this->reg_id, 'rid' => $this->ticket_id]))
            ->line('Это сообщение сформировано автоматически, пожалуйста не отвечайте на него.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
