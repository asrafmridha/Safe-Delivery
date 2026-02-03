<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\RiderPayment;
use App\Models\Staff;
use App\Models\StaffPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RiderPaymentController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'salary';
        $data['child_menu'] = 'rider-payment';
        $data['collapse'] = 'sidebar-collapse';
        $data['page_title'] = 'Rider Payment';
        $data['branches'] = Branch::where('status', 1)->get();
        $data['riders'] = Rider::where('status', 1)->get();
        return view('admin.account.salary.riderPayment.index', $data);
    }

    public function datatable(Request $request)
    {
        $model = RiderPayment::with(['rider'])
            ->where(function ($query) use ($request) {

                $branch_id = $request->input('branch_id');
                $rider_id = $request->input('rider_id');

                $from_date = date("Y-m-01", strtotime(Carbon::now()->subMonth()->format("Y-m-d")));
                $to_date = Carbon::now()->format("Y-m-d");


                if (($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0)
                    || ($request->has('rider_id') && !is_null($rider_id) && $rider_id != '' && $rider_id != 0)
                    || ($request->has('from_date') && !is_null($request->input('from_date')) && $request->input('from_date') != '')
                    || ($request->has('to_date') && !is_null($request->input('to_date')) && $request->input('to_date') != '')
                ) {

                    if ($request->input('from_date')) {
                        $from_date = date("Y-m-01", strtotime($request->input('from_date')));
                    } else {
                        $from_date = "";
                    }

                    if ($request->input('to_date')) {
                        $to_date = date("Y-m-t", strtotime($request->input('to_date')));
                    } else {
                        $to_date = "";
                    }

                    if ($request->has('branch_id') && !is_null($branch_id) && $branch_id != '' && $branch_id != 0) {
                        $query->whereHas('rider', function ($query2) use ($branch_id) {
                            $query2->where('branch_id', $branch_id);
                        });
                    }

                    if ($request->has('rider_id') && !is_null($rider_id) && $rider_id != '' && $rider_id != 0) {
                        $query->where('rider_id', $rider_id);
                    }


                    if ($request->has('from_date') && !is_null($request->input('from_date'))) {
                        $query->whereDate('payment_month', '>=', $from_date);
                    }

                    if ($request->has('to_date') && !is_null($request->input('from_date'))) {
                        $query->whereDate('payment_month', '<=', $to_date);
                    }
                } else {
//                    $query->whereBetween('payment_month', [$from_date, $to_date]);
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


    public function getRiderByBranch(Request $request)
    {
        if ($request->ajax()) {
            $option = '<option value="0" data-charge="0">Select Rider </option>';

            $data = Rider::where('branch_id', $request->branch_id)
                ->where('status', 1)->get();

            if ($data->count() > 0) {

                foreach ($data as $item) {
                    $option .= '<option  value="' . $item->id . '" data-salary="' . $item->salary . '">
                        ' . $item->name . '
                    </option>';
                }
            }
            return response()->json(['option' => $option]);
        }
        return redirect()->back();
    }

    public function getRiderById(Request $request)
    {
        if ($request->ajax()) {
            $data = Rider::where('id', $request->rider_id)
                ->first();
            $month = $request->input('payment_month');
            $separate = explode('-',$month);
            $d=cal_days_in_month(CAL_GREGORIAN,$separate[1],$separate[0]);
            $from_date = $request->input('payment_month') . "-01";
            $to_date = $request->input('payment_month'). "-". $d;
            $parcels = Parcel::where('delivery_rider_id', $request->input('rider_id'))
                ->whereDate('parcel_date', '>=', $from_date)
                ->whereDate('parcel_date', '<=', $to_date)
                ->where('status','>=', 25)
                ->whereIn('delivery_type', [1,2])
                ->get();
            $rider = [
                "data" => $data,
                "salary" => $data->salary ?? 0,
                "total_parcel" => count($parcels),
            ];
            return response()->json(['rider' => $rider]);
        }
        return redirect()->back();
    }

    public function create()
    {
        $data = [];
        $data['main_menu'] = 'salary';
        $data['child_menu'] = 'rider-payment';
        $data['page_title'] = 'Create Rider Payment';
        $data['branches'] = Branch::where('status', 1)->get();
        return view('admin.account.salary.riderPayment.create', $data);
    }

    public function store(Request $request)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'rider_id' => 'required',
            'salary_amount' => 'required',
            'payment_month' => 'required',
            'paid_amount' => 'required',
            'total_parcel' => 'required',
            'par_parcel_commission' => 'required',
            'total_parcel_commission' => 'required',
            'total_km' => 'required',
            'par_km_commission' => 'required',
            'total_km_commission' => 'required',
            'total_weight' => 'required',
            'par_weight_commission' => 'required',
            'total_weight_commission' => 'required',
            'total_salary' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $payment_month = $request->input('payment_month');
        $data = [
            'rider_id' => $request->input('rider_id'),
            'salary_amount' => $request->input('salary_amount'),
            'paid_amount' => $request->input('paid_amount'),
            'total_amount' => $request->input('total_salary'),

            'km' => $request->input('total_km'),
            'km_commission' => $request->input('par_km_commission'),
            'total_km_commission' => $request->input('total_km_commission'),
            'total_parcel' => $request->input('total_parcel'),
            'parcel_commission' => $request->input('par_parcel_commission'),
            'total_parcel_commission' => $request->input('total_parcel_commission'),
            'total_weight' => $request->input('total_weight'),
            'weight_commission' => $request->input('par_weight_commission'),
            'total_weight_commission' => $request->input('total_weight_commission'),

            'payment_month' => date("Y-m-01", strtotime($payment_month)),
            'payment_date' => date("Y-m-d"),
        ];

        $check = RiderPayment::create($data);

        if ($check) {
            $this->setMessage('Rider Payment Successfully', 'success');
            return redirect()->route('admin.rider.payment');
        } else {
            $this->setMessage('Rider Payment Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }
}
