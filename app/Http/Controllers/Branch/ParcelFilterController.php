<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\ParcelMerchantDeliveryPaymentDetail;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\Upazila;
use App\Models\WeightPackage;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;

class ParcelFilterController extends Controller {


    public function filterParcelList(Request $request) {

//        dd($request->all());

        $branch_user        = auth()->guard('branch')->user();
        $branch_id          = $branch_user->branch_id;
        $current_date       = date("Y-m-d");
//        $current_date       = "2021-07-25";
        $data               = [];
        $data['main_menu']  = 'home';
        $data['child_menu'] = 'home';
        $data['page_title'] = 'Parcel Filter List';
        $data['collapse']   = 'sidebar-collapse';
//        $data['merchants']   = Merchant::with('branch')
//                                ->where('branch_id', auth()->guard('branch')->user()->branch_id)
//                                ->orderBy('company_name', 'ASC')
//                                ->get();

        if($request->get('filter_type') == 1) {

            $sql = "select m.id, m.m_id, m.company_name, m.address, m.contact_number, p.date, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 and p.pickup_branch_id = {$branch_id} and p.status = 1 and p.date = '{$current_date}' and p.deleted_at IS NULL  group by p.date, m.id, m.m_id, m.company_name, m.address, m.contact_number";
            $merchant_with_parcel = DB::select(DB::raw($sql));

        }elseif($request->get('filter_type') == 2){

            $sql = "select m.id, m.m_id, m.company_name, m.address, m.contact_number, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 and p.pickup_branch_id = {$branch_id} and p.status in (11,13,15) and p.date = '{$current_date}' and p.deleted_at IS NULL group by m.id, m.m_id, m.company_name, m.address, m.contact_number";

            $merchant_with_parcel = DB::select(DB::raw($sql));

        }elseif($request->get('filter_type') == 3){

            $sql = "select m.id, m.m_id, m.company_name, m.address, m.contact_number, count(p.id) as count_parcel from merchants m join parcels p on p.merchant_id = m.id where m.status = 1 and p.pickup_branch_id = {$branch_id} and p.status >= 11 and p.deleted_at IS NULL group by m.id, m.m_id, m.company_name, m.address, m.contact_number";

            $merchant_with_parcel = DB::select(DB::raw($sql));

        }else{

            $merchant_with_parcel = [];
        }

        //dd($branch_id, $merchant_with_parcel);

        $data['parcels_data']   = $merchant_with_parcel;
        $data['filter_type']    = $request->get('filter_type');

        return view('branch.parcel.parcelFilterList', $data);
    }

    public function getAllParcelList(Request $request) {
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = Parcel::with(['district', 'upazila', 'area',
            'merchant' => function ($query) {
                $query->select('id', 'name','company_name', 'contact_number', 'address');
            },
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            }
        ])
            // ->whereRaw('pickup_branch_id = ? and (delivery_branch_id = ? OR delivery_branch_id IS NULL)' , [$branch_id,$branch_id])
            ->select();

        $parcel_status = $request->parcel_status;
        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if($parcel_status == 1){
//                $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('status >= 25 and delivery_type in (1,2)');
            }
            elseif($parcel_status == 2){
//                            $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
            }
            elseif($parcel_status == 3){
//                            $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25,2,4]);
            }
            elseif($parcel_status == 4){
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            }
            elseif($parcel_status == 5){
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            }
            elseif($parcel_status == 6){
//                            $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36,2,4]);
            }
            elseif($parcel_status == 7){
                $model->whereRaw('status in (1,2,4) and delivery_type IS NULL');
            }
        }

        if ($request->has('merchant_id') && !is_null($request->get('merchant_id')) && $request->get('merchant_id') != 0 ) {
            $model->where('merchant_id', $request->get('merchant_id'));
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0 ) {
            $model->whereDate('date', '>=', $request->get('from_date'));
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0 ) {
            $model->whereDate('date', '<=', $request->get('to_date'));
        }


        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date_time', function ($data) {
                $date_time = $data->date."/".date("h:i A", strtotime($data->created_at));

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
                if($data->parcel_logs){
                    foreach ($data->parcel_logs as $parcel_log) {
                        if("" != $logs_note) {
                            $logs_note .= ",<br>";
                        }
                        $logs_note .= $parcel_log->note;
                    }
                }

                return $logs_note;
            })
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

                $button .= '&nbsp; <a href="' . route('branch.parcel.editParcel', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel "> <i class="fa fa-edit"></i> </a>';

                if($data->status < 11) {
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm delete-btn" parcel_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['status', 'action', 'image'])
            ->make(true);
    }


}
