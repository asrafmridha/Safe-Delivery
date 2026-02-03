<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\ParcelPickupRequest;
use App\Models\Rider;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParcelPickupRequestController extends Controller {

    public function parcelPickupRequest($merchant_id) {
        $branch_id = auth()->guard('branch')->user()->branch_id;

        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Parcel Pickup Request ';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchant']   = Merchant::with(['branch'])->where('id', $merchant_id)->first();

        return view('branch.parcelPickupRequest.parcelPickupRequestGenerate', $data);
    }


    public function confirmPickupRequestGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'merchant_id'       => 'required',
            'request_type'      => 'required',
            'date'              => 'required|date_format:Y-m-d|after_or_equal:'.date('Y-m-d'),
            'total_parcel'      => 'required|min:1',
            'note'              => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $check = ParcelPickupRequest::where(['merchant_id'=> $request->input('merchant_id'), 'status'=>1])->first();
        if($check == null){
            \DB::beginTransaction();
            try {
                $data = [
                    'pickup_request_invoice'        => $this->returnUniquePickupRequestInvoice(),
                    'merchant_id'                   => $request->input('merchant_id'),
                    'branch_id'                     => auth('branch')->user()->branch_id,
                    'request_type'                  => $request->input('request_type'),
                    'date'                          => $request->input('date'),
                    'total_parcel'                  => $request->input('total_parcel'),
                    'note'                          => $request->input('note'),
                ];
                $parcelPickupRequest = ParcelPickupRequest::create($data);
                if ($parcelPickupRequest) {

                    \DB::commit();
                    $this->setMessage('Parcel Pickup Request Create Successfully', 'success');
                    return redirect()->back();
                } else {
                    $this->setMessage('Parcel Pickup Request Create Failed', 'danger');
                    return redirect()->back()->withInput();
                }
            }
            catch (\Exception $e){
                \DB::rollback();
                $this->setMessage('Database Error', 'danger');
                return redirect()->back()->withInput();
            }
        }
        else{
            $this->setMessage('Already have a pending parcel pickup request.', 'warning');
            return redirect()->back()->withInput();
        }
    }


    public function parcelPickupRequestList() {
        $data               = [];
        $data['main_menu']  = 'request';
        $data['child_menu'] = 'parcelPickupRequestList';
        $data['page_title'] = 'Parcel Pickup Request  List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcelPickupRequest.parcelPickupRequestList', $data);
    }

    public function getParcelPickupRequestList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $model     = ParcelPickupRequest::with(['merchant'])->where('branch_id', $branch_id)
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
                else {
                    $current_date   = date("Y-m-d");
                    $from_date  = date("Y-m-d", strtotime('-3 day', strtotime($current_date)));
                    $to_date    = date("Y-m-d");
                    $query->whereDate('created_at', '>=', $from_date);
                    $query->whereDate('created_at', '<=', $to_date);
                }

            })
            ->orderBy('id', 'desc')->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('merchant.company_name', function ($data) {
                $company_name = ($data->merchant->company_name) ? $data->merchant->company_name : "Merchant Company";

                return $company_name;
            })
            ->editColumn('merchant.contact_number', function ($data) {
                $contact_number = ($data->merchant->contact_number) ? $data->merchant->contact_number : "Merchant Phone";

                return $contact_number;
            })
            ->editColumn('merchant.address', function ($data) {
                $address = ($data->merchant->address) ? $data->merchant->address : "Merchant Address";

                return $address;
            })
            ->editColumn('created_at', function ($data) {
                $date_time = date("Y-m-d H:i:s", strtotime($data->created_at));

                return $date_time;
            })
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
            ->editColumn('status', function ($data) {
                $status = "";

                switch ($data->status) {
                    case 1:$status = "<span class='text-bold text-warning' style='font-size:16px;'>Requested</span>";
                        break;
                    case 2:$status = "<span class='text-bold text-success' style='font-size:16px;'>Accepted</span>";
                        break;
                    case 3:$status = "<span class='text-bold text-danger' style='font-size:16px;'>Rejected</span>";
                        break;
                    case 4:$status = "<span class='text-bold text-primary' style='font-size:16px;'>Rider Assigned</span>";
                        break;
                    case 5:$status = "<span class='text-bold text-success' style='font-size:16px;'>Complete Request</span>";
                        break;
                    default:$status = "";
                        break;
                }

                return $status;
            })
            ->addColumn('action', function ($data) {
                $button = "";
                $button .= '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_pickup_request_id="' . $data->id . '"  title="Parcel Pickup Request View">
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success pickup-request-accept btn-sm" parcel_pickup_request_id="' . $data->id . '" title="Parcel Pickup Request Accept">
                                Accept
                            </button>';
                    $button .= '&nbsp; <button class="btn btn-danger pickup-request-reject btn-sm" parcel_pickup_request_id="' . $data->id . '" title="Parcel Pickup Request Reject">
                                Reject
                            </button>';
                }

                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-primary request_assign_rider btn-sm" data-toggle="modal" data-target="#viewModal" pickup_request_id="' . $data->id . '" >
                    Rider Assign </button> ';
                }

                if ($data->status == 4) {
                    $button .= '&nbsp; <button class="btn btn-success request_complete btn-sm" data-toggle="modal" data-target="#viewModal" pickup_request_id="' . $data->id . '" >
                    <i class="fa fa-check"></i> </button> ';
                }

                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function viewParcelPickupRequest(Request $request, ParcelPickupRequest $parcelPickupRequest) {
        $parcelPickupRequest->load('merchant', 'merchant.branch');
        return view('branch.parcelPickupRequest.viewParcelPickupRequest', compact('parcelPickupRequest'));
    }

    public function acceptPickupRequestParcel(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_pickup_request_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $data = [
                    'status' => 2,
                    'action_branch_user_id' =>  auth()->guard('branch')->user()->id,
                ];
                $check = ParcelPickupRequest::where('id', $request->parcel_pickup_request_id)->update($data);

                if ($check) {
                    $response = ['success' => 'Parcel Pickup Request Accept Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function rejectPickupRequestParcel(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_pickup_request_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $data = [
                    'status' => 3,
                    'action_branch_user_id' =>  auth()->guard('branch')->user()->id,
                ];
                $check = ParcelPickupRequest::where('id', $request->parcel_pickup_request_id)->update($data);

                if ($check) {
                    $response = ['success' => 'Parcel Pickup Request Reject Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function assignRiderPickupRequest(Request $request, ParcelPickupRequest $parcelPickupRequest) {

        $parcelPickupRequest->load('merchant', 'merchant.branch');

        $branch_id  = auth('branch')->user()->branch_id;
        $data = [];
        $data['riders']     = Rider::where([
                'status'    => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();
        $data['parcelPickupRequest']    = $parcelPickupRequest;

        return view('branch.parcelPickupRequest.parcelPickupRequestRiderAssign', $data);
    }

    public function confirmPickupRequestAssignRider(Request $request)
    {
        $response = ['error' => 'Error Found!'];

        if($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
                'pickup_request_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {

                $data = [
                    'status' => 4,
                    'rider_id' => $request->rider_id,
                    'action_branch_user_id' => auth()->guard('branch')->user()->id,
                ];

                $request_data = ParcelPickupRequest::where('id', $request->pickup_request_id)->first();
                $check = $request_data->update($data);

                if ($check) {
                    $sms_message = "Assign Rider Name: {$request->rider_name} and Phone: {$request->rider_phone}.";
                    $this->send_sms($request_data->merchant->contact_number, $sms_message);

                    $response = [
                        'success' => 'Rider Assign Successfully',
                        'sms'     => $sms_message
                    ];

                   

                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);

    }

    public function completePickupRequest(Request $request, ParcelPickupRequest $parcelPickupRequest) {

        $parcelPickupRequest->load('merchant', 'merchant.branch');

        $branch_id  = auth('branch')->user()->branch_id;
        $data = [];
        $data['parcelPickupRequest']    = $parcelPickupRequest;

        return view('branch.parcelPickupRequest.parcelPickupRequestComplete', $data);
    }

    public function confirmCompletePickupRequest(Request $request)
    {
        $response = ['error' => 'Error Found!'];

        if($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'pickup_request_id' => 'required',
                'total_complete_parcel' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {

                $data = [
                    'status' => 5,
                    'total_complete_parcel' => $request->total_complete_parcel,
                    'action_branch_user_id' => auth()->guard('branch')->user()->id,
                ];

                $request_data = ParcelPickupRequest::where('id', $request->pickup_request_id)->first();
                $check = $request_data->update($data);

                if ($check) {

                    $response = [
                        'success' => 'Pickup Request Complete'
                    ];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }

        return response()->json($response);

    }

}
