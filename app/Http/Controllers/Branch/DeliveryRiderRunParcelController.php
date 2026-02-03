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

class DeliveryRiderRunParcelController extends Controller
{


    public function deliveryRiderRunList()
    {
        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryRiderRunList';
        $data['page_title'] = 'Delivery Rider List';
        $data['collapse'] = 'sidebar-collapse';
        $data['riders'] = Rider::where([
            'status' => 1,
            'branch_id' => auth()->guard('branch')->user()->branch->id,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();
        return view('branch.parcel.deliveryParcel.deliveryRiderRunList', $data);
    }

    public function getDeliveryRiderRunList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $model = RiderRun::with(['rider' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereRaw('branch_id = ? and run_type = 2', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();

        if ($request->has('run_status') && !is_null($request->get('run_status')) && $request->get('run_status') != 0) {
            $model->where('status', $request->get('run_status'));
        } elseif ($request->get('run_status') == '') {
            $model->whereIn('status', [1, 2]);
        } else {
            $model->whereIn('status', [1, 2, 3, 4]);
        }
        if ($request->has('rider_id') && !is_null($request->get('rider_id')) && $request->get('rider_id') != 0) {
            $model->where('rider_id', $request->get('rider_id'));
        }
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('create_date_time', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
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
            ->addColumn('total_amount', function ($data) {
                $parcels=$data->rider_run_details;
                $total_amount = 0;
                foreach($parcels as $parcel){
                    
                $total_amount += $parcel->parcel->total_collect_amount;
                }
                
                return $total_amount;
            })
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 :
                        $status_name = "Run Create";
                        $class = "success";
                        break;
                    case 2 :
                        $status_name = "Run Start";
                        $class = "success";
                        break;
                    case 3 :
                        $status_name = "Run Cancel";
                        $class = "danger";
                        break;
                    case 4 :
                        $status_name = "Run Complete";
                        $class = "success";
                        break;
                    default :
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.printDeliveryRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Delivery Rider Run" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Delivery Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Delivery Run Cancel">
                    <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryRiderRunGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Delivery Run" >
                        <i class="fas fa-edit"></i> </a>';
                }
                if ($data->status == 2) {
                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryRiderRunGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Delivery Run" >
                        <i class="fas fa-edit"></i> </a>';
                        
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                    <i class="fa fa-check"></i> </button> ';
                    
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time','total_amount'])
            ->make(true);
    }


    public function printDeliveryRiderRunList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $model = RiderRun::with(['rider' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereRaw('branch_id = ? and run_type = 2', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $filter=[];

        if ($request->has('run_status') && !is_null($request->get('run_status')) && $request->get('run_status') != 0) {
            $model->where('status', $request->get('run_status'));
            $filter['run_status']=$request->get('run_status');
        } elseif ($request->get('run_status') == '') {
            $model->whereIn('status', [1, 2]);
        } else {
            $model->whereIn('status', [1, 2, 3, 4]);
        }
        if ($request->has('rider_id') && !is_null($request->get('rider_id')) && $request->get('rider_id') != 0) {
            $model->where('rider_id', $request->get('rider_id'));
            $filter['rider_id']=$request->get('rider_id');
        }
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('create_date_time', '>=', $request->get('from_date'));
            $filter['from_date']=$request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('create_date_time', '<=', $request->get('to_date'));
            $filter['to_date']=$request->get('to_date');
        }

        $riderRuns = $model->get();
        return view('branch.parcel.deliveryParcel.printDeliveryRiderRunList', compact('riderRuns', 'filter'));

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
                    case 1 :
                        $status_name = "Run Create";
                        $class = "success";
                        break;
                    case 2 :
                        $status_name = "Run Start";
                        $class = "success";
                        break;
                    case 3 :
                        $status_name = "Run Cancel";
                        $class = "danger";
                        break;
                    case 4 :
                        $status_name = "Run Complete";
                        $class = "success";
                        break;
                    default :
                        $status_name = "None";
                        $class = "success";
                        break;
                }
                return '<a class="text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.printDeliveryRiderRun', $data->id) . '" class="btn btn-success btn-sm" title="Print Delivery Rider Run" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Delivery Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Delivery Run Cancel">
                    <i class="far fa-window-close"></i> </button>';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryRiderRunGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Delivery Run" >
                        <i class="fas fa-edit"></i> </a>';
                }
                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '" >
                    <i class="fa fa-check"></i> </button> ';
                }
                return $button;
            })
            ->rawColumns(['action', 'status', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }


    public function viewDeliveryRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.deliveryParcel.viewDeliveryRiderRun', compact('riderRun'));
    }

    public function printDeliveryRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.deliveryParcel.printDeliveryRiderRun', compact('riderRun'));
    }


    public function startDeliveryRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $branch_id = auth()->guard('branch')->user()->branch->id;
                $branch_user_id = auth()->guard('branch')->user()->id;

                \DB::beginTransaction();
                try {
                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'start_date_time' => date('Y-m-d H:i:s'),
                        'status' => 2,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();
                        foreach ($riderRunDetails as $riderRunDetail) {
                            $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        $parcel->update([
                                'status' => 19,
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                                'parcel_date' => date('Y-m-d'),
                                'delivery_rider_date' => date('Y-m-d'),
                            ]);
                            ParcelLog::create([
                                'parcel_id' => $riderRunDetail->parcel_id,
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 19,
                                'delivery_type' => $parcel->delivery_type,
                            ]);

                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 4,
                            ]);
                            
                            


                            // $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            // $message = "Dear ".$parcel->customer_name.", ";
                            // $message .= "Your sms OTP ".$parcel->parcel_code.", ";
                            // $message .= "For  parcel ID No ".$parcel->parcel_invoice.".";
                            // $message .= "Please rate your experience with us in our https://www.facebook.com/beaconcourier.com.bd.";
                            // $this->send_sms($parcel->customer_contact_number, $message);
                            
                            // if ($parcel->delivery_rider->id != 18 && $parcel->delivery_rider->id != 1) {

                            
                            // if($parcel->delivery_rider->id!=18) {

                            $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            
                            $message = "Dear " . $parcel->customer_name . ", ";
                            $message .= "Your OTP " . $parcel->parcel_code . ". \n";
                            $message .= "Parcel from " . $parcel->merchant->company_name . " (TK " . $parcel->total_collect_amount . ")";
                            $message .= " will be delivered by " . $parcel->delivery_rider->name . ", " . $parcel->delivery_rider->contact_number . ".\n";
                            $message .= " Track here: " . route('frontend.orderTracking') . "?trackingBox=" . $parcel->parcel_invoice . "   \n- SafeDelivery";
                            $this->send_sms($parcel->customer_contact_number, $message);
                            
                                                            //  }

                            $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                            // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Delivery Rider Run Start Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => $e->getMessage()];
//                    $response = ['error' => 'Database Error Found' ];
                }
            }
        }
        return response()->json($response);
    }


    public function cancelDeliveryRiderRun(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_run_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {

                $branch_id = auth()->guard('branch')->user()->branch->id;
                $branch_user_id = auth()->guard('branch')->user()->id;

                \DB::beginTransaction();
                try {

                    $check = RiderRun::where('id', $request->rider_run_id)->update([
                        'cancel_date_time' => date('Y-m-d H:i:s'),
                        'status' => 3,
                    ]);

                    if ($check) {
                        $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                        foreach ($riderRunDetails as $riderRunDetail) {
                            $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        $parcel->update([
                                'status' => 18,
                                'parcel_date' => date('Y-m-d'),
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                            ]);
                            ParcelLog::create([
                                'parcel_id' => $riderRunDetail->parcel_id,
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 18,
                                'delivery_type' => $parcel->delivery_type,
                            ]);
                            RiderRunDetail::where('id', $riderRunDetail->id)->update([
                                'status' => 3,
                            ]);
                            // $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                            // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }


                        \DB::commit();

                        //$this->adminDashboardCounterEvent();
                        $response = ['success' => 'Delivery Rider Run Cancel Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => "Database Error Found"];
                }
            }
        }
        return response()->json($response);
    }


    public function deliveryRiderRunGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryRiderRunGenerate';
        $data['page_title'] = 'Delivery Rider Run Generate';
        $data['collapse'] = 'sidebar-collapse';
        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
        }])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $parcels = Parcel::with(['rider_run_detail.rider_run','merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number');
        },
        ])
            ->whereRaw('((status = 25 AND delivery_type = 3) OR status in (14,18,20)) and delivery_branch_id = ?', $branch_id)
            ->select('id', 'parcel_invoice', 'status', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'customer_address', 'merchant_id','total_collect_amount','cod_charge','total_charge', 'created_at')
            ->orderBy('id', 'DESC')
            ->get();
            // dd($parcels);
            
            
            foreach ($parcels as $key => $parcel) {
                foreach ($parcel->rider_run_detail as $rider_run_detail) {
                      if ($rider_run_detail->rider_run->status == 2) {
                          $parcels->forget($key);
                      }
                }
            }
            
            
            $data['parcels'] = $parcels;

        return view('branch.parcel.deliveryParcel.deliveryRiderRunGenerate', $data);
    }


    public function returnDeliveryRiderRunParcel(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice) || !empty($merchant_order_id)) {


            $parcels = Parcel::with(['rider_run_detail.rider_run','merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number');
            },
            ])
                ->whereRaw('((status = 25 AND delivery_type = 3) OR status in (14,18,20)) and delivery_branch_id = ?', $branch_id)
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {
                    if (!empty($parcel_invoice)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice,
                        ]);
                    } elseif (!empty($merchant_order_id)) {
                        // $query->where([
                        //     'merchant_order_id' => $merchant_order_id,
                        // ]);
                        
                         $query->where([
                            'customer_contact_number' => $merchant_order_id,
                            
                        ]);
                         $query->orWhere([
                            'merchant_order_id' => $merchant_order_id,
                            
                        ]);
                    }
                })
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'customer_address', 'merchant_id','total_collect_amount','cod_charge','total_charge')
                ->get();
                
                foreach ($parcels as $key => $parcel) {
                    foreach ($parcel->rider_run_detail as $rider_run_detail) {
                          if ($rider_run_detail->rider_run->status == 2) {
                              $parcels->forget($key);
                          }
                    }
                }

        } else {
            $parcels = [];
        }
        
        

        $data['parcels'] = $parcels;
        return view('branch.parcel.deliveryParcel.deliveryRiderRunParcel', $data);
    }


    public function deliveryRiderRunParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        $parcel_invoice = $request->input('parcel_invoice');

         $parcels = Parcel::with(['rider_run_detail.rider_run','merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->orWhereIn('parcel_invoice', $request->parcel_invoices)
            ->whereRaw('delivery_branch_id = ? and ((status = 25 AND delivery_type in (3)) OR status in (14,18,20))', [$branch_id])
            ->get();
            
             foreach ($parcels as $key => $parcel) {
                foreach ($parcel->rider_run_detail as $rider_run_detail) {
                      if ($rider_run_detail->rider_run->status == 2) {
                          $parcels->forget($key);
                      }
                }
            }


            // dd($parcels);
        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->merchant->name,
                        'price' => 1,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.deliveryParcel.deliveryRiderRunParcelCart', $data);
    }


   public function deliveryRiderEditRunParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
         $parcels = Parcel::with(['rider_run_detail.rider_run','merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->orWhereIn('parcel_invoice', $request->parcel_invoices)
            ->whereRaw('delivery_branch_id = ? and ((status = 25 AND delivery_type in (3)) OR status in (14,18,20))', [$branch_id])
            ->get();
            
             foreach ($parcels as $key => $parcel) {
                foreach ($parcel->rider_run_detail as $rider_run_detail) {
                      if ($rider_run_detail->rider_run->status == 2) {
                          $parcels->forget($key);
                      }
                }
            }

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->merchant->name,
                        'price' => 1,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.deliveryParcel.deliveryRiderRunParcelCart', $data);
    }



    public function deliveryRiderRunParcelDeleteCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.parcel.deliveryParcel.deliveryRiderRunParcelCart', $data);
    }

    public function confirmDeliveryRiderRunGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
         // \DB::beginTransaction();
        try {
            
            
            $checkRiderRun = RiderRun::where('rider_id',$request->input('rider_id'))->where('run_type',2)->where('status','<=',2)->first();
            if($checkRiderRun){
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');
                
                if($checkRiderRun->status==2){
                    foreach ($cart as $item) {
                        $parcel_id = $item->id;
                        $RiderRunDetail = RiderRunDetail::create([
                            'rider_run_id' => $checkRiderRun->id,
                            'parcel_id' => $parcel_id,
                            'status' => 4,
                        ]);
                        // dd($RiderRunDetail);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                           
                        $parcel->update([
                            'status' => 19,
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'parcel_date' => date('Y-m-d'),
                            'delivery_rider_date' => date('Y-m-d'),
                            'delivery_rider_id' => $request->input('rider_id'),
                            // 'delivery_rider_accept_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $parcel->id,
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_rider_id' => $request->input('rider_id'),
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 19,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }
                    
                    $total_run_parcel = $checkRiderRun->rider_run_details->count();
                    $checkRiderRun->update(['total_run_parcel'=>$total_run_parcel]);
                }else{
                    foreach ($cart as $item) {
                        $parcel_id = $item->id;
                        RiderRunDetail::create([
                            'rider_run_id' => $checkRiderRun->id,
                            'parcel_id' => $parcel_id,
                        ]);
    
                        $parcel=Parcel::where('id', $parcel_id)->first();
                            $parcel->update([
                            'status' => 16,
                            'parcel_date' => $request->input('date'),
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_rider_accept_date' => date('Y-m-d'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
    
                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 16,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }
                    
                    $total_run_parcel = $checkRiderRun->rider_run_details->count();
                    $checkRiderRun->update(['total_run_parcel'=>$total_run_parcel]);
                    
                }
                
                $this->setMessage('Delivery Rider Run Insert Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryRiderRunList');
            }else{
                
                $data = [
                    'run_invoice' => $this->returnUniqueRiderRunInvoice(),
                    'rider_id' => $request->input('rider_id'),
                    'branch_id' => $branch_id,
                    'branch_user_id' => $branch_user_id,
                    'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'total_run_parcel' => $request->input('total_run_parcel'),
                    'note' => $request->input('note'),
                    'run_type' => 2,
                    'status' => 1,
                ];
                $riderRun = RiderRun::create($data);
    
                if ($riderRun) {
                    $cart = \Cart::session($branch_id)->getContent();
                    $cart = $cart->sortBy('id');
    
                    foreach ($cart as $item) {
                        $parcel_id = $item->id;
    
                        RiderRunDetail::create([
                            'rider_run_id' => $riderRun->id,
                            'parcel_id' => $parcel_id,
                        ]);
    
                        $parcel=Parcel::where('id', $parcel_id)->first();
                            $parcel->update([
                            'status' => 16,
                            'parcel_date' => $request->input('date'),
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_rider_accept_date' => date('Y-m-d'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
    
                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 16,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
    
                        $parcel = Parcel::where('id', $parcel_id)->first();
    
                        $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                        // $merchant_user->notify(new MerchantParcelNotification($parcel));
    
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);
    
                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }
    
    
                    // $this->adminDashboardCounterEvent();
    
                    $this->setMessage('Delivery Rider Run Insert Successfully', 'success');
                    return redirect()->route('branch.parcel.deliveryRiderRunList');
                } else {
                    $this->setMessage('Delivery Rider Run Insert Failed', 'danger');
                    return redirect()->back()->withInput();
                }
            }
            
        // \DB::commit();
        } catch (\Exception $e) {
            // \DB::rollback();
            dd($e->getMessage());
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }


    // public function confirmDeliveryRiderRunGenerate(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'total_run_parcel' => 'required',
    //         'rider_id' => 'required',
    //         'date' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withInput()->withErrors($validator);
    //     }

    //     $branch_id = auth()->guard('branch')->user()->branch->id;
    //     $branch_user_id = auth()->guard('branch')->user()->id;

    //     \DB::beginTransaction();
    //     try {

    //         $data = [
    //             'run_invoice' => $this->returnUniqueRiderRunInvoice(),
    //             'rider_id' => $request->input('rider_id'),
    //             'branch_id' => $branch_id,
    //             'branch_user_id' => $branch_user_id,
    //             'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
    //             'total_run_parcel' => $request->input('total_run_parcel'),
    //             'note' => $request->input('note'),
    //             'run_type' => 2,
    //             'status' => 1,
    //         ];
    //         $riderRun = RiderRun::create($data);

    //         if ($riderRun) {
    //             $cart = \Cart::session($branch_id)->getContent();
    //             $cart = $cart->sortBy('id');

    //             foreach ($cart as $item) {
    //                 $parcel_id = $item->id;

    //                 RiderRunDetail::create([
    //                     'rider_run_id' => $riderRun->id,
    //                     'parcel_id' => $parcel_id,
    //                 ]);

    //                 $parcel=Parcel::where('id', $parcel_id)->first();
    //                     $parcel->update([
    //                     'status' => 16,
    //                     'parcel_date' => $request->input('date'),
    //                     'delivery_rider_id' => $request->input('rider_id'),
    //                     'delivery_rider_accept_date' => date('Y-m-d'),
    //                     'delivery_branch_id' => $branch_id,
    //                     'delivery_branch_user_id' => $branch_user_id,
    //                 ]);

    //                 ParcelLog::create([
    //                     'parcel_id' => $parcel_id,
    //                     'delivery_rider_id' => $request->input('rider_id'),
    //                     'delivery_branch_id' => $branch_id,
    //                     'delivery_branch_user_id' => $branch_user_id,
    //                     'date' => date('Y-m-d'),
    //                     'time' => date('H:i:s'),
    //                     'status' => 16,
    //                     'delivery_type' => $parcel->delivery_type,
    //                 ]);

    //                 $parcel = Parcel::where('id', $parcel_id)->first();

    //                 $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
    //                 // $merchant_user->notify(new MerchantParcelNotification($parcel));

    //                 // $this->merchantDashboardCounterEvent($parcel->merchant_id);

    //                 // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
    //             }

    //             \DB::commit();

    //             // $this->adminDashboardCounterEvent();

    //             $this->setMessage('Delivery Rider Run Insert Successfully', 'success');
    //             return redirect()->route('branch.parcel.deliveryRiderRunList');
    //         } else {
    //             $this->setMessage('Delivery Rider Run Insert Failed', 'danger');
    //             return redirect()->back()->withInput();
    //         }
    //     } catch (\Exception $e) {
    //         \DB::rollback();
    //         $this->setMessage('Database Error Found', 'danger');
    //         return redirect()->back()->withInput();
    //     }
    // }


    public function deliveryRiderRunGenerateEdit(Request $request, RiderRun $riderRun)
    {

        $riderRun->load('branch', 'rider', 'rider_run_details');

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        foreach ($riderRun->rider_run_details as $rider_run_detail) {
            $cart_id = $rider_run_detail->parcel->id;

            \Cart::session($branch_id)->add([
                'id' => $cart_id,
                'name' => $rider_run_detail->parcel->merchant->name,
                'price' => 1,
                'quantity' => 1,
                'target' => 'subtotal',
                'attributes' => [
                    'parcel_invoice' => $rider_run_detail->parcel->parcel_invoice,
                    'customer_name' => $rider_run_detail->parcel->customer_name,
                    'customer_address' => $rider_run_detail->parcel->customer_address,
                    'customer_contact_number' => $rider_run_detail->parcel->customer_contact_number,
                    'merchant_name' => $rider_run_detail->parcel->merchant->name,
                    'merchant_contact_number' => $rider_run_detail->parcel->merchant->contact_number,
                    'option' => [],
                ],
                'associatedModel' => $rider_run_detail->parcel,
            ]);
        }

        $data = [];
        $cart = \Cart::session($branch_id)->getContent();
        $data['cart'] = $cart->sortBy('id');
        $data['totalItem'] = \Cart::session($branch_id)->getTotalQuantity();
        $data['getTotal'] = \Cart::session($branch_id)->getTotal();


        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryRiderRunGenerate';
        $data['page_title'] = 'Delivery Rider List';
        $data['collapse'] = 'sidebar-collapse';
        $data['riderRun'] = $riderRun;

        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
        }])
            ->where([
                'status' => 1,
                'branch_id' => $branch_id,
            ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number');
        },
        ])
            ->where([
                'delivery_branch_id' => $branch_id,
            ])
            ->whereRaw('status in (14,18,20)')
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'customer_address', 'merchant_id')
            ->get();

        return view('branch.parcel.deliveryParcel.deliveryRiderRunGenerateEdit', $data);
    }

    public function confirmDeliveryRiderRunGenerateEdit(Request $request, RiderRun $riderRun)
    {

        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {

            $data = [
                'rider_id' => $request->input('rider_id'),
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note' => $request->input('note'),
                'run_type' => 2,
                // 'status' => 1,
            ];

            $check = RiderRun::where('id', $riderRun->id)->update($data) ? true : false;

            if ($check) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');
                
                
                 

                $riderRunDetails = RiderRunDetail::where('rider_run_id', $riderRun->id)->get();

                foreach ($riderRunDetails as $riderRunDetail) {
                    if (!$cart->has($riderRunDetail->parcel_id)){
                        Parcel::where('id', $riderRunDetail->parcel_id)->update([
                            'status' => 14,
                        ]);   
                        
                        RiderRunDetail::where('rider_run_id', $riderRun->id)->where('parcel_id', $riderRunDetail->parcel_id)->delete();
                    }
                }

                // RiderRunDetail::where('rider_run_id', $riderRun->id)->delete();

                foreach ($cart as $item) {
                    $parcel_id = $item->id;
                    
                    $check_old = RiderRunDetail::where('rider_run_id', $riderRun->id)->where('parcel_id', $parcel_id)->first();
                    
                    if(!$check_old){
                        RiderRunDetail::create([
                            'rider_run_id' => $riderRun->id,
                            'parcel_id' => $parcel_id,
                        ]);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                        $parcel->update([
                            'status' => 16,
                            'parcel_date' => $request->input('date'),
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_rider_accept_date' => date('Y-m-d'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
    
                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'delivery_rider_id' => $request->input('rider_id'),
                            'delivery_branch_id' => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 16,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }
                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Rider Run Update Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryRiderRunList');
            } else {
                $this->setMessage('Rider Run Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function deliveryRiderRunReconciliation(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.deliveryParcel.deliveryRiderRunReconciliation', compact('riderRun'));
    }

    public function confirmDeliveryRiderRunReconciliation(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'run_note' => 'sometimes',
                'rider_run_id' => 'required',
                'total_run_complete_parcel' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {

                \DB::beginTransaction();
                try {

                    $riderRun = RiderRun::where([
                        'id' => $request->rider_run_id,
                        'run_type' => 2,
                        'status' => 2,
                    ])
                        ->update([
                            'complete_date_time' => date('Y-m-d H:i:s'),
                            'total_run_complete_parcel' => $request->total_run_complete_parcel,
                            'note' => $request->run_note,
                            'status' => 4,
                        ]);


                    if ($riderRun) {
                        $rider_run_status = $request->rider_run_status;
                        $rider_run_details_id = $request->rider_run_details_id;
                        $parcel_id = $request->parcel_id;
                        $complete_type = $request->complete_type;
                        $customer_collect_amount = $request->customer_collect_amount;
                        $reschedule_parcel_date = $request->reschedule_parcel_date;
                        $complete_note = $request->complete_note;

                        $count = count($rider_run_details_id);

                        for ($i = 0; $i < $count; $i++) {
                            RiderRunDetail::where('id', $rider_run_details_id[$i])->update([
                                'complete_note' => $complete_note[$i],
                                'complete_date_time' => date('Y-m-d H:i:s'),
                                'status' => $rider_run_status[$i],
                            ]);

                            $parcel_update_data = [
                                'status' => 20,
//                                'parcel_note' => $complete_note[$i],
                                'parcel_date' => date('Y-m-d'),
                                'delivery_branch_date' => date('Y-m-d'),
                            ];

                            $parcel_log_create_data = [
                                'parcel_id' => $parcel_id[$i],
                                'pickup_branch_id' => auth()->guard('branch')->user()->id,
                                'date' => date('Y-m-d'),
                                'note' => $complete_note[$i],
                                'time' => date('H:i:s'),
                                'status' => 20,
                            ];

                            $sms_delivery_status = 0;
                            $sms_delivery_type = "";
                            $confirm_customer_collect_amount = $customer_collect_amount[$i];
                            
                            
                            $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            switch ($complete_type[$i]) {
                                case 21 :
                                    $parcel_update_data['status'] = 25;
                                    $parcel_update_data['customer_collect_amount'] = $confirm_customer_collect_amount;
                                    $parcel_update_data['delivery_type'] = 1;
                                    $parcel_update_data['delivery_date'] = date("Y-m-d");
                                    $parcel_log_create_data['status'] = 25;
                                    $sms_delivery_status = 1;
                                    $sms_delivery_type = "Delivered";
                                    break;

                                case 22 :
                                    $parcel_update_data['status'] = 25;
                                    $parcel_update_data['customer_collect_amount'] = $confirm_customer_collect_amount;
                                    $parcel_update_data['delivery_type'] = 2;
                                    $parcel_update_data['delivery_date'] = date("Y-m-d");
                                    $parcel_log_create_data['status'] = 25;
                                    $sms_delivery_status = 1;
                                    $sms_delivery_type = "Delivered";
                                    break;

                                case 23 :
                                    $parcel_update_data['status'] = 25;
                                    $parcel_update_data['reschedule_parcel_date'] = $reschedule_parcel_date[$i];
                                    $parcel_update_data['customer_collect_amount'] = 0;
                                    $parcel_update_data['delivery_type'] = 3;
                                    $parcel_log_create_data['status'] = 25;
                                    $parcel_log_create_data['reschedule_parcel_date'] = $reschedule_parcel_date[$i];
                                    $sms_delivery_status = 0;
                                    $sms_delivery_type = "";
                                    break;

                                case 24 :
                                    $parcel_update_data['status'] = 25;
                                    $parcel_update_data['delivery_type'] = 4;
                                    if ($parcel->cod_charge != 0) {
                                    $parcel_update_data['total_charge'] = ($parcel->total_charge - $parcel->cod_charge);
                                    }
                                    $parcel_update_data['customer_collect_amount'] = 0;
                                    $parcel_update_data['cod_percent'] = 0;
                                    $parcel_update_data['cod_charge'] = 0;
                                    $parcel_log_create_data['status'] = 25;
                                    $sms_delivery_status = 1;
                                    $sms_delivery_type = "Canceled";
                                    break;

                                default:

                                    break;
                            }
                            Parcel::where('id', $parcel_id[$i])->update($parcel_update_data);
                           $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            $parcel_log_create_data['delivery_type'] = $parcel->delivery_type;
                            ParcelLog::create($parcel_log_create_data);

//                            if ($sms_delivery_status == 1) {
                                $parcel = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();
                                $message = "Dear " . $parcel->merchant->name . ", ";
                                $message .= "Your Parcel ID No " . $parcel->parcel_invoice . "  is successfully " . $sms_delivery_type . ".";
                                $message .= "Please rate your experience https://www.facebook.com/SafeDelivery  \n-SafeDelivery";
                              //  $this->send_sms($parcel->merchant->contact_number, $message);
//                            }

                            $parcel = Parcel::where('id', $parcel_id[$i])->first();
                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                            // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();
                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Delivery Rider Run Reconciliation Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                    // $response = ['error' => $e->getMessage() ];
                }
            }
        }
        return response()->json($response);

    }

}
