<?php

namespace App\Notifications;

use App\Models\Merchant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\DatabaseManager;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MerchantRegisterNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $merchant;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($merchant)
    {

        $merchant_data  = (object) [
            "id"            => $merchant->id,
            "m_id"          => $merchant->m_id,
            "company_name"  => $merchant->company_name,
            "contact_number"=> $merchant->contact_number,
            "date"          => $merchant->date,
            "created_at"    => $merchant->created_at
        ];

        $this->merchant = $merchant_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

//{"merchant":
//    {
//        "id":273,
//        "m_id":"M-0253",
//        "company_name":"Childhood &Happiness",
//        "contact_number":"01687007530",
//        "date":"2021-07-18",
//        "created_at":"2021-07-18T07:30:32.000000Z"
//    },
// "admin":
//    {
//        "id":3,
//        "name":"Accounts",
//        "contact_number":"01312210335",
//        "email":"sadmanaccount@beacon.com",
//        "type":3
//    }
//}

    public function toDatabase($notifiable): array
    {
        $admin_data  = (object) [
            "id"            => $notifiable->id,
            "name"          => $notifiable->name,
            "contact_number"=> $notifiable->contact_number,
            "email"         => $notifiable->email,
            "type"          => $notifiable->type
        ];

        return [
            'merchant'  => $this->merchant,
            'admin'     => $admin_data
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'merchant'  => $this->merchant,
            'admin'     => $notifiable
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
//    public function toArray($notifiable)
//    {
//        return [
//            //
//        ];
//    }

}
