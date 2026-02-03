<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnRiderRunParcelController extends Controller {

    public function returnRiderRunList() {
        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnRiderRunList';
        $data['page_title'] = 'Return Rider List';
        $data['collapse']   = 'sidebar-collapse';
        $data['riders']     = Rider::where([
            'status'    => 1,
            'branch_id' =>  auth()->guard('branch')->user()->branch->id,
        ])
        ->select('id', 'name', 'contact_number', 'address')
        ->get();
        return view('branch.parcel.returnParcel.returnRiderRunList', $data);
    }

    public function getReturnRiderRunList(Request $request) {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $model = RiderRun::with(['rider' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
                ])
                ->whereRaw('branch_id = ? and run_type = 3', [$branch_id])
                ->orderBy('id', 'desc')
                ->select();

                if ($request->has('run_status') && ! is_null($request->get('run_status')) && $request->get('run_status') != 0 ) {
                    $model->where('status', $request->get('run_status'));
                }
                elseif($request->get('run_status') == ''){
                    $model->whereIn('status', [1,2]);
                }
                else{
                    $model->whereIn('status', [1,2,3,4]);
                }

                if ($request->has('rider_id') && ! is_null($request->get('rider_id')) && $request->get('rider_id') != 0 ) {
                    $model->where('rider_id', $request->get('rider_id'));
                }
                if ($request->has('from_date') && ! is_null($request->get('from_date')) && $request->get('from_date') != 0 ) {
                    $model->whereDate('create_date_time', '>=', $request->get('from_date'));
                }
                if ($request->has('to_date') && ! is_null($request->get('to_date')) && $request->get('to_date') != 0 ) {
                    $model->whereDate('create_date_time', '<=', $request->get('to_date'));
                }

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('create_date_time', function ($data) {
                return date('d-m-Y H:i:s', strtotime($data->create_date_time));
            })
            ->editColumn('start_date_time', function ($data) {
                return ($data->start_date_time) ? date('d-m-Y H:i:s', strtotime($data->start_date_time)) : "";
            })
            ->editColumn('cancel_date_time', function ($data) {
                return ($data->cancel_date_time) ? date('d-m-Y H:i:s', strtotime($data->cancel_date_time)) : "";
            })
            ->editColumn('complete_date_time', function ($data) {
                return ($data->complete_date_time) ? date('d-m-Y H:i:s', strtotime($data->complete_date_time)) : "";
            })

            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 : $status_name  = "Run Create"; $class  = "success";break;
                    case 2 : $status_name  = "Run Start"; $class  = "success";break;
                    case 3 : $status_name  = "Run Cancel"; $class  = "danger";break;
                    case 4 : $status_name  = "Run Complete"; $class  = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.printReturnRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Return Rider Run" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Return Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Return Run Cancel">
                    <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnRiderRun', $data->id) . '" class="btn btn-info btn-sm" title="Edit Return Run" >
                        <i class="fas fa-edit"></i> </a>';
                }
                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" title="Reconciliation Return Run">
                    <i class="fa fa-check"></i> </button> ';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }


    public function printReturnRiderRunList(Request $request) {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $model = RiderRun::with(['rider' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
                ])
                ->whereRaw('branch_id = ? and run_type = 3', [$branch_id])
                ->orderBy('id', 'desc')
                ->select();
        $filter=[];

                if ($request->has('run_status') && ! is_null($request->get('run_status')) && $request->get('run_status') != 0 ) {
                    $model->where('status', $request->get('run_status'));
                    $filter['run_status']=$request->get('run_status');
                }
                elseif($request->get('run_status') == ''){
                    $model->whereIn('status', [1,2]);
                }
                else{
                    $model->whereIn('status', [1,2,3,4]);
                }

                if ($request->has('rider_id') && ! is_null($request->get('rider_id')) && $request->get('rider_id') != 0 ) {
                    $model->where('rider_id', $request->get('rider_id'));
                    $filter['rider_id']=$request->get('rider_id');
                }
                if ($request->has('from_date') && ! is_null($request->get('from_date')) && $request->get('from_date') != 0 ) {
                    $model->whereDate('create_date_time', '>=', $request->get('from_date'));
                    $filter['from_date']=$request->get('from_date');
                }
                if ($request->has('to_date') && ! is_null($request->get('to_date')) && $request->get('to_date') != 0 ) {
                    $model->whereDate('create_date_time', '<=', $request->get('to_date'));
                    $filter['to_date']=$request->get('to_date');
                }
        $riderRuns = $model->get();
        return view('branch.parcel.returnParcel.printReturnRiderRunList', compact('riderRuns','filter'));

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('create_date_time', function ($data) {
                return date('d-m-Y H:i:s', strtotime($data->create_date_time));
            })
            ->editColumn('start_date_time', function ($data) {
                return ($data->start_date_time) ? date('d-m-Y H:i:s', strtotime($data->start_date_time)) : "";
            })
            ->editColumn('cancel_date_time', function ($data) {
                return ($data->cancel_date_time) ? date('d-m-Y H:i:s', strtotime($data->cancel_date_time)) : "";
            })
            ->editColumn('complete_date_time', function ($data) {
                return ($data->complete_date_time) ? date('d-m-Y H:i:s', strtotime($data->complete_date_time)) : "";
            })

            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 : $status_name  = "Run Create"; $class  = "success";break;
                    case 2 : $status_name  = "Run Start"; $class  = "success";break;
                    case 3 : $status_name  = "Run Cancel"; $class  = "danger";break;
                    case 4 : $status_name  = "Run Complete"; $class  = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.printReturnRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Return Rider Run" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Return Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Return Run Cancel">
                    <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnRiderRun', $data->id) . '" class="btn btn-info btn-sm" title="Edit Return Run" >
                        <i class="fas fa-edit"></i> </a>';
                }
                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" title="Reconciliation Return Run">
                    <i class="fa fa-check"></i> </button> ';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }


    public function printReturnRiderRun(Request $request,  RiderRun $riderRun) {
        $riderRun->load('branch', 'rider', 'rider_run_details.parcel.parcel_logs');
        return view('branch.parcel.returnParcel.printReturnRiderRun', compact('riderRun'));
    }


    public function viewReturnRiderRun(Request $request, RiderRun $riderRun) {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.returnParcel.viewReturnRiderRun', compact('riderRun'));
    }


    public function startReturnRiderRun(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            }
            else {
                \DB::beginTransaction();
                try {
                    $branch_id      = auth()->guard('branch')->user()->branch->id;
                    $branch_user_id = auth()->guard('branch')->user()->id;

                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'start_date_time' => date('Y-m-d H:i:s'),
                        'status'          => 2,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();
                        foreach ($riderRunDetails as $riderRunDetail) {
                            Parcel::where('id', $riderRunDetail->parcel_id)->update([
                                'status'                    => 31,
                                'return_branch_id'          => $branch_id,
                                'return_branch_user_id'     => $branch_user_id,
                                'parcel_date'               => date('Y-m-d'),
                            ]);
                            $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            ParcelLog::create([
                                'parcel_id'                 => $riderRunDetail->parcel_id,
                                'return_branch_id'          => $branch_id,
                                'return_branch_user_id'     => $branch_user_id,
                                'date'                      => date('Y-m-d'),
                                'time'                      => date('H:i:s'),
                                'status'                    => 31,
                                'delivery_type' => $parcel->delivery_type,
                            ]);

                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 2,
                            ]);

                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();

                            $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        }

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Return Rider Run Start Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }

                } catch (\Exception$e) {
                    \DB::rollback();
                    $this->setMessage('Database Error Found', 'danger');
                    return redirect()->back()->withInput();
                }
            }
        }
        return response()->json($response);
    }


    public function cancelReturnRiderRun(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {
                    $branch_id      = auth()->guard('branch')->user()->branch->id;
                    $branch_user_id = auth()->guard('branch')->user()->id;


                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'cancel_date_time' => date('Y-m-d H:i:s'),
                        'status'           => 3,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();
                        foreach ($riderRunDetails as $riderRunDetail) {
                            Parcel::where('id', $riderRunDetail->parcel_id)->update([
                                'status'      => 32,
                                'parcel_date' => date('Y-m-d'),
                                'delivery_branch_id'        => $branch_id,
                                'delivery_branch_user_id'   => $branch_user_id,
                            ]);
                            $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            ParcelLog::create([
                                'parcel_id'        => $riderRunDetail->parcel_id,
                                'delivery_branch_id'        => $branch_id,
                                'delivery_branch_user_id'   => $branch_user_id,
                                'date'             => date('Y-m-d'),
                                'time'             => date('H:i:s'),
                                'status'           => 32,
                                'delivery_type' => $parcel->delivery_type,
                            ]);
                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 3,
                            ]);

                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                        }

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Return Rider Run Cancel Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception$e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }



    public function returnRiderRunGenerate() {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnRiderRunGenerate';
        $data['page_title'] = 'Return Rider Run Generate';
        $data['collapse']   = 'sidebar-collapse';
        $data['riders']     = Rider::with(['rider_runs' => function($query){
                    $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
                }])
                ->where([
                    'status'    => 1,
                    'branch_id' => $branch_id,
                ])
                ->select('id', 'name', 'contact_number', 'address')
                ->get();
                
        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
        }])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();


        $data['merchants'] = Parcel::where([
            'return_branch_id' => $branch_id,
        ])
            ->whereIn('status', [28, 32, 34])
            ->select('merchant_id')
            ->distinct()
            ->orderBy('merchant_id', 'ASC')
            ->get();

        $data['parcels'] = Parcel::with([
            'merchant:id,name,company_name,contact_number,address',
            'parcel_logs:id,note',
            ])
            ->where([
                'return_branch_id' => $branch_id,
            ])
            ->whereRaw('status in (28,32,34)')
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
            ->orderBy('id', 'DESC')
            ->get();

        return view('branch.parcel.returnParcel.returnRiderRunGenerate', $data);
    }


    public function returnReturnRiderRunParcel(Request $request) {
        $branch_id              = auth()->guard('branch')->user()->branch->id;
        $branch_user_id         = auth()->guard('branch')->user()->id;

        $parcel_invoice_barcode = $request->input('parcel_invoice_barcode');
        $parcel_invoice         = $request->input('parcel_invoice');
      //  $merchant_order_id      = $request->input('merchant_order_id');

        $merchant_order_id      = $request->input('merchant_order_id');
        $merchant_id            = $request->input('merchant_id');

        if (!empty($parcel_invoice_barcode) || !empty($parcel_invoice) || !empty($merchant_order_id) || !empty($merchant_id)) {

            $data['parcels'] = Parcel::with([
                'merchant:id,name,company_name,contact_number,address',
                'parcel_logs:id,note',
                ])
                ->where(function ($query) use ($branch_id, $parcel_invoice_barcode, $parcel_invoice, $merchant_order_id, $merchant_id) {
                    $query->whereRaw('status in (28,32,34)');
                    $query->where([
                        'return_branch_id' => $branch_id,
                    ]);

                    if (!empty($parcel_invoice)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice,
                        ]);
                    } elseif (!empty($merchant_order_id)) {
                        $query->where([
                            'customer_contact_number' => $merchant_order_id,
                            
                        ]);
                         $query->orWhere([
                            'merchant_order_id' => $merchant_order_id,
                            
                        ]);
                        
                    } elseif (!empty($merchant_id)) {
                        $query->where([
                            'merchant_id' => $merchant_id,
                        ]);
                    }
                })
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'customer_collect_amount')
                ->get();
        } else {
            $data['parcels'] = [];
        }

        $parcels = $data['parcels'];
        return view('branch.parcel.returnParcel.returnRiderRunParcel', $data);
    }


    public function returnRiderRunParcelAddCart(Request $request) {
        $branch_id              = auth()->guard('branch')->user()->branch->id;
        $branch_user_id         = auth()->guard('branch')->user()->id;
        $parcels        = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            ])
            ->whereIn('id', $request->parcel_invoices)
            ->orWhereIn('parcel_invoice', $request->parcel_invoices)
            ->whereRaw('status in (28,32,34) and return_branch_id = ?', [$branch_id])
            ->get();


        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag    = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id'              => $cart_id,
                        'name'            => $parcel->merchant->name,
                        'price'           => 1,
                        'quantity'        => 1,
                        'target'          => 'subtotal',
                        'attributes'      => [
                            'parcel_invoice'          => $parcel->parcel_invoice,
                            'customer_name'           => $parcel->customer_name,
                            'customer_address'        => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name'           => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option'                  => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";
            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        }
        else {
            $error      = "Parcel Invoice Not Found";
            $cart       = \Cart::session($branch_id)->getContent();
            $cart       = $cart->sortBy('id');
            $totalItem  = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal   = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'getTotal'  => $getTotal,
            'error'     => $error,
        ];
        return view('branch.parcel.returnParcel.returnRiderRunParcelCart', $data);
    }


    public function returnRiderEditRunParcelAddCart(Request $request) {
        $branch_id              = auth()->guard('branch')->user()->branch->id;
        $branch_user_id         = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
        $parcels        = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            ])
            ->whereIn('id', $request->parcel_invoices)
            ->where([
                'return_branch_id' => $branch_id,
            ])
            -> whereRaw('status in (28,32,34)')
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag    = 0;
                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id'              => $cart_id,
                        'name'            => $parcel->merchant->name,
                        'price'           => 1,
                        'quantity'        => 1,
                        'target'          => 'subtotal',
                        'attributes'      => [
                            'parcel_invoice'          => $parcel->parcel_invoice,
                            'customer_name'           => $parcel->customer_name,
                            'customer_address'        => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name'           => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option'                  => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";
            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        }
        else {
            $error = "Parcel Invoice Not Found";
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'getTotal'  => $getTotal,
            'error'     => $error,
        ];
        return view('branch.parcel.returnParcel.returnRiderRunParcelCart', $data);
    }


    public function returnRiderRunParcelDeleteCart(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart'      => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal'  => \Cart::session($branch_id)->getTotal(),
            'error'     => "",
        ];
        return view('branch.parcel.returnParcel.returnRiderRunParcelCart', $data);
    }


    public function confirmReturnRiderRunGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id'         => 'required',
            'date'             => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        \DB::beginTransaction();
        try {

            $branch_id              = auth()->guard('branch')->user()->branch->id;
            $branch_user_id         = auth()->guard('branch')->user()->id;

            $data = [
                'run_invoice'      => $this->returnUniqueRiderRunInvoice(),
                'rider_id'         => $request->input('rider_id'),
                'branch_id'        => $branch_id,
                'branch_user_id'   => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note'             => $request->input('note'),
                'run_type'         => 3,
                'status'           => 2,
            ];
            $riderRun = RiderRun::create($data);

            if ($riderRun) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    RiderRunDetail::create([
                        'rider_run_id' => $riderRun->id,
                        'parcel_id'    => $parcel_id,
                        'status' => 4,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'status'            => 33,
                        'parcel_date'       => $request->input('date'),
                        'return_rider_id'   => $request->input('rider_id'),
                        'return_branch_id'  => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel=Parcel::where('id', $parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'             => $parcel_id,
                        'return_rider_id'       => $request->input('rider_id'),
                        'return_branch_id'      => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                        'date'                  => date('Y-m-d'),
                        'time'                  => date('H:i:s'),
                        'status'                => 33,
                        'delivery_type' => $parcel->delivery_type,
                    ]);


                    $parcel = Parcel::where('id', $parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Return Rider Run Insert Successfully', 'success');
                return redirect()->route('branch.parcel.returnRiderRunGenerate');
            }
            else{
                $this->setMessage('Return Rider Run Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function editReturnRiderRun(Request $request, RiderRun $riderRun) {
        $riderRun->load('branch', 'rider', 'rider_run_details');

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        foreach ($riderRun->rider_run_details as $rider_run_detail) {
            $cart_id = $rider_run_detail->parcel->id;

            \Cart::session($branch_id)->add([
                'id'              => $cart_id,
                'name'            => $rider_run_detail->parcel->merchant->name,
                'price'           => 1,
                'quantity'        => 1,
                'target'          => 'subtotal',
                'attributes'      => [
                    'parcel_invoice'          => $rider_run_detail->parcel->parcel_invoice,
                    'customer_name'           => $rider_run_detail->parcel->customer_name,
                    'customer_address'        => $rider_run_detail->parcel->customer_address,
                    'customer_contact_number' => $rider_run_detail->parcel->customer_contact_number,
                    'merchant_name'           => $rider_run_detail->parcel->merchant->name,
                    'merchant_contact_number' => $rider_run_detail->parcel->merchant->contact_number,
                    'option'                  => [],
                ],
                'associatedModel' => $rider_run_detail->parcel,
            ]);
        }

        $data               = [];
        $cart               = \Cart::session($branch_id)->getContent();
        $data['cart']       = $cart->sortBy('id');
        $data['totalItem']  = \Cart::session($branch_id)->getTotalQuantity();
        $data['getTotal']   = \Cart::session($branch_id)->getTotal();


        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnRiderRunGenerate';
        $data['page_title'] = 'Return Rider List';
        $data['collapse']   = 'sidebar-collapse';
        $data['riderRun']   = $riderRun;

        $data['riders']     = Rider::with(['rider_runs' => function($query){
                    $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
                }])
                ->where([
                'status'    => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();


        $data['merchants'] = Parcel::where([
            'return_branch_id' => $branch_id,
        ])
            ->whereIn('status', [28, 32, 34])
            ->select('merchant_id')
            ->distinct()
            ->orderBy('merchant_id', 'ASC')
            ->get();

        $data['parcels']    = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'company_name', 'address');
            },
            ])
            ->where([
                'return_branch_id' => $branch_id,
            ])
            ->whereRaw('status in (28, 32,34)')
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
            ->get();

        return view('branch.parcel.returnParcel.returnRiderRunGenerateEdit', $data);
    }


    public function confirmReturnRiderRunGenerateEdit(Request $request, RiderRun $riderRun) {

        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id'         => 'required',
            'date'             => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        \DB::beginTransaction();
        try {
            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            $data = [
                'rider_id'         => $request->input('rider_id'),
                'branch_id'        => $branch_id,
                'branch_user_id'   => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note'             => $request->input('note'),
                'status'           => 1,
            ];
            $check = RiderRun::where('id', $riderRun->id)->update($data) ? true : false;
            if ($check) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');
                $riderRiderRunDetails = RiderRunDetail::where('rider_run_id', $riderRun->id)->get();

                foreach($riderRiderRunDetails as $riderRiderRunDetail){
                    Parcel::where('id', $riderRiderRunDetail->parcel_id)->update([
                        'status'          => 28,
                    ]);
                }
                RiderRunDetail::where('rider_run_id', $riderRun->id)->delete();


                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    RiderRunDetail::create([
                        'rider_run_id' => $riderRun->id,
                        'parcel_id'    => $parcel_id,
                    ]);

                    Parcel::where('id', $parcel_id)->update([
                        'status'          => 30,
                        'parcel_date'     => $request->input('date'),
                        'return_rider_id' => $request->input('rider_id'),
                        'return_branch_id' => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel=Parcel::where('id', $parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'        => $parcel_id,
                        'return_rider_id'  => $request->input('rider_id'),
                        'return_branch_id' => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                        'date'             => date('Y-m-d'),
                        'time'             => date('H:i:s'),
                        'status'           => 30,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    $parcel = Parcel::where('id', $parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Rider Run Update Successfully', 'success');
                return redirect()->route('branch.parcel.returnRiderRunList');
            }
            else {
                $this->setMessage('Rider Run Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function returnRiderRunReconciliation(Request $request, RiderRun $riderRun) {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.returnParcel.returnRiderRunReconciliation', compact('riderRun'));
    }

    public function confirmReturnRiderRunReconciliation(Request $request, RiderRun $riderRun) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'run_note'                  => 'sometimes',
                'total_run_complete_parcel' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            }
            else{
                \DB::beginTransaction();
                try {
                    $check = RiderRun::where('id', $riderRun->id)
                    ->update([
                        'complete_date_time'        => date('Y-m-d H:i:s'),
                        'total_run_complete_parcel' => $request->total_run_complete_parcel,
                        'note'                      => $request->run_note,
                        'status'                    => 4,
                    ]);
                    if ($check) {
                        $rider_run_details_id = $request->rider_run_details_id;
                        $rider_run_status     = $request->rider_run_status;
                        $complete_note        = $request->complete_note;
                        $parcel_id            = $request->parcel_id;
                        $count                = count($rider_run_details_id);

                        for ($i = 0; $i < $count; $i++) {
                            RiderRunDetail::where('id', $rider_run_details_id[$i])->update([
                                'complete_note'      => $complete_note[$i],
                                'complete_date_time' => date('Y-m-d H:i:s'),
                                'status'             => $rider_run_status[$i],
                            ]);
                            $status = 34;
                            if($rider_run_status[$i] == 7){
                                $status = 36;
                            }

                            Parcel::where('id', $parcel_id[$i])->update([
                                'status'             => $status,
                                'parcel_date'        => date('Y-m-d'),
                                'return_branch_date' => date('Y-m-d'),
                            ]);
                            $parcel=Parcel::where('id', $parcel_id[$i])->first();
                            ParcelLog::create([
                                'parcel_id'             => $parcel_id[$i],
                                'return_branch_id'      => auth()->guard('branch')->user()->branch->id,
                                'return_branch_user_id' => auth()->guard('branch')->user()->id,
                                'date'                  => date('Y-m-d'),
                                'time'                  => date('H:i:s'),
                                'note'                  => $complete_note[$i],
                                'status'                => $status,
                                'delivery_type' => $parcel->delivery_type,
                            ]);

                            $parcel = Parcel::where('id', $parcel_id[$i])->first();

                            $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                            // $this->branchDashboardCounterEvent($parcel->return_branch_id);
                        }

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Return Rider Run Reconciliation Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

}
