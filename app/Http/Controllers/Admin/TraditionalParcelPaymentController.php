<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingParcelPayment;
use App\Models\BookingParcelPaymentDetails;
use App\Models\BookingParcelPaymentLog;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TraditionalParcelPaymentController extends Controller
{


    public function branchParcelPaymentList() {
        $data               = [];
        $data['main_menu']  = 'traditional_parcel';
        $data['child_menu'] = 'branchParcelPaymentList';
        $data['page_title'] = 'Branch Parcel Payment List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.traditionalParcelPayment.branchParcelPaymentList', $data);
    }

    public function getBranchParcelPaymentList(Request $request) {

        $model = BookingParcelPayment::with(['branch' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])->where(function ($query) use ($request) {
                $branch_id = $request->input('branch_id');
                $status    = $request->input('status');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                    $query->where('branch_id', $branch_id);
                }

                if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
                    $query->where('payment_status', $status);
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('payment_date', '>=', $from_date);
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('payment_date', '<=', $to_date);
                }

            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('total_amount', function ($data) {
                $total_amount = $data->total_amount;
                return sprintf("%.2f", $total_amount);
            })
            ->editColumn('receive_amount', function ($data) {
                $total_amount = $data->receive_amount;
                return sprintf("%.2f", $total_amount);
            })
            ->editColumn('payment_status', function ($data) {
                switch ($data->payment_status) {
                    case '0':$delivery_type  = "Request Cancel"; $class  = "danger";break;
                    case '1':$delivery_type = "Send Request"; $class = "info";break;
                    case '2':$delivery_type = "Request Accept"; $class = "Success";break;
                    default:$delivery_type    = "None"; $class    = "warning";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_id="' . $data->id . '" title="View Branch Parcel Payment">
                <i class="fa fa-eye"></i> </button>';

                if ($data->payment_status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success accept-branch-parcel-payment btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_id="' . $data->id . '" title="Accept Branch Parcel Payment">
                    <i class="fa fa-check"></i> </button>';
                    $button .= '&nbsp; <button class="btn btn-danger btn-sm reject-branch-parcel-payment" data-toggle="modal" data-target="#viewModal" parcel_payment_id="' . $data->id . '" title="Reject Branch Parcel Payment">
                            <i class="far fa-window-close"></i> </button>';
                }
                return $button;
            })
            ->rawColumns(['action', 'payment_status'])
            ->make(true);
    }

    public function viewBranchParcelPayment(Request $request, BookingParcelPayment $parcelPayment) {
        $parcelPayment->load('branch', 'booking_parcel_payment_logs');
        return view('admin.account.traditionalParcelPayment.branchParcelPaymentView', compact('parcelPayment'));
    }

    public function acceptBranchParcelPayment(Request $request, BookingParcelPayment $parcelPayment) {
        $parcelPayment->load('branch', 'branch_user', 'booking_parcel_payment_logs');
        return view('admin.account.traditionalParcelPayment.acceptBranchParcelPayment', compact('parcelPayment'));
    }

    public function confirmAcceptBranchParcelPayment(Request $request, BookingParcelPayment $parcelPayment) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'total_payment_received_parcel' => 'required',
                'total_payment_received_amount' => 'required',
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {

                $admin_id = auth()->guard('admin')->user()->id;

                //dd($request->all());

                \DB::beginTransaction();
                try {

                    $check = BookingParcelPayment::where([
                        'id' => $parcelPayment->id,

                    ])->update([
                        'receive_parcel'            => $request->total_payment_received_parcel,
                        'receive_amount'            => $request->total_payment_received_amount,
                        'payment_note'              => $request->note,
                        'payment_status'            => 2,
                        'updated_admin_user_id'     => $admin_id,
                        'updated_at'                => date('Y-m-d H:i:s'),
                    ]);

                    if ($check) {
                        $parcel_payment_detail_status           = $request->parcel_payment_detail_status;
                        $parcel_payment_detail_id               = $request->parcel_payment_detail_id;
                        $parcel_payment_log_id                  = $request->parcel_payment_log_id;
                        $amount                                 = $request->amount;
                        $payments_note                          = $request->payment_note;

                        $count = count($parcel_payment_detail_id);

                        for ($i = 0; $i < $count; $i++) {

                            $payment_details_status = ($parcel_payment_detail_status[$i] == 0) ? 0 : 3;
                            $payment_note = ($payments_note[$i] != "") ? $payments_note[$i] : $request->note;

                            BookingParcelPaymentLog::where('id', $parcel_payment_log_id[$i])->update([
                                'payment_status'             => $parcel_payment_detail_status[$i],
                                'payment_note'               => $payment_note,
                                'updated_at'                 => date("Y-m-d H:i:s"),
                                'updated_admin_user_id'      => $admin_id,
                            ]);

                            BookingParcelPaymentDetails::where('id', $parcel_payment_detail_id[$i])->update([
                                'updated_at'            => date('Y-m-d H:i:s'),
                                'updated_admin_user_id' => $admin_id,
                                'status'                => $payment_details_status,
                            ]);

                        }

                        \DB::commit();
                        $response = ['success' => 'Accept Parcel Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
//                    $response = ['error' => $e->getMessage()];
                }
            }
        }
        return response()->json($response);

    }

    public function rejectBranchParcelPayment(Request $request, BookingParcelPayment $parcelPayment) {
        $parcelPayment->load('branch', 'branch_user');
        $parcelPaymentLogDetails = BookingParcelPaymentLog::with('booking_parcels', 'booking_parcel_payment_details')->where('payment_id', $parcelPayment->id)->get();
        return view('admin.account.traditionalParcelPayment.rejectBranchParcelPayment', compact('parcelPayment', 'parcelPaymentLogDetails'));
    }

    public function confirmRejectBranchParcelPayment(Request $request, BookingParcelPayment $parcelPayment) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note'                          => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = BookingParcelPayment::where([
                        'id' => $parcelPayment->id,
                    ])->update([
                            'updated_at'            => date('Y-m-d H:i:s'),
                            'payment_note'          => $request->note,
                            'receive_parcel'        => 0,
                            'receive_amount'        => 0,
                            'payment_status'        => 0,
                            'updated_admin_user_id' => $admin_id,
                        ]);

                    if ($check) {

                        BookingParcelPaymentLog::where('payment_id', $parcelPayment->id)->update([
                            'payment_status'        => 0,
                            'updated_admin_user_id' => $admin_id,
                            'updated_at'            => date('Y-m-d H:i:s'),
                        ]);

                        $payment_details_id                            = $request->payment_details_id;

                        $count = count($payment_details_id);

                        for ($i = 0; $i < $count; $i++) {
                            BookingParcelPaymentDetails::where('id', $payment_details_id[$i])->update([
                                'status'                   => 0,
                                'updated_admin_user_id'    => $admin_id,
                                'updated_at'               => date("Y-m-d H:i:s"),
                            ]);
                        }

                        \DB::commit();
                        $response = ['success' => 'Reject Parcel Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                }
                catch (\Exception $e){
                    \DB::rollback();
//                    $response = ['error' => 'Database Error'];
                    $response = ['error' => $e->getMessage()];
                }
            }
        }
        return response()->json($response);

    }

    public function branchBookingParcelPaymentReport()
    {
        $data               = [];
        $data['main_menu']  = 'traditional_parcel';
        $data['child_menu'] = 'branchParcelPaymentReport';
        $data['page_title'] = 'Branch Parcel Payment Report';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['parcel_payment_reports']   = [];
        $data['payment_total_amount']     = 0;
        $data['payment_total_pending_amount']   = 0;
        $data['payment_total_receive_amount']   = 0;


        $model_data = BookingParcelPayment::with(['branch' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])->whereIn('payment_status', [1,2])
//        ->where(function ($query) use ($request) {
//            $branch_id = $request->input('branch_id');
//            $status    = $request->input('status');
//            $from_date = $request->input('from_date');
//            $to_date   = $request->input('to_date');
//
//            if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
//                $query->where('branch_id', $branch_id);
//            }
//
//            if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
//                $query->where('payment_status', $status);
//            }
//
//            if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
//                $query->whereDate('payment_date', '>=', $from_date);
//            }
//
//            if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
//                $query->whereDate('payment_date', '<=', $to_date);
//            }
//
//        })
        ->orderBy('id', 'desc')
        ->get();


        if(count($model_data) > 0) {
            $i = 0;
            foreach ($model_data as $payment_data) {
                $i++;

                $pending_amount = ($payment_data->payment_status == 1) ? number_format((float) $payment_data->total_amount, 2, '.', '') : number_format((float) 0, 2, '.', '');
                $data['payment_total_amount'] += number_format((float) $payment_data->total_amount, 2, '.', '');
                $data['payment_total_pending_amount'] += number_format((float) $pending_amount, 2, '.', '');
                $data['payment_total_receive_amount'] += number_format((float) $payment_data->receive_amount, 2, '.', '');
                $data['parcel_payment_reports'][] = '<tr>
                                                        <td class="text-center">'.$i.'</td>
                                                        <td class="text-center">'.$payment_data->payment_date.'</td>
                                                        <td class="text-center">'.$payment_data->bill_no.'</td>
                                                        <td class="text-center">'.$payment_data->branch->name.'</td>
                                                        <td class="text-center">'.$payment_data->payment_parcel.'</td>
                                                        <td class="text-center">'.$payment_data->receive_parcel.'</td>
                                                        <td class="text-center">'.number_format((float) $payment_data->total_amount, 2, '.', '').'</td>
                                                        <td class="text-center">'.$pending_amount.'</td>
                                                        <td class="text-center">'.number_format((float) $payment_data->receive_amount, 2, '.', '').'</td>
                                                    </tr>';
            }
        }


        return view('admin.account.traditionalParcelPayment.branchParcelPaymentReport', $data);
    }

    public function branchBookingParcelPaymentReportAjax(Request $request)
    {
        $data               = [];
        $data['parcel_payment_reports']   = [];
        $data['payment_total_amount']     = 0;
        $data['payment_total_pending_amount']   = 0;
        $data['payment_total_receive_amount']   = 0;


        $model_data = BookingParcelPayment::with(['branch' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])->whereIn('payment_status', [1,2])
        ->where(function ($query) use ($request) {
            $branch_id = $request->input('branch_id');
//            $status    = $request->input('status');
            $from_date = $request->input('from_date');
            $to_date   = $request->input('to_date');

            if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '') {
                $query->where('branch_id', $branch_id);
            }

//            if ($request->has('status') && !is_null($status) && $status != '' && $status != 0) {
//                $query->where('payment_status', $status);
//            }

            if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                $query->whereDate('payment_date', '>=', $from_date);
            }

            if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                $query->whereDate('payment_date', '<=', $to_date);
            }

        })
        ->orderBy('id', 'desc')
        ->get();


        if(count($model_data) > 0) {
            $i = 0;
            foreach ($model_data as $payment_data) {
                $i++;

                $pending_amount = ($payment_data->payment_status == 1) ? number_format((float) $payment_data->total_amount, 2, '.', '') : number_format((float) 0, 2, '.', '');
                $data['payment_total_amount'] += number_format((float) $payment_data->total_amount, 2, '.', '');
                $data['payment_total_pending_amount'] += number_format((float) $pending_amount, 2, '.', '');
                $data['payment_total_receive_amount'] += number_format((float) $payment_data->receive_amount, 2, '.', '');
                $data['parcel_payment_reports'][] = '<tr>
                                                        <td class="text-center">'.$i.'</td>
                                                        <td class="text-center">'.$payment_data->payment_date.'</td>
                                                        <td class="text-center">'.$payment_data->bill_no.'</td>
                                                        <td class="text-center">'.$payment_data->branch->name.'</td>
                                                        <td class="text-center">'.$payment_data->payment_parcel.'</td>
                                                        <td class="text-center">'.$payment_data->receive_parcel.'</td>
                                                        <td class="text-center">'.number_format((float) $payment_data->total_amount, 2, '.', '').'</td>
                                                        <td class="text-center">'.$pending_amount.'</td>
                                                        <td class="text-center">'.number_format((float) $payment_data->receive_amount, 2, '.', '').'</td>
                                                    </tr>';
            }
        }


        return view('admin.account.traditionalParcelPayment.filterBranchParcelPaymentReport', $data);
    }


}
