<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Merchant;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\WarehousePasswordRestMail;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\WarehouseUser;

class HomeController extends Controller{

    public function home() {
        $warehouse_id = auth()->guard('warehouse')->user()->id;
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Home';
       return view('warehouse.home', $data);
    }


    public function profile() {
        $data               = [];
        $data['main_menu']  = 'profile';
        $data['child_menu'] = 'profile';
        $data['page_title'] = 'Profile';
        $data['warehouseUser']     = WarehouseUser::with(['warehouse'])->where('id', auth()->guard('warehouse')->user()->id)->first();
        return view('warehouse.profile', $data);
    }



}
