<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelPaymentRequest;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParcelPaymentRequestController extends Controller {

    public function parcelPaymentRequest() {
        $merchant_id = auth()->guard('merchant')->user()->id;

        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPaymentRequest';
        $data['page_title'] = 'Parcel Payment Request ';
        $data['collapse']   = 'sidebar-collapse';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchant']   = Merchant::with('branch')->where('id', $merchant_id)->first();

        $parcels    = Parcel::where('merchant_id', $merchant_id)
            ->where('status', '>=',25)
            ->whereRaw('delivery_type in (?,?) and payment_type in (?,?,?) and payment_request_status = ?', [1,2,2,4,6,0])
            ->get();

        $total_parcel_collect_amount = 0;
        $total_parcel_charge_amount = 0;
        $parcel_ids = [];
        if($parcels) {
            foreach ($parcels as $parcel) {

                $total_parcel_collect_amount += $parcel->customer_collect_amount;
                $total_parcel_charge_amount  += $parcel->total_charge;
                $parcel_ids[]   = $parcel->id;
            }
        }

        $data['request_amount'] = $total_parcel_collect_amount - $total_parcel_charge_amount;
        $data['parcel_ids']     = json_encode($parcel_ids);


        return view('merchant.parcelPaymentRequest.parcelPaymentRequestGenerate', $data);
    }

    public function confirmPaymentRequestGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'date'         => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
            'note'         => 'sometimes',
            'request_amount'         => 'required|gt:0',
            'parcel_ids'         => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $merchant = auth()->guard('merchant')->user();
//        $check = ParcelPaymentRequest::where(['merchant_id'=>$merchant->id,'status'=>1])->first();
        $check = ParcelPaymentRequest::whereRaw("merchant_id = '{$merchant->id}' AND status < 5 AND status NOT IN (3)")->first();
        if($check == null){
            \DB::beginTransaction();
            try {
                $data = [
                    'payment_request_invoice' => $this->returnUniquePaymentRequestInvoice(),
                    'merchant_id'            => $merchant->id,
                    'request_amount'         => $request->input('request_amount'),
                    'parcel_ids'             => $request->input('parcel_ids'),
                    'date'                   => $request->input('date').' '.date('H:i:s'),
                    'note'                   => $request->input('note'),
                ];

                if($request->input('request_payment_type') == 2) {

                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['bank_name']              = $request->input('bank_name');
                    $data['bank_account_no']        = $request->input('bank_account_no');
                    $data['bank_account_name']      = $request->input('bank_account_name');
                    $data['routing_no']             = $request->input('routing_no');
                }
                elseif($request->input('request_payment_type') == 3) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['bkash_number']              = $request->input('bkash_number');
                }
                elseif($request->input('request_payment_type') == 4) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['rocket_number']              = $request->input('rocket_number');
                }
                elseif($request->input('request_payment_type') == 5) {
                    $data['request_payment_type']   = $request->input('request_payment_type');
                    $data['nagad_number']              = $request->input('nagad_number');
                }
                else{
                    $data['request_payment_type']   = 1;

                }

                $parcelPaymentRequest = ParcelPaymentRequest::create($data);

                if ($parcelPaymentRequest) {

                    $parcel_update = Parcel::whereIn('id', json_decode($request->input('parcel_ids')))->update(['payment_request_status' => 1]);

                    \DB::commit();
                    $this->setMessage('Parcel Payment Request Send Successfully', 'success');
                    return redirect()->back();
                } else {
                    $this->setMessage('Parcel Payment Request Send Failed', 'danger');
                    return redirect()->back()->withInput();
                }

            } catch (\Exception $e) {
                \DB::rollback();
                $this->setMessage($e->getMessage(), 'danger');
                return redirect()->back()->withInput();
            }
        }
        else{
            $this->setMessage('Already you have a parcel payment request.', 'warning');
            return redirect()->back()->withInput();
        }
    }

    public function parcelPaymentRequestList() {
        $data               = [];
        $data['main_menu']  = 'account';
        $data['child_menu'] = 'parcelPaymentRequestList';
        $data['page_title'] = 'Parcel Payment Request  List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.parcelPaymentRequest.parcelPaymentRequestList', $data);
    }

    public function getParcelPaymentRequestList(Request $request) {
        $merchant_id = auth()->guard('merchant')->user()->id;
        $model       = ParcelPaymentRequest::whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if (($request->has('status') && !is_null($status))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {

                    if ($request->has('status') && !is_null($status)) {
                        $query->where('status', $status);
                    }

                    if ($request->has('from_date') && !is_null($from_date)) {
                        $query->whereDate('date', '>=', $from_date);
                    }

                    if ($request->has('to_date') && !is_null($to_date)) {
                        $query->whereDate('date', '<=', $to_date);
                    }

                }
//                else {
//                    $query->whereDate('date', '=', date('Y-m-d'));
//                }

            })
            ->orderBy('id', 'desc')->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                $status = "";

                switch ($data->status) {
                case 1:$status = "<span class='text-bold text-warning' style='font-size:16px;'>Requested</span>";
                    break;
                case 2:$status = "<span class='text-bold text-success' style='font-size:16px;'>Accepted</span>";
                    break;
                case 3:$status = "<span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>";
                    break;
                case 4:$status = "<span class='text-bold text-primary' style='font-size:16px;'>Processing</span>";
                    break;
                case 5:$status = "<span class='text-bold text-success' style='font-size:16px;'>Paid</span>";
                    break;
                default:$status = "";break;
                }

                return $status;
            })
            ->addColumn('action', function ($data) {
                $button = "";
                $button .= '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_request_id="' . $data->id . '"  title="Parcel Payment Request View">
                <i class="fa fa-eye"></i> </button>';
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function viewParcelPaymentRequest(Request $request, ParcelPaymentRequest $parcelPaymentRequest) {

        $parcelPaymentRequest->load('merchant');

        return view('merchant.parcelPaymentRequest.viewParcelPaymentRequest', compact('parcelPaymentRequest'));
    }

}
