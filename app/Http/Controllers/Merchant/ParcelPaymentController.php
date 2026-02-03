<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\MerchantBulkParcelImport;

class ParcelPaymentController extends Controller {


    public function parcelPaymentList() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.parcelPayment.parcelPaymentList', $data);
    }

    public function getParcelPaymentList(Request $request) {
        $merchant_id = auth()->guard('merchant')->user()->id;

        $model = Parcel::whereRaw('merchant_id = ?', [$merchant_id])
        ->where(function($query) use($request){
            $delivery_parcel_type          = $request->input('delivery_parcel_type');
            $parcel_invoice         = $request->input('parcel_invoice');
            $merchant_order_id      = $request->input('merchant_order_id');
            $customer_contact_number= $request->input('customer_contact_number');
            $from_date              = $request->input('from_date');
            $to_date                = $request->input('to_date');

            if ( ($request->has('delivery_parcel_type')  && !is_null($delivery_parcel_type))
                || ($request->has('parcel_invoice')  && !is_null($parcel_invoice))
                || ($request->has('customer_contact_number')  && !is_null($customer_contact_number))
                || ($request->has('merchant_order_id')  && !is_null($merchant_order_id))
                || ($request->has('from_date')  && !is_null($from_date))
                || ($request->has('to_date')  && !is_null($to_date))
            ) {
                if((!is_null($parcel_invoice) && !is_null($parcel_invoice))
                || (!is_null($merchant_order_id) && !is_null($merchant_order_id))
                || (!is_null($customer_contact_number) && !is_null($customer_contact_number))
                ){
                    if(!is_null($parcel_invoice) && !is_null($parcel_invoice)){
                        $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                    }
                    elseif(!is_null($merchant_order_id) && !is_null($merchant_order_id)){
                        $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                    }
                    elseif(!is_null($customer_contact_number) && !is_null($customer_contact_number)){
                        $query->where('customer_contact_number', 'like', "%$customer_contact_number");
                    }
                }
                else{
                    if ($request->has('delivery_parcel_type') && ! is_null($delivery_parcel_type) && $delivery_parcel_type != 0) {
                        if($delivery_parcel_type == 1){
                            $query->whereRaw('delivery_type = 1');
                        }
                        elseif($delivery_parcel_type == 2){
                            $query->whereRaw('delivery_type = 2');
                        }
                        elseif($delivery_parcel_type == 3){
                            $query->whereRaw('delivery_type = 4');
                        }
                        elseif($delivery_parcel_type == 4){
                            $query->whereRaw('status < 11');
                        }
                        elseif($delivery_parcel_type == 5){
                            $query->whereRaw('status >= 11 and status < 25');
                        }
                        elseif($delivery_parcel_type == 6){
                            $query->whereRaw('delivery_type = 4 and status > 25');
                        }
                    }
                    if ($request->has('from_date') && ! is_null($from_date)) {
                        $query->whereDate('date', '>=', $from_date);
                    }
                    if ($request->has('to_date') && ! is_null($to_date)) {
                        $query->whereDate('date', '<=', $to_date);
                    }
                }
            }
            $query->where('status', '!=', 3);
        })
        ->orderBy('id', 'desc')
        ->select( 'id', 'parcel_invoice',
        'total_collect_amount', 'cod_percent',
        'cod_charge', 'weight_package_charge',
        'delivery_charge', 'return_charge',
        'customer_collect_amount', 'total_charge',
        'merchant_paid_amount');

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_invoice', function ($data) {
                return '<a href="'. route('merchant.orderTracking', $data->parcel_invoice) .'"
                title="Parcel View">
                    '. $data->parcel_invoice.'
                </a>';
            })
            ->editColumn('total_collect_amount', function ($data) {
                return number_format($data->total_collect_amount,2);
            })
            ->editColumn('cod_percent', ' {{$cod_percent}} %')
            ->editColumn('cod_charge', function ($data) {
                return number_format($data->cod_charge,2);
            })
            ->editColumn('weight_package_charge', function ($data) {
                return number_format($data->weight_package_charge,2);
            })
            ->editColumn('delivery_charge', function ($data) {
                return number_format($data->delivery_charge,2);
            })
            ->editColumn('return_charge', function ($data) {
                return number_format($data->return_charge,2);
            })
            ->editColumn('customer_collect_amount', function ($data) {
                return number_format($data->customer_collect_amount,2);
            })
            ->editColumn('total_charge', function ($data) {
                return number_format($data->total_charge,2);
            })
            ->editColumn('merchant_paid_amount', function ($data) {
                return number_format($data->merchant_paid_amount,2);
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                return $button;
            })
            ->rawColumns(['parcel_invoice', 'total_collect_amount', 'cod_percent', 'cod_charge',
            'weight_package_charge','delivery_charge', 'return_charge', 'customer_collect_amount',
            'total_charge', 'merchant_paid_amount',  'action', 'image'])
            ->make(true);
    }

}
