<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Client;
use App\Models\Country;
use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\TransactionFrom;
use App\Models\TransactionTo;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        return $request->all();
//        if ($request->ajax()) {
//        $data = Transaction::orderBy('id', 'desc')->with('client', 'currency', 'user', 'created_user', 'supplier');
        $model = Transaction::with('client', 'currency', 'user', 'created_user', 'supplier')
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            } elseif ($status == 3) {
                $model->whereRaw('status in (3)');
            } elseif ($status == 4) {
                $model->whereRaw('status in (4)');
            }
            $filter['status'] = $request->get('status');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by) && $created_by!=0) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id) && $currency_id!=0) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $client_id = $request->input('client_id');
        if ($request->has('client_id') && !is_null($client_id) && $client_id!=0) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        return Datatables::of($model)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('transaction view')) {
                    $btn .= '<button class="btn btn-success btn-sm view-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="View transaction"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit') && $row->b_status != 1) {
                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('transaction delete') && $row->b_status != 1) {
                    $btn .= " <a href='" . route("transaction.delete", $row->id) . "' class='btn btn-danger btn-sm ' title='Delete transaction' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                $btn .= '<button class="btn btn-info btn-sm print-modal ml-1" transaction_id="' . $row->id . '" title="Print transaction"><i class="fa fa-print"></i></button>';

                return $btn;
            })
           /* ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })*/
//            ->addColumn('bdt_amount', function ($row) {
//                return number_format($row->bdt_amount, 2);
//            })
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
                    $btn .= '<button class="btn btn-primary btn-sm mt-1 ml-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        return view('backend.transactions.index', compact('users', 'currencies', 'clients'));
    }

    public function create()
    {
        if (!check_access("transaction create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $countries = Country::all();
        $clients = Client::where('is_default',"!=",1)->get();
        $users = User::all();
        $currencies = Currency::all();
        $accounts = Account::all();
        $paymentMethods = PaymentMethod::where("status", 1)->get();
        return view('backend.transactions.create', compact('countries', "users", 'clients', 'currencies', "accounts", "paymentMethods"));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("transaction create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $rules = [
                'supplier_id' => 'required',
                'client_id' => 'required',
                'date' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $buying_amount = $request->input('amount') * $request->input('b_rate');
            $selling_amount = $request->input('amount') * $request->input('s_rate');
            $profit_amount = $selling_amount - $buying_amount;


            $last_transaction = Transaction::orderBy("id", 'desc')->select("transaction_no")->first();
            if ($last_transaction) {
                $tn_no = $last_transaction->transaction_no;
                $tn_no = explode("_", $tn_no);
                $transaction_no = "TN_" . ($tn_no[1] + 1);
            } else {
                $transaction_no = "TN_1";
            }
            $inputs = [
                'transaction_no' => $transaction_no,
                'supplier_id' => $request->input('supplier_id'),
                'client_id' => $request->input('client_id'),
                'currency_id' => $request->input('currency_id'),
                'date' => $request->input('date'),
                'amount' => $request->input('amount'),
                'b_rate' => $request->input('b_rate'),
                's_rate' => $request->input('s_rate'),
                'b_bdt_amount' => $buying_amount,
                's_bdt_amount' => $selling_amount,
                'profit' => $profit_amount,
                'sl' => $request->input('sl'),
                'beneficiary' => $request->input('beneficiary'),
                'remarks' => $request->input('remarks'),
                'supplier_representative' => $request->input('supplier_representative'),
                'client_representative' => $request->input('client_representative'),
                'created_by' => auth()->user()->id,
            ];

            DB::beginTransaction();
            if ($request->file("attachment")) {
                $attachment = $request->file('attachment');
                $newName = 'at_' . time() . '.' . $attachment->getClientOriginalExtension();
                $attachment->move('uploads/attachments', $newName);
                $inputs["attachment"] = $newName;
            }

            /*$client = Client::find($request->input('client_id'));
            $supplier = Client::find($request->input('supplier_id'));
            $client->update(["balance" => $client->balance - $selling_amount]);
            $supplier->update(["balance" => $supplier->balance + $buying_amount]);*/

            $transaction = Transaction::create($inputs);

            $sub = "Transaction created.";
            $details = auth()->user()->name . " created transaction.";
            t_log($transaction->id, $sub, $details);
            DB::commit();
            Toastr::success("Transaction Created!");
            b_notify("Created!", "New Transaction Created!", route('transaction'));
            if (!check_access("transaction list")) {
                return redirect()->route('transaction.myList');
            }
            return redirect()->route('transaction');
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $transaction = Transaction::where('id', $id)->with('client', 'supplier')->first();
        return view('backend.transactions.view', compact('transaction'));
    }

    public function print($id)
    {
        $transaction = Transaction::where('id', $id)->with('client')->first();
        return view('backend.transactions.print', compact('transaction'));
    }

    public function edit($id)
    {
        if (!check_access("transaction edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $clients = Client::all();
        $currencies = Currency::all();
        $transaction = Transaction::where('id', $id)->first();
        return view('backend.transactions.edit', compact('clients', 'currencies', 'transaction'));
    }

    public function update(Request $request, $id)
    {
        try {
//            dd($request->all());
            if (!check_access("transaction edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $rules = [
                'supplier_id' => 'required',
                'client_id' => 'required',
                'date' => 'required',
                'pin' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $buying_amount = $request->input('amount') * $request->input('b_rate');
            $selling_amount = $request->input('amount') * $request->input('s_rate');
            $profit_amount = $selling_amount - $buying_amount;


            if (auth()->user()->pin != $request->input("pin")) {
                Toastr::warning("Wrong pin!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'supplier_id' => $request->input('supplier_id'),
                'client_id' => $request->input('client_id'),
                'currency_id' => $request->input('currency_id'),
                'date' => $request->input('date'),
                'amount' => $request->input('amount'),
                'b_rate' => $request->input('b_rate'),
                's_rate' => $request->input('s_rate'),
                'b_bdt_amount' => $buying_amount,
                's_bdt_amount' => $selling_amount,
                'profit' => $profit_amount,
                'sl' => $request->input('sl'),
                'beneficiary' => $request->input('beneficiary'),
                'remarks' => $request->input('remarks'),
                'supplier_representative' => $request->input('supplier_representative'),
                'client_representative' => $request->input('client_representative'),
            ];
            DB::beginTransaction();

           /* $transaction = Transaction::where('id', $id)->first();
            $old_client = Client::find($transaction->client_id);
            $old_supplier = Client::find($transaction->supplier_id);
            $old_client->update(["balance" => $old_client->balance + $transaction->s_bdt_amount]);
            $old_supplier->update(["balance" => $old_supplier->balance - $transaction->b_bdt_amount]);*/


            $transaction = Transaction::where('id', $id)->first();
            $transaction->update($inputs);

            /*$client = Client::find($request->input('client_id'));
            $supplier = Client::find($request->input('supplier_id'));
            $client->update(["balance" => $client->balance - $selling_amount]);
            $supplier->update(["balance" => $supplier->balance + $buying_amount]);*/

            $sub = "Transaction updated.";
            $details = auth()->user()->name . " updated transaction.";
            t_log($transaction->id, $sub, $details);
            DB::commit();
            Toastr::success("Transaction updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("transaction delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Transaction::find($id);
        $item->delete();
        Toastr::success("Transaction Deleted!");
        return redirect()->back();
    }

    public function changeStatus($id)
    {
        if (!check_access("transaction status change")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $transaction = Transaction::where('id', $id)->with('client')->first();
        return view('backend.transactions.status', compact('transaction'));
    }

    public function storeStatus(Request $request, $id)
    {
        try {
//            dd($request->all());
            if (!check_access("transaction status change")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $transaction = Transaction::find($id);
            $oldStatus = '';
            if ($transaction->status == 0) {
                $oldStatus = "pending";
            } elseif ($transaction->status == 1) {
                $oldStatus = "approved";
            } elseif ($transaction->status == 2) {
                $oldStatus = "rejected";
            } elseif ($transaction->status == 3) {
                $oldStatus = "order";
            } elseif ($transaction->status == 4) {
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
            $transactionInputs = [
                'status' => $request->input('status'),
                'remarks' => $request->input('remarks')
            ];
//            $transactionInputs["date"] = date('Y-m-d', strtotime($transaction->date));
            $transaction->update($transactionInputs);

            $buying_amount = $transaction->amount * $transaction->b_rate;
            $selling_amount = $transaction->amount * $transaction->s_rate;
            $client = Client::find($transaction->client_id);
            $supplier = Client::find($transaction->supplier_id);
            if ($request->input('status') == 1 && $transaction->b_status == 0) {
                $client->update(["balance" => $client->balance - $selling_amount]);
                $supplier->update(["balance" => $supplier->balance + $buying_amount]);
                $transaction->update([
                    "b_status" => 1,
                    "approve_date" => date('Y-m-d H:i:s'),
                    ]);
            } else if ($request->input('status') == 2 && $transaction->b_status == 1) {
                $client->update(["balance" => $client->balance + $selling_amount]);
                $supplier->update(["balance" => $supplier->balance - $buying_amount]);
                $transaction->update(["b_status" => 0]);
            } else if ($request->input('status') == 0 && $transaction->b_status == 1) {
                $client->update(["balance" => $client->balance + $selling_amount]);
                $supplier->update(["balance" => $supplier->balance - $buying_amount]);
                $transaction->update(["b_status" => 0]);
            } else if ($request->input('status') == 4 && $transaction->b_status == 0) {
                $client->update(["balance" => $client->balance - $selling_amount]);
                $supplier->update(["balance" => $supplier->balance + $buying_amount]);
                $transaction->update(["b_status" => 1]);
            }

            $sub = "Status changed";
            $details = auth()->user()->name . " changed status from " . $oldStatus . " to " . $newStatus;
            t_log($transaction->id, $sub, $details);
            b_notify("Transaction Status Updated!", $details, route('transaction'));
            Toastr::success("Transaction Status updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            DB::rollBack();
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function returnStatusView($id, $t_id)
    {
        $transaction = Transaction::where('id', $t_id)->with('client')->first();
        $countries = Country::all();
        $accounts = Account::all();
//        return $id;
        if ($id == 1) {
            return view('backend.load.approved_status', compact('transaction', "countries", "accounts"));
        } elseif ($id == 3) {
            return view('backend.load.confirm_order_status', compact('transaction', "countries", "accounts"));
        } elseif ($id == 4) {
            return view('backend.load.complete_status', compact('transaction', "countries", "accounts"));
        } else {
            return view('backend.load.pending_status', compact('transaction'));
        }
    }

    public function filter(Request $request)
    {
//        dd($request->all());
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            } elseif ($status == 3) {
                $model->whereRaw('status in (3)');
            } elseif ($status == 4) {
                $model->whereRaw('status in (4)');
            }
            $filter['status'] = $request->get('status');
        }
        $transaction_type = $request->input('transaction_type');
        if ($request->has('transaction_type') && !is_null($transaction_type)) {
            if ($transaction_type == "debit") {
                $model->whereRaw('transaction_type in ("debit")');
            } elseif ($transaction_type == "credit") {
                $model->whereRaw('transaction_type in ("credit")');
            }
            $filter['transaction_type'] = $request->get('transaction_type');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by)) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $client_id = $request->input('client_id');
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        $items = $model->get();
//        dd($filter);
        return view('backend.transactions.filter', compact('items', 'users', 'currencies', 'clients', 'filter'));
    }

    public function filterPrint(Request $request)
    {
        //        dd($request->all());
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            } elseif ($status == 3) {
                $model->whereRaw('status in (3)');
            } elseif ($status == 4) {
                $model->whereRaw('status in (4)');
            }
            $filter['status'] = $request->get('status');
        }
        $transaction_type = $request->input('transaction_type');
        if ($request->has('transaction_type') && !is_null($transaction_type)) {
            if ($transaction_type == "debit") {
                $model->whereRaw('transaction_type in ("debit")');
            } elseif ($transaction_type == "credit") {
                $model->whereRaw('transaction_type in ("credit")');
            }
            $filter['transaction_type'] = $request->get('transaction_type');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by)) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $client_id = $request->input('client_id');
        if ($request->has('client_id') && !is_null($client_id)) {
            $model->whereRaw('client_id in (' . $client_id . ')');
            $filter['client_id'] = $request->get('client_id');
        }
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        $items = $model->get();
//        dd($filter);
        return view('backend.transactions.print_filter', compact('items', 'users', 'currencies', 'clients', 'filter'));

    }

    public function internalDatatable(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Transaction::orderBy('id', 'desc')->where('transaction_for', "internal")->with('client', 'currency', 'user')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('transaction view')) {
                    $btn .= '<button class="btn btn-info btn-sm view-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="View transaction"><i class="fa fa-eye"></i></button>';
                }
                if (check_permission('transaction edit')) {
                    $btn .= '<a href="' . route("transaction.edit", $row->id) . '" class="btn btn-warning btn-sm ml-1" title="Edit transaction"><i class="fa fa-edit"></i></a>';
                }
                $btn .= '<button class="btn btn-secondary btn-sm print-modal ml-1" transaction_id="' . $row->id . '" title="Print transaction"><i class="fa fa-print"></i></button>';

                return $btn;
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
                    $btn .= '<button class="btn btn-primary btn-sm mt-1 status-modal" data-toggle="modal" data-target="#viewModal" transaction_id="' . $row->id . '" title="Change Status"><i class="fa fa-edit"></i></button>';
                }
                return $btn;
            })->editColumn('date', function ($row) {
                return date('d M, Y', strtotime($row->date));
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
//        }
    }

    public function internalList(Request $request)
    {
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        return view('backend.transactions.internal.index', compact('users', 'currencies', 'clients'));
    }

    public function internalFilter(Request $request)
    {
//        dd($request->all());
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->where('transaction_for', "internal")
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            } elseif ($status == 3) {
                $model->whereRaw('status in (3)');
            } elseif ($status == 4) {
                $model->whereRaw('status in (4)');
            }
            $filter['status'] = $request->get('status');
        }
        $transaction_type = $request->input('transaction_type');
        if ($request->has('transaction_type') && !is_null($transaction_type)) {
            if ($transaction_type == "debit") {
                $model->whereRaw('transaction_type in ("debit")');
            } elseif ($transaction_type == "credit") {
                $model->whereRaw('transaction_type in ("credit")');
            }
            $filter['transaction_type'] = $request->get('transaction_type');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by)) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $user_id = $request->input('user_id');
        if ($request->has('user_id') && !is_null($user_id)) {
            $model->whereRaw('user_id in (' . $user_id . ')');
            $filter['user_id'] = $request->get('user_id');
        }
        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        $items = $model->get();
//        dd($filter);
        return view('backend.transactions.internal.filter', compact('items', 'users', 'currencies', 'clients', 'filter'));
    }

    public function internalPrint(Request $request)
    {
        //        dd($request->all());
        if (!check_access("transaction list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $model = Transaction::with('client', 'currency')
            ->where('transaction_for', "internal")
            ->select();
        $filter = [];
        $status = $request->input('status');
        if ($request->has('status') && !is_null($status)) {
            if ($status == 0) {
                $model->whereRaw('status in (0)');
            } elseif ($status == 1) {
                $model->whereRaw('status in (1)');
            } elseif ($status == 2) {
                $model->whereRaw('status in (2)');
            } elseif ($status == 3) {
                $model->whereRaw('status in (3)');
            } elseif ($status == 4) {
                $model->whereRaw('status in (4)');
            }
            $filter['status'] = $request->get('status');
        }
        $transaction_type = $request->input('transaction_type');
        if ($request->has('transaction_type') && !is_null($transaction_type)) {
            if ($transaction_type == "debit") {
                $model->whereRaw('transaction_type in ("debit")');
            } elseif ($transaction_type == "credit") {
                $model->whereRaw('transaction_type in ("credit")');
            }
            $filter['transaction_type'] = $request->get('transaction_type');
        }
        $created_by = $request->input('created_by');
        if ($request->has('created_by') && !is_null($created_by)) {
            $model->whereRaw('created_by in (' . $created_by . ')');
            $filter['created_by'] = $request->get('created_by');
        }
        $currency_id = $request->input('currency_id');
        if ($request->has('currency_id') && !is_null($currency_id)) {
            $model->whereRaw('currency_id in (' . $currency_id . ')');
            $filter['currency_id'] = $request->get('currency_id');
        }
        $user_id = $request->input('user_id');
        if ($request->has('user_id') && !is_null($user_id)) {
            $model->whereRaw('user_id in (' . $user_id . ')');
            $filter['user_id'] = $request->get('user_id');
        }

        if ($request->has('from_date') && !is_null($request->get('from_date')) && $request->get('from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('from_date'));
            $filter['from_date'] = $request->get('from_date');
        }
        if ($request->has('to_date') && !is_null($request->get('to_date')) && $request->get('to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('to_date'));
            $filter['to_date'] = $request->get('to_date');
        }

        $users = User::all();
        $currencies = Currency::all();
        $clients = Client::all();
        $items = $model->get();
//        dd($filter);
        return view('backend.transactions.internal.print_filter', compact('items', 'users', 'currencies', 'clients', 'filter'));

    }

    public function returnCreateForm($id)
    {
        $countries = Country::all();
        $clients = Client::all();
        $users = User::all();
        $currencies = Currency::all();
        $accounts = Account::all();
        $paymentMethods = PaymentMethod::where("status", 1)->get();
//        dd($id);
        if ($id == "client") {
            return view('backend.load.client_transaction_form', compact('countries', "users", 'clients', 'currencies', "accounts", "paymentMethods"));
        } elseif ($id == "payment") {
            return view('backend.load.payment_transaction_form', compact('countries', "users", 'clients', 'currencies', "accounts", "paymentMethods"));
        }
    }

}
