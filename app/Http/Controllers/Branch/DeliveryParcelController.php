<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\ItemType;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\ServiceType;
use App\Models\Upazila;
use App\Models\WeightPackage;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryParcelController extends Controller
{

    public function deliveryParcelList()
    {
        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryParcelList';
        $data['page_title'] = 'Delivery Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.deliveryParcel.deliveryParcelList', $data);
    }

    public function getDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status in (14,16,17,18,19,20,21,22,23,24,25) and delivery_type IS NULL', [$branch_id])
            ->select();

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
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    }
                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                }
                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';
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

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 11) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
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

    public function printDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status in (14,16,17,18,19,20,21,22,23,24,25) and delivery_type IS NULL', [$branch_id])
            ->select();
        $filter = [];

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('parcel_date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('parcel_date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }
        $parcels = $model->get();
        return view('branch.parcel.deliveryParcel.printDeliveryParcelList', compact('parcels', 'filter'));

    }

    public function completeDeliveryParcelList()
    {
        $data = [];
        $data['main_menu'] = 'completeDeliveryParcel';
        $data['child_menu'] = 'completeDeliveryParcelList';
        $data['page_title'] = 'Complete Delivery Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.deliveryParcel.completeDeliveryParcelList', $data);
    }

    public function getCompleteDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status >= ? and delivery_type in (1,2)', [$branch_id, 25])
            ->select();

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('delivery_date', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('delivery_date', '<=', $request->get('to_date'));
        }

       /* return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('merchant.company_name', function ($data) {
                $company_name = ($data->merchant) ? $data->merchant->company_name : "Default";

                return $company_name;
            })
            ->editColumn('status', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';

            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                // $button .= '&nbsp; <a href="' . route('branch.parcel.editDeliveryCompleteParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);*/


        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_status', function ($data) {
                $date_time = '---';
                if ($data->status >= 25) {
                    if ($data->delivery_type == 3) {
                        $date_time = date("Y-m-d", strtotime($data->reschedule_parcel_date));
                    } elseif ($data->delivery_type == 1 || $data->delivery_type == 2) {
                        $date_time = date("Y-m-d", strtotime($data->delivery_date));
                    }
                } elseif ($data->status == 11 || $data->status == 13 || $data->status == 15) {
                    $date_time = date("Y-m-d", strtotime($data->pickup_branch_date));
                } else {
                    $date_time = $data->date . " " . date("h:i A", strtotime($data->created_at));
                }
                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class="  text-bold badge badge-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span> <p><strong>Date: </strong>' . $date_time . '</p>';
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

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 11) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
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

    public function printCompleteDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status >= ? and delivery_type in (1,2)', [$branch_id, 25])
            ->select();
        $filter = [];

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('delivery_date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('delivery_date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $parcels = $model->get();
        return view('branch.parcel.deliveryParcel.printCompleteDeliveryParcelList', compact('parcels','filter'));
    }

    public function rescheduleDeliveryParcelList()
    {
        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'rescheduleDeliveryParcelList';
        $data['page_title'] = 'Reschedule Delivery Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.deliveryParcel.rescheduleDeliveryParcelList', $data);
    }

    public function getRescheduleDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status in (23,25) and delivery_type in (3)', [$branch_id])
            ->select();
        if (($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0)
            || ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0)) {
            if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
                $model->where('reschedule_parcel_date', '>=', $request->get('from_date'));
            }
            if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
                $model->where('reschedule_parcel_date', '<=', $request->get('to_date'));
            }
        } else {
            // $model->where('reschedule_parcel_date', '>=', date("Y-m-d"));
        }


        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('merchant.company_name', function ($data) {
                $company_name = ($data->merchant) ? $data->merchant->company_name : "Default";

                return $company_name;
            })
            ->editColumn('status', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';

            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

//                if($data->status != 25){
                $button .= '&nbsp; <a href="' . route('branch.parcel.editRescheduleParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';
//                }
                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }


    public function printRescheduleDeliveryParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            }
        ])
            ->whereRaw('delivery_branch_id = ? and status in (23,25) and delivery_type in (3)', [$branch_id])
            ->select();
        $filter = [];
        if (($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0)
            || ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0)) {
            if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
                $model->where('reschedule_parcel_date', '>=', $request->get('from_date'));
                $filter['from_date'] = $request->get('from_date');
            }
            if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
                $model->where('reschedule_parcel_date', '<=', $request->get('to_date'));
                $filter['to_date'] = $request->get('to_date');
            }
        } else {
            // $model->where('reschedule_parcel_date', '>=', date("Y-m-d"));
        }
        $parcels = $model->get();
        return view('branch.parcel.deliveryParcel.printRescheduleDeliveryParcelList', compact('parcels', 'filter'));
    }


    public function editDeliveryParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'deliveryParcelList';
        $data['page_title'] = 'Update Delivery Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['riders'] = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        $data['districts'] = District::where([
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
        $data['itemTypes'] = ItemType::where('service_area_id', $service_area_id)->get();
        return view('branch.parcel.deliveryParcel.editDeliveryParcel', $data);
    }

    public function confirmEditDeliveryParcel(Request $request, Parcel $parcel)
    {
        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'                   => 'required',
            'area_id' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $data = [
//                'date'                         => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'district_id' => $request->input('district_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'weight_package_id' => $request->input('weight_package_id'),
                'delivery_charge' => $request->input('delivery_charge'),
                'weight_package_charge' => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
                'cod_percent' => $request->input('cod_percent'),
                'cod_charge' => $request->input('cod_charge'),
                'total_charge' => $request->input('total_charge'),
                'delivery_option_id' => $request->input('delivery_option_id'),
                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'parcel_note' => $request->input('parcel_note'),
//                'parcel_date'                  => date('Y-m-d'),
            ];
            if ($request->input('item_type_id') != 0) {
                $data['service_type_id'] = null;
            }
            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => $parcel->status,
                    'delivery_branch_id' => auth()->guard('branch')->user()->branch->id,
                    'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,
                ];

                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryParcelList');
            } else {
                $this->setMessage('Parcel Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }


    public function editDeliveryCompleteParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data = [];
        $data['main_menu'] = 'completeDeliveryParcel';
        $data['child_menu'] = 'completeDeliveryParcelList';
        $data['page_title'] = 'Update Delivery Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['riders'] = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        $data['upazilas'] = Upazila::where([
            ['district_id', '=', $parcel->district->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
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

        return view('branch.parcel.deliveryParcel.editDeliveryCompleteParcel', $data);
    }

    public function confirmEditDeliveryCompleteParcel(Request $request, Parcel $parcel)
    {
        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'area_id' => 'required',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $data = [
//                'date'                         => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'district_id' => $request->input('district_id'),
                'upazila_id' => $request->input('upazila_id'),
                'area_id' => $request->input('area_id'),
                'weight_package_id' => $request->input('weight_package_id'),
                'delivery_charge' => $request->input('delivery_charge'),
                'weight_package_charge' => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
                'cod_percent' => $request->input('cod_percent'),
                'cod_charge' => $request->input('cod_charge'),
                'total_charge' => $request->input('total_charge'),
                'delivery_option_id' => $request->input('delivery_option_id'),
                'parcel_note' => $request->input('parcel_note'),
//                'parcel_date'                  => date('Y-m-d'),
            ];

            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => $parcel->status,
                    'delivery_branch_id' => auth()->guard('branch')->user()->branch->id,
                    'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,
                ];

                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.completeDeliveryParcelList');
            } else {
                $this->setMessage('Parcel Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }

    }


    public function editRescheduleParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data = [];
        $data['main_menu'] = 'deliveryParcel';
        $data['child_menu'] = 'rescheduleDeliveryParcelList';
        $data['page_title'] = 'Update Reschedule Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['riders'] = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        $data['upazilas'] = Upazila::where([
            ['district_id', '=', $parcel->district->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
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

        return view('branch.parcel.deliveryParcel.editRescheduleParcel', $data);
    }

    public function confirmEditRescheduleParcel(Request $request, Parcel $parcel)
    {
        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'area_id' => 'required',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $data = [
//                'date'                         => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'district_id' => $request->input('district_id'),
                'upazila_id' => $request->input('upazila_id'),
                'area_id' => $request->input('area_id'),
                'weight_package_id' => $request->input('weight_package_id'),
                'delivery_charge' => $request->input('delivery_charge'),
                'weight_package_charge' => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
                'cod_percent' => $request->input('cod_percent'),
                'cod_charge' => $request->input('cod_charge'),
                'total_charge' => $request->input('total_charge'),
                'delivery_option_id' => $request->input('delivery_option_id'),
                'parcel_note' => $request->input('parcel_note'),
//                'parcel_date'                  => date('Y-m-d'),
            ];

            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => $parcel->status,
                    'delivery_branch_id' => auth()->guard('branch')->user()->branch->id,
                    'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,
                ];

                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.rescheduleDeliveryParcelList');
            } else {
                $this->setMessage('Parcel Update Failed', 'danger');
                return redirect()->back()->withInput();
            }

        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
//            return $e->getMessage();
            return redirect()->back()->withInput();
        }

    }

    public function deliveryParcelReceived(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 11,
                    'parcel_date' => date('Y-m-d'),
                    'delivery_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 11,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    /** Parcel Notification */
                    $parcel = Parcel::where('id', $request->parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->adminDashboardCounterEvent();

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);

                    $response = ['success' => 'Delivery Parcel Received Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function deliveryParcelReject(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 12,
                    'parcel_date' => date('Y-m-d'),
                    'delivery_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 12,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    $parcel = Parcel::where('id', $request->parcel_id)->first();
                    $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    $this->adminDashboardCounterEvent();

                    $this->branchDashboardCounterEvent($parcel->delivery_branch_id);

                    $response = ['success' => 'Delivery Parcel Reject Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function deliveryRiderRunList()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'deliveryRiderRunList';
        $data['page_title'] = 'Delivery Rider List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.deliveryRiderRunList', $data);
    }

    public function getDeliveryRiderRunList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->id;

        $model = RiderRun::with(['rider' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereRaw('branch_id = ? and run_type = 2', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
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
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '}" >
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success btn-sm run-start-btn" rider_run_id="' . $data->id . '" title="Pickup Run Start">
                    <i class="far fa-play-circle"></i> </button>';

                    $button .= '&nbsp; <button class="btn btn-warning btn-sm run-cancel-btn" rider_run_id="' . $data->id . '" title="Pickup Run Cancel">
                    <i class="far fa-window-close"></i> </button>';
                }

                if ($data->status == 2) {
                    $button .= '&nbsp; <button class="btn btn-success rider-run-reconciliation btn-sm" data-toggle="modal" data-target="#viewModal" rider_run_id="' . $data->id . '}" >
                    <i class="fa fa-check"></i> </button> ';
                }
                return $button;
            })
            ->rawColumns(['action', 'create_date_time', 'start_date_time', 'cancel_date_time', 'complete_date_time'])
            ->make(true);
    }

    public function deliveryRiderRunGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'deliveryRiderRunGenerate';
        $data['page_title'] = 'Delivery Rider Run Generate';
        $data['collapse'] = 'sidebar-collapse';
        $data['riders'] = Rider::where([
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
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [11, 15, 17])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
            ->get();

        return view('branch.parcel.deliveryRiderRunGenerate', $data);
    }

    public function returnDeliveryRiderRunParcel(Request $request)
    {

        $branch_id = auth()->guard('branch')->user()->id;
        $parcel_invoice_barcode = $request->input('parcel_invoice_barcode');
        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice_barcode) || !empty($parcel_invoice) || !empty($merchant_order_id)) {

            $data['parcels'] = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number');
            },
            ])
                ->where(function ($query) use ($branch_id, $parcel_invoice_barcode, $parcel_invoice, $merchant_order_id) {
                    $query->whereIn('status', [11, 15, 17]);
                    $query->where([
                        'delivery_branch_id' => $branch_id,
                    ]);

                    if (!empty($parcel_invoice_barcode)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice_barcode,
                        ]);
                    } elseif (!empty($parcel_invoice)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice,
                        ]);
                    } elseif (!empty($merchant_order_id)) {
                        $query->where([
                            'merchant_order_id' => $merchant_order_id,
                        ]);
                    }
                })
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
                ->get();
        } else {
            $data['parcels'] = [];
        }
        $parcels = $data['parcels'];
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
    }


    public function deliveryRiderRunParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->id;
        $parcel_invoice = $request->input('parcel_invoice');
        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [11, 15, 17])
            ->get();

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
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
    }

    public function deliveryRiderRunParcelDeleteCart(Request $request)
    {

        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
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

        $branch_id = auth()->guard('branch')->user()->id;

        $data = [
            'run_invoice' => $this->returnUniqueRiderRunInvoice(),
            'rider_id' => $request->input('rider_id'),
            'branch_id' => $branch_id,
            'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
            'total_run_parcel' => $request->input('total_run_parcel'),
            'note' => $request->input('note'),
            'run_type' => 2,
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
                    'status' => 13,
                    'parcel_date' => $request->input('date'),
                    'delivery_rider_id' => $request->input('rider_id'),
                ]);

                ParcelLog::create([
                    'parcel_id' => $parcel_id,
                    'delivery_rider_id' => $request->input('rider_id'),
                    'delivery_branch_id' => $branch_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 13,
                    'delivery_type' => $parcel->delivery_type,
                ]);

                $parcel = Parcel::where('id', $parcel_id)->first();
                // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
            }

            // $this->adminDashboardCounterEvent();

            $this->setMessage('Delivery Rider Run Insert Successfully', 'success');
            return redirect()->back();
        } else {
            $this->setMessage('Delivery Rider Run Insert Failed', 'danger');
            return redirect()->back()->withInput();
        }
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
                $check = RiderRun::where('id', $request->rider_run_id)->update([
                    'start_date_time' => date('Y-m-d H:i:s'),
                    'status' => 2,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                    foreach ($riderRunDetails as $riderRunDetail) {
                        $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->update([
                            'status' => 14,
                            'parcel_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $riderRunDetail->parcel_id,
                            'pickup_branch_id' => auth()->guard('branch')->user()->id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 14,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 2,
                        ]);

                        $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Delivery Rider Run Start Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
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
                $check = RiderRun::where('id', $request->rider_run_id)->update([
                    'cancel_date_time' => date('Y-m-d H:i:s'),
                    'status' => 3,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                    foreach ($riderRunDetails as $riderRunDetail) {
                        $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->update([
                            'status' => 15,
                            'parcel_date' => date('Y-m-d'),
                        ]);
                        ParcelLog::create([
                            'parcel_id' => $riderRunDetail->parcel_id,
                            'pickup_branch_id' => auth()->guard('branch')->user()->id,
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 15,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 3,
                        ]);

                        $parcel = Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Cancel Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function viewDeliveryRiderRun(Request $request, RiderRun $riderRun)
    {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.viewDeliveryRiderRun', compact('riderRun'));
    }


    public function viewParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('branch.parcel.viewParcel', compact('parcel'));
    }

    public function editParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = ($parcel->status < 7) ? 'pickupParcelList' : 'deliveryParcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->get();

        $data['riders'] = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        $data['upazilas'] = Upazila::where([
            ['district_id', '=', $parcel->district->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
            ['status', '=', 1],
        ])->get();

        $data['areas'] = Area::where([
            ['upazila_id', '=', $parcel->upazila->id],
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

        return view('branch.parcel.editParcel', $data);
    }

    public function confirmEditParcel(Request $request, Parcel $parcel)
    {

        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $status = $request->input('status');
        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details' => $request->input('product_details'),
            'district_id' => $request->input('district_id'),
            'upazila_id' => $request->input('upazila_id'),
            'area_id' => $request->input('area_id'),
            'weight_package_id' => $request->input('weight_package_id'),
            'delivery_charge' => $request->input('delivery_charge'),
            'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
            'cod_percent' => $request->input('cod_percent'),
            'cod_charge' => $request->input('cod_charge'),
            'total_charge' => $request->input('total_charge'),
            'delivery_option_id' => $request->input('delivery_option_id'),
            'parcel_date' => date('Y-m-d'),
        ];

        $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

        if ($check) {
            $data = [
                'parcel_id' => $parcel->id,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'status' => $parcel->status,
                'delivery_type' => $parcel->delivery_type,
            ];

            if ($parcel->status >= 1 || $parcel->status < 7) {
                $data['pickup_branch_id'] = auth()->guard('branch')->user()->id;
            } else {
                $data['delivery_branch_id'] = auth()->guard('branch')->user()->id;
            }

            ParcelLog::create($data);

            $this->setMessage('Parcel Update Successfully', 'success');
            return redirect()->back();
        } else {
            $this->setMessage('Parcel Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }


    public function assignPickupRider(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        $riders = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        return view('branch.parcel.assignPickupRider', compact('parcel', 'riders'));
    }

    public function confirmAssignPickupRider(Request $request)
    {

        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $data = [
                    'pickup_rider_id' => $request->rider_id,
                    'status' => 3,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'pickup_rider_id' => $request->rider_id,
                        'pickup_branch_id' => auth()->guard('branch')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 3,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    $parcel = Parcel::where('id', $request->parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                    // $this->adminDashboardCounterEvent();
                    // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                    $response = ['success' => 'Parcel Pickup Rider Assign Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

            return response()->json($response);
        }

        return redirect()->back();
    }

    public function pickupParcelReceived(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status' => 6,
                    'parcel_date' => date('Y-m-d'),
                    'pickup_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update($data);

                if ($parcel) {

                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'pickup_branch_id' => auth()->guard('branch')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 6,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    $parcel = Parcel::where('id', $request->parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);
                    // $this->adminDashboardCounterEvent();
                    // $this->branchDashboardCounterEvent($parcel->pickup_branch_id);

                    $response = ['success' => 'Pickup Parcel Received Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function assignDeliveryRider(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        $riders = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        return view('branch.parcel.assignDeliveryRider', compact('parcel', 'riders'));
    }

    public function confirmAssignDeliveryRider(Request $request)
    {

        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id' => 'required',
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $data = [
                    'parcel_code' => mt_rand(100000, 999999),
                    'delivery_rider_id' => $request->rider_id,
                    'status' => 10,
                    'parcel_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->first();
                $parcel->update($data);

                if ($parcel) {
                    $data = [
                        'parcel_id' => $request->parcel_id,
                        'delivery_rider_id' => $request->rider_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date' => date('Y-m-d'),
                        'time' => date('H:i:s'),
                        'status' => 10,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    $parcel = Parcel::where('id', $request->parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->adminDashboardCounterEvent();

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);

                    $response = ['success' => 'Parcel Delivery Rider Assign Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

            return response()->json($response);
        }

        return redirect()->back();
    }

    public function add()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'addParcel';
        $data['page_title'] = 'Add Parcel';
        $data['districts'] = District::where('status', 1)->get();
        $data['merchant'] = Merchant::with('branch')->where('id', auth()->guard('merchant')->user()->id)->first();
        return view('branch.parcel.addParcel', $data);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
            'weight_package_id' => 'required',
            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'upazila_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $merchant = auth()->guard('merchant')->user();

        $data = [
            'parcel_invoice' => $this->returnUniqueParcelInvoice(),
            'merchant_id' => $merchant->id,
            'date' => date('Y-m-d'),
            'customer_name' => $request->input('customer_name'),
            'customer_address' => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details' => $request->input('product_details'),
            'district_id' => $request->input('district_id'),
            'upazila_id' => $request->input('upazila_id'),
            'area_id' => $request->input('area_id'),
            'weight_package_id' => $request->input('weight_package_id'),
            'delivery_charge' => $request->input('delivery_charge'),
            'total_collect_amount' => $request->input('total_collect_amount') ?? 0,
            'cod_percent' => $request->input('cod_percent'),
            'cod_charge' => $request->input('cod_charge'),
            'total_charge' => $request->input('total_charge'),
            'delivery_option_id' => $request->input('delivery_option_id'),
            'pick_branch_id' => $merchant->branch_id,
            'parcel_date' => date('Y-m-d'),
            'status' => 1,
        ];

        $check = Parcel::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Parcel Create Successfully', 'success');
            return redirect()->back();
        } else {
            $this->setMessage('Parcel Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function list()
    {
        $data = [];
        $data['main_menu'] = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.parcelList', $data);
    }

    public function getParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->id;

        $model = Parcel::with(['district', 'upazila', 'area'])
            ->whereRaw('(pickup_branch_id = ? and status in (1,2,3,4,5,6)) or (delivery_branch_id = ? and status in (7,8))', [$branch_id, $branch_id])
            ->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                    case 1:
                        $status_name = "Pickup Request";
                        $class = "success";
                        break;
                    case 2:
                        $status_name = "Pickup Branch Request Accept";
                        $class = "success";
                        break;
                    case 3:
                        $status_name = "Pickup Rider Assign";
                        $class = "success";
                        break;
                    case 4:
                        $status_name = "Pickup Rider Request Accept";
                        $class = "success";
                        break;
                    case 5:
                        $status_name = "Pickup Rider Pick Parcel";
                        $class = "success";
                        break;
                    case 6:
                        $status_name = "Pickup Branch Received Parcel";
                        $class = "success";
                        break;
                    case 7:
                        $status_name = "Pickup Branch Assign Delivery Branch";
                        $class = "success";
                        break;
                    case 8:
                        $status_name = "Delivery Branch Received";
                        $class = "success";
                        break;
                    case 9:
                        $status_name = "Delivery Branch Reject";
                        $class = "success";
                        break;
                    case 10:
                        $status_name = "Delivery Branch Assign Rider";
                        $class = "success";
                        break;
                    case 11:
                        $status_name = "Delivery  Rider Accept";
                        $class = "success";
                        break;
                    case 12:
                        $status_name = "Delivery Rider Complete";
                        $class = "success";
                        break;
                    case 12:
                        $status_name = "Delivery Rider Reschedule";
                        $class = "success";
                        break;
                    default:
                        $status_name = "None";
                        $class = "success";
                        break;
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {

                switch ($data->status) {
                    case 1:
                        $button = '&nbsp;&nbsp;&nbsp; <button class="btn btn-success request-accept-btn" parcel_id="' . $data->id . '">
                        Pickup Request Accept <i class="fa fa-check"></i> </button>';
                        break;
                    case 2:
                        $button = '<button class="btn btn-secondary assignPickupRider" data-toggle="modal" data-target="#modal" parcel_id="' . $data->id . '}" >
                                Assign Pickup Rider <i class="fas fa-share-square"></i> </button>';
                        break;
                    case 5:
                        $button = '&nbsp;&nbsp;&nbsp; <button class="btn btn-success pickup-parcel-received-btn" parcel_id="' . $data->id . '">
                            Pickup Parcel Received <i class="fa fa-check"></i> </button>';
                        break;
                    case 6:
                        $button = '<button class="btn btn-secondary assignDeliveryBranch" data-toggle="modal" data-target="#modal" parcel_id="' . $data->id . '}" >
                            Delivery Branch  <i class="fas fa-share-square"></i> </button>';
                        break;
                    case 7:
                        $button = '&nbsp;&nbsp;&nbsp; <button class="btn btn-success delivery-parcel-received-btn" parcel_id="' . $data->id . '">
                        Delivery Parcel Received <i class="fa fa-check"></i> </button>';
                        break;
                    case 8:
                        $button = '<button class="btn btn-secondary assignDeliveryRider" data-toggle="modal" data-target="#modal" parcel_id="' . $data->id . '}" >
                                Assign Delivery Rider <i class="fas fa-share-square"></i> </button>';
                        break;

                    default:
                        $button = "";
                        break;
                }

                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }

}
