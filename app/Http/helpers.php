<?php

use App\Models\TransactionLog;
use Brian2694\Toastr\Facades\Toastr;

if (!function_exists('check_permission')) {
    function check_permission($permission): bool
    {
        if (auth()->user()->user_role == 'super_admin' || auth()->user()->hasAnyPermission($permission)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('check_access')) {
    function check_access($permission)
    {
        if (auth()->user()->user_role != 'super_admin' && !auth()->user()->hasPermissionTo($permission)) {
            return false;
        }
        return true;
    }
}
if (!function_exists('t_log')) {
    function t_log($id, $subject, $details)
    {
        TransactionLog::create([
            "transaction_id" => $id,
            "user_id" => auth()->user()->id,
            "subject" => $subject,
            "description" => $details,
        ]);
    }
}

if (!function_exists('b_notify')) {
    function b_notify($title,$message,$url)
    {
        $hello = [
            'title' => $title,
            "message" => $message,
            "url" => $url,
        ];
        event(new \App\Events\TransactionCreateAlert(json_encode($hello)));
        return "sent!";
    }
}
if (!function_exists('nicetime')) {
    function nicetime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date         = strtotime($date);

        // check validity of date
        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }
}

