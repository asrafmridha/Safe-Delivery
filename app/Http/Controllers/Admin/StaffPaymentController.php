<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Staff;
use App\Models\StaffPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StaffPaymentController extends Controller
{

    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_payment_list';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Staff Payment';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['staff']   = Staff::where('status', 1)->get();
        return view('admin.account.salary.staffPayment.staffPaymentList', $data);
    }



    public function getStaffPaymentList(Request $request) {

        $model = StaffPayment::with(['staff'])
                ->where(function ($query) use ($request) {

                    $branch_id  = $request->input('branch_id');
                    $staff_id   = $request->input('staff_id');

                    $from_date  = date("Y-m-01", strtotime(Carbon::now()->subMonth()->format("Y-m-d")));
                    $to_date  = Carbon::now()->format("Y-m-d");


                    if(($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0)
                        || ($request->has('staff_id') && !is_null($staff_id) && $staff_id != '' && $staff_id != 0)
                        || ($request->has('from_date') && !is_null($request->input('from_date')) && $request->input('from_date') != '')
                        || ($request->has('to_date') && !is_null($request->input('to_date')) && $request->input('to_date') != '')
                    ){

                        if($request->input('from_date')) {
                            $from_date = date("Y-m-01", strtotime($request->input('from_date')));
                        }else{
                            $from_date = "";
                        }

                        if($request->input('to_date')) {
                            $to_date = date("Y-m-t", strtotime($request->input('to_date')));
                        }else{
                            $to_date = "";
                        }

                        if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                            $query->whereHas('staff', function ($query2) use($branch_id){
                                $query2->where('branch_id', $branch_id);
                            });
                        }

                        if ($request->has('staff_id') && !is_null($staff_id) && $staff_id != '' && $staff_id != 0) {
                            $query->where('staff_id', $staff_id);
                        }


                        if ($request->has('from_date') && !is_null($request->input('from_date'))) {
                            $query->whereDate('payment_month', '>=', $from_date);
                        }

                        if ($request->has('to_date') && !is_null($request->input('from_date'))) {
                            $query->whereDate('payment_month', '<=', $to_date);
                        }
                    }else{
                        $query->whereBetween('payment_month', [$from_date, $to_date]);
                    }
                })->select();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('payment_month', function ($data) {

                $month = date("M Y", strtotime($data->payment_month));

                return $month;
            })
            ->make(true);
    }

    public function create()
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_payment_list';
        $data['page_title'] = 'Create Staff Payment';
        $data['branches']   = Branch::where('status', 1)->get();
        return view('admin.account.salary.staffPayment.staffPayment', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id'             => 'required',
            'staff_id'              => 'required',
            'salary_amount'         => 'required',
            'payment_month'         => 'required',
            'paid_amount'           => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $payment_month = $request->input('payment_month');

        //dd(date("Y-m-01", strtotime($payment_month)));

        $data     = [
            'staff_id'              => $request->input('staff_id'),
            'salary_amount'         => $request->input('salary_amount'),
            'paid_amount'           => $request->input('paid_amount'),
            'payment_month'           => date("Y-m-01", strtotime($payment_month)),
            'payment_date'          => date("Y-m-d"),
        ];

        $check = StaffPayment::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Staff Payment Successfully', 'success');
            return redirect()->route('admin.account.staffPaymentList');
        } else {
            $this->setMessage('Staff Payment Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }


    /** For Staff Payment Statement */
    public function staffPaymentStatement()
    {
        $data               = [];
        $data['main_menu']  = 'salary';
        $data['child_menu'] = 'staff_payment_statement';
        $data['collapse']   = 'sidebar-collapse';
        $data['page_title'] = 'Staff Payment Statement';
        $data['branches']   = Branch::where('status', 1)->get();
        $data['staff']   = Staff::where('status', 1)->get();

        $start_date   = Carbon::now();
        $to_date            = $start_date->format("Y-m-d");
        $month_first_date   = $start_date->firstOfMonth()->format("Y-m-d");

        $from_date      = Carbon::createFromFormat("Y-m-d", $month_first_date)->subMonths(2)->format("Y-m-d");


        $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t1.payment_month BETWEEN '$from_date' AND '$to_date'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

        //dd($statement_data);

        $final_array = array();
        $data['staff_payments'] = [];
        $data['final_array'] = [];

        if(count($statement_data) > 0) {
            foreach ($statement_data as $statement) {
                $final_array[$statement->payment_month][] = $statement->staff_id;
            }

            $data['staff_payments']  = $statement_data;
            $data['final_array']    = $final_array;
        }




        return view('admin.account.salary.staffPayment.staffPaymentStatement', $data);
    }


    public function filterStaffPaymentStatement(Request $request)
    {
        $data = array();
        if($request->ajax()) {

            //dd($request->all());

            $branch_id  = $request->input('branch_id');
            $staff_id   = $request->input('staff_id');

            $from_date  = "";
            if($request->input('from_date') != "" && !is_null($request->input('from_date'))){
                $from_date  = date("Y-m-01", strtotime($request->input('from_date')));

            }

            $to_date    = "";
            if($request->input('from_date') != "" && !is_null($request->input('from_date'))){
                $to_date    = date("Y-m-t", strtotime($request->input('to_date')));
            }


            if(($branch_id != "" && $branch_id != 0) && ($staff_id != "" && $staff_id != 0) && $from_date != "" && $to_date != "") {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t1.staff_id = '$staff_id' AND t2.branch_id = '$branch_id' AND t1.payment_month BETWEEN '$from_date' AND '$to_date'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }elseif(($branch_id != "" && $branch_id != 0) && $from_date != "" && $to_date != "") {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t2.branch_id = '$branch_id' AND t1.payment_month BETWEEN '$from_date' AND '$to_date'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }elseif(($staff_id != "" && $staff_id != 0) && $from_date != "" && $to_date != "") {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t1.staff_id = '$staff_id' AND t1.payment_month BETWEEN '$from_date' AND '$to_date'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }elseif($from_date != "" && $to_date != "") {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t1.payment_month BETWEEN '$from_date' AND '$to_date'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }elseif(($branch_id != "" && $branch_id != 0)) {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t2.branch_id = '$branch_id'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }elseif(($staff_id != "" && $staff_id != 0)) {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
WHERE t1.staff_id = '$staff_id'
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }else {

                $statement_data = \DB::select(\DB::raw("SELECT t1.staff_id,  t1.payment_month, t1.salary_amount, sum(t1.paid_amount) as paid_amount, t2.name, t2.designation, t2.phone, t3.name as branch_name FROM `staff_payments` t1
INNER JOIN staff t2 ON t2.id = t1.staff_id
LEFT JOIN branches t3 ON t3.id = t2.branch_id
GROUP BY t1.staff_id, t1.payment_month, t1.salary_amount, t2.name, t2.designation, t2.phone, t3.name
ORDER BY t1.payment_month DESC, t1.staff_id ASC"));

            }

            //dd($statement_data);

            $final_array = array();
            $data['staff_payments'] = [];
            $data['final_array'] = [];

            if (count($statement_data) > 0) {
                foreach ($statement_data as $statement) {
                    $final_array[$statement->payment_month][] = $statement->staff_id;
                }

                $data['staff_payments'] = $statement_data;
                $data['final_array'] = $final_array;
            }

        }

        return view('admin.account.salary.staffPayment.filterStaffPaymentStatement', $data);
    }


    public function getStaffByBranch(Request $request)
    {
        if($request->ajax()) {
            $option         = '<option value="0" data-charge="0">Select Staff </option>';

            $staff_data = Staff::where('branch_id', $request->branch_id)
                                ->where('status', 1)->get();

            if($staff_data->count() > 0){

                foreach ($staff_data as $staff){
                    $option .= '<option  value="'.$staff->id.'" data-salary="'.$staff->salary.'">
                        '.$staff->name .'
                    </option>';
                }
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }





}
