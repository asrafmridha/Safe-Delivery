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
use App\Models\Upazila;
use App\Models\Area;
use App\Models\ParcelPickupRequest;
use App\Models\WeightPackage;

class ParcelPickupRequestController extends Controller {

    public function parcelPickupRequest() {
        $merchant_id = auth()->guard('merchant')->user()->id;

        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPickupRequest';
        $data['page_title'] = 'Parcel Pickup Request ';
        $data['collapse']   = 'sidebar-collapse';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchant']   = Merchant::with('branch')->where('id', $merchant_id)->first();
        return view('merchant.parcelPickupRequest.parcelPickupRequestGenerate', $data);
    }


    public function confirmPickupRequestGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'request_type'      => 'required',
            'date'              => 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d'),
            'total_parcel'      => 'required|min:1',
            'note'              => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $merchant    = auth()->guard('merchant')->user();

        $check = ParcelPickupRequest::where(['merchant_id'=> $merchant->id,'status'=>1])->first();
        if($check == null){
            \DB::beginTransaction();
            try {
                $data = [
                    'pickup_request_invoice'        => $this->returnUniquePickupRequestInvoice(),
                    'merchant_id'                   => $merchant->id,
                    'branch_id'                     => $merchant->branch_id,
                    'request_type'                  => $request->input('request_type'),
                    'date'                          => $request->input('date'),
                    'total_parcel'                  => $request->input('total_parcel'),
                    'note'                          => $request->input('note'),
                ];
                $parcelPickupRequest = ParcelPickupRequest::create($data);
                if ($parcelPickupRequest) {

                    \DB::commit();
                    $this->setMessage('Parcel Pickup Request Send Successfully', 'success');
                    return redirect()->back();
                } else {
                    $this->setMessage('Parcel Pickup Request Send Failed', 'danger');
                    return redirect()->back()->withInput();
                }
            }
            catch (\Exception $e){
               // dd($e->getMessage());
                \DB::rollback();
                $this->setMessage('Database Error', 'danger');
                return redirect()->back()->withInput();
            }
        }
        else{
            $this->setMessage('Allready have a pendding parcel pickup request.', 'warning');
            return redirect()->back()->withInput();
        }
    }


    public function parcelPickupRequestList() {
        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPickupRequestList';
        $data['page_title'] = 'Parcel Pickup Request  List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.parcelPickupRequest.parcelPickupRequestList', $data);
    }

    public function getParcelPickupRequestList(Request $request) {
        $merchant_id = auth()->guard('merchant')->user()->id;
        $model = ParcelPickupRequest::whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function($query) use($request){
                $status          = $request->input('status');
                $from_date       = $request->input('from_date');
                $to_date         = $request->input('to_date');

                if ( ($request->has('status')  && !is_null($status))
                || ($request->has('from_date')  && !is_null($from_date))
                || ($request->has('to_date')  && !is_null($to_date))
                ) {
                    if ($request->has('status') && ! is_null($status)) {
                        $query->where('status', $status);
                    }

                    if ($request->has('from_date') && ! is_null($from_date)) {
                        $query->whereDate('date', '>=', $from_date);
                    }
                    if ($request->has('to_date') && ! is_null($to_date)) {
                        $query->whereDate('date', '<=', $to_date);
                    }
                }

//                else{
//                    $query->whereDate('date', '=', date('Y-m-d'));
//                }
            })
            ->orderBy('id', 'desc')->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('request_type', function ($data) {

                switch ($data->request_type){
                    case 1:
                        $type = "Regular Delivery";
                        break;
                    case 2:
                        $type = "Express Delivery";
                        break;
                    default:
                        $type = "N/A";
                        break;
                }

                return $type;
            })
            ->editColumn('created_at', function ($data) {
                $date_time = date("Y-m-d H:i:s", strtotime($data->created_at));

                return $date_time;
            })
            ->editColumn('status', function($data){
                $status = "";
                switch($data->status){
                    case 1 : $status = "<span class='text-bold text-warning' style='font-size:16px;'>Requested</span>";
                        break;
                    case 2 : $status = "<span class='text-bold text-success' style='font-size:16px;'>Accepted</span>";
                        break;
                    case 3 : $status = "<span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>";
                        break;
                    case 4 : $status = "<span class='text-bold text-primary' style='font-size:16px;'>Rider Assigned</span>";
                        break;
                    case 5 : $status = "<span class='text-bold text-success' style='font-size:16px;'>Request Complete</span>";
                        break;
                    default : $status = ""; break;
                }
                return $status;
            })
            ->addColumn('action', function ($data) {
                $button = "";
                $button .= '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_pickup_request_id="' . $data->id . '"  title="Parcel Pickup Request View">
                <i class="fa fa-eye"></i> </button>';
                return $button;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }



    public function viewParcelPickupRequest(Request $request, ParcelPickupRequest $parcelPickupRequest) {

        $parcelPickupRequest->load('merchant', 'merchant.branch', 'riders');

        return view('merchant.parcelPickupRequest.viewParcelPickupRequest', compact('parcelPickupRequest'));
    }


}
