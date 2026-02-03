<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\Upazila;
use App\Models\WeightPackage;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnParcelController extends Controller {

    public function returnParcelList() {
        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnParcelList';
        $data['page_title'] = 'Return Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.returnParcel.returnParcelList', $data);
    }

    public function getReturnParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area', 'merchant'])
//        ->whereRaw('return_branch_id = ? and status in (28,31,32,33,34,35,36)', [$branch_id])
        ->whereRaw('return_branch_id = ? and status >= 25 and delivery_type = 4', [$branch_id])
            ->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                $parcelStatus   = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name    = $parcelStatus['status_name'];
                $class          = $parcelStatus['class'];

                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';

            })->addColumn('area', function ($data) {
                return $data->area->name;
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['status', 'action', 'image', 'area'])
            ->make(true);
    }

    public function printReturnParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area', 'merchant'])
//        ->whereRaw('return_branch_id = ? and status in (28,31,32,33,34,35,36)', [$branch_id])
        ->whereRaw('return_branch_id = ? and status >= 25 and delivery_type = 4', [$branch_id])
            ->select();
        $parcels = $model->get();
        return view('branch.parcel.returnParcel.printReturnParcelList', compact('parcels'));
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                $parcelStatus   = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name    = $parcelStatus['status_name'];
                $class          = $parcelStatus['class'];

                return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';

            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }

    public function completeReturnParcelList() {
        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'completeReturnParcelList';
        $data['page_title'] = 'Complete Return Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.returnParcel.completeReturnParcelList', $data);
    }

    public function getCompleteReturnParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area', 'merchant'])
        ->whereRaw('return_branch_id = ? and status in (36) and delivery_type = ?', [$branch_id, 4])
            ->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                $parcelStatus   = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name    = $parcelStatus['status_name'];
                $class          = $parcelStatus['class'];
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnCompleteParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }


    public function printCompleteReturnParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area', 'merchant'])
        ->whereRaw('return_branch_id = ? and status in (36) and delivery_type = ?', [$branch_id, 4])
            ->select();
        $parcels = $model->get();
        return view('branch.parcel.returnParcel.printCompleteReturnParcelList', compact('parcels'));
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                $parcelStatus   = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name    = $parcelStatus['status_name'];
                $class          = $parcelStatus['class'];
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                    <i class="fas fa-print"></i> </a>';

                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editReturnCompleteParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }


    public function editReturnParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnParcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['parcel']     = $parcel;
        $data['branches']   = Branch::where([
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

        return view('branch.parcel.returnParcel.editReturnParcel', $data);
    }

    public function confirmEditReturnParcel(Request $request, Parcel $parcel) {
        $validator = Validator::make($request->all(), [
            'cod_percent'                  => 'required',
            'cod_charge'                   => 'required',
            'delivery_charge'              => 'required',
            'weight_package_charge'        => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge'                 => 'required',
            'weight_package_id'            => 'required',
            'delivery_option_id'           => 'required',
            'product_details'              => 'required',
            'total_collect_amount'         => 'sometimes',
            'customer_name'                => 'required',
            'customer_contact_number'      => 'required',
            'customer_address'             => 'required',
            'district_id'                  => 'required',
            'upazila_id'                   => 'required',
            'area_id'                      => 'required',
            'parcel_note'                  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $data = [
//                'date'                         => date('Y-m-d'),
                'merchant_order_id'            => $request->input('merchant_order_id'),
                'customer_name'                => $request->input('customer_name'),
                'customer_address'             => $request->input('customer_address'),
                'customer_contact_number'      => $request->input('customer_contact_number'),
                'product_details'              => $request->input('product_details'),
                'district_id'                  => $request->input('district_id'),
                'upazila_id'                   => $request->input('upazila_id'),
                'area_id'                      => $request->input('area_id'),
                'weight_package_id'            => $request->input('weight_package_id'),
                'delivery_charge'              => $request->input('delivery_charge'),
                'weight_package_charge'        => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount'         => $request->input('total_collect_amount') ?? 0,
                'cod_percent'                  => $request->input('cod_percent'),
                'cod_charge'                   => $request->input('cod_charge'),
                'total_charge'                 => $request->input('total_charge'),
                'delivery_option_id'           => $request->input('delivery_option_id'),
                'parcel_note'                  => $request->input('parcel_note'),
//                'parcel_date'                  => date('Y-m-d'),
            ];

            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id'             => $parcel->id,
                    'date'                  => date('Y-m-d'),
                    'time'                  => date('H:i:s'),
                    'status'                => $parcel->status,
                    'delivery_branch_id'      => auth()->guard('branch')->user()->branch->id,
                    'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,
                ];

                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.returnParcelList');
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


    public function editReturnCompleteParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'completeReturnParcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['parcel']     = $parcel;
        $data['branches']   = Branch::where([
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

        return view('branch.parcel.returnParcel.editReturnCompleteParcel', $data);
    }

    public function confirmEditReturnCompleteParcel(Request $request, Parcel $parcel) {
        $validator = Validator::make($request->all(), [
            'cod_percent'                  => 'required',
            'cod_charge'                   => 'required',
            'delivery_charge'              => 'required',
            'weight_package_charge'        => 'required',
            'merchant_service_area_charge' => 'required',
            'merchant_service_area_return_charge' => 'required',
            'total_charge'                 => 'required',
            'weight_package_id'            => 'required',
            'delivery_option_id'           => 'required',
            'product_details'              => 'required',
            'total_collect_amount'         => 'sometimes',
            'customer_name'                => 'required',
            'customer_contact_number'      => 'required',
            'customer_address'             => 'required',
            'district_id'                  => 'required',
            'upazila_id'                   => 'required',
            'area_id'                      => 'required',
            'parcel_note'                  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $data = [
//                'date'                         => date('Y-m-d'),
                'merchant_order_id'            => $request->input('merchant_order_id'),
                'customer_name'                => $request->input('customer_name'),
                'customer_address'             => $request->input('customer_address'),
                'customer_contact_number'      => $request->input('customer_contact_number'),
                'product_details'              => $request->input('product_details'),
                'district_id'                  => $request->input('district_id'),
                'upazila_id'                   => $request->input('upazila_id'),
                'area_id'                      => $request->input('area_id'),
                'weight_package_id'            => $request->input('weight_package_id'),
                'delivery_charge'              => $request->input('delivery_charge'),
                'weight_package_charge'        => $request->input('weight_package_charge'),
                'merchant_service_area_charge' => $request->input('merchant_service_area_charge'),
                'merchant_service_area_return_charge' => $request->input('merchant_service_area_return_charge'),
                'total_collect_amount'         => $request->input('total_collect_amount') ?? 0,
                'cod_percent'                  => $request->input('cod_percent'),
                'cod_charge'                   => $request->input('cod_charge'),
                'total_charge'                 => $request->input('total_charge'),
                'delivery_option_id'           => $request->input('delivery_option_id'),
                'parcel_note'                  => $request->input('parcel_note'),
//                'parcel_date'                  => date('Y-m-d'),
            ];

            $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

            if ($check) {
                $data = [
                    'parcel_id'             => $parcel->id,
                    'date'                  => date('Y-m-d'),
                    'time'                  => date('H:i:s'),
                    'status'                => $parcel->status,
                    'delivery_branch_id'      => auth()->guard('branch')->user()->branch->id,
                    'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,

                ];

                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.completeReturnParcelList');
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


    public function deliveryParcelReceived(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status'               => 11,
                    'parcel_date'          => date('Y-m-d'),
                    'delivery_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                $parcel=Parcel::where('id', $request->parcel_id)->first();
                if ($parcel) {
                    $data = [
                        'parcel_id'          => $request->parcel_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date'               => date('Y-m-d'),
                        'time'               => date('H:i:s'),
                        'status'             => 11,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Parcel Received Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function deliveryParcelReject(Request $request) {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status'               => 12,
                    'parcel_date'          => date('Y-m-d'),
                    'delivery_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                $parcel=Parcel::where('id', $request->parcel_id)->first();
                if ($parcel) {
                    $data = [
                        'parcel_id'          => $request->parcel_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date'               => date('Y-m-d'),
                        'time'               => date('H:i:s'),
                        'status'             => 12,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);



                    $parcel_data = Parcel::where('id', $request->parcel_id)->first();
                    // $this->branchDashboardCounterEvent($parcel_data->delivery_branch_id);
                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Parcel Reject Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function deliveryRiderRunList() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'deliveryRiderRunList';
        $data['page_title'] = 'Delivery Rider List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.deliveryRiderRunList', $data);
    }

    public function getDeliveryRiderRunList(Request $request) {
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

    public function deliveryRiderRunGenerate() {
        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'deliveryRiderRunGenerate';
        $data['page_title'] = 'Delivery Rider Run Generate';
        $data['collapse']   = 'sidebar-collapse';
        $data['riders']     = Rider::where([
            'status'    => 1,
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
        ->whereIn('status', [11,15,17])
        ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
        ->get();

        return view('branch.parcel.deliveryRiderRunGenerate', $data);
    }

    public function returnDeliveryRiderRunParcel(Request $request) {

        $branch_id = auth()->guard('branch')->user()->id;
        $parcel_invoice_barcode = $request->input('parcel_invoice_barcode');
        $parcel_invoice         = $request->input('parcel_invoice');
        $merchant_order_id      = $request->input('merchant_order_id');

        if (!empty($parcel_invoice_barcode) || !empty($parcel_invoice) || !empty($merchant_order_id)) {

            $data['parcels'] = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number');
            },
            ])
            ->where(function ($query) use ($branch_id, $parcel_invoice_barcode, $parcel_invoice, $merchant_order_id) {
                $query->whereIn('status', [11,15,17]);
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
        }
        else{
            $data['parcels'] = [];
        }
        $parcels = $data['parcels'];
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
    }


    public function deliveryRiderRunParcelAddCart(Request $request) {
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
        ->whereIn('status', [11,15,17])
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
        } else {
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
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
    }

    public function deliveryRiderRunParcelDeleteCart(Request $request) {

        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart'      => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal'  => \Cart::session($branch_id)->getTotal(),
            'error'     => "",
        ];
        return view('branch.parcel.deliveryRiderRunParcelCart', $data);
    }

    public function confirmDeliveryRiderRunGenerate(Request $request) {

        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id'         => 'required',
            'date'             => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->id;

        $data = [
            'run_invoice'      => $this->returnUniqueRiderRunInvoice(),
            'rider_id'         => $request->input('rider_id'),
            'branch_id'        => $branch_id,
            'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
            'total_run_parcel' => $request->input('total_run_parcel'),
            'note'             => $request->input('note'),
            'run_type'         => 2,
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
                ]);

                Parcel::where('id', $parcel_id)->update([
                    'status'          => 13,
                    'parcel_date'     => $request->input('date'),
                    'delivery_rider_id' => $request->input('rider_id'),
                ]);
                $parcel=Parcel::where('id', $parcel_id)->first();
                ParcelLog::create([
                    'parcel_id'        => $parcel_id,
                    'delivery_rider_id'  => $request->input('rider_id'),
                    'delivery_branch_id' => $branch_id,
                    'date'             => date('Y-m-d'),
                    'time'             => date('H:i:s'),
                    'status'           => 13,
                    'delivery_type' => $parcel->delivery_type,
                ]);
            }

            // $this->branchDashboardCounterEvent($branch_id);
            // $this->adminDashboardCounterEvent();

            $this->setMessage('Delivery Rider Run Insert Successfully', 'success');
            return redirect()->back();
        }
        else{
            $this->setMessage('Delivery Rider Run Insert Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function startDeliveryRiderRun(Request $request) {
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
                    'status'          => 2,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                    foreach ($riderRunDetails as $riderRunDetail) {
                        Parcel::where('id', $riderRunDetail->parcel_id)->update([
                            'status'      => 14,
                            'parcel_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $riderRunDetail->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'        => $riderRunDetail->parcel_id,
                            'pickup_branch_id' => auth()->guard('branch')->user()->id,
                            'date'             => date('Y-m-d'),
                            'time'             => date('H:i:s'),
                            'status'           => 14,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 2,
                        ]);
                    }


                    // $this->branchDashboardCounterEvent(auth()->guard('branch')->user()->branch->id);
                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Start Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function cancelDeliveryRiderRun(Request $request) {
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
                    'status'           => 3,
                ]);

                if ($check) {
                    $riderRunDetails = RiderRunDetail::where('rider_run_id', $request->rider_run_id)->get();

                    foreach ($riderRunDetails as $riderRunDetail) {
                        Parcel::where('id', $riderRunDetail->parcel_id)->update([
                            'status'      => 15,
                            'parcel_date' => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id',  $riderRunDetail->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'        => $riderRunDetail->parcel_id,
                            'pickup_branch_id' => auth()->guard('branch')->user()->id,
                            'date'             => date('Y-m-d'),
                            'time'             => date('H:i:s'),
                            'status'           => 15,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                        RiderRunDetail::where('id', $riderRunDetail->id)->update([
                            'status' => 3,
                        ]);
                    }


                    // $this->branchDashboardCounterEvent(auth()->guard('branch')->user()->branch->id);
                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Delivery Rider Run Cancel Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function viewDeliveryRiderRun(Request $request, RiderRun $riderRun) {
        $riderRun->load('branch', 'rider', 'rider_run_details');
        return view('branch.parcel.viewDeliveryRiderRun', compact('riderRun'));
    }





    public function viewParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');
        return view('branch.parcel.viewParcel', compact('parcel'));
    }

    public function editParcel(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = ($parcel->status < 7) ? 'pickupParcelList' : 'deliveryParcelList';
        $data['page_title'] = 'Update Parcel';
        $data['collapse']   = 'sidebar-collapse';
        $data['parcel']     = $parcel;
        $data['branches']   = Branch::where([
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

    public function confirmEditParcel(Request $request, Parcel $parcel) {

        $validator = Validator::make($request->all(), [
            'cod_percent'             => 'required',
            'cod_charge'              => 'required',
            'delivery_charge'         => 'required',
            'total_charge'            => 'required',
            'weight_package_id'       => 'required',
            'delivery_option_id'      => 'required',
            'product_details'         => 'required',
            'total_collect_amount'    => 'sometimes',
            'customer_name'           => 'required',
            'customer_contact_number' => 'required',
            'customer_address'        => 'required',
            'district_id'             => 'required',
            'upazila_id'              => 'required',
            'area_id'                 => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $status = $request->input('status');
        $data   = [
            'customer_name'           => $request->input('customer_name'),
            'customer_address'        => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details'         => $request->input('product_details'),
            'district_id'             => $request->input('district_id'),
            'upazila_id'              => $request->input('upazila_id'),
            'area_id'                 => $request->input('area_id'),
            'weight_package_id'       => $request->input('weight_package_id'),
            'delivery_charge'         => $request->input('delivery_charge'),
            'total_collect_amount'    => $request->input('total_collect_amount') ?? 0,
            'cod_percent'             => $request->input('cod_percent'),
            'cod_charge'              => $request->input('cod_charge'),
            'total_charge'            => $request->input('total_charge'),
            'delivery_option_id'      => $request->input('delivery_option_id'),
            'parcel_date'             => date('Y-m-d'),
        ];

        $check = Parcel::where('id', $parcel->id)->update($data) ? true : false;

        if ($check) {
            $data = [
                'parcel_id' => $parcel->id,
                'date'      => date('Y-m-d'),
                'time'      => date('H:i:s'),
                'status'    => $parcel->status,
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




    public function assignPickupRider(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        $riders = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        return view('branch.parcel.assignPickupRider', compact('parcel', 'riders'));
    }

    public function confirmAssignPickupRider(Request $request) {

        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id'  => 'required',
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $data = [
                    'pickup_rider_id' => $request->rider_id,
                    'status'          => 3,
                    'parcel_date'     => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                $parcel=Parcel::where('id', $request->parcel_id)->first();
                if ($parcel) {
                    $data = [
                        'parcel_id'        => $request->parcel_id,
                        'pickup_rider_id'  => $request->rider_id,
                        'pickup_branch_id' => auth()->guard('branch')->user()->id,
                        'date'             => date('Y-m-d'),
                        'time'             => date('H:i:s'),
                        'status'           => 3,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);
                    // $this->branchDashboardCounterEvent(auth()->guard('branch')->user()->branch->id);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Pickup Rider Assign Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

            return response()->json($response);
        }

        return redirect()->back();
    }

    public function pickupParcelReceived(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $data = [
                    'status'             => 6,
                    'parcel_date'        => date('Y-m-d'),
                    'pickup_branch_date' => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                $parcel=Parcel::where('id', $request->parcel_id)->first();
                if ($parcel) {

                    $data = [
                        'parcel_id'        => $request->parcel_id,
                        'pickup_branch_id' => auth()->guard('branch')->user()->id,
                        'date'             => date('Y-m-d'),
                        'time'             => date('H:i:s'),
                        'status'           => 6,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    // $this->branchDashboardCounterEvent(auth()->guard('branch')->user()->branch->id);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Pickup Parcel Received Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

    public function assignDeliveryRider(Request $request, Parcel $parcel) {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package');
        $riders = Rider::where([
            ['status', '=', 1],
            ['branch_id', '=', auth()->guard('branch')->user()->id],
        ])->get();

        return view('branch.parcel.assignDeliveryRider', compact('parcel', 'riders'));
    }

    public function confirmAssignDeliveryRider(Request $request) {

        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'rider_id'  => 'required',
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                $data = [
                    'parcel_code'       => mt_rand(100000, 999999),
                    'delivery_rider_id' => $request->rider_id,
                    'status'            => 10,
                    'parcel_date'       => date('Y-m-d'),
                ];
                $parcel = Parcel::where('id', $request->parcel_id)->update($data);
                $parcel=Parcel::where('id', $request->parcel_id)->first();
                if ($parcel) {
                    $data = [
                        'parcel_id'          => $request->parcel_id,
                        'delivery_rider_id'  => $request->rider_id,
                        'delivery_branch_id' => auth()->guard('branch')->user()->id,
                        'date'               => date('Y-m-d'),
                        'time'               => date('H:i:s'),
                        'status'             => 10,
                        'delivery_type' => $parcel->delivery_type,
                    ];
                    ParcelLog::create($data);

                    // $this->branchDashboardCounterEvent(auth()->guard('branch')->user()->branch->id);

                    // $this->adminDashboardCounterEvent();

                    $response = ['success' => 'Parcel Delivery Rider Assign Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

            return response()->json($response);
        }

        return redirect()->back();
    }

    public function add() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'addParcel';
        $data['page_title'] = 'Add Parcel';
        $data['districts']  = District::where('status', 1)->get();
        $data['merchant']   = Merchant::with('branch')->where('id', auth()->guard('merchant')->user()->id)->first();
        return view('branch.parcel.addParcel', $data);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'cod_percent'             => 'required',
            'cod_charge'              => 'required',
            'delivery_charge'         => 'required',
            'total_charge'            => 'required',
            'weight_package_id'       => 'required',
            'delivery_option_id'      => 'required',
            'product_details'         => 'required',
            'total_collect_amount'    => 'sometimes',
            'customer_name'           => 'required',
            'customer_contact_number' => 'required',
            'customer_address'        => 'required',
            'district_id'             => 'required',
            'upazila_id'              => 'required',
            'area_id'                 => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $merchant = auth()->guard('merchant')->user();

        $data = [
            'parcel_invoice'          => $this->returnUniqueParcelInvoice(),
            'merchant_id'             => $merchant->id,
            'date'                    => date('Y-m-d'),
            'customer_name'           => $request->input('customer_name'),
            'customer_address'        => $request->input('customer_address'),
            'customer_contact_number' => $request->input('customer_contact_number'),
            'product_details'         => $request->input('product_details'),
            'district_id'             => $request->input('district_id'),
            'upazila_id'              => $request->input('upazila_id'),
            'area_id'                 => $request->input('area_id'),
            'weight_package_id'       => $request->input('weight_package_id'),
            'delivery_charge'         => $request->input('delivery_charge'),
            'total_collect_amount'    => $request->input('total_collect_amount') ?? 0,
            'cod_percent'             => $request->input('cod_percent'),
            'cod_charge'              => $request->input('cod_charge'),
            'total_charge'            => $request->input('total_charge'),
            'delivery_option_id'      => $request->input('delivery_option_id'),
            'pick_branch_id'          => $merchant->branch_id,
            'parcel_date'             => date('Y-m-d'),
            'status'                  => 1,
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

    public function list() {
        $data               = [];
        $data['main_menu']  = 'parcel';
        $data['child_menu'] = 'parcelList';
        $data['page_title'] = 'Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.parcelList', $data);
    }

    public function getParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->id;

        $model = Parcel::with(['district', 'upazila', 'area'])
            ->whereRaw('(pickup_branch_id = ? and status in (1,2,3,4,5,6)) or (delivery_branch_id = ? and status in (7,8))', [$branch_id, $branch_id])
            ->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                switch ($data->status) {
                case 1:$status_name  = "Pickup Request"; $class  = "success";break;
                case 2:$status_name  = "Pickup Branch Request Accept"; $class  = "success";break;
                case 3:$status_name  = "Pickup Rider Assign"; $class  = "success";break;
                case 4:$status_name  = "Pickup Rider Request Accept"; $class  = "success";break;
                case 5:$status_name  = "Pickup Rider Pick Parcel"; $class  = "success";break;
                case 6:$status_name  = "Pickup Branch Received Parcel"; $class  = "success";break;
                case 7:$status_name  = "Pickup Branch Assign Delivery Branch"; $class  = "success";break;
                case 8:$status_name  = "Delivery Branch Received"; $class  = "success";break;
                case 9:$status_name  = "Delivery Branch Reject"; $class  = "success";break;
                case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                default:$status_name = "None"; $class = "success";break;
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
