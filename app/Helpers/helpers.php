<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;


if (!function_exists('send_bl_sms')) {
    function send_bl_sms($phone, $message)
    {
        $msisdn=trim($phone);
        $n = strlen($msisdn);
        if ($n==11){
            $msisdn = '88' . $msisdn;
        }


        //  $csms_id = time(); // Example: Use timestamp as a unique ID
        // $apiToken = 'iumyif5b-qskltfmm-lvc9l2ay-eojioy0k-5x9ju2xa';
        // $sid = 'SAFEDELIVERYNON'; // Replace with the actual SID

       
        //     $response = Http::asForm()->post('https://smsplus.sslwireless.com/api/v3/send-sms', [
        //         'api_token' => $apiToken,
        //         'sid' => $sid,
        //         'sms' => $message,
        //         'msisdn' => $msisdn,
        //         'csms_id' => $csms_id
        //     ]);

        // return $response;
        
         $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . '408|KuVuzCpqy3D0gbO2vWa7gRVhXSyo3EIAbtqLhX95',
        ])->post("https://login.esms.com.bd/api/v3/sms/send", [
            'recipient' => $msisdn,
            'sender_id' => "8809601001345",
            //  'sender_id' => "8809601001309",
            'type' => "plain",
            // 'schedule_time' => $schedule_time,
            'message' => $message,
        ]);
         return $response;

         
         

        // return Http::post("http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=Parceldex&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");
        //  return Http::post("https://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");



   //   return Http::post("http://bulksms.zaman-it.com/api/sendsms?api_key=01708063104.E1EqubtDkEcyCPHvaj&type=text&phone=$msisdn&senderid=8809604903051&message=$message");
       // Too stop SMS
    //   return true;


    }
}


if (!function_exists('send_signup_sms')) {
    function send_signup_sms($phone, $message)
    {
        $msisdn=trim($phone);
        $n = strlen($msisdn);
        if ($n==11){
            $msisdn = '88' . $msisdn;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . '408|KuVuzCpqy3D0gbO2vWa7gRVhXSyo3EIAbtqLhX95',
        ])->post("https://login.esms.com.bd/api/v3/sms/send", [
            'recipient' => $msisdn,
            'sender_id' => "8809601001345",
            //  'sender_id' => "8809601001309",
            'type' => "plain",
            // 'schedule_time' => $schedule_time,
            'message' => $message,
        ]);
         return $response;

    //  $csms_id = time(); // Example: Use timestamp as a unique ID
    //     $apiToken = 'iumyif5b-qskltfmm-lvc9l2ay-eojioy0k-5x9ju2xa';
    //     $sid = 'SAFEDELIVERYNON'; // Replace with the actual SID

       
    //         $response = Http::asForm()->post('https://smsplus.sslwireless.com/api/v3/send-sms', [
    //             'api_token' => $apiToken,
    //             'sid' => $sid,
    //             'sms' => $message,
    //             'msisdn' => $msisdn,
    //             'csms_id' => $csms_id
    //         ]);

    //     return $response;

        //  return Http::post("http://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=Parceldex&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");
        //  return Http::post("https://api.boom-cast.com/boomcast/WebFramework/boomCastWebService/externalApiSendTextMessage.php?masking=NOMASK&userName=Parceldex&password=9b70712043cd3344e52cf6c3bd9d024d&MsgType=TEXT&receiver=$msisdn&message=$message");
    // return true;


   //   return Http::post("http://bulksms.zaman-it.com/api/sendsms?api_key=01708063104.E1EqubtDkEcyCPHvaj&type=text&phone=$msisdn&senderid=8809604903051&message=$message");
       // Too stop SMS
    //   return true;


    }
}



if (!function_exists('pathao_access_token')) {
    function pathao_access_token()
    {
        //parceldesk
        $data = [
            'client_id' => 'oQeZ0M8bpZ',
            'client_secret' => 'CHFyKqEfAovxp5ov6qLoXO7TEkirKw2kSpi8QTBW',
            'username' => 'info.safedelivery24@gmail.com',
            'password' => 'Safe@2024',
            'grant_type' => 'password',
        ];
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post("https://api-hermes.pathao.com/aladdin/api/v1/issue-token", $data);
        $response = json_decode($res, true);
        return $response['access_token'];
    }
}

if (!function_exists('get_pathao_stores')) {
    function get_pathao_stores()
    {
        $access_token = pathao_access_token(); // token helper

        if (!$access_token) {
            return [];
        }

        $res = Http::withHeaders([
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json; charset=UTF-8',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/stores");

        $response = $res->json();

        return $response['data']['data'] ?? [];
    }
}



if (!function_exists('get_pathao_cities')) {
    function get_pathao_cities($access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/countries/1/city-list");

        // ভুল ছিল: $response = json_decode($res, true);
        
        // সঠিক নিয়ম:
        $response = $res->json();

        // ডাটা আছে কিনা চেক করে রিটার্ন করা ভালো, নাহলে এরর দিতে পারে
        if (isset($response['data']['data'])) {
            return $response['data']['data'];
        }

        return []; // যদি ডাটা না আসে তবে ফাঁকা অ্যারে রিটার্ন করবে
    }
}


if (!function_exists('get_pathao_zones')) {
    function get_pathao_zones($city_id, $access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/cities/$city_id/zone-list");
        $response = json_decode($res, true);
        return $response['data']['data'];
    }
}

if (!function_exists('get_pathao_areas')) {
    function get_pathao_areas($zone_id, $access_token = null)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->get("https://api-hermes.pathao.com/aladdin/api/v1/zones/$zone_id/area-list");
        $response = json_decode($res, true);
        return $response['data']['data'];
    }
}


if (!function_exists('create_pathao_order')) {
    // function create_pathao_order($access_token, $city_id, $zone_id, $area_id, $parcel)
    function create_pathao_order($access_token, $city_id, $zone_id, $area_id, $parcel, $store_id)
    {
        $data = [
            
            // "store_id" => 282165,
            // "merchant_order_id" => $parcel->parcel_invoice,
            // "sender_name" => "Safe delivery courier limited",
            // "sender_phone" => '01971556264',
            "store_id"          => $store_id,       // এখন ডাইনামিক
            // "sender_name"       => $sender_name,    // এখন ডাইনামিক
            // "sender_phone"      => $sender_phone,   // এখন ডাইনামিক
            "merchant_order_id" => $parcel->parcel_invoice,
            "recipient_name" => $parcel->customer_name,
            "recipient_phone" => $parcel->customer_contact_number,
            "recipient_address" => $parcel->customer_address,

            // "recipient_city" => $city_id,
            // "recipient_zone" => $zone_id,
            // "recipient_area" => $area_id,

            "delivery_type" => 48,
            "item_type" => 2,
            "special_instruction" => $parcel->parcel_note,
//            "special_instruction" => "Api testing",
            "item_quantity" => 1,
            "item_weight" => (float)$parcel->weight_package->name,
            "amount_to_collect" => $parcel->total_collect_amount,
            "item_description" => $parcel->product_details
        ];
        // dd($data);
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $access_token"
        ])->post("https://api-hermes.pathao.com/aladdin/api/v1/orders", $data);
        return json_decode($res, true);
    }
}



function last_query_start(){
        \DB::enableQueryLog();
    }

    function last_query_end(){
        $query = \DB::getQueryLog();
        dd(end($query));
    }

    function debugger_data($data){
        echo "<pre>"; print_r(json_decode($data)); exit;
    }

    function setTimeZone(){
        date_default_timezone_set('Asia/Dhaka');
    }


    function notification_data()
    {

    }

    function file_url($file, $path) {
        return asset('uploads/' . $path . '/' . $file);
    }



    function merchantParcelNotification($merchant_id){
        Notification::where('send_to', $merchant_id)->where('status', 'unread')->union(Notification::where('send_to', $merchant_id)
            ->where('status', 'read')
            ->orderBy('id', 'desc')
            ->limit(15)
            ->getQuery()
        )->get();

    }


    function error_processor($validator){
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }




    function returnParcelStatusNameForAdmin($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
        $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

        if($status == 1){
            $status_name  = "Pickup Request";
            $class        = "success";
        }
        elseif($status == 2){
            $status_name  = "Parcel Hold";
            $class        = "warning";
        }
        elseif($status == 3){
            // $status_name  = "Parcel Cancel";
            $status_name  = "Deleted";
            $class        = "danger";
        }
        elseif($status == 4){
            $status_name  = "Re-schedule Pickup";
            $class        = "warning";
        }
        elseif($status == 5){
            $status_name  = "Assign for Pickup";
            $class        = "success";
        }
        elseif($status == 6){
            $status_name  = "Rider Assign For Pick";
            $class        = "success";
        }
        elseif($status == 7){
            $status_name  = "Pickup Run Cancel";
            $class        = "warning";
        }
        elseif($status == 8){
            $status_name  = "On the way to Pickup";
            $class        = "success";
        }
        elseif($status == 9){
            $status_name  = "Pickup Rider Reject";
            $class        = "warning";
        }
        elseif($status == 10){
            $status_name  = "Rider Picked";
            $class        = "success";
        }
        elseif($status == 11){
            $status_name  = "Picked Up";
            $class        = "success";
        }
        elseif($status == 12){
            $status_name  = "On the Way To Delivery Hub";
            $class        = "secondary";
        }
        elseif($status == 13){
            $status_name  = "Branch Transfer Cancel";
            $class        = "warning";
        }
        elseif($status == 14){
            $status_name  = "At Delivery Hub";
            $class        = "success";
        }
        elseif($status == 15){
            $status_name  = "Delivery Branch Reject";
            $class        = "warning";
        }
        elseif($status == 16){
            $status_name  = "Assign For Delivery";
            $class        = "success";
        }
        elseif($status == 17){
            $status_name  = "Assign For Delivery";
            $class        = "success";
        }
        elseif($status == 18){
            $status_name  = "Delivery Run Cancel";
            $class        = "warning";
        }
        elseif($status == 19){
            $status_name  = "On The Way To Delivery";
            $class        = "secondary";
        }
        elseif($status == 20){
            $status_name  = "Delivery Rider Reject";
            $class        = "warning";
        }
        elseif($status == 21){
            $status_name  = "Rider Delivered";
            $class        = "success";
        }
        elseif($status == 22){
            $status_name  = "Partially Delivered";
            $class        = "success";
        }
        elseif($status == 23){
            $status_name  = "Rescheduled";
            $class        = "success";
        }
        elseif($status == 24){
            $status_name  = "Rider Return";
            $class        = "warning";
        }
        elseif($status == 25 && $delivery_type == 1){
            $status_name  = "Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 2){
            $status_name  = "Partial Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 3){
            $status_name  = "Rescheduled";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 4){
            $status_name  = "Cancelled";
            $class        = "success";
        }

        /** For Partial Delivery Return */
        elseif($status == 26 && $delivery_type == 2){
            $status_name  = "Partial Delivered Branch Transfer";
            $class        = "success";
        }
        elseif($status == 27 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Branch Transfer Cancel";
            $class        = "success";
        }
        elseif($status == 28 && $delivery_type == 2){
            $status_name  = "Partial Delivered Branch & Transfer Complete";
            $class        = "success";
        }
        elseif($status == 29 && $delivery_type == 2){
            $status_name  = "Partial Delivered Branch & Transfer Reject";
            $class        = "success";
        }
        elseif($status == 30 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Run Create";
            $class        = "success";
        }
        elseif($status == 31 && $delivery_type == 2){
            $status_name  = "Partial Delivered Branch & Return Run start";
            $class        = "success";
        }
        elseif($status == 32 && $delivery_type == 2){
            $status_name  = "Partial Delivered Branch & Return Run Cancel";
            $class        = "success";
        }
        elseif($status == 33 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Rider Accept";
            $class        = "success";
        }
        elseif($status == 34 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Rider Reject";
            $class        = "success";
        }
        elseif($status == 35 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Rider Returned";
            $class        = "success";
        }
        elseif($status == 36 && $delivery_type == 2){
            $status_name  = "Partial Delivered  & Returned";
            $class        = "success";
        }

        /** For Return Parcel */
        elseif($status == 26 && $delivery_type == 4){
            $status_name  = "Return Transfer";
            $class        = "success";
        }
        elseif($status == 27 && $delivery_type == 4){
            $status_name  = "Return Transfer Cancel";
            $class        = "success";
        }
        elseif($status == 28 && $delivery_type == 4){
            $status_name  = "Return Transfer Complete";
            $class        = "success";
        }
        elseif($status == 29 && $delivery_type == 4){
            $status_name  = "Return Transfer Reject";
            $class        = "success";
        }
        elseif($status == 30 && $delivery_type == 4){
            $status_name  = "Return Run Create";
            $class        = "success";
        }
        elseif($status == 31 && $delivery_type == 4){
            $status_name  = "Return Run start";
            $class        = "success";
        }
        elseif($status == 32 && $delivery_type == 4){
            $status_name  = "Return Run Cancel";
            $class        = "success";
        }
        elseif($status == 33 && $delivery_type == 4){
            $status_name  = "Return Run Rider Accept";
            $class        = "success";
        }
        elseif($status == 34 && $delivery_type == 4){
            $status_name  = "Return Run Rider Reject";
            $class        = "success";
        }
        elseif($status == 35 && $delivery_type == 4){
            $status_name  = "Return Run Complete";
            $class        = "success";
        }
        elseif($status == 36 && $delivery_type == 4){
            $status_name  = "Returned";
            $class        = "success";
        }

        /** For Payment Status */
        if($delivery_type == 1 && $status == 25 && $payment_type == 1){
            $status_name  = "Branch Payment Request";
            $class        = "primary";
        }elseif($delivery_type == 1 && $status == 25 && $payment_type == 2){
            $status_name  = "Accounts Accept Payment";
            $class        = "success";
        }elseif($delivery_type == 1 && $status == 25 && $payment_type == 3){
            $status_name  = "Accounts Reject Payment";
            $class        = "warning";
        }elseif($delivery_type == 1 && $status == 25 && $payment_type == 4){
            $status_name  = "Processing";
            $class        = "primary";
        }elseif($delivery_type == 1 && $status == 25 && $payment_type == 5){
            $status_name  = "Paid ";
            // $status_name  = "Accounts Payment Done";
            $class        = "success";
        }elseif($delivery_type == 1 && $status == 25 && $payment_type == 6){
            $status_name  = "Merchant Payment Reject";
            $class        = "warning";
        }


        /** For Partial Payment Status */
        if($delivery_type == 2 && $status == 25 && $payment_type == 1){
            $status_name  = "Branch Delivery Exchange Payment Request";
            $class        = "primary";
        }elseif($delivery_type == 2 && $status == 25 && $payment_type == 2){
            $status_name  = "Accounts Delivery Exchange Accept Payment";
            $class        = "success";
        }elseif($delivery_type == 2 && $status == 25 && $payment_type == 3){
            $status_name  = "Accounts Delivery Exchange Reject Payment";
            $class        = "warning";
        }elseif($delivery_type == 2 && $status == 25 && $payment_type == 4){
            $status_name  = "Accounts Delivery Exchange Payment Request";
            $class        = "primary";
        }elseif($delivery_type == 2 && $status == 25 && $payment_type == 5){
            $status_name  = "Accounts Delivery Exchange Payment Done";
            $class        = "success";
        }elseif($delivery_type == 2 && $status == 25 && $payment_type == 6){
            $status_name  = "Merchant Delivery Exchange Payment Reject";
            $class        = "warning";
        }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }





// color class comment------
//  primary -blue, warning -yellow  , info - akasi , danger - red , success - green , secondary - gray  

    function returnParcelStatusForAdmin($status, $delivery_type){
        $status_name    = "";
        $class          = "";

       if ($status == 1) {
    $status_name = "Pickup Request";
    $class = "success";
} elseif ($status == 2) {
    $status_name = "Parcel Hold";
    $class = "warning";
} elseif ($status == 3) {
    $status_name = "Deleted";
    $class = "danger";
} elseif ($status == 4) {
    $status_name = "Re-schedule Pickup";
    $class = "warning";
} elseif ($status == 5) {
    $status_name = "Assign for Pickup";
    $class = "success";
} elseif ($status == 6) {
    $status_name = "Rider Assign For Pick";
    $class = "success";
} elseif ($status == 7) {
    $status_name = "Pickup Run Cancel";
    $class = "warning";
} elseif ($status == 8) {
    $status_name = "On the way to Pickup";
    $class = "success";
} elseif ($status == 9) {
    $status_name = "Pickup Reject";
    $class = "warning";
} elseif ($status == 10) {
    $status_name = "Rider Picked";
    $class = "success";
} elseif ($status == 11) {
    $status_name = "Picked Up";
    $class = "success";
} elseif ($status == 12) {
    $status_name = "On the Way To Delivery Hub";
    $class = "secondary";
} elseif ($status == 13) {
    $status_name = "Hub Transfer Cancel";
    $class = "warning";
} elseif ($status == 14) {
    $status_name = "At Delivery Hub";
    $class = "success";
} elseif ($status == 15) {
    $status_name = "Delivery Hub Reject";
    $class = "warning";
} elseif ($status == 16) {
    $status_name = "Assign For Delivery";
    $class = "success";
} elseif ($status == 17) {
    $status_name = "Out For Delivery";
    $class = "success";
} elseif ($status == 18) {
    $status_name = "Delivery Run Cancel";
    $class = "warning";
} elseif ($status == 19) {
    $status_name = "On The Way To Delivery";
    $class = "secondary";
} elseif ($status == 20) {
    $status_name = "Delivery Rider Reject";
    $class = "warning";
} elseif ($status == 21) {
    $status_name = "Rider Delivered";
    $class = "success";
} elseif ($status == 22) {
    $status_name = "Rider Partial Delivered";
    $class = "success";
} elseif ($status == 23) {
    $status_name = "Rider Rescheduled";
    $class = "success";
} elseif ($status == 24) {
    $status_name = "Rider Return";
    $class = "warning";
} elseif ($status == 25 && $delivery_type == 1) {
    $status_name = "Delivered";
    $class = "success";
} elseif ($status == 25 && $delivery_type == 2) {
    $status_name = "Partial Delivered";
    $class = "success";
} elseif ($status == 25 && $delivery_type == 3) {
    $status_name = "Rescheduled";
    $class = "success";
} elseif ($status == 25 && $delivery_type == 4) {
    $status_name = "Cancelled";
    $class = "success";
}

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }

    function returnDeliveryStatusForAdmin($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        if($delivery_type){
            if($status >= 25 && $delivery_type == 1){
                $status_name  = "Delivered";
                $class        = "success";
            }
            elseif($status >= 25 && $delivery_type == 2){
                $status_name  = "Partial Delivery";
                $class        = "success";
            }
            elseif($delivery_type == 3){
                $status_name  = "Reschedule";
                $class        = "warning";
            }
            elseif($status >= 25 && $delivery_type == 4){
                $status_name  = "Cancelled";
                $class        = "danger";
            }
        }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }

    function returnPaymentStatusForAdmin($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        if($status >= 25 && ($delivery_type == 1 || $delivery_type == 2 || $delivery_type == 4) && $payment_type){
            if($payment_type == 1){
                $status_name  = "Branch Payment Request";
                $class        = "primary";
            }elseif($payment_type == 2){
                $status_name  = "Accounts Accept Payment";
                $class        = "success";
            }elseif($payment_type == 3){
                $status_name  = "Accounts Reject Payment";
                $class        = "warning";
            }elseif($payment_type == 4){
                $status_name  = "Accounts Payment Request";
                $class        = "primary";
            }elseif($payment_type == 5){
                $status_name  = "Paid ";
                // $status_name  = "Accounts Payment Done";
                $class        = "success";
            }elseif($payment_type == 6){
                $status_name  = "Merchant Payment Reject";
                $class        = "warning";
            }
        }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }

    function returnReturnStatusForAdmin($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        if($status >= 25 && $delivery_type && ($delivery_type == 2 || $delivery_type == 4)){

            /** For Partial Delivery Return */
            if($status == 26){
                $status_name  = "Return Transfer";
                $class        = "success";
            }
            elseif($status == 27){
                $status_name  = "Return Transfer Cancel";
                $class        = "success";
            }
            elseif($status == 28){
                $status_name  = "Return Transfer Complete";
                $class        = "success";
            }
            elseif($status == 29){
                $status_name  = "Return Transfer Reject";
                $class        = "success";
            }
            elseif($status == 30){
                $status_name  = "Assign For Return";
                $class        = "success";
            }
            elseif($status == 31){
                $status_name  = "Assign For Return start";
                $class        = "success";
            }
            elseif($status == 32){
                $status_name  = "Return Run Cancel";
                $class        = "success";
            }
            elseif($status == 33){
                $status_name  = "On The Way To Return";
                $class        = "success";
            }
            elseif($status == 34){
                $status_name  = "Return Run Rider Reject";
                $class        = "success";
            }
            elseif($status == 35){
                $status_name  = "Rider Returned";
                $class        = "success";
            }
            elseif($status == 36){
                $status_name  = "Returned";
                $class        = "success";
            }
        }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }


    function returnParcelStatusNameForBranch($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
        $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

        if($status == 1){
            $status_name  = "Pickup Request";
            $class        = "warning";
        }
        elseif($status == 2){
            $status_name  = "Parcel Hold";
            $class        = "warning";
        }
        elseif($status == 3){
            // $status_name  = "Parcel Cancel";
             $status_name  = "Deleted";
            $class        = "danger";
        }
        elseif($status == 4){
            $status_name  = "Re-schedule Pickup";
            $class        = "warning";
        }
        elseif($status == 5){
            $status_name  = "Assign for Pickup";
            $class        = "success";
        }
        elseif($status == 6){
            $status_name  = "Rider Assign For Pick";
            $class        = "success";
        }
        elseif($status == 7){
            $status_name  = "Pickup Run Cancel";
            $class        = "warning";
        }
        elseif($status == 8){
            $status_name  = "On the way to Pickup";
            $class        = "success";
        }
        elseif($status == 9){
            $status_name  = "Pickup Rider Reject";
            $class        = "danger";
        }
        elseif($status == 10){
            $status_name  = "Rider Picked";
            $class        = "success";
        }
        elseif($status == 11){
            $status_name  = "Picked Up";
            $class        = "primary";
        }
        elseif($status == 12){
            $status_name  = "On the Way To Delivery Hub";
            $class        = "secondary";
        }
        elseif($status == 13){
            $status_name  = "Branch Transfer Cancel";
            $class        = "warning";
        }
        elseif($status == 14){
            $status_name  = "At Delivery Hub";
            $class        = "info";
        }
        elseif($status == 15){
            $status_name  = "Delivery Branch Reject";
            $class        = "warning";
        }
        elseif($status == 16){
            $status_name  = "Delivery Run Create";
            $class        = "success";
        }
        elseif($status == 17){
            $status_name  = "Assign For Delivery";
            $class        = "success";
        }
        elseif($status == 18){
            $status_name  = "Delivery Run Cancel";
            $class        = "warning";
        }
        elseif($status == 19){
            $status_name  = "On The Way To Delivery";
            $class        = "secondary";
        }
        elseif($status == 20){
            $status_name  = "Delivery Rider Reject";
            $class        = "danger";
        }
        elseif($status == 21){
//            $status_name  = "Delivery Rider Complete Delivery";
            $status_name  = "Rider Delivered";
            $class        = "success";
        }
        elseif($status == 22){
            $status_name  = "Partially Delivered";
            $class        = "success";
        }
        elseif($status == 23){
            $status_name  = "Rescheduled";
            $class        = "success";
        }
        elseif($status == 24){
            $status_name  = "Delivery Rider Return";
            $class        = "warning";
        }
        elseif($status >= 25 && $delivery_type == 1){
            $status_name  = "Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 2){
            $status_name  = "Partial Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 3){
            $status_name  = "Rescheduled";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 4){
            $status_name  = "Delivery Cancel";
            $class        = "danger";
        }

        /** For Partial Delivery Return */
        elseif($status == 26 && $delivery_type == 2){
            $status_name  = "Partial Delivered & On the Way To Returned Hub";
            $class        = "secondary";
        }
        elseif($status == 27 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Transfer Cancel";
            $class        = "secondary";
        }
        elseif($status == 28 && $delivery_type == 2){
            $status_name  = "Partial Delivered & At Returned Hub";
            $class        = "secondary";
        }
        elseif($status == 29 && $delivery_type == 2){
            $status_name  = "Partial Delivered &  Return Transfer Reject";
            $class        = "secondary";
        }
        elseif($status == 30 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Assing For Returned";
            $class        = "secondary";
        }
        elseif($status == 31 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Assing For Returned";
            $class        = "secondary";
        }
        elseif($status == 32 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Run Cancel";
            $class        = "secondary";
        }
        elseif($status == 33 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Rider Accept";
            $class        = "secondary";
        }
        elseif($status == 34 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Rider Reject";
            $class        = "secondary";
        }
        elseif($status == 35 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Rider Returned";
            $class        = "secondary";
        }
        elseif($status == 36 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Returned";
            $class        = "success";
        }

        /** For Return Parcel */
        elseif($status == 26 && $delivery_type == 4){
            $status_name  = "On the Way To Returned Hub";
            $class        = "success";
        }
        elseif($status == 27 && $delivery_type == 4){
            $status_name  = "Return Transfer Cancel";
            $class        = "success";
        }
        elseif($status == 28 && $delivery_type == 4){
            $status_name  = "At Returned Hub";
            $class        = "success";
        }
        elseif($status == 29 && $delivery_type == 4){
            $status_name  = "Return Transfer Reject";
            $class        = "success";
        }
        elseif($status == 30 && $delivery_type == 4){
            $status_name  = "Assign for Return";
            $class        = "success";
        }
        elseif($status == 31 && $delivery_type == 4){
            $status_name  = "Assign for Return";
            $class        = "success";
        }
        elseif($status == 32 && $delivery_type == 4){
            $status_name  = "Return Run Cancel";
            $class        = "success";
        }
        elseif($status == 33 && $delivery_type == 4){
            $status_name  = "Return Rider Accept";
            $class        = "success";
        }
        elseif($status == 34 && $delivery_type == 4){
            $status_name  = "Return Run Rider Reject";
            $class        = "success";
        }
        elseif($status == 35 && $delivery_type == 4){
            $status_name  = "Rider Returned";
            $class        = "success";
        }
        elseif($status == 36 && $delivery_type == 4){
            $status_name  = "Returned";
            $class        = "success";
        }

        // /** For Payment Status */
        // if($delivery_type == 1 && $status == 25 && $payment_type == 1){
        //     $status_name  = "Branch Payment Request";
        //     $class        = "primary";
        // }elseif($delivery_type == 1 && $status == 25 && $payment_type == 2){
        //     $status_name  = "Accounts Accept Payment";
        //     $class        = "success";
        // }elseif($delivery_type == 1 && $status == 25 && $payment_type == 3){
        //     $status_name  = "Accounts Reject Payment";
        //     $class        = "warning";
        // }elseif($delivery_type == 1 && $status == 25 && $payment_type == 4){
        //     $status_name  = "Accounts Payment Request";
        //     $class        = "primary";
        // }elseif($delivery_type == 1 && $status == 25 && $payment_type == 5){
        //     $status_name  = "Paid ";
        //      $status_name  = "Accounts Payment Done";
        //     $class        = "success";
        // }elseif($delivery_type == 1 && $status == 25 && $payment_type == 6){
        //     $status_name  = "Merchant Payment Reject";
        //     $class        = "warning";
        // }


        /** For Partial Payment Status */
        // if($delivery_type == 2 && $status == 25 && $payment_type == 1){
        //     $status_name  = "Branch Delivery Exchange Payment Request";
        //     $class        = "primary";
        // }elseif($delivery_type == 2 && $status == 25 && $payment_type == 2){
        //     $status_name  = "Accounts Delivery Exchange Accept Payment";
        //     $class        = "success";
        // }elseif($delivery_type == 2 && $status == 25 && $payment_type == 3){
        //     $status_name  = "Accounts Delivery Exchange Reject Payment";
        //     $class        = "warning";
        // }elseif($delivery_type == 2 && $status == 25 && $payment_type == 4){
        //     $status_name  = "Accounts Delivery Exchange Payment Request";
        //     $class        = "primary";
        // }elseif($delivery_type == 2 && $status == 25 && $payment_type == 5){
        //     $status_name  = "Accounts Delivery Exchange Payment Done";
        //     $class        = "success";
        // }elseif($delivery_type == 2 && $status == 25 && $payment_type == 6){
        //     $status_name  = "Merchant Delivery Exchange Payment Reject";
        //     $class        = "warning";
        // }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }


    function returnParcelStatusNameForMerchant($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        $returnableParcel   = ($delivery_type == 2 || $delivery_type == 4);
        $deliveredParcel    = ($delivery_type == 1 || $delivery_type == 2);

        if($status == 1){
            $status_name  = "Pickup Request";
            $class        = "success";
        }
        elseif($status == 2){
            $status_name  = "Parcel Hold";
            $class        = "warning";
        }
        elseif($status == 3){
            // $status_name  = "Parcel Cancel";
             $status_name  = "Deleted";
            $class        = "danger";
        }
        elseif($status == 4){
            $status_name  = "Re-schedule Pickup";
            $class        = "warning";
        }
        elseif($status == 5){
            $status_name  = "Assign for pickup";
            $class        = "success";
        }
        elseif($status == 6){
            $status_name  = "Pickup Processing";
            $class        = "success";
        }
        elseif($status == 7){
            $status_name  = "Pickup Processing";
            $class        = "warning";
        }
        elseif($status == 8){
            $status_name  = "Pickup Processing";
            $class        = "success";
        }
        elseif($status == 9){
            $status_name  = "Pickup Processing";
            $class        = "warning";
        }
        elseif($status == 10){
            $status_name  = "Pickup Processing";
            $class        = "success";
        }
        elseif($status == 11){
            $status_name  = "Picked Up";
            $class        = "success";
        }
        elseif($status == 12){
            $status_name  = "On the Way To Delivery Hub";
            $class        = "secondary";
        }
        elseif($status == 13){
            $status_name  = "Picked Up";
            $class        = "warning";
        }
        elseif($status == 14){
            $status_name  = "At Delivery Hub";
            $class        = "info";
        }
        elseif($status == 15){
            $status_name  = "Picked Up";
            $class        = "warning";
        }
        elseif($status == 16){
            $status_name  = "Assign for Delivery";
            $class        = "success";
        }
        elseif($status == 17){
            $status_name  = "Delivery Processing";
            $class        = "success";
        }
        elseif($status == 18){
            $status_name  = "On The Way To Delivery";
            $class        = "secondary";
        }
        elseif($status == 19){
            $status_name  = "On The Way To Delivery";
            $class        = "secondary";
        }
        elseif($status == 20){
            $status_name  = "Delivery Processing";
            $class        = "warning";
        }
        elseif($status == 21){
            $status_name  = "Rider Delivered";
            $class        = "success";
        }
        elseif($status == 22){
            $status_name  = "Partial Delivered";
            $class        = "success";
        }
        elseif($status == 23){
            $status_name  = "Rider Reschedule";
            $class        = "success";
        }
        elseif($status == 24){
            $status_name  = "Rider Return";
            $class        = "warning";
        }

        elseif($status == 25 && $delivery_type == 1){
            $status_name  = "Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 2){
            $status_name  = "Partially Delivered";
            $class        = "success";
        }
        elseif($status == 25 && $delivery_type == 3){
            $status_name  = "Rescheduled";
            $class        = "warning";
        }
        elseif($status == 25 && $delivery_type == 4){
            $status_name  = "Cancelled";
            $class        = "danger";
        }

        /** For Partial Delivery Return */
        elseif($status >= 26 && $status <= 35 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return Processing";
            $class        = "warning";
        }
        
        elseif($status == 36 && $delivery_type == 2){
            $status_name  = "Partial Delivered & Return ";
            $class        = "success";
        }

        /** For Return Parcel */
        elseif($status >= 26 && $status <= 35 && $delivery_type == 4){
            $status_name  = "Return Processing";
            $class        = "warning";
        }
        elseif($status == 36 && $delivery_type == 4){
            $status_name  = "Returned";
            $class        = "danger";
        }

        // /** For Payment Status */
        // if($delivery_type == 1 && $status == 25 && (($payment_type >= 1 && $payment_type <= 4) || ($payment_type == 6))){
        //     $status_name  = "Payment Pending";
        //     $class        = "warning";
        // }
        // elseif($delivery_type == 1 && $status == 25 && $payment_type == 5){
        //     $status_name  = "Payment Done";
        //     $class        = "success";
        // }


        // /** For Partial Payment Status */
        // if($delivery_type == 2 && $status >= 25 && (($payment_type >= 1 && $payment_type <= 4) || ($payment_type == 6))){
        //     $status_name  .=  "Payment Pending";
        // }
        // elseif($delivery_type == 2 && $status >= 25 && $payment_type == 5){
        //     $status_name  .=  "Payment Done";
        // }



        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }
    
    
    function returnPaymentStatusForMerchant($status, $delivery_type, $payment_type){
        $status_name    = "";
        $class          = "";

        if($status >= 25 && ($delivery_type == 1 || $delivery_type == 2 || $delivery_type == 4) && $payment_type){
            if($payment_type == 1){
                $status_name  = "Unpaid";
                $class        = "primary";
            }elseif($payment_type == 2){
                $status_name  = "Unpaid";
                $class        = "primary";
            }elseif($payment_type == 3){
                $status_name  = "Unpaid";
                $class        = "warning";
            }elseif($payment_type == 4){
                $status_name  = "Unpaid";
                $class        = "primary";
            }elseif($payment_type == 5){
                $status_name  = "Paid ";
                // $status_name  = "Accounts Payment Done";
                $class        = "success";
            }elseif($payment_type == 6){
                $status_name  = "Reject";
                $class        = "warning";
            }
        }

        return [
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }



    function returnParcelLogStatusNameForAdmin($parcelLog, $delivery_type){
        $status         = $parcelLog->status;
        $to_user        = "";
        $from_user      = "";
        $status_name    = "";
        $class          = "";

        if($status == 1){
            $status_name  = "Pickup Request";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->merchant){
                $to_user    = "Merchant : ".$parcelLog->merchant->name;
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $from_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
            }

        }
        elseif($status == 2){
            $status_name  = "Parcel Hold";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->merchant){
                $to_user    = "Merchant : ".$parcelLog->merchant->name;
            }
        }
        elseif($status == 3){
            // $status_name  = "Parcel Cancel";
             $status_name  = "Deleted";
            $class        = "danger";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->merchant){
                $to_user    = "Merchant : ".$parcelLog->merchant->name;
            }
        }
        elseif($status == 4){
            $status_name  = "Re-schedule Pickup";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_rider){
                if(!empty($parcelLog->pickup_rider)){
                    $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
                }
                if(!empty($parcelLog->pickup_branch)){
                    $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                    $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                    //$to_user    .= "Pickup Branch : ".$parcelLog->pickup_branch->name;
                }
            }
        }
        elseif($status == 5){
            $status_name  = "Assign for Pickup";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch){
                if(!empty($parcelLog->pickup_branch)){

                    $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                    $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                    //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
                }
            }
        }
        elseif($status == 6){
            $status_name  = "Rider Assign For Pick";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch_user){
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
        elseif($status == 7){
            $status_name  = "Pickup Run Cancel";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch_user){
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
        elseif($status == 8){
            $status_name  = "On the way to Pickup";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_rider){
                $to_user    = "Pickup Rider : ".$parcelLog->pickup_rider->name;
            }
        }
        elseif($status == 9){
            $status_name  = "Pickup Rider Reject";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_rider){
                $to_user    = "Pickup Rider : ".($parcelLog->pickup_rider) ? $parcelLog->pickup_rider->name : "";
            }

        }
        elseif($status == 10){
            $status_name  = "Rider Picked";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_rider){
                $to_user    = (!empty($parcelLog->pickup_rider)) ? "Pickup Rider : ".$parcelLog->pickup_rider->name : '';

                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  .= (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                //$to_user    .= (!empty($parcelLog->pickup_branch)) ? "Pickup Branch : ".$parcelLog->pickup_branch->name : '';
            }
        }
        elseif($status == 11){
            $status_name  = "Picked Up";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch_user){
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;
                //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
        elseif($status == 12){
            $status_name  = "On the Way To Delivery Hub";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch_user){
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;

                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $from_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $from_user   .= $dbranch_user;
            }
        }
        elseif($status == 13){
            $status_name  = "Hub Transfer Cancel";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->pickup_branch_user){
                $branch_user = (!empty($parcelLog->pickup_branch_user)) ?   " (".$parcelLog->pickup_branch_user->name.")" : " (General)";
                $to_user  = (!empty($parcelLog->pickup_branch)) ?   "Pickup Branch : ".$parcelLog->pickup_branch->name. $branch_user : "Default".$branch_user;

                //$to_user    = "Pickup Branch : ".$parcelLog->pickup_branch->name;
            }
        }
        elseif($status == 14){
            $status_name  = "At Delivery Hub";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch_user){

                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $to_user   .= $dbranch_user;

                //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
            }
        }
        elseif($status == 15){
            $status_name  = "Delivery Hub Reject";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch_user){
                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $to_user   .= $dbranch_user;

                //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
            }
        }
        elseif($status == 16){
            $status_name  = "Assign For Delivery";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch_user){
                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $to_user   .= $dbranch_user;

                //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
            }
        }
        elseif($status == 17){
            $status_name  = "Out For Delivery";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch_user){
                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $to_user   .= $dbranch_user;

                //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
            }
        }
        elseif($status == 18){
            $status_name  = "Delivery Run Cancel";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch_user){
                $dbranch_user = (!empty($parcelLog->delivery_branch_user)) ?   " (".$parcelLog->delivery_branch_user->name.")" : " (General)";
                $to_user    = (!empty($parcelLog->delivery_branch)) ? "Delivery Branch : ".$parcelLog->delivery_branch->name : "Default";
                $to_user   .= $dbranch_user;

                //$to_user    = "Delivery Branch : ".$parcelLog->delivery_branch->name;
            }
        }
        elseif($status == 19){
            $status_name  = "On The Way To Delivery";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
            }
        }
        elseif($status == 20){
            $status_name  = "Delivery Rider Reject";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
            }
        }
        elseif($status == 21){
            $status_name  = "Rider Delivered";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
            }
        }
        elseif($status == 22){
            $status_name  = "Rider Partial Delivered";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
            }
        }
        elseif($status == 23){
            $status_name  = "Rescheduled";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name." (Reschedule Date : " .\Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') .")";
            }
        }
        elseif($status == 24){
            $status_name  = "Rider Return";
            $class        = "warning";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_rider){
                $to_user    = "Delivery Rider : ".$parcelLog->delivery_rider->name;
            }
        }
       /* elseif($status == 25){
            $status_name  = "Delivery Complete";
            $class        = "success";

            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch){
                $to_user    = !empty($parcelLog->delivery_branch)? "Delivery Branch : ".$parcelLog->delivery_branch->name : "";
            }
        }*/
        elseif($status == 25 && $parcelLog->delivery_type == 1){
            $status_name  = "Delivered";
            $class        = "success";
            if(!empty($parcelLog->admin)){
                $to_user    = "Admin : ".$parcelLog->admin->name;
            }
            elseif($parcelLog->delivery_branch){
                $to_user    = !empty($parcelLog->delivery_branch)? "Delivery Branch : ".$parcelLog->delivery_branch->name : "";
            }

        }
        elseif($status == 25 && $parcelLog->delivery_type == 2){
            $status_name  = "Partial Delivered";
            $class        = "success";


        }
        elseif($status == 25 && $parcelLog->delivery_type == 3){
            $status_name  = "Rescheduled";
            $class        = "success";
        }
        elseif($status == 25 && $parcelLog->delivery_type == 4){
            $status_name  = "Delivery Cancel";
            $class        = "success";
        } elseif($status == 25){
            $status_name  = "Delivery Rider Run Complete(unknown)";
            $class        = "success";
        }
        
        
        elseif($status == 26){
            $status_name  = "On the Way To Returned Hub";
            $class        = "success";
        }
        elseif($status == 27){
            $status_name  = "Return Transfer Cancel";
            $class        = "success";
        }
        elseif($status == 28){
            $status_name  = "At Returned Hub";
            $class        = "success";
        }
        elseif($status == 29){
            $status_name  = "Return Transfer Reject";
            $class        = "success";
        }
        elseif($status == 30){
            $status_name  = "Assign for Return";
            $class        = "success";
        }elseif($status == 31){
            $status_name  = "Assign for Return";
            $class        = "success";
        }elseif($status == 32){
            $status_name  = "Return Run Cancel";
            $class        = "success";
        }
        elseif($status == 33){
            $status_name  = "Out For Return";
            $class        = "success";
        }
        elseif($status == 34){
            $status_name  = "Return Run Rider Reject";
            $class        = "success";
        }
        elseif($status == 35){
            $status_name  = "Returned by Rider";
            $class        = "success";
        }
        elseif($status == 36){
            $status_name  = "Returned";
            $class        = "success";
        }

        return [
            'to_user'       => $to_user,
            'from_user'     => $from_user,
            'status_name'   => $status_name,
            'class'         => $class
        ];
    }



?>
