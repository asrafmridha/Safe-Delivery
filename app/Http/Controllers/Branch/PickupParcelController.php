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
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;

class PickupParcelController extends Controller
{

    public function pickupParcelList()
    {
        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupParcelList';
        $data['page_title'] = 'Pickup Parcel List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.pickupParcel.pickupParcelList', $data);
    }

    public function getPickupParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
        ])
            ->whereRaw('pickup_branch_id = ? and status in (1,4,5,6,7,8,9,10,11)', [$branch_id])
            ->orderBy('id', 'desc')
            ->select(
                'id', 'parcel_invoice', 'date', 'pickup_branch_date','customer_name',
                'total_collect_amount', 'cod_charge', 'pickup_address',
                'delivery_charge', 'weight_package_charge',
                'total_charge', 'district_id', 'upazila_id', 'merchant_id',
                'status', 'delivery_type', 'payment_type'
            );
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_status', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];

                return '<span class=" text-bold" style="font-size:16px;"> ' . $status_name . '</span>';

            })
            ->editColumn('parcel_color', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return $class;
            })
            ->editColumn("pickup_branch_date", function ($data) {

                $date = $data->pickup_branch_date ?? $data->date;
                return $date;
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                        <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editPickupParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 3 || $data->status == 9) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns([
                'parcel_status',
                'action'
            ])
            ->make(true);
    }

    public function printPickupParcelList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number', 'address');
            },
        ])
            ->whereRaw('pickup_branch_id = ? and status in (1,4,5,6,7,8,9,10,11)', [$branch_id])
            ->orderBy('id', 'desc')
            ->select(
                'id', 'parcel_invoice', 'date', 'pickup_branch_date',
                'total_collect_amount', 'cod_charge', 'pickup_address',
                'delivery_charge', 'weight_package_charge',
                'total_charge', 'district_id', 'upazila_id', 'merchant_id','customer_name','customer_contact_number',
                'status', 'delivery_type', 'payment_type'
            );
        $pickupParcels = $model->get();
        return view('branch.parcel.pickupParcel.printPickupParcelList', compact('pickupParcels'));
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('parcel_status', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];

                return '<span class=" text-bold" style="font-size:16px;"> ' . $status_name . '</span>';

            })
            ->editColumn('parcel_color', function ($data) {

                $parcelStatus = returnParcelStatusNameForBranch($data->status, $data->delivery_type, $data->payment_type);
                $status_name = $parcelStatus['status_name'];
                $class = $parcelStatus['class'];
                return $class;
            })
            ->editColumn("pickup_branch_date", function ($data) {

                $date = $data->pickup_branch_date ?? $data->date;
                return $date;
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('parcel.printParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Pickup Parcel" target="_blank">
                        <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_id="' . $data->id . '}" title=" View Pickup Parcel ">
                        <i class="fa fa-eye"></i> </button>';

                $button .= '&nbsp; <a href="' . route('branch.parcel.editPickupParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Parcel "> <i class="fa fa-edit"></i> </a>';

                if ($data->status < 11) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns([
                'parcel_status',
                'action'
            ])
            ->make(true);
    }

    public function editPickupParcel(Request $request, Parcel $parcel)
    {
        $parcel->load('district', 'upazila', 'area', 'merchant', 'weight_package', 'pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider');

        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'pickupParcelList';
        $data['page_title'] = 'Update Pickup Parcel';
        $data['collapse'] = 'sidebar-collapse';
        $data['parcel'] = $parcel;
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

        return view('branch.parcel.pickupParcel.editPickupParcel', $data);
    }

    public function confirmEditPickupParcel(Request $request, Parcel $parcel)
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
                    'pickup_branch_id' => auth()->guard('branch')->user()->branch->id,
                    'pickup_branch_user_id' => auth()->guard('branch')->user()->id,
                    'delivery_type' => $parcel->delivery_type,
                ];
                ParcelLog::create($data);

                \DB::commit();
                $this->setMessage('Parcel Update Successfully', 'success');
                return redirect()->route('branch.parcel.pickupParcelList');
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


    public function destroy(Request $request, Parcel $parcel)
    {
        $check = $parcel->delete() ? true : false;

        if ($check) {
            $this->setMessage('Parcel Delete Successfully', 'success');
        } else {
            $this->setMessage('Parcel Delete Failed', 'danger');
        }

        return redirect()->route('branch.parcel.pickupParcelList');
    }

    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'parcel_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $parcel = Parcel::where('id', $request->parcel_id)->first();
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


}
