<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['send_sms_test', 'vue_js_test']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function send_sms_test() {
        // dd("asdf");

        dd($this->send_sms("01813158551","This is test message from Controller"));

    }

    public function vue_js_test() {
        dd(date('y'));

        $get_serial = substr('1234A',4,20);
        dd($get_serial);

        echo self::getNextAlphaNumeric('00A');

        dd("asdf");

        // dd($this->send_sms("01813158551","This is test message from Controller"));

    }

    public static function getNextAlphaNumeric($code) {
        $base_ten = strtoupper(base_convert(base_convert($code,36,10)+1,10,36));
        dd($base_ten);
        return base_convert($base_ten+1,10,36);
    }
}
