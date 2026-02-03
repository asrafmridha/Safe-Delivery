<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DataTables;
use App\Models\ExpenseHead;

class ExpenseHeadController extends Controller
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
        $data['child_menu'] = 'expenseHead';
        $data['page_title'] = 'Expense Heads';
        return view('admin.expense.expense_head', $data);
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
        $data['child_menu'] = 'expenseHead';
        $data['page_title'] = 'Create Expense Heads';
        return view('admin.expense.create_expense_head', $data);
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
            'name'      => ['required','unique:expense_heads']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                  => $request->input('name'),
            'created_admin_id'      => auth()->guard('admin')->user()->id,
        ];

        //dd($data);

        $check = ExpenseHead::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Expense Head Create Successfully', 'success');
            return redirect()->route('admin.expense-head');
        } else {
            $this->setMessage('Expense Head Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseHead  $expenseHead
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseHead $expenseHead)
    {
        $model  = ExpenseHead::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = "";
                    // $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" item_category_id="'.$data->id.'}" >
                    //             <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.expenseHead.edit', $data->id).'" class="btn btn-sm btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-sm btn-danger delete-btn" id="'.$data->id.'">
                                <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
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
                $check = ExpenseHead::where('id', $request->id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Expense Head Status Update Successfully',
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

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = ExpenseHead::where('id', $request->id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Expense Head Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseHead  $expenseHead
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        //$id = $request->id;
        $expenseHead = ExpenseHead::find($id);
        //dd($expenseHead->id);
        $data               = [];
        $data['main_menu']  = 'expenses';
        $data['child_menu'] = 'expenseHead';
        $data['page_title'] = 'Edit Expense Heads';
        $data['expenseHead']      = $expenseHead;
        //dd($expenseHead->id);
        return view('admin.expense.edit_expense_head', $data);
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
            'name'      => ['required','unique:expense_heads']
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'updated_admin_id'   => auth()->guard('admin')->user()->id,
        ];
        $check = ExpenseHead::where('id', $id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Expense Head Update Successfully', 'success');
            return redirect()->route('admin.expense-head');
        } else {
            $this->setMessage('Expense Head Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseHead  $expenseHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseHead $expenseHead)
    {
        //
    }
}
