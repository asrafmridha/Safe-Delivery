<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantRegisterNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:admin', 'auth']);
    }

    public function notificationRead()
    {
        $admin_user = Auth::guard('admin')->user();

        $admin_user->unreadNotifications->markAsRead();

        return true;
    }
}
