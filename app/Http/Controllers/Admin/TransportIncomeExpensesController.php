<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Parcel;
use App\Models\ParcelDeliveryPayment;
use App\Models\ParcelDeliveryPaymentDetail;
use App\Models\ParcelLog;
use App\Models\TransportIncomeExpense;
use App\Models\Vehicle;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransportIncomeExpensesController extends Controller {

    public function transportIncomeExpenseGenerate() {
        $data               = [];
        $data['main_menu']  = 'account';
        $data['child_menu'] = 'transportIncomeExpenseGenerate';
        $data['page_title'] = 'Generate Transport Income  Expense';
        $data['collapse']   = 'sidebar-collapse';
        $data['vehicles']   = Vehicle::where('status', 1)->get();
        return view('admin.account.transportIncomeExpense.transportIncomeExpenseGenerate', $data);
    }

    public function confirmTransportIncomeExpenseGenerate(Request $request) {

        $validator = Validator::make($request->all(), [
            'vehicle_id'           => 'required',
            'date'                 => 'required',
            'driver_name'          => 'required',
            'vehicle_driver_phone' => 'required',
            'starting_km'          => 'required',
            'ending_km'            => 'required',
            'total_km'             => 'required',
            'to_destination'       => 'required',
            'from_destination'     => 'required',
            'advance_trip_amount'  => 'required',
            'up_trip_amount'       => 'required',
            'down_to_amount'       => 'required',
            'total_trip_amount'    => 'required',
            'all_expense_amount'   => 'required',
            'all_income_amount'    => 'required',
            'all_net_income'       => 'required',
            'received_amount'      => 'required',
            'due_amount'           => 'required',
            'remark'               => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $admin = auth()->guard('admin')->user();

            $data = [
                'vehicle_id'           => $request->input('vehicle_id'),
                'date'                 => $request->input('date'),
                'driver_name'          => $request->input('driver_name'),
                'vehicle_driver_phone' => $request->input('vehicle_driver_phone'),
                'starting_km'          => $request->input('starting_km'),
                'ending_km'            => $request->input('ending_km'),
                'total_km'             => $request->input('total_km'),
                'to_destination'       => $request->input('to_destination'),
                'from_destination'     => $request->input('from_destination'),
                'advance_trip_amount'  => $request->input('advance_trip_amount'),
                'up_trip_amount'       => $request->input('up_trip_amount'),
                'down_to_amount'       => $request->input('down_to_amount'),
                'total_trip_amount'    => $request->input('total_trip_amount'),
                'all_expense_amount'   => $request->input('all_expense_amount') ?? 0,
                'all_income_amount'    => $request->input('all_income_amount'),
                'all_net_income'       => $request->input('all_net_income'),
                'received_amount'      => $request->input('received_amount'),
                'due_amount'           => $request->input('due_amount'),
                'remark'               => $request->input('remark'),
                'created_admin_id'     => $admin->id,
            ];

            $transportIncomeExpenses = TransportIncomeExpense::create($data);

            if (!empty($transportIncomeExpenses)) {

                \DB::commit();
                $this->setMessage('Transport Income Expenses Create Successfully', 'success');
                return redirect()->back();
            } else {
                $this->setMessage('Transport Income Expenses Create Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception$e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function transportIncomeExpenseList() {
        $data               = [];
        $data['main_menu']  = 'account';
        $data['child_menu'] = 'transportIncomeExpenseList';
        $data['page_title'] = 'Transport Income  Expense List';
        $data['collapse']   = 'sidebar-collapse';
        $data['vehicles']   = Vehicle::where('status', 1)->get();
        return view('admin.account.transportIncomeExpense.transportIncomeExpenseList', $data);
    }

    public function getTransportIncomeExpenseList(Request $request) {

        $model = TransportIncomeExpense::with(['vehicle' => function ($query) {
                    $query->select('id', 'name', 'vehicle_sl_no', 'vehicle_no', 'vehicle_driver_name');
                },
            ])
            ->where(function ($query) use ($request) {
                $vehicle_id = $request->input('vehicle_id');
                $from_date = $request->input('from_date');
                $to_date   = $request->input('to_date');

                if ($request->has('vehicle_id') && !is_null($vehicle_id) && $vehicle_id != '' && $vehicle_id != 0) {
                    $query->where('vehicle_id', $request->input('vehicle_id'));
                }

                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('date', '>=', $request->input('from_date'));
                }

                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('date', '<=', $request->input('to_date'));
                }

            })
            ->orderBy('id', 'desc')
            ->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date', function ($data) {
                return date('d-m-Y', strtotime($data->date));
            })
            ->editColumn('received_amount', function ($data) {
                return number_format($data->received_amount, 2);
            })
            ->editColumn('due_amount', function ($data) {
                return number_format($data->due_amount, 2);
            })
            ->addColumn('vehicle_name', function ($data) {
                return $data->vehicle->name.' '.$data->vehicle->vehicle_sl_no.' '.$data->vehicle->vehicle_no;
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" transport_income_expense_id="' . $data->id . '" title="View Branch Delivery Payment">
                <i class="fa fa-eye"></i> </button>';
                return $button;
            })
            ->rawColumns(['action', 'vehicle_name', 'total_payment_amount', 'received_amount', 'due_amount', 'date'])
            ->make(true);
    }

    public function viewTransportIncomeExpense(Request $request, TransportIncomeExpense $transportIncomeExpense) {
        $transportIncomeExpense->load('vehicle');
        return view('admin.account.transportIncomeExpense.viewTransportIncomeExpense', compact('transportIncomeExpense'));
    }

    public function acceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user', 'admin', 'parcel_delivery_payment_details');
        return view('admin.account.deliveryPayment.acceptBranchDeliveryPayment', compact('parcelDeliveryPayment'));
    }

    public function confirmAcceptBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
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
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                        'id' => $parcelDeliveryPayment->id,

                    ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'total_payment_received_parcel' => $request->total_payment_received_parcel,
                            'total_payment_received_amount' => $request->total_payment_received_amount,
                            'note'                          => $request->note,
                            'status'                        => 2,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {
                        $parcel_delivery_payment_detail_status = $request->parcel_delivery_payment_detail_status;
                        $parcel_delivery_payment_detail_id     = $request->parcel_delivery_payment_detail_id;
                        $parcel_id                             = $request->parcel_id;
                        $amount                                = $request->amount;
                        $detail_note                           = $request->detail_note;

                        $count = count($parcel_delivery_payment_detail_id);

                        for ($i = 0; $i < $count; $i++) {
                            ParcelDeliveryPaymentDetail::where('id', $parcel_delivery_payment_detail_id[$i])->update([
                                'note'      => $detail_note[$i],
                                'date_time' => date('Y-m-d H:i:s'),
                                'admin_id'  => $admin_id,
                                'status'    => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type' => $parcel_delivery_payment_detail_status[$i],
                            ]);

                            $parcel  = Parcel::with('merchant')->where('id', $parcel_id[$i])->first();
                            $message = "Dear " . $parcel->merchant->name . ", Flier Express just delivered/partial delivered/Returned your product Reff-" . $parcel->parcel_invoice . " .  Please Collect the amount from accounts.";
                            $this->send_sms($parcel->customer_contact_number, $message);
                        }

                        \DB::commit();
                        $response = ['success' => 'Accept Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }

                } catch (\Exception$e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
                }

            }

        }

        return response()->json($response);

    }

    public function rejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $parcelDeliveryPayment->load('branch', 'branch_user');
        $parcelDeliveryPaymentDetails = ParcelDeliveryPaymentDetail::with('parcel')->where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->get();
        return view('admin.account.deliveryPayment.rejectBranchDeliveryPayment', compact('parcelDeliveryPayment', 'parcelDeliveryPaymentDetails'));
    }

    public function confirmRejectBranchDeliveryPayment(Request $request, ParcelDeliveryPayment $parcelDeliveryPayment) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'note' => 'sometimes',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()]);
            } else {
                \DB::beginTransaction();
                try {
                    $admin_id = auth()->guard('admin')->user()->id;

                    $check = ParcelDeliveryPayment::where([
                        'id' => $parcelDeliveryPayment->id,
                    ])
                        ->update([
                            'action_date_time'              => date('Y-m-d H:i:s'),
                            'note'                          => $request->note,
                            'total_payment_received_parcel' => 0,
                            'total_payment_received_amount' => 0,
                            'status'                        => 3,
                            'admin_id'                      => $admin_id,
                        ]);

                    if ($check) {

                        ParcelDeliveryPaymentDetail::where('parcel_delivery_payment_id', $parcelDeliveryPayment->id)->update([
                            'date_time' => date('Y-m-d H:i:s'),
                            'status'    => 3,
                        ]);

                        $parcel_id = $request->parcel_id;

                        $count = count($parcel_id);

                        for ($i = 0; $i < $count; $i++) {
                            Parcel::where('id', $parcel_id[$i])->update([
                                'payment_type' => 3,
                                'admin_id'     => $admin_id,
                            ]);
                        }

                        \DB::commit();
                        $response = ['success' => 'Reject Delivery Payment Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }

                } catch (\Exception$e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error'];
                }

            }

        }

        return response()->json($response);

    }

}
