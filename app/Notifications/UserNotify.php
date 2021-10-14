<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotify extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($item)
    {
        $this->title = $item['title'];
        $this->message = $item['message'];
        $this->route = $item['route'];
        $this->op = $item['op'];
        $this->module = $item['module'];
        $this->id_op =  $item['id_op'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'id' => $this->id,
            'title' => $this->title ,
            'message' => $this->message,
            'route' => $this->route,
            'op' => $this->op,
            'module' => $this->module,
            'id_op' => $this->id_op,
        ];
    }

    public static function read($module, $id_op){
        foreach (auth()->user()->unreadNotifications as $notify){
            $data = $notify->data;
            if($data['module'] == $module && $data['id_op'] == $id_op){
                $notify->markAsRead();
                return null;
            }
        }
    }



}
