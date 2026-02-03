<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Payment::orderBy('id', 'desc')->with('client', 'created_user','payment_method','supplier');
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('payment edit')) {
                    $btn .= '<button class="btn btn-success btn-sm view-modal" data-toggle="modal" data-target="#viewModal" payment_id="' . $row->id . '" title="View payment"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit')) {
//                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('transaction delete')) {
//                    $btn .= " <a href='" . route("transaction.delete", $row->id) . "' class='btn btn-danger btn-sm ' title='Delete transaction' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                $btn .= '<button class="btn btn-info btn-sm print-modal ml-1" payment_id="' . $row->id . '" title="Print payment"><i class="fa fa-print"></i></button>';

                return $btn;
            })
           /* ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })*/
            ->addColumn('status', function ($row) {
                $btn = '';
                if ($row->status == 1) {
                    $btn .= '<p class="badge badge-info">Approved</p>';
                } elseif ($row->status == 0) {
                    $btn .= '<p class="badge badge-warning">Pending</p>';
                } elseif ($row->status == 2) {
                    $btn .= '<p class="badge badge-danger">Rejected</p>';
                } elseif ($row->status == 3) {
                    $btn .= '<p class="badge badge-primary">Order</p>';
                } elseif ($row->status == 4) {
                    $btn .= '<p class="badge badge-success">Completed</p>';
                }
                if (check_permission('transaction status change')) {
//                    $btn .= '<button class="btn btn-primary btn-sm mt-1 ml-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function index()
    {
        $clients = Client::all();
        $paymentMethods = PaymentMethod::all();
        $currencies = Currency::all();
        $users = User::all();
        return view("backend.payments.index", compact('users','clients', 'paymentMethods', 'currencies'));
    }

    public function create()
    {
        $clients = Client::all();
        $paymentMethods = PaymentMethod::all();
        $currencies = Currency::all();
        return view("backend.payments.create", compact('clients', 'paymentMethods', 'currencies'));
    }

    public function store(Request $request)
    {
//        dd($request->all());
        try {

            if (!check_access("transaction create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $rules = [
                'client_id' => 'required',
                'date' => 'required',
                'amount' => 'required',
                'payment_method_id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'payment_no' => "pay" . time() . $request->input('client_id'),
                "client_id" => $request->input('client_id'),
                "supplier_id" => $request->input('supplier_id'),
                "payment_method_id" => $request->input('payment_method_id'),
                "date" => $request->input('date'),
                "amount" => $request->input('amount'),
                "client_representative" => $request->input('client_representative'),
                "supplier_representative" => $request->input('supplier_representative'),
                "remarks" => $request->input('remarks'),
                'created_by' => auth()->user()->id,
            ];
            DB::beginTransaction();
            if ($request->file("attachment")) {
                $attachment = $request->file('attachment');
                $newName = 'at_' . time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move('uploads/attachments', $newName);
                $inputs["attachment"] = $newName;
            }
           /* $client = Client::find($request->input('client_id'));
            $supplier = Client::find($request->input('supplier_id'));
            $client->update(["balance"=>$client->balance + $request->input('amount')]);
            $supplier->update(["balance"=>$supplier->balance - $request->input('amount')]);*/

            Payment::create($inputs);
            DB::commit();
            Toastr::success("Payment Created!");
            return redirect()->route('payment');
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $payment = Payment::where('id', $id)->with('client','supplier')->first();
        return view('backend.payments.view', compact('payment'));
    }
    public function print($id)
    {
        $payment = Payment::where('id', $id)->with('client','supplier')->first();
        return view('backend.payments.print', compact('payment'));
    }

    public function changeStatus(Request $request, $id)
    {
        try {
//            dd($request->all());
           /* if (!check_access("payment status change")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }*/
            $payment = Payment::find($id);
            $oldStatus = '';
            if ($payment->status == 0) {
                $oldStatus = "pending";
            } elseif ($payment->status == 1) {
                $oldStatus = "approved";
            } elseif ($payment->status == 2) {
                $oldStatus = "rejected";
            } elseif ($payment->status == 3) {
                $oldStatus = "order";
            } elseif ($payment->status == 4) {
                $oldStatus = "completed";
            }
            $newStatus = '';
            if ($request->input('status') == 0) {
                $newStatus = "pending";
            } elseif ($request->input('status') == 1) {
                $newStatus = "approved";
            } elseif ($request->input('status') == 2) {
                $newStatus = "rejected";
            } elseif ($request->input('status') == 3) {
                $newStatus = "order";
            } elseif ($request->input('status') == 4) {
                $newStatus = "completed";
            }
            $rules = [
                "pin" => "required",
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error($validator->getMessageBag());
                return redirect()->back();
            }
            if (auth()->user()->pin != $request->input("pin")) {
                Toastr::warning("Wrong pin!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [];
            $paymentInputs = [
                'status' => $request->input('status'),
                'remarks' => $request->input('remarks')
            ];
//            $transactionInputs["date"] = date('Y-m-d', strtotime($transaction->date));

//            $auth_user = User::find( auth()->user()->id);
            $created_user = User::find($payment->created_by);
            $client = Client::find( $payment->client_id);
            $supplier = Client::find($payment->supplier_id);

            if ($request->input('status') == 1 && $payment->b_status == 0){
                if ($client->is_default==1){
                    if ($created_user->balance<=$payment->amount && !check_access('create debit without balance')){
                        Toastr::error("User Don't have enough balance!");
                        return redirect()->back();
                    }else{
                        $created_user->update(["balance"=>$created_user->balance - $payment->amount]);
                    }
                }
                if ($supplier->is_default==1){
                    $created_user->update(["balance"=>$created_user->balance + $payment->amount]);
                }
                $client->update(["balance"=>$client->balance + $payment->amount]);
                $supplier->update(["balance"=>$supplier->balance - $payment->amount]);
                $payment->update(["b_status"=>1]);
                $paymentInputs['approve_date']=date("Y-m-d H:i:s");

            }else if ($request->input('status') == 2 && $payment->b_status == 1){
                if ($client->is_default==1){
                    $created_user->update(["balance"=>$created_user->balance + $payment->amount]);
                }
                if ($supplier->is_default==1){
                    if ($created_user->balance<=$payment->amount && !check_access('create debit without balance')){
                        Toastr::error("User Don't have enough balance!");
                        return redirect()->back();
                    }else{
                        $created_user->update(["balance"=>$created_user->balance - $payment->amount]);
                    }
                }
                $client->update(["balance"=>$client->balance - $payment->amount]);
                $supplier->update(["balance"=>$supplier->balance + $payment->amount]);
                $payment->update(["b_status"=>0]);
            }else if ($request->input('status') == 0 && $payment->b_status == 1){
                if ($client->is_default==1){
                    $created_user->update(["balance"=>$created_user->balance + $payment->amount]);
                }
                if ($supplier->is_default==1){
                    if ($created_user->balance<=$payment->amount && !check_access('create debit without balance')){
                        Toastr::error("User Don't have enough balance!");
                        return redirect()->back();
                    }else{
                        $created_user->update(["balance"=>$created_user->balance - $payment->amount]);
                    }
                }
                $client->update(["balance"=>$client->balance - $payment->amount]);
                $supplier->update(["balance"=>$supplier->balance + $payment->amount]);
                $payment->update(["b_status"=>0]);

            }else if ($request->input('status') == 4 && $payment->b_status == 0){
                $client->update(["balance"=>$client->balance + $payment->amount]);
                $supplier->update(["balance"=>$supplier->balance - $payment->amount]);
                $payment->update(["b_status"=>1]);
                if ($client->is_default==1){
                    $created_user->update(["balance"=>$created_user->balance - $payment->amount]);
                }
                if ($supplier->is_default==1){
                    $created_user->update(["balance"=>$created_user->balance + $payment->amount]);
                }
            }
            $payment->update($paymentInputs);
            $sub = "Status changed";
            $details = auth()->user()->name . " changed status from " . $oldStatus . " to " . $newStatus;
//            t_log($payment->id, $sub, $details);
//            b_notify("Transaction Status Updated!",$details,route('transaction'));
            Toastr::success("Payment Status updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }



    public function pendingDatatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Payment::orderBy('id', 'desc')->with('client', 'created_user','payment_method','supplier')->where('status',0);
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('payment edit')) {
                    $btn .= '<button class="btn btn-success btn-sm view-modal" data-toggle="modal" data-target="#viewModal" payment_id="' . $row->id . '" title="View payment"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit')) {
//                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('transaction delete')) {
//                    $btn .= " <a href='" . route("transaction.delete", $row->id) . "' class='btn btn-danger btn-sm ' title='Delete transaction' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                $btn .= '<button class="btn btn-info btn-sm print-modal ml-1" payment_id="' . $row->id . '" title="Print payment"><i class="fa fa-print"></i></button>';

                return $btn;
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('status', function ($row) {
                $btn = '';
                if ($row->status == 1) {
                    $btn .= '<p class="badge badge-info">Approved</p>';
                } elseif ($row->status == 0) {
                    $btn .= '<p class="badge badge-warning">Pending</p>';
                } elseif ($row->status == 2) {
                    $btn .= '<p class="badge badge-danger">Rejected</p>';
                } elseif ($row->status == 3) {
                    $btn .= '<p class="badge badge-primary">Order</p>';
                } elseif ($row->status == 4) {
                    $btn .= '<p class="badge badge-success">Completed</p>';
                }
                if (check_permission('transaction status change')) {
//                    $btn .= '<button class="btn btn-primary btn-sm mt-1 ml-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function pending()
    {
        $clients = Client::all();
        $paymentMethods = PaymentMethod::all();
        $currencies = Currency::all();
        $users = User::all();
        return view("backend.payments.pending", compact('users','clients', 'paymentMethods', 'currencies'));
    }

}
