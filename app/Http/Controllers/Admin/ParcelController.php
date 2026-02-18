<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminParcelExport;
use App\Http\Controllers\Controller;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\Branch;
use App\Models\Area;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Upazila;
use App\Models\WeightPackage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ParcelController extends Controller
{

    public function list()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('admin.parcel.parcelList', $data);
    }

    public function getParcelList(Request $request)
    {
        $model = Parcel::with(['district', 'upazila', 'area'])
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                $parcelStatus = returnParcelStatusNameForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];

                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" >
                <i class="fa fa-eye"></i> </button>';

                if (auth()->guard('admin')->user()->type == 1) {
                    $button .= '&nbsp; <a href="' . route('admin.parcel.editParcel', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                }

                if (auth()->guard('admin')->user()->type == 1 && $data->status < 3 || $data->status == 9) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '" >
                <i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            ->editColumn('service_type', function ($data) {
                return optional($data->service_type)->title;
            })
            ->editColumn('item_type', function ($data) {
                return optional($data->item_type)->title;
            })
            ->rawColumns(['status', 'action', 'service_type', 'item_type', 'image'])
            ->make(true);
    }


    public function allParcelList()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'allParcelList';
        $data['page_title'] = 'All Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        $data['merchants'] = Merchant::with('branch')
            ->orderBy('company_name', 'ASC')
            ->get();
        $data['branches'] = Branch::orderBy('name', 'ASC')
            ->get();

        return view('admin.parcel.allParcelList', $data);
    }

    public function getAllParcelList(Request $request)
    {
        //        dd($request->input('parcel_status'));
        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs'
            /*'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },*/
        ])
            ->whereRaw('pickup_branch_id IS NOT NULL')
            ->select();

        $parcel_status = $request->parcel_status;

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
        }

        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0 && $parcel_status == 13) {

            if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
                $model->whereRaw('pickup_branch_id = ' . $request->input('delivery_branch_id'));
            }
        } elseif ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0 && $parcel_status == 9) {
            if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
                $model->whereRaw('pickup_branch_id = ' . $request->input('delivery_branch_id'));
            }
        } elseif ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0 && $parcel_status == 14) {
            if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {

                $deliveryBranchId = $request->input('delivery_branch_id');
                $model->whereHas('merchant', function ($query) use ($deliveryBranchId) {
                    $query->where('on_board_branch_id', $deliveryBranchId);
                });
            }
        } else {
            if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
                $model->whereRaw('delivery_branch_id = ' . $request->input('delivery_branch_id'));
            }
        }


        if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {

            $parcel_invoice = $request->get('parcel_invoice');
            $model->where('parcel_invoice', 'like', "{$parcel_invoice}");
            $model->orWhere('merchant_order_id', 'like', "{$parcel_invoice}");
            $model->orWhere('customer_contact_number', 'like', "%{$parcel_invoice}%");
            $model->orWhere('customer_name', 'like', "%{$parcel_invoice}%");
            $model->orWhere('customer_address', 'like', "%{$parcel_invoice}%");
        }

        /*if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('merchant_order_id'));
        }

        if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
            $model->where('customer_contact_number','like', '%'.$request->get('customer_contact_number').'%' );
        }*/

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                if ($parcel_status == 1) {
                    $model->whereDate('delivery_date', '>=', $request->get('from_date'));
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
                } elseif ($parcel_status == 9) {
                    $model->whereDate('pickup_branch_date', '<=', $request->get('to_date'));
                } else {
                    $model->whereDate('date', '<=', $request->get('to_date'));
                }
            } else {
                $model->whereDate('date', '<=', $request->get('to_date'));
            }
        }
        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if ($parcel_status == 1) {
                // $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                //                $model->whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)');
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                // $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
                //                $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');
                $model->whereRaw('status >= 11 and status <= 25 and (delivery_type IS NULL OR (status in (23,25) and delivery_type = 3))');
                //  $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');

            } elseif ($parcel_status == 3) {
                // $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                // $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('status >= 25 and delivery_type in(3)');
            } elseif ($parcel_status == 13) {
                $model->whereRaw('status >= 11');
            } elseif ($parcel_status == 14) {
                $model->whereRaw('status >= 11');
            }
        }

        //        dd($model->toSql());
        return DataTables::of($model)
            ->addIndexColumn()
            /*->editColumn('date_time', function ($data) {
                $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                return $date_time;
            })
            ->editColumn('merchant.company_name', function ($data) {
                $company_name = ($data->merchant) ? $data->merchant->company_name : "Default";

                return $company_name;
            })
            ->editColumn('merchant.contact_number', function ($data) {
                $contact_number = ($data->merchant) ? $data->merchant->contact_number : "Not Provide";

                return $contact_number;
            })
            ->editColumn('log_notes', function ($data) {
                $logs_note = "";
                if ($data->parcel_logs) {
                    foreach ($data->parcel_logs as $parcel_log) {
                        if ("" != $logs_note) {
                            $logs_note .= ",<br>";
                        }
                        $logs_note .= $parcel_log->note;
                    }
                }
                return $logs_note;
            })
            ->editColumn('customer_district', function ($data) {
                $district = "";
                if ($data->district) {
                    $district = $data->district->name;
                }
                return $district;
            })
            ->editColumn('customer_upazila', function ($data) {
                $upazila = "";
                if ($data->upazila) {
                    $upazila = $data->upazila->name;
                }
                return $upazila;
            })
            ->editColumn('customer_area', function ($data) {
                $area = "";
                if ($data->area) {
                    $area = $data->area->name;
                }
                return $area;
            })
            ->editColumn('parcel_color', function ($data) {
                $parcelStatus = returnParcelStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $class = $parcelStatus['class'];
                return $class;
            })
            ->editColumn('service_type', function ($data) {
                return optional($data->service_type)->title;
            })
            ->editColumn('item_type', function ($data) {
                return optional($data->item_type)->title;
            })*/

            ->editColumn('parcel_invoice', function ($data) {
                return '<a href="' . route('admin.parcel.orderTracking', $data->parcel_invoice) . '"
                title="Parcel View">
                    ' . $data->parcel_invoice . '
                </a>';
            })
            ->editColumn('parcel_status', function ($data) {
                $date_time = '---';
                if ($data->status >= 25) {
                    if ($data->delivery_type == 3) {
                        $date_time = date("Y-m-d", strtotime($data->reschedule_parcel_date));
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    } elseif ($data->delivery_type == 4) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_rider_date));
                    }
                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                }
                $parcelStatus = returnParcelStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];

                $status_data = ((date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime("+24 hours", strtotime($data->created_at)))) && ($data->status < 25)) ? '<span class="text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p><p style="    background: #e55555;
                margin: auto;
                margin-top: 10px;
                width: fit-content;
                padding: 2px 5px;
                border-radius: 15px;
                font-size:10px;

                color: #fff;">' . '24 hours exceed'
                    // color: #fff;">' . '24 hours exceed & <br>  delivery not complete'

                    . '</p>' : '<span class="text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';

                if ($data->return_branch) {
                    $status_data .= optional($data->return_branch)->name;
                } elseif ($data->delivery_branch) {
                    $status_data .= optional($data->delivery_branch)->name;
                } elseif ($data->pickup_branch) {
                    $status_data .= optional($data->pickup_branch)->name;
                }

                return $status_data;






                // return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';
            })
            ->editColumn('payment_status', function ($data) {
                $parcelStatus = returnPaymentStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->editColumn('return_status', function ($data) {
                $parcelStatus = returnReturnStatusForAdmin($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';
            })
            ->addColumn('action', function ($data) {

                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" >
                <i class="fa fa-eye"></i> </button>';

                if (auth()->guard('admin')->user()->type == 1 && in_array($data->status, [1, 2, 3, 4, 5, 7, 11, 13, 14, 15, 18, 25, 27, 28, 29, 32, 36])) {
                    $button .= '&nbsp; <a href="' . route('admin.parcel.editParcel', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                }

                if (auth()->guard('admin')->user()->type == 1 && $data->status < 3 || $data->status == 9) {
                    //     $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '" >
                    // <i class="fa fa-trash"></i> </button>';
                }

                return $button;
            })
            ->addColumn('parcel_info', function ($data) {
                $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
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

    public function printAllParcelList(Request $request)
    {

        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },
        ])
            // ->WhereBetween('date', [$request->from_date, $request->to_date])
            // ->whereRaw('pickup_branch_id IS NOT NULL')
            ->orderBy('id', 'desc')
            ->select();

        $filter = [];

        $parcel_status = $request->parcel_status;
        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if ($parcel_status == 1) {
                // $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                // $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
                $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');
            } elseif ($parcel_status == 3) {
                // $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                // $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('status >= 25 and delivery_type in(3)');
            }
            $filter['parcel_status'] = $parcel_status;
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0) {
            $model->where('merchant_id', $request->get('merchant_id'));
            $filter['merchant_id'] = $request->get('merchant_id');
        }
        if ($request->has('delivery_branch_id') && !is_null($request->get('delivery_branch_id')) && $request->get('delivery_branch_id') != 0) {
            $model->where('delivery_branch_id', $request->get('delivery_branch_id'));
            $filter['delivery_branch_id'] = $request->get('delivery_branch_id');
        }

        if ($request->has('parcel_invoice') && !is_null($request->get('parcel_invoice')) && $request->get('parcel_invoice') != 0) {
            $model->where('parcel_invoice', $request->get('parcel_invoice'));
            $filter['parcel_invoice'] = $request->get('parcel_invoice');
        }

        if ($request->has('merchant_order_id') && !is_null($request->get('merchant_order_id')) && $request->get('merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('merchant_order_id'));
            $filter['merchant_order_id'] = $request->get('merchant_order_id');
        }

        if ($request->has('customer_contact_number') && !is_null($request->get('customer_contact_number')) && $request->get('customer_contact_number') != 0) {
            $model->where('customer_contact_number', $request->get('customer_contact_number'));
            $filter['customer_contact_number'] = $request->get('customer_contact_number');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }

        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }







        $parcels = $model->get();

        return view('admin.parcel.printParcelList', compact('parcels', 'filter'));
    }

    public function excelAllParcelList(Request $request)
    {
        $fileName = 'parcel_' . time() . '.xlsx';
        return Excel::download(new AdminParcelExport($request), $fileName);
    }


    public function viewParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_branch_user', 'pickup_rider', 'delivery_branch', 'delivery_branch_user', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->orderBy('id', 'DESC')->get();

        $parcelBranchPaymentDeltails = ParcelDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->with('parcel_delivery_payment')
            ->get();
        $parcelMerchantPaymentDeltails = ParcelMerchantDeliveryPaymentDetail::where('parcel_id', $parcel->id)
            ->orderBy('id', 'DESC')
            ->with('parcel_merchant_delivery_payment')
            ->get();
        return view('admin.parcel.viewParcel', compact('parcel', 'parcelLogs', 'parcelBranchPaymentDeltails', 'parcelMerchantPaymentDeltails'));
    }

    public function editParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $parcelLogs = ParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
            ->where('parcel_id', $parcel->id)->get();

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['parcelLogs'] = $parcelLogs;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        if (!empty($parcel->pickup_branch_id)) {
            $data['pickupRiders'] = Rider::where([
                ['status', '=', 1],
                ['branch_id', '=', $parcel->pickup_branch_id],
            ])->get();
        } else {
            $data['pickupRiders'] = [];
        }

        if (!empty($parcel->delivery_branch_id)) {
            $data['deliveryRiders'] = Rider::where([
                ['status', '=', 1],
                ['branch_id', '=', $parcel->delivery_branch_id],
            ])->get();
        } else {
            $data['deliveryRiders'] = [];
        }


        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        // $data['upazilas']   = Upazila::where([
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
            }
        ])
            ->where([
                ['status', '=', 1],
                ['weight_type', '=', 1]
            ])->get();
        $data['serviceTypes'] = ServiceType::where('service_area_id', $service_area_id)->get();
        $data['itemTypes'] = ItemType::where('service_area_id', $service_area_id)->get();
        return view('admin.parcel.editParcel', $data);
    }


    public function confirmEditParcel(Request $request, Parcel $parcel)
    {

        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'weight_package_charge' => 'required',
            'delivery_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            //            'delivery_option_id' => 'required',
            //            'product_details' => 'required',
            'product_value' => 'sometimes',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'              => 'required',
            'area_id' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [

            'pickup_branch_id' => $request->input('pickup_branch_id'),
            'pickup_rider_id' => $request->input('pickup_rider_id'),
            'delivery_branch_id' => $request->input('delivery_branch_id'),
            'delivery_rider_id' => $request->input('delivery_rider_id'),
            'return_branch_id' => $request->input('return_branch_id'),
            'return_rider_id' => $request->input('return_rider_id'),
            'status' => $request->input('status'),
            'delivery_type' => $request->input('delivery_type'),
            'customer_collect_amount' => $request->input('customer_collect_amount') ?? 0,

            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details' => $request->input('product_details'),
            'product_value' => $request->input('product_value'),
            'district_id' => $request->input('district_id'),
            // 'upazila_id'              => $request->input('upazila_id'),
            'upazila_id' => 0,
            'area_id' => $request->input('area_id') ?? 0,
            'weight_package_id' => $request->input('weight_package_id'),
            'weight_package_charge' => $request->input('weight_package_charge'),
            'delivery_charge' => $request->input('delivery_charge'),
            'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
            'cod_percent' => $request->input('cod_percent'),
            'cod_charge' => $request->input('cod_charge'),
            'total_charge' => $request->input('total_charge'),
            //            'delivery_option_id' => $request->input('delivery_option_id'),
            'parcel_note' => $request->input('parcel_note'),
            'merchant_order_id' => $request->input('merchant_order_id'),
            'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
            'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
            'item_type_charge' => $request->input('item_type_charge'),
            'service_type_charge' => $request->input('service_type_charge'),
            'updated_admin_id' => auth()->guard('admin')->user()->id,
        ];
        //        dd($data);


        $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

        if ($check) {
            $data = [
                'parcel_id' => $parcel->id,
                'admin_id' => auth()->guard('admin')->user()->id,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'status' => $parcel->status,
                'delivery_type' => $parcel->delivery_type,
            ];
            ParcelLog::create($data);

            $this->setMessage('Parcel Update Successfully', 'success');
            return redirect()->back();
        } else {
            $this->setMessage('Parcel Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function printParcel(Request $request, Parcel $parcel)
    {
        // echo '<img src="data:image/png; base64,' . \DNS1D::getBarcodePNG('4', 'C39+',3,33) . '" alt="barcode"   />'; exit;
        // echo \DNS1D::getBarcodeHTML($parcel->parcel_invoice, 'C39', 1.5, 33); exit;

        $parcel->load('merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        $data = [];
        $data['parcel'] = $parcel;
        return view('admin.parcel.printParcel', $data);
    }


    //    public function destroy(Request $request, Parcel $parcel) {
    //        $check = $parcel->delete() ? true : false;
    //
    //        if ($check) {
    //            $this->setMessage('Parcel Delete Successfully', 'success');
    //        } else {
    //            $this->setMessage('Parcel Delete Failed', 'danger');
    //        }
    //
    //        return redirect()->route('admin.parcel.parcelList');
    //    }

    public function delete(Request $request)
    {
        // dd($request->all());
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                if ($parcel->parcel_logs) {
                    $parcel->parcel_logs()->delete();
                }
                $check = Parcel::where('id', $request->parcel_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Parcel Delete Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function orderTracking($parcel_invoice = '')
    {

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'orderTracking';
        $data['parcel_invoice'] = urldecode($parcel_invoice);
        $data['page_title'] = 'Order Tracking';
        return view('admin.parcel.orderTracking', $data);
    }


    public function returnOrderTrackingResult(Request $request)
    {
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if ((!is_null($parcel_invoice) && $parcel_invoice != '') || (!is_null($merchant_order_id) && $merchant_order_id != '')) {
            $parcel = Parcel::with(
                'district',
                'upazila',
                'area',
                'merchant',
                'weight_package',
                'pickup_branch',
                'pickup_rider',
                'delivery_branch',
                'delivery_rider'
            )
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {
                    if (!is_null($parcel_invoice)) {
                        $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                    } elseif (!is_null($merchant_order_id)) {
                        $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                    }
                })
                ->first();
            if ($parcel) {
                $parcelLogs = ParcelLog::with(
                    'pickup_branch',
                    'pickup_rider',
                    'delivery_branch',
                    'delivery_rider',
                    'admin',
                    'merchant'
                )
                    ->where('parcel_id', $parcel->id)
                    ->orderBy('id', 'desc')
                    ->get();
                // dd($parcelLogs);
                return view('admin.parcel.orderTrackingResult', compact('parcel', 'parcelLogs'));
            }
        }
    }
    public function branch_wise_parcel_report(Request $request)
    {
        $data = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'branch_wise_parcel_report';
        $data['page_title'] = 'Branch Wise Parcel Report';
        $data['collapse']   = 'sidebar-collapse';

        $from = $request->from_date ?? date('Y-m-d');
        $to   = $request->to_date ?? date('Y-m-d');

        $data['from'] = $from;
        $data['to']   = $to;

        $data['branches'] = DB::table('branches')
            ->leftJoin('parcels', function ($join) use ($from, $to) {
                $join->on('branches.id', '=', 'parcels.delivery_branch_id')
                    ->whereBetween('parcels.date', [$from, $to]);
            })
            ->select(
                'branches.id',
                'branches.name',

                // Picked Up
                DB::raw("SUM(CASE WHEN parcels.status IN (10,11) THEN 1 ELSE 0 END) as picked_count"),
                DB::raw("SUM(CASE WHEN parcels.status IN (10,11) THEN parcels.total_charge ELSE 0 END) as picked_amount"),

                // Received
                DB::raw("SUM(CASE WHEN parcels.status IN (11,14) THEN 1 ELSE 0 END) as received_count"),
                DB::raw("SUM(CASE WHEN parcels.status IN (11,14) THEN parcels.total_charge ELSE 0 END) as received_amount"),

                // Pending
                DB::raw("SUM(CASE WHEN parcels.status IN (1,2,4,22,23) THEN 1 ELSE 0 END) as pending_count"),
                DB::raw("SUM(CASE WHEN parcels.status IN (1,2,4,22,23) THEN parcels.total_charge ELSE 0 END) as pending_amount"),

                // Delivered
                DB::raw("SUM(CASE WHEN parcels.status = 21 THEN 1 ELSE 0 END) as delivered_count"),
                DB::raw("SUM(CASE WHEN parcels.status = 21 THEN parcels.total_charge ELSE 0 END) as delivered_amount"),

                // Canceled
                DB::raw("SUM(CASE
                WHEN parcels.status IN (3,7,13,18)
                OR (parcels.status = 25 AND parcels.delivery_type = 4)
                THEN 1 ELSE 0 END) as canceled_count"),
                DB::raw("SUM(CASE
                WHEN parcels.status IN (3,7,13,18)
                OR (parcels.status = 25 AND parcels.delivery_type = 4)
                THEN parcels.total_charge ELSE 0 END) as canceled_amount"),

                // Dispatched
                DB::raw("SUM(CASE WHEN parcels.status = 12 THEN 1 ELSE 0 END) as dispatched_count"),
                DB::raw("SUM(CASE WHEN parcels.status = 12 THEN parcels.total_charge ELSE 0 END) as dispatched_amount"),

                // Returned
                DB::raw("SUM(CASE WHEN parcels.status BETWEEN 24 AND 36 THEN 1 ELSE 0 END) as returned_count"),
                DB::raw("SUM(CASE WHEN parcels.status BETWEEN 24 AND 36 THEN parcels.total_charge ELSE 0 END) as returned_amount")
            )
            ->groupBy('branches.id', 'branches.name')
            ->get();
            // dd($data);

        return view('admin.parcel.branch_wise_parcel_report', $data);
    }
    function today_parcel_for_delivery(){
         $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'today_parcel_for_delivery';
        $data['page_title'] = 'Today Parcel For Delivery';
        $data['collapse']   = 'sidebar-collapse';
        $today =  date('Y-m-d');
        // $today =  date('2025-03-05');
        $data['todayDelivery'] = Parcel::whereDate('delivery_date', $today)
        ->join('merchants', 'merchants.id', '=', 'parcels.merchant_id')
        ->selectRaw('
            merchant_id,
            merchants.name as merchant_name,
            SUM(total_collect_amount) as total_collect_amount,
            SUM(total_charge) as total_charge,
            COUNT(parcels.id) as total_parcel
        ')
    ->groupBy('merchant_id', 'merchants.name')
    ->get();
    // return  $todayDelivery;
     return view('admin.parcel.today_parcel_delivery_report', $data);



    }

     function merchant_today_pickup(){
         $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'merchant_today_pickup';
        $data['page_title'] = 'Today Merchant Pickup';
        $data['collapse']   = 'sidebar-collapse';
        $today =  date('Y-m-d');
        // $today =  date('2025-03-05');
        $data['todayPickup'] = Parcel::whereDate('delivery_date', $today)
        ->join('merchants', 'merchants.id', '=', 'parcels.merchant_id')
        ->selectRaw('
            merchant_id,
            merchants.name as merchant_name,
            SUM(total_collect_amount) as total_collect_amount,
            SUM(total_charge) as total_charge,
            COUNT(parcels.id) as total_parcel
        ')
    ->groupBy('merchant_id')
    ->get();
    // return  $todayDelivery;
     return view('admin.parcel.today_parcel_delivery_report', $data);



    }
}
