<?php
namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Models\ExpenseHead;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data               = [];
        $data['p_main_menu']  = 'accounts';
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenses';
        $data['page_title'] = 'Account Entry List';
        $data['collapse']   = 'sidebar-collapse';
        $data['heads']  = ExpenseHead::where('status', 1)->get();
        return view('admin.expense.expense_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data               = [];
        $data['p_main_menu']  = 'accounts';
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenses';
        $data['page_title'] = 'Account Entry';
        $data['heads']  = ExpenseHead::where('status', 1)->get();
        return view('admin.expense.create_expense', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_head_id'      => ['required'],
            'amount'      => ['required'],
            'date'      => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'expense_head_id'       => $request->input('expense_head_id'),
            'type'                  => $request->input('type'),
            'date'                  => $request->input('date'),
            'amount'                => $request->input('amount'),
            'note'                  => $request->input('note'),
            'created_admin_id'      => auth()->guard('admin')->user()->id,
        ];

        //dd($data);

        $check = Expense::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Entry Create Successfully', 'success');
            return redirect()->route('admin.expenses');
        } else {
            $this->setMessage('Entry Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $model = Expense::with(['expense_heads'])->orderBy('date', 'DESC')->get();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('date', function ($data) {
                return date('d/m/Y',strtotime($data->date));
            })
            ->editColumn('type', function ($data) {

                if ($data->type == 1) {
                    $status_name = "Expense";
                } elseif ($data->type == 2) {
                    $status_name = "Income";
                }else{
                    $status_name = "N/A";
                }

                return $status_name;
            })
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                //                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" item_id="' . $data->id . '}" >
                //                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button = '<a href="' . route('admin.expense.print', $data->id) . '" class="btn btn-success btn-sm" target="_blank">   <i class="fas fa-print" title="Print" target="_blank" ></i> </a> &nbsp;&nbsp; &nbsp;';
                
                $button .=  '<a href="' . route('admin.expense.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
              
              
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }


    public function printExpense($id){
        $model = Expense::with(['expense_heads'])->orderBy('date', 'DESC')->find($id);

        return view('admin.expense.print_expense', compact('model'));
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = Expense::where('id', $request->id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Expense Status Update Successfully',
                        'status'  => $request->status,
                    ];
                }
                else{
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //$id = $request->id;
        $expense = Expense::find($id);
        //dd($expenseHead->id);
        $data               = [];
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenses';
        $data['page_title'] = 'Expense List';
        $data['expense']      = $expense;
        $data['heads']  = ExpenseHead::where('status', 1)->get();
        //dd($expenseHead->id);
        return view('admin.expense.edit_expense', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseHead  $expenseHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'expense_head_id'      => ['required'],
            'amount'      => ['required'],
            'date'      => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'expense_head_id'       => $request->input('expense_head_id'),
            'date'                  => $request->input('date'),
            'amount'                => $request->input('amount'),
            'note'                  => $request->input('note'),
            'created_admin_id'      => auth()->guard('admin')->user()->id,
        ];
        $check = Expense::where('id', $id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Expense Update Successfully', 'success');
            return redirect()->route('admin.expenses');
        } else {
            $this->setMessage('Expense Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = Expense::where('id', $request->id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Expense Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function expenseFilter(Request $request)
    {   
        $data               = [];
        $data['p_main_menu']  = 'accounts';
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenses';
        $data['page_title'] = 'Account Entry List';
        $data['collapse']   = 'sidebar-collapse';

        $data['type']   = $request->type;
        $data['expense_head_id']   = $request->expense_head_id;
        $data['from_date']   = $request->from_date;
        $data['to_date']   = $request->to_date;

        $data['heads']  = ExpenseHead::where('status', 1)->get();

        $data['models'] = Expense::where(function ($query) use ($request) {
            
            $type            = $request->type;
            $expense_head_id = $request->expense_head_id;
            $from_date       = $request->from_date;
            $to_date         = $request->to_date;

            if ($type) {
                $query->where('type',$type);
            }
            if ($expense_head_id) {
                $query->where('expense_head_id',$expense_head_id);
            }
            if ($from_date) {
                $query->where('date','>=',$from_date);
            }
            if ($to_date) {
                $query->where('date','<=',$to_date);
            }
        })
            ->with('expense_heads')
            ->orderBy('date', 'DESC')
            ->get();




        // $data['models'] = Expense::with(['expense_heads'])
        // ->where('type',$type)
        // ->where('expense_head_id',$expense_head_id)
        // ->where('date','>=',$from_date)
        // ->where('date','<=',$to_date)
        // ->orderBy('date', 'DESC')
        // ->get();

    



        return view('admin.expense.expense-filter',$data);
    }

    public function expenseFilterPrint($type,$expense_head_id,$from_date,$to_date)
    {   
        $data               = [];
        $data['p_main_menu']  = 'accounts';
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenses';
        $data['page_title'] = 'Account Entry List';
        $data['collapse']   = 'sidebar-collapse';
        $data['heads']  = ExpenseHead::where('status', 1)->get();

        $data['models'] = Expense::where(function ($query) use ($type,$expense_head_id,$from_date,$to_date) {
            
            // $type            = $request->type;
            // $expense_head_id = $request->expense_head_id;
            // $from_date       = $request->from_date;
            // $to_date         = $request->to_date;

            if ($type) {
                $query->where('type',$type);
            }
            if ($expense_head_id) {
                $query->where('expense_head_id',$expense_head_id);
            }
            if ($from_date) {
                $query->where('date','>=',$from_date);
            }
            if ($to_date) {
                $query->where('date','<=',$to_date);
            }
        })
            ->with('expense_heads')
            ->orderBy('date', 'DESC')
            ->get();




        // $data['models'] = Expense::with(['expense_heads'])
        // ->where('type',$type)
        // ->where('expense_head_id',$expense_head_id)
        // ->where('date','>=',$from_date)
        // ->where('date','<=',$to_date)
        // ->orderBy('date', 'DESC')
        // ->get();

    



        return view('admin.expense.expense-filter-print',$data);
    }

}
