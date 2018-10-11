<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SendPushNotification extends Model
{
    public function sendNotification($sendTo,$title)
    {
        $url = "https://fcm.googleapis.com/fcm/send";
        $serverKey = 'AAAARfn66B0:APA91bHSDU_gi0PDCq300t82cco2wj4CmFFPa02rLlT0Amdh1XlyohDFJ0pMaRrObfpud6SNNsujxJIpH4DC1F2w2D7attCsl62AEWvV1j_ITLdPF-0CafI4Lgs2taBgOnSBfcST0JzG';
//        $title = "Liked your picture";
        $body = " Liked your picture on instagram";
        $notification = array('Title' => $title, 'sound' => 'default', 'badge' => '1');
        $arrayToSend = array('to' => $sendTo, 'data' => $notification, 'priority' => 'high');
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,

            "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


//Send the request
        curl_exec($ch);
//Close request
//        if ($response === FALSE) {
//            die('FCM Send Error: ' . curl_error($ch));
//        }
        curl_close($ch);
// }

    }
}
