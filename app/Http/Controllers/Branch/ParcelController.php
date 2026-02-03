<?php

namespace App\Http\Controllers\Branch;

use App\Exports\BranchParcelExport;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\ServiceType;
use App\Models\WeightPackage;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ParcelController extends Controller {

    public function allParcelList() {
        $data               = [];
        $data['main_menu']  = 'allParcel';
        $data['child_menu'] = 'allParcelList';
        $data['page_title'] = 'All Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchants']  = Merchant::with('branch')
            // ->where('branch_id', auth()->guard('branch')->user()->branch_id)
            ->orderBy('company_name', 'ASC')
            ->get();

        // dd( $data['merchants']);
        return view('branch.parcel.parcel.allParcelList', $data);
    }

    public function allRiderParcelList() {
        $data               = [];
        $data['main_menu']  = 'allRiderParcelList';
        $data['child_menu'] = 'allRiderParcelList';
        $data['page_title'] = 'All Rider Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        $data['merchants']  = Merchant::with('branch')
            // ->where('branch_id', auth()->guard('branch')->user()->branch_id)
            ->orderBy('company_name', 'ASC')
            ->get();
        $data['riders'] = Rider::with('branch')
            ->where('branch_id', auth()->guard('branch')->user()->branch_id)
            ->orderBy('name')
            ->get();
        return view('branch.parcel.parcel.allRiderParcelList', $data);
    }

    public function getAllParcelList(Request $request) {
        
       
        $branch_user = auth()->guard('branch')->user();
        $branch_id   = $branch_user->branch->id;
        $branch_type = $branch_user->branch->type;

        if ($branch_type == 1) {
             $where_condition = " (pickup_branch_id = {$branch_id} or delivery_branch_id = {$branch_id})";

                // $where_condition = " (pickup_branch_id = {$branch_id} OR pickup_branch_id IS NULL) and (delivery_branch_id = {$branch_id} OR delivery_branch_id IS NULL)";
            //            $where_condition = "status NOT IN (2,3,4) and (pickup_branch_id = {$branch_id} OR delivery_branch_id = {$branch_id})";
            // $where_condition = "status NOT IN (2,3,4)";
        } else {
            $where_condition = "sub_branch_id = {$branch_id} and status NOT IN (2,3,4)";
        }

        $model = Parcel::with(['district', 'upazila', 'area', 'parcel_logs',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
        ])
            ->whereRaw($where_condition)
            ->select();

        // if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {

        $parcel_invoice = $request->get('parcel_invoice');
        if ($parcel_invoice) {
            
            $model->where(function($query)  use ($parcel_invoice)
            {
                $query->where('parcel_invoice','like', "{$parcel_invoice}")
                ->orWhere('merchant_order_id','like', "{$parcel_invoice}")
                ->orWhere('customer_contact_number','like', "%{$parcel_invoice}%")
                ->orWhere('customer_name','like', "%{$parcel_invoice}%")
                ->orWhere('customer_address','like', "%{$parcel_invoice}%");
            });
            // $model->where('parcel_invoice','like', "{$parcel_invoice}");
            // $model->orWhere('merchant_order_id','like', "{$parcel_invoice}");
            // $model->orWhere('customer_contact_number','like', "%{$parcel_invoice}%");
            // $model->orWhere('customer_name','like', "%{$parcel_invoice}%");
            // $model->orWhere('customer_address','like', "%{$parcel_invoice}%");
        }

        // if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {
        //     $model->where('parcel_invoice', $request->get('parcel_invoice'));
        // }

        // if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
        //     $model->where('merchant_order_id', $request->get('merchant_order_id'));
        // }

        // if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
        //     $model->where('customer_contact_number', 'like', '%' . $request->get('customer_contact_number') . '%');
        // }

        $parcel_status = $request->parcel_status;

        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

            if ($parcel_status == 1) {
                //    $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id]);
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                //    $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                $model->whereRaw('(delivery_branch_id = ? and ((status > 11 and status <= 25 and delivery_type IS NULL) or (status = 25 and delivery_type in (?))))', [$branch_id, 3]);
            } elseif ($parcel_status == 3) {

           //    $query->whereRaw('status = 3');
                //    $model->whereRaw('status >= ? and delivery_type in (?)', [25,2,4]);
                $model->whereRaw('delivery_branch_id = ' . $branch_id . ' and status >= 25 and delivery_type in (4)');
            } elseif ($parcel_status == 4) {
                $model->whereRaw('delivery_branch_id = ' . $branch_id . ' and status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('delivery_branch_id = ' . $branch_id . ' and status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                //    $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('return_branch_id = ' . $branch_id . ' and status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('pickup_branch_id = ' . $branch_id . ' and status in (1,2,4) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('(pickup_branch_id = ' . $branch_id . ' or delivery_branch_id = ' . $branch_id.') and status in (14) ');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('pickup_branch_id = ' . $branch_id . ' and status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('pickup_branch_id = ' . $branch_id . ' and status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('delivery_branch_id = ' . $branch_id . ' and status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('delivery_branch_id = ' . $branch_id . ' and status >= 25 and delivery_type in(3)');
            }

        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {

            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

                if ($parcel_status == 1) {
                    $model->whereDate('delivery_date', '>=', $request->get('from_date'));
                } elseif ($parcel_status == 6) {
                    $model->whereDate('return_branch_date', '>=', $request->get('from_date'));
                } elseif ($parcel_status == 9) {
                    $model->whereDate('pickup_branch_date', '>=', $request->get('from_date'));
                } else {
                    $model->whereDate('date', '>=', $request->get('from_date'));
                }

            } else {
                $model->whereDate('date', '>=', $request->get('from_date'));
            }

        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {

            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

                if ($parcel_status == 1) {
                    $model->whereDate('delivery_date', '<=', $request->get('to_date'));
                }  elseif ($parcel_status == 6) {
                    $model->whereDate('return_branch_date', '<=', $request->get('to_date'));
                }elseif ($parcel_status == 9) {
                    $model->whereDate('pickup_branch_date', '<=', $request->get('to_date'));
                } else {
                    $model->whereDate('date', '<=', $request->get('to_date'));
                }

            } else {
                $model->whereDate('date', '<=', $request->get('to_date'));
            }

        }

        // dd($model->toSql());
        return DataTables::of($model)

            // ->setRowClass('{{ ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime("+72 hours", strtotime($model->created_at))))&&($model->status<25)) ? "alert-warning" : "" }}')

            ->addIndexColumn()
            ->editColumn('parcel_status', function ($data) {
                $date_time = '---';

                if ($data->status >= 25) {

                    if ($data->delivery_type == 3) {
                        $date_time = date("Y-m-d", strtotime($data->reschedule_parcel_date));
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    }elseif ($data->delivery_type == 4) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_rider_date));
                    }

                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                }

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];

                //Status color red for message '72 hours exceed & <br>  delivery not complete'

                $status_data=  ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime("+24 hours", strtotime($data->created_at))))&&($data->status<25)) ? '<span class="text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p><p style="    background: #e55555;
                margin: auto;
                margin-top: 10px;
                width: fit-content;
                padding: 2px 5px;
                border-radius: 15px;
                font-size:10px;
                color: #fff;">' . '24 hours exceed'
                // color: #fff;">' . '24 hours exceed & <br>  delivery not complete'

                . '</p>' : '<span class="text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';
                
                if($data->return_branch){
                   $status_data.=optional($data->return_branch)->name; 
                }elseif($data->delivery_branch){
                   $status_data.=optional($data->delivery_branch)->name; 
                }elseif($data->pickup_branch){
                   $status_data.=optional($data->pickup_branch)->name; 
                }
                
                return $status_data;

                //Status color red for message '72 hours exceed & <br>  delivery not complete'
            })
            // ->editColumn('parcel_status', function($data) {
            //     return ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime("+72 hours", strtotime($data->created_at))))&&($data->status<25)) ? "<span style='background: red;
            //     display: block;
            //     width: 20px;
            //     height: 20px;
            //     border-radius: 50%;
            //     margin: auto;'></span>" : "";
            // })

            ->editColumn('payment_status', function ($data) {
                $parcelStatus = returnPaymentStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->editColumn('return_status', function ($data) {
                $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 3 || $data->status == 9) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            ->addColumn('parcel_info', function ($data) {
                $date_time   = $data->date . " " . date("h:i A", strtotime($data->created_at));
                $parcel_info = '<p><strong>Merchant Order ID: </strong>' . $data->merchant_order_id . '</p>';
                $parcel_info .= '<p><strong>Parcel OTP: </strong>' . $data->parcel_code . '</p>';
                $parcel_info .= '<p><strong>Service Type: </strong>' . optional($data->service_type)->title . '</p>';
                $parcel_info .= '<p><strong>Item Type: </strong>' . optional($data->item_type)->title . '</p>';
                $parcel_info .= '</span> <p><strong>Created Date: </strong>' .$date_time. '</p>';

                return $parcel_info;
            })

            // date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($date_time)))
            ->addColumn('company_info', function ($data) {
                $company_info = '<p><strong>Name: </strong>' . $data->merchant->company_name . '</p>';
                $company_info .= '<p><strong>Phone: </strong>' . $data->merchant->contact_number . '</p>';
                //                $company_info .= '<span><strong>Address: </strong>'.$data->merchant->address.'</span>';
                return $company_info;
            })
            ->addColumn('customer_info', function ($data) {
                $district = "---";

                if ($data->district) {
                    $district = $data->district->name;
                }

                $area = "---";

                if ($data->area) {
                    $area = $data->area->name;
                }

                $customer_info = '<p><strong>Name: </strong>' . $data->customer_name . '</p>';
                $customer_info .= '<p><strong>Number: </strong>' . $data->customer_contact_number . '</p>';
                $customer_info .= '<p><strong>District: </strong>' . $district . '</p>';
                $customer_info .= '<p><strong>Area: </strong>' . $area . '</p>';
                $customer_info .= '<span><strong>Address: </strong>' . $data->customer_address . '</span>';

                return $customer_info;
            })
            ->addColumn('amount', function ($data) {
                $amount = '<p><strong>Collection: </strong>' . $data->total_collect_amount . '</p>';
                $amount .= '<p><strong>Collected: </strong>' . $data->customer_collect_amount . '</p>';
                $amount .= '<p><strong>Total Charge: </strong>' . $data->total_charge . '</p>';
                $amount .= '<p><strong>COD Charge: </strong>' . $data->cod_charge . '</p>';
                return $amount;
            })
            ->addColumn('remarks', function ($data) {
                $logs_note = "";

                if ($data->parcel_logs) {

                    foreach ($data->parcel_logs as $parcel_log) {
                        $logs_note .= $parcel_log->note;

                        if (null != $parcel_log->note && "" != $parcel_log->note) {
                            $logs_note .= ",<br>";
                        }

                    }

                }

                $remarks = '<span><strong>Remarks: </strong>' . $data->parcel_note . '</span> <br>';
                $remarks .= '<span><strong>Notes: </strong>' . $logs_note . '</span>';
                return $remarks;
            })
            ->rawColumns([
                'parcel_invoice',
                'parcel_status',
                'payment_status',
                'return_status',
                'action',
                'image',
                'parcel_info',
                'company_info',
                'customer_info',
                'amount',
                'remarks',
            ])
            ->make(true);
    }

    public function getAllRiderParcelList(Request $request) {
        $branch_user = auth()->guard('branch')->user();
        $branch_id   = $branch_user->branch->id;
        $branch_type = $branch_user->branch->type;

        if ($branch_type == 1) {
            //    $where_condition = " (pickup_branch_id = {$branch_id} OR pickup_branch_id IS NULL) and (delivery_branch_id = {$branch_id} OR delivery_branch_id IS NULL)";
            $where_condition = "status NOT IN (2,3,4) and (pickup_branch_id = {$branch_id} OR delivery_branch_id = {$branch_id})";
        } else {
            $where_condition = "sub_branch_id = {$branch_id} and status NOT IN (2,3,4)";
        }

        $model = Parcel::with(['district', 'upazila', 'area', 'pickup_rider', 'delivery_rider', 'return_rider',
            'merchant'    => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },

        ])
            ->whereRaw($where_condition)

        //            ->where('pickup_rider_id','!=',null)

        //            ->orWhere('delivery_rider_id','!=',null)
                //            ->orWhere('return_rider_id','!=',null)
            ->select();

        $parcel_status = $request->parcel_status;

        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

            if ($parcel_status == 1) {
                //    $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id]);
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                //    $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                $model->whereRaw('status > 11 and status <= 25 and delivery_type IS NULL or (status = 25 and delivery_type in (?))', [3]);
            } elseif ($parcel_status == 3) {

            //    $query->whereRaw('status = 3');
                //    $model->whereRaw('status >= ? and delivery_type in (?)', [25,2,4]);
                $model->whereRaw('return_branch_id = ' . $branch_id . ' and status >= 25 and delivery_type in (4)');
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                //    $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1,2,4) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            }

        }

        if ($request->has('pickup_rider_id') && !is_null($request->get('pickup_rider_id')) && $request->get('pickup_rider_id') != 0) {
            $model->where('pickup_rider_id', $request->get('pickup_rider_id'));
        }

        if ($request->has('delivery_rider_id') && !is_null($request->get('delivery_rider_id')) && $request->get('delivery_rider_id') != 0) {
            $model->where('delivery_rider_id', $request->get('delivery_rider_id'));
        }

        if ($request->has('return_rider_id') && !is_null($request->get('return_rider_id')) && $request->get('return_rider_id') != 0) {
            $model->where('return_rider_id', $request->get('return_rider_id'));
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('parcel_date', '>=', $request->get('from_date'));
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('parcel_date', '<=', $request->get('to_date'));
        }

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_status', function ($data) {
                $date_time = '---';

                if ($data->status >= 25) {

                    if ($data->delivery_type == 3) {
                        $date_time = date("Y-m-d", strtotime($data->reschedule_parcel_date));
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2 ) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    } elseif ($data->delivery_type == 4) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_rider_date));
                    }

                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                }

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';
            })
            ->editColumn('payment_status', function ($data) {
                $parcelStatus = returnPaymentStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->editColumn('return_status', function ($data) {
                $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name  = $parcelStatus['status_name'];
                $class        = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 3 || $data->status == 9 ) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            ->addColumn('parcel_info', function ($data) {
                $date_time   = $data->date . " " . date("h:i A", strtotime($data->created_at));
                $parcel_info = '<p><strong>Merchant Order ID: </strong>' . $data->merchant_order_id . '</p>';
                $parcel_info .= '<p><strong>Parcel OTP: </strong>' . $data->parcel_code . '</p>';
                $parcel_info .= '<p><strong>Service Type: </strong>' . optional($data->service_type)->title . '</p>';
                $parcel_info .= '<p><strong>Item Type: </strong>' . optional($data->item_type)->title . '</p>';
                $parcel_info .= '</span> <p><strong>Created Date: </strong>' . $date_time . '</p>';
                return $parcel_info;
            })
            ->addColumn('company_info', function ($data) {
                $company_info = '<p><strong>Name: </strong>' . $data->merchant->company_name . '</p>';
                $company_info .= '<p><strong>Phone: </strong>' . $data->merchant->contact_number . '</p>';
                //                $company_info .= '<span><strong>Address: </strong>'.$data->merchant->address.'</span>';
                return $company_info;
            })
            ->addColumn('customer_info', function ($data) {
                $district = "---";

                if ($data->district) {
                    $district = $data->district->name;
                }

                $area = "---";

                if ($data->area) {
                    $area = $data->area->name;
                }

                $customer_info = '<p><strong>Name: </strong>' . $data->customer_name . '</p>';
                $customer_info .= '<p><strong>Number: </strong>' . $data->customer_contact_number . '</p>';
                $customer_info .= '<p><strong>District: </strong>' . $district . '</p>';
                $customer_info .= '<p><strong>Area: </strong>' . $area . '</p>';
                $customer_info .= '<span><strong>Address: </strong>' . $data->customer_address . '</span>';

                return $customer_info;
            })
            ->addColumn('amount', function ($data) {
                $amount = '<p><strong>Collection: </strong>' . $data->total_collect_amount . '</p>';
                $amount .= '<p><strong>Collected: </strong>' . $data->customer_collect_amount . '</p>';
                $amount .= '<p><strong>Total Charge: </strong>' . $data->total_charge . '</p>';
                $amount .= '<p><strong>COD Charge: </strong>' . $data->cod_charge . '</p>';
                return $amount;
            })
            ->addColumn('remarks', function ($data) {
                $logs_note = "";

                if ($data->parcel_logs) {

                    foreach ($data->parcel_logs as $parcel_log) {

                        if ("" != $logs_note) {
                            $logs_note .= ",<br>";
                        }

                        $logs_note .= $parcel_log->note;
                    }

                }

                $remarks = '<span><strong>Remarks: </strong>' . $data->parcel_note . '</span> <br>';
                $remarks .= '<span><strong>Notes: </strong>' . $logs_note . '</span>';
                return $remarks;
            })
            ->rawColumns([
                'parcel_invoice',
                'parcel_status',
                'payment_status',
                'return_status',
                'action',
                'image',
                'parcel_info',
                'company_info',
                'customer_info',
                'amount',
                'remarks',
            ])
            ->make(true);
    }

    public function printAllParcelList(Request $request) {
        $branch_user = auth()->guard('branch')->user();
        $branch_id   = $branch_user->branch->id;
        $branch_type = $branch_user->branch->type;

        if ($branch_type == 1) {
            //    $where_condition = " (pickup_branch_id = {$branch_id} OR pickup_branch_id IS NULL) and (delivery_branch_id = {$branch_id} OR delivery_branch_id IS NULL)";
            $where_condition = "status NOT IN (2,3,4) and (pickup_branch_id = {$branch_id} OR delivery_branch_id = {$branch_id})";
        } else {
            $where_condition = "sub_branch_id = {$branch_id} and status NOT IN (2,3,4)";
        }

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant'    => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },

        ])
            ->whereRaw($where_condition)
            ->select();

        if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {
            $model->where('parcel_invoice', $request->get('parcel_invoice'));
        }

        if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('merchant_order_id'));
        }

        if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
            $model->where('customer_contact_number', 'like', '%' . $request->get('customer_contact_number') . '%');
        }

        $filter = [];

        $parcel_status = $request->parcel_status;

        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

            if ($parcel_status == 1) {
                //    $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id]);
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                //    $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                $model->whereRaw('status > 11 and status <= 25 and delivery_type IS NULL or (status = 25 and delivery_type in (?))', [3]);
            } elseif ($parcel_status == 3) {

             //    $query->whereRaw('status = 3');
                //    $model->whereRaw('status >= ? and delivery_type in (?)', [25,2,4]);
                $model->whereRaw('return_branch_id = ' . $branch_id . ' and status >= 25 and delivery_type in (4)');
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                //    $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1,2,4) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            }

            $filter['parcel_status'] = $parcel_status;
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
            $filter['merchant_id'] = $request->get('merchant_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('parcel_date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('parcel_date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $parcels = $model->get();
        return view('branch.parcel.parcel.printParcelList', compact('parcels', 'filter'));
    }

    public function excelAllParcelList(Request $request)
    {
        $fileName= 'parcel_'.time().'.xlsx';
        return Excel::download(new BranchParcelExport($request), $fileName);
    }
    public function printAllRiderParcelList(Request $request) {
        $branch_user = auth()->guard('branch')->user();
        $branch_id   = $branch_user->branch->id;
        $branch_type = $branch_user->branch->type;

        if ($branch_type == 1) {
            //    $where_condition = " (pickup_branch_id = {$branch_id} OR pickup_branch_id IS NULL) and (delivery_branch_id = {$branch_id} OR delivery_branch_id IS NULL)";
            $where_condition = "status NOT IN (2,3,4) and (pickup_branch_id = {$branch_id} OR delivery_branch_id = {$branch_id})";
        } else {
            $where_condition = "sub_branch_id = {$branch_id} and status NOT IN (2,3,4)";
        }

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant'    => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },

        ])
            ->whereRaw($where_condition)

            //            ->where('pickup_rider_id','!=',null)

            //            ->orWhere('delivery_rider_id','!=',null)
                    //            ->orWhere('return_rider_id','!=',null)
            ->select();
        $filter = [];

        $parcel_status = $request->parcel_status;

        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {

            if ($parcel_status == 1) {
                //    $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id = ? and status >= 25 and delivery_type in (1,2)', [$branch_id]);
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                //    $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                $model->whereRaw('status > 11 and status <= 25 and delivery_type IS NULL or (status = 25 and delivery_type in (?))', [3]);
            } elseif ($parcel_status == 3) {

          //    $query->whereRaw('status = 3');
                //    $model->whereRaw('status >= ? and delivery_type in (?)', [25,2,4]);
                $model->whereRaw('return_branch_id = ' . $branch_id . ' and status >= 25 and delivery_type in (4)');
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                //    $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1,2,4) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            }

            $filter['parcel_status'] = $parcel_status;
        }

        if ($request->has('pickup_rider_id') && !is_null($request->get('pickup_rider_id')) && $request->get('pickup_rider_id') != 0) {
            $model->where('pickup_rider_id', $request->get('pickup_rider_id'));
            $filter['pickup_rider_id'] = $request->get('pickup_rider_id');
        }

        if ($request->has('delivery_rider_id') && !is_null($request->get('delivery_rider_id')) && $request->get('delivery_rider_id') != 0) {
            $model->where('delivery_rider_id', $request->get('delivery_rider_id'));
            $filter['delivery_rider_id'] = $request->get('delivery_rider_id');
        }

        if ($request->has('return_rider_id') && !is_null($request->get('return_rider_id')) && $request->get('return_rider_id') != 0) {
            $model->where('return_rider_id', $request->get('return_rider_id'));
            $filter['return_rider_id'] = $request->get('return_rider_id');
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
            $filter['merchant_id'] = $request->get('merchant_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('parcel_date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('parcel_date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $parcels = $model->get();
        return view('branch.parcel.parcel.printRiderParcelList', compact('parcels', 'filter'));
    }

    public function add() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'addParcel';
        $data['page_title'] = 'Add Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['districts']  = District::where('status', 1)->get();
        $data['merchants'] = Merchant::with('branch')->where('branch_id', auth()->guard('branch')->user()->branch_id)->where('status', 1)->get();
        return view('branch.parcel.parcel.addParcel', $data);
    }

     // For getting customer Info -->

    public function customerInfo(Request $request) {
        $phone = $request->phone;
        // dd($phone);
        $customer = Parcel::where('customer_contact_number', $phone)->select('customer_name', 'customer_address')->first();
        return response()->json($customer);
    }

    // For getting customer Info -->

    public function store(Request $request) {
       //        dd($request->all());
        $validator = Validator::make($request->all(), [
            'merchant_id'                         => 'required',
            'cod_percent'                         => 'required',
            'cod_charge'                          => 'required',
            'delivery_charge'                     => 'required',
            'weight_package_charge'               => 'required',
            'merchant_service_area_charge'        => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge'                        => 'required',
            'weight_package_id'                   => 'required',
            'delivery_option_id'                  => 'required',
            //            'product_details' => 'required',
            'product_value'                       => 'sometimes',
            'total_collect_amount'                => 'sometimes',
            'customer_name'                       => 'required',
            'customer_contact_number'             => 'required|numeric|digits:11',
            'customer_address'                    => 'required',
            'district_id'                         => 'required',
            // 'upazila_id'                   => 'required',
            'area_id'                             => 'sometimes',
            'parcel_note'                         => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $branch = auth()->guard('branch')->user();

            $branchType = $branch->branch->type;

            if ($branchType == 1) {
                $pickup_branch_id      = $branch->branch_id;
                $pickup_branch_user_id = $branch->id;
                $pickup_branch_date    = date("Y-m-d");
                $sub_branch_id         = NULL;
                $status                = 11;
            } else {
                $pickup_branch_id      = $branch->branch->parent_id;
                $pickup_branch_user_id = NULL;
                $pickup_branch_date    = NULL;
                $sub_branch_id         = $branch->branch_id;
                $status                = 1;
            }

            $merchant_id = $request->input('merchant_id');

            $data = [
                'parcel_invoice'                      => $this->returnUniqueParcelInvoice(),
                'merchant_id'                         => $merchant_id,
                'sub_branch_id'                       => $sub_branch_id,
                'date'                                => date('Y-m-d'),
                'merchant_order_id'                   => $request->input('merchant_order_id'),
                'shop_id'                             => $request->input('shop_id'),
                'pickup_address'                      => $request->input('pickup_address'),
                'customer_name'                       => $request->input('customer_name'),
                'customer_address'                    => $request->input('customer_address'),
                'customer_contact_number'             => $request->input('customer_contact_number'),
                'product_details'                     => $request->input('product_details'),
                'product_value'                       => $request->input('product_value'),
                'district_id'                         => $request->input('district_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id'                          => 0,
                'area_id'                             => $request->input('area_id') ?? 0,
                'weight_package_id'                   => $request->input('weight_package_id'),
                'delivery_charge'                     => $request->input('delivery_charge'),
                'weight_package_charge'               => $request->input('weight_package_charge'),
                'merchant_service_area_charge'        => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount'                => $request->input('total_collect_amount') ?? 0,
                'cod_percent'                         => $request->input('cod_percent'),
                'cod_charge'                          => $request->input('cod_charge'),
                'total_charge'                        => $request->input('total_charge'),
                'item_type_charge'                    => $request->input('item_type_charge'),
                'service_type_charge'                 => $request->input('service_type_charge'),
                'delivery_option_id'                  => $request->input('delivery_option_id'),
                'parcel_note'                         => $request->input('parcel_note'),
                'pickup_branch_id'                    => $pickup_branch_id,
                'pickup_branch_user_id'               => $pickup_branch_user_id,
                'pickup_branch_date'                  => $pickup_branch_date,
                'parcel_date'                         => date('Y-m-d'),
                  //                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'service_type_id'                     => $request->input('service_type_id'),
                //                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'item_type_id'                        => $request->input('item_type_id'),
                'status'                              => $status,
            ];

            //            if ($request->input('item_type_id') != 0) {

            //                $data['service_type_id'] = null;

            //            }
                        //            dd($data);
            $parcel = Parcel::create($data);

            if (!empty($parcel)) {

                $data = [
                    'parcel_id'             => $parcel->id,
                    'merchant_id'           => $request->input('merchant_id'),
                    'sub_branch_id'         => $sub_branch_id,
                    'pickup_branch_id'      => $pickup_branch_id,
                    'pickup_branch_user_id' => $pickup_branch_user_id,
                    'date'                  => date('Y-m-d'),
                    'time'                  => date('H:i:s'),
                    'status'                => $status,
                    'delivery_type'         => $parcel->delivery_type,
                ];
                ParcelLog::create($data);

                $merchant = Merchant::find($merchant_id);

                /*+++++++++++++++++++++++++++
                // PaperFly order Placement
                +++++++++++++++++++++++++++++*/
                $parameter_data = json_encode([
                    "merOrderRef"          => $parcel->parcel_invoice,
                    "pickMerchantName"     => $merchant->name,
                    "pickMerchantAddress"  => $merchant->address,
                    // "pickMerchantThana"     => $merchant->upazila->name,
                    "pickMerchantDistrict" => $merchant->district->name,
                    "pickupMerchantPhone"  => $merchant->contact_number,
                    "productSizeWeight"    => "statndard",
                    "ProductBrief"         => $parcel->product_details,
                    "packagePrice"         => $parcel->total_collect_amount,
                    "max_weight"           => "1",
                    "deliveryOption"       => "regular",
                    "custname"             => $parcel->customer_name,
                    "custaddress"          => $parcel->customer_address,
                    // "customerThana"         => $parcel->upazila->name,
                    "customerDistrict"     => $parcel->district->name,
                    "custPhone"            => $parcel->customer_contact_number,
                ]);

                // $order_placement = json_decode($this->callPaperFlyAPI($parameter_data), true);

                // if($order_placement && $order_placement['response_code'] == 200){

                //     $tracking_number = $order_placement['success']['tracking_number'];

                //     Parcel::where('id', $parcel->id)

                //     ->update([

                //         'tracking_number' => $tracking_number

                //     ]);

                // }
                                /*+++++++++++++++++++++++++++
                // PaperFly order Placement
                +++++++++++++++++++++++++++++*/

                \DB::commit();

                $this->setMessage('Parcel Create Successfully', 'success');
                
                $url = route('parcel.printParcel',$parcel->id);
                
                echo "<script>window.open('".$url."', '_blank')</script>";
                
                $url1=route('branch.parcel.add');
                
                echo "<script> setTimeout(function() {window.location.href = '".$url1."'}, 1000);</script>";
                
                // return redirect()->back();
                 //                return redirect()->route('branch.parcel.allParcelList');
            } else {
                $this->setMessage('Parcel Create Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function editParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data               = [];
        $data['main_menu']  = 'allParcel';
        $data['child_menu'] = 'allParcel';
        $data['page_title'] = 'Update Pickup Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['parcel']     = $parcel;
        $data['districts']  = District::where([
            ['status', '=', 1],
        ])->get();

            // $data['upazilas'] = Upazila::where([

            //     ['district_id', '=', $parcel->district->id],

            //     ['status', '=', 1],
                    // ])->get();

        $data['areas'] = Area::where([
            ['district_id', '=', $parcel->district_id],
            ['status', '=', 1],
        ])->get();

        $service_area_id = $parcel->district->service_area_id;

        $data['weightPackages'] = WeightPackage::with([
            'service_area' => function ($query) use ($service_area_id) {
                $query->where('service_area_id', '=', $service_area_id);
            },
        ])
            ->where([
                ['status', '=', 1],
                ['weight_type', '=', 1],
            ])->get();
        $data['serviceTypes'] = ServiceType::where('service_area_id', $service_area_id)->get();
        $data['itemTypes']    = ItemType::where('service_area_id', $service_area_id)->get();

        return view('branch.parcel.parcel.editParcel', $data);
    }

    public function confirmEditParcel(Request $request, Parcel $parcel) {
       //        dd($request->all());
        $validator = Validator::make($request->all(), [
            'cod_percent'                         => 'required',
            'cod_charge'                          => 'required',
            'delivery_charge'                     => 'required',
            'weight_package_charge'               => 'required',
            'merchant_service_area_charge'        => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge'                        => 'required',
            'weight_package_id'                   => 'required',
            'delivery_option_id'                  => 'required',
            //            'product_details' => 'required',
            'product_value'                       => 'sometimes',
            'total_collect_amount'                => 'sometimes',
            'customer_name'                       => 'required',
            'customer_contact_number'             => 'required|numeric|digits:11',
            'customer_address'                    => 'required',
            'district_id'                         => 'required',
            // 'upazila_id'                   => 'required',
            'area_id'                             => 'sometimes',
            'parcel_note'                         => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $branch = auth()->guard('branch')->user();

            $branchType = $branch->branch->type;

            if ($branchType == 1) {
                $pickup_branch_id      = $branch->branch_id;
                $pickup_branch_user_id = $branch->id;
                //    $pickup_branch_date         = date("Y-m-d");
                $sub_branch_id = NULL;
                //    $status                     = 11;
            } else {
                $pickup_branch_id      = $branch->branch->parent_id;
                $pickup_branch_user_id = NULL;
                //    $pickup_branch_date         = NULL;
                $sub_branch_id = $branch->branch_id;
                //    $status                     = 1;
            }

            $data = [
                'merchant_order_id'                   => $request->input('merchant_order_id'),
                'customer_name'                       => $request->input('customer_name'),
                'customer_address'                    => $request->input('customer_address'),
                'customer_contact_number'             => $request->input('customer_contact_number'),
                'product_details'                     => $request->input('product_details'),
                'district_id'                         => $request->input('district_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id'                          => 0,
                'area_id'                             => $request->input('area_id') ?? 0,
                'weight_package_id'                   => $request->input('weight_package_id'),
                'delivery_charge'                     => $request->input('delivery_charge'),
                'weight_package_charge'               => $request->input('weight_package_charge'),
                'merchant_service_area_charge'        => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount'                => $request->input('total_collect_amount') ?? 0,
                'product_value'                       => $request->input('product_value'),
                'cod_percent'                         => $request->input('cod_percent'),
                'cod_charge'                          => $request->input('cod_charge'),
                'item_type_charge'                    => $request->input('item_type_charge'),
                'service_type_charge'                 => $request->input('service_type_charge'),
                'total_charge'                        => $request->input('total_charge'),
                'delivery_option_id'                  => $request->input('delivery_option_id'),
                'service_type_id'                     => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id'                        => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'parcel_note'                         => $request->input('parcel_note'),
            ];
            /* if ($request->input('item_type_id') != 0) {
            $data['service_type_id'] = null;
            }*/
            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id'             => $parcel->id,
                    'date'                  => date('Y-m-d'),
                    'time'                  => date('H:i:s'),
                    'status'                => $parcel->status,
                    'sub_branch_id'         => $sub_branch_id,
                    'pickup_branch_id'      => $pickup_branch_id,
                    'pickup_branch_user_id' => $pickup_branch_user_id,
                    'delivery_type'         => $parcel->delivery_type,
                ];
                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.allParcelList');
            } else {
                $this->setMessage('Parcel Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function viewParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'service_type', 'item_type', 'upazila', 'area', 'merchant', 'merchant_shops', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->orderBy('id', 'desc')->get();

        $parcelBranchPaymentDeltails = ParcelDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->get();
        $parcelMerchantPaymentDeltails = ParcelMerchantDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->get();

        return view('branch.parcel.viewParcel', compact('parcel', 'parcelLogs', 'parcelBranchPaymentDeltails', 'parcelMerchantPaymentDeltails'));
    }

    public function getMerchantInfo(Request $request) {

        if ($request->ajax()) {

            $merchant_data  = Merchant::where('id', $request->merchant_id)->first();
            $merchant_shops = $merchant_data->merchant_shops;

            $merchant_shop_option = '<option value="0">----Select----</option>';

            if ($merchant_shops != "") {

                foreach ($merchant_shops as $shop) {
                    $merchant_shop_option .= '<option value="' . $shop->id . '" data-shop_address="' . $shop->shop_address . '">' . $shop->shop_name . '</option>';
                }

            }

            $response = [
                'merchant_data'        => $merchant_data,
                'merchant_shop_option' => $merchant_shop_option,
                'success'              => true,
            ];

            return response(json_encode($response));

        }

    }

}
