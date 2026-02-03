<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTestController extends Controller
{

    public function storeOrder(Request $request)
    {
        $data   = [
            "merOrderRef"           => "32473675",
            "pickMerchantName"      => "John",
            "pickMerchantAddress"   => "Jatrabari, Dhaka",
            "pickMerchantThana"     => "Jatrabari",
            "pickMerchantDistrict"  => "Dhaka",
            "pickupMerchantPhone"   => "01975513558",
            "productSizeWeight"     => "statndard",
            "ProductBrief"          => "Tshirt, Shirt",
            "packagePrice"          => "3000",
            "max_weight"            => "1",
            "deliveryOption"        => "regular",
            "custname"              => "Hasan",
            "custaddress"           => "Adabor, Dhaka",
            "customerThana"         => "Adabor",
            "customerDistrict"      => "Dhaka",
            "custPhone"             => "01583256486"
        ];

        $api_url = "https://sandbox.paperflybd.com/OrderPlacement";
        $headers = [
            'paperflykey: Paperfly_~La?Rj73FcLm'
        ];
        $authorizationUserPWD = "m117216:29255";


        return $this->callAPI($authorizationUserPWD, $headers, "POST",  $api_url, json_encode($data));

    }

    protected function callAPI($userPwd, $headers = array(), $method, $url, $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $userPwd); //Your credentials goes here

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }
}
