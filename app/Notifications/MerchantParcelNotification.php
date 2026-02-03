<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MerchantParcelNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $parcel_info;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($parcel_info)
    {
        $parcel_status = returnParcelStatusNameForMerchant($parcel_info->status, $parcel_info->delivery_type, $parcel_info->payment_type);
        $status_name = $parcel_status['status_name'];
        $status_class = $parcel_status['class'];

        $parcel_data = (object) [
            'id'                => $parcel_info->id,
            'parcel_invoice'    => $parcel_info->parcel_invoice,
            'status_name'       => $status_name,
            'status_class'      => $status_class,
            'action_date'      => $parcel_info->updated_at,
        ];
        $this->parcel_info = $parcel_data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'parcel_info' => $this->parcel_info,
            'merchant'    => $notifiable
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */

//{"parcel_info": {
//        "id":3324,
//        "parcel_invoice":"20210716-03321",
//        "status_name":"Assign for Delivery",
//        "status_class":"success",
//        "action_date":"2021-07-17T05:05:55.000000Z"
//    },
//"merchant":{
//        "id":196,
//        "m_id":"M-0185",
//        "company_name":"Wholesale Bazaar",
//        "contact_number":"01742512211",
//        "date":"2021-07-03",
//        "created_at":"2021-06-24T08:01:52.000000Z"
//    }
//}
    public function toArray($notifiable)
    {

        $merchant_data  = (object) [
            'id'                => $notifiable->id,
            'm_id'              => $notifiable->m_id,
            'company_name'      => $notifiable->company_name,
            'contact_number'    => $notifiable->contact_number,
            'date'              => $notifiable->date,
            'created_at'        => $notifiable->created_at,
        ];

        return [
            'parcel_info'   => $this->parcel_info,
            'merchant'      => $merchant_data
        ];
    }
}
