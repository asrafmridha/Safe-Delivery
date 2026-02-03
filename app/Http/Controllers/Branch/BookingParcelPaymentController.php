<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\BookingParcelPayment;
use App\Models\BookingParcelPaymentDetails;
use App\Models\BookingParcelPaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BookingParcelPaymentController extends Controller
{
    public function index()
    {
        $data = [];
        $data['main_menu'] = 'booking_report';
        $data['child_menu'] = 'bookingParcelPaymentReport';
        $data['page_title'] = 'Booking Parcel Payment List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.booking_parcel.bookingParcelPaymentList', $data);
    }

    public function getBookingParcelPaymentList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $user_id = auth()->guard('branch')->user()->id;

        //dd($request->all());
        if ($request->status != "" && $request->from_date != "" && $request->to_date != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->where('payment_status', $request->get('status'))
                ->whereBetween('payment_date', [$request->from_date, $request->to_date])
                ->select();
        } elseif ($request->from_date != "" && $request->to_date != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->whereBetween('payment_date', [$request->from_date, $request->to_date])
                ->select();
        } elseif ($request->status != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->where('payment_status', $request->get('status'))
                ->select();
        } else {
            $model = BookingParcelPayment::where('branch_id', $branch_id)->select();
        }


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
                    case '0':
                        $delivery_type = "Request Cancel";
                        $class = "Warning";
                        break;
                    case '1':
                        $delivery_type = "Send Request";
                        $class = "info";
                        break;
                    case '2':
                        $delivery_type = "Request Accept";
                        $class = "Success";
                        break;
                    default:
                        $delivery_type = "None";
                        $class = "danger";
                        break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                if ($data->payment_status == 1) {
                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryPaymentGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel Payment " >
                        <i class="fas fa-edit"></i> </a>';
                }
                return $button;
            })
            ->rawColumns(['payment_status', 'action'])
            ->make(true);
    }

    public function printBookingParcelPaymentList(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $user_id = auth()->guard('branch')->user()->id;
        $filter = [];

        //dd($request->all());
        if ($request->status != "" && $request->from_date != "" && $request->to_date != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->where('payment_status', $request->get('status'))
                ->whereBetween('payment_date', [$request->from_date, $request->to_date])
                ->select();
        } elseif ($request->from_date != "" && $request->to_date != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->whereBetween('payment_date', [$request->from_date, $request->to_date])
                ->select();
            $filter['from_date'] = $request->get('from_date');
            $filter['to_date'] = $request->get('to_date');
        } elseif ($request->status != "") {
            $model = BookingParcelPayment::where('branch_id', $branch_id)
                ->where('payment_status', $request->get('status'))
                ->select();
            $filter['status'] = $request->get('status');
        } else {
            $model = BookingParcelPayment::where('branch_id', $branch_id)->select();
        }
        $bookingParcelPayments = $model->get();
        return view('branch.booking_parcel.printBookingParcelPaymentList', compact('bookingParcelPayments', 'filter'));

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
                    case '0':
                        $delivery_type = "Request Cancel";
                        $class = "Warning";
                        break;
                    case '1':
                        $delivery_type = "Send Request";
                        $class = "info";
                        break;
                    case '2':
                        $delivery_type = "Request Accept";
                        $class = "Success";
                        break;
                    default:
                        $delivery_type = "None";
                        $class = "danger";
                        break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" parcel_payment_id="' . $data->id . '" >
                <i class="fa fa-eye"></i> </button>';
                if ($data->payment_status == 1) {
                    $button .= '&nbsp; <a href="' . route('branch.parcel.deliveryPaymentGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Parcel Payment " >
                        <i class="fas fa-edit"></i> </a>';
                }
                return $button;
            })
            ->rawColumns(['payment_status', 'action'])
            ->make(true);
    }

    public function viewBookingParcelPayment(Request $request, BookingParcelPayment $parcelPayment)
    {
        $parcelPayment->load('branch', 'booking_parcel_payment_logs');
//         dd($parcelPayment);
        return view('branch.booking_parcel.bookingParcelPaymentView', compact('parcelPayment'));
    }


    /** Parcel Payment Forward */
    public function paymentForwardToAccounts()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'booking_report';
        $data['child_menu'] = 'paymentForwardToAccounts';
        $data['page_title'] = 'Payment Forward Accounts';
        $data['collapse'] = 'sidebar-collapse';

        $data['bookingParcelPayment'] = BookingParcelPaymentDetails::where([
            'branch_id' => $branch_id,
        ])
            ->whereIn('status', [0, 1])
            ->get();

        return view('branch.booking_parcel.bookingParcelPaymentForward', $data);
    }

    public function paymentParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcels = BookingParcelPaymentDetails::with(['booking_parcels' => function ($query) {
            $query->select('id', 'parcel_code');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->get();

        //dd($parcels);

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag = 0;

                if (count($cart) > 0) {
                    foreach ($cart as $item) {
                        if ($cart_id == $item->id) {
                            $flag++;
                        }
                    }
                }

                if ($flag == 0) {
                    \Cart::session($branch_id)->add([
                        'id' => $cart_id,
                        'name' => $parcel->booking_parcels->parcel_code,
                        'price' => number_format((float)$parcel->total_amount, 2, '.', ''),
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'booking_id' => $parcel->booking_id,
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }
            }

            $error = "";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart' => $cart,
            'totalItem' => $totalItem,
            'getTotal' => number_format((float)$getTotal, 2, '.', ''),
            'error' => $error,
        ];

        //dd($data);
        return view('branch.booking_parcel.cart_blade.paymentParcelCart', $data);
    }

    public function paymentParcelDeleteCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.booking_parcel.cart_blade.paymentParcelCart', $data);
    }

    public function confirmPaymentForwardToAccounts(Request $request)
    {
        if ($request->ajax()) {

            $validator = Validator::make($request->all(), [
                'total_payment_parcel' => 'required',
            ], [
                'total_payment_parcel.required' => 'Please checked minimum 1 payment for forward',
            ]);

            if ($validator->fails()) {

                $response = [
                    'success' => false,
                    'errors' => $validator->errors()
                ];
                return response()->json($response);
            }

            $branch_id = auth()->guard('branch')->user()->branch->id;
            $user_id = auth()->guard('branch')->user()->id;
            $currdate_time = date("Y-m-d H:i:s");


            $data_payment_update = [
                'status' => 2,
                'forward_date' => date("Y-m-d"),
                'updated_branch_user_id' => $user_id,
                'updated_at' => $currdate_time,
            ];
            $payment_details_ids = [];
            $booking_ids = [];
            $parcel_payment_logs = [];
            if ($request->total_payment_parcel > 0) {

                $cart_data = \Cart::session($branch_id)->getContent();

                foreach ($cart_data as $cart_item) {
                    $payment_details_id = $cart_item->id;
                    $payment_details_ids[] = $payment_details_id;
                    $booking_ids[] = $cart_item->attributes->booking_id;

                    $parcel_payment_logs[] = new BookingParcelPaymentLog([
                        'payment_details_id' => $payment_details_id,
                        'booking_id' => $cart_item->attributes->booking_id,
                        'payment_status' => 1,
                        'payment_note' => $request->get('payment_note'),
                        'payment_date' => $request->get('date'),
                        'created_branch_user_id' => $user_id,
                    ]);

                }
            } else {
                $error = array(
                    'payment_item' => "You did't select parcel, please try again"
                );
                $response = [
                    'success' => false,
                    'errors' => $error
                ];
                return response()->json($response);
            }

            $parcel_payments = [
                'bill_no' => $this->returnUniqueParcelPaymentBillNo(),
                'booking_ids' => json_encode($booking_ids),
                'payment_parcel' => $request->get('total_payment_parcel'),
                'total_amount' => $request->get('total_payment_amount'),
                'payment_status' => 1,
                'payment_note' => $request->get('payment_note'),
                'created_branch_user_id' => $user_id,
                'branch_id' => $branch_id,
                'payment_date' => $request->get('date'),
            ];

//            dd($request->all(), $data_payment_update, $parcel_payments, json_encode($payment_details_ids), json_encode($booking_ids), $parcel_payment_logs);

            DB::beginTransaction();
            try {
                $data_update = BookingParcelPaymentDetails::whereIn('id', $payment_details_ids)->update($data_payment_update);
                $payment_save = BookingParcelPayment::create($parcel_payments);
                $log_save = $payment_save->booking_parcel_payment_logs()->saveMany($parcel_payment_logs);
                DB::commit();
                $response = [
                    'success' => true,
                    'errors' => []
                ];
                return response()->json($response);

            } catch (\Exception $ex) {
                DB::rollBack();
                $response = [
                    'success' => false,
                    'errors' => [$ex->getMessage()]
                ];
                return response()->json($response);
            }


        }
    }


    /** Parcel Payment Report */
    public function bookingParcelPaymentReport()
    {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $data = [];
        $data['main_menu'] = 'booking_report';
        $data['child_menu'] = 'bookingParcelPaymentReportList';
        $data['page_title'] = 'Booking Parcel Payment Report';
        $data['collapse'] = 'sidebar-collapse';
        $data['payment_details'] = [];


        $data_parcel_payment = BookingParcelPaymentDetails::with(['booking_parcels'])
            ->where('branch_id', $branch_id)
            ->get();

        $data['total_parcel_amount'] = 0;
        $data['total_branch_amount'] = 0;
        $data['total_forward_amount'] = 0;
        $data['total_receive_amount'] = 0;

        if (count($data_parcel_payment) > 0) {
            $i = 0;
            foreach ($data_parcel_payment as $data_parcel) {
                $i++;

                switch ($data_parcel->payment_receive_type) {
                    case 'booking':
                        $payment_receive_type = "Booking";
                        $class = "info";
                        break;
                    case 'delivery':
                        $payment_receive_type = "Delivery";
                        $class = "success";
                        break;
                    default:
                        $payment_receive_type = "None";
                        $class = "danger";
                        break;
                }
                $payment_receive = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $payment_receive_type . '</a>';


                $parcel_amount = number_format((float)$data_parcel->total_amount, 2, '.', '');
                $branch_amount = ($data_parcel->status == 0 || $data_parcel->status == 1) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $forward_amount = ($data_parcel->status == 2) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $receive_amount = ($data_parcel->status == 3) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');

                $data['total_parcel_amount'] += number_format((float)$parcel_amount, 2, '.', '');
                $data['total_branch_amount'] += number_format((float)$branch_amount, 2, '.', '');
                $data['total_forward_amount'] += number_format((float)$forward_amount, 2, '.', '');
                $data['total_receive_amount'] += number_format((float)$receive_amount, 2, '.', '');

                $data['payment_details'][] = '<tr>
                                                <td class="text-center">' . $i . '</td>
                                                <td class="text-center">' . $data_parcel->payment_date . '</td>
                                                <td class="text-center">' . $data_parcel->booking_parcels->parcel_code . '</td>
                                                <td class="text-center">' . $payment_receive . '</td>
                                                <td class="text-center">' . $parcel_amount . '</td>
                                                <td class="text-center">' . $branch_amount . '</td>
                                                <td class="text-center">' . $forward_amount . '</td>
                                                <td class="text-center">' . $receive_amount . '</td>
                                            </tr>';
            }

        }

        return view('branch.booking_parcel.bookingParcelPaymentReport', $data);
    }

    /** Parcel Payment Report */
    public function printBookingParcelPaymentReport(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $data = [];
        $data['main_menu'] = 'booking_report';
        $data['child_menu'] = 'bookingParcelPaymentReportList';
        $data['page_title'] = 'Booking Parcel Payment Report';
        $data['collapse'] = 'sidebar-collapse';
        $data['payment_details'] = [];

        $data_parcel_payment = BookingParcelPaymentDetails::with(['booking_parcels'])
            ->where('branch_id', $branch_id)
            ->where(function ($query) use ($request) {
                $payment_type = $request->input('payment_receive_type');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                if ($request->has('payment_receive_type') && $payment_type != "") {
                    $query->where('payment_receive_type', $payment_type);
                }
                if ($request->has('from_date') && $from_date != "") {
                    $query->whereDate('payment_date', '>=', $from_date);
                }
                if ($request->has('to_date') && $to_date != "") {
                    $query->where('payment_date', '<=', $to_date);
                }

            })
            ->get();

        $data['total_parcel_amount'] = 0;
        $data['total_branch_amount'] = 0;
        $data['total_forward_amount'] = 0;
        $data['total_receive_amount'] = 0;

        if (count($data_parcel_payment) > 0) {
            $i = 0;
            foreach ($data_parcel_payment as $data_parcel) {
                $i++;

                switch ($data_parcel->payment_receive_type) {
                    case 'booking':
                        $payment_receive_type = "Booking";
                        $class = "info";
                        break;
                    case 'delivery':
                        $payment_receive_type = "Delivery";
                        $class = "success";
                        break;
                    default:
                        $payment_receive_type = "None";
                        $class = "danger";
                        break;
                }
                $payment_receive = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $payment_receive_type . '</a>';


                $parcel_amount = number_format((float)$data_parcel->total_amount, 2, '.', '');
                $branch_amount = ($data_parcel->status == 0 || $data_parcel->status == 1) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $forward_amount = ($data_parcel->status == 2) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $receive_amount = ($data_parcel->status == 3) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');

                $data['total_parcel_amount'] += number_format((float)$parcel_amount, 2, '.', '');
                $data['total_branch_amount'] += number_format((float)$branch_amount, 2, '.', '');
                $data['total_forward_amount'] += number_format((float)$forward_amount, 2, '.', '');
                $data['total_receive_amount'] += number_format((float)$receive_amount, 2, '.', '');

                $data['payment_details'][] = '<tr>
                                                <td class="text-center">' . $i . '</td>
                                                <td class="text-center">' . $data_parcel->payment_date . '</td>
                                                <td class="text-center">' . $data_parcel->booking_parcels->parcel_code . '</td>
                                                <td class="text-center">' . $payment_receive . '</td>
                                                <td class="text-center">' . $parcel_amount . '</td>
                                                <td class="text-center">' . $branch_amount . '</td>
                                                <td class="text-center">' . $forward_amount . '</td>
                                                <td class="text-center">' . $receive_amount . '</td>
                                            </tr>';
            }

        }
        $filter['payment_receive_type'] = $request->input('payment_receive_type');
        $filter['from_date'] = $request->input('from_date');
        $filter['to_date'] = $request->input('to_date');
        $data['filter'] = $filter;
        return view('branch.booking_parcel.printBookingParcelPaymentReport', $data);
    }


    public function bookingParcelPaymentReportAjax(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch_id;
        $data = [];
//        $data['main_menu']  = 'booking_report';
//        $data['child_menu'] = 'bookingParcelPaymentReportList';
//        $data['page_title'] = 'Booking Parcel Payment Report';
//        $data['collapse']   = 'sidebar-collapse';
        $data['payment_details'] = [];


        $data_parcel_payment = BookingParcelPaymentDetails::with(['booking_parcels'])
            ->where('branch_id', $branch_id)
            ->where(function ($query) use ($request) {
                $payment_type = $request->input('payment_receive_type');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                if ($request->has('payment_receive_type') && $payment_type != "") {
                    $query->where('payment_receive_type', $payment_type);
                }
                if ($request->has('from_date') && $from_date != "") {
                    $query->whereDate('payment_date', '>=', $from_date);
                }
                if ($request->has('to_date') && $to_date != "") {
                    $query->where('payment_date', '<=', $to_date);
                }

            })
            ->get();

        $data['total_parcel_amount'] = 0;
        $data['total_branch_amount'] = 0;
        $data['total_forward_amount'] = 0;
        $data['total_receive_amount'] = 0;

        if (count($data_parcel_payment) > 0) {
            $i = 0;
            foreach ($data_parcel_payment as $data_parcel) {
                $i++;

                switch ($data_parcel->payment_receive_type) {
                    case 'booking':
                        $payment_receive_type = "Booking";
                        $class = "info";
                        break;
                    case 'delivery':
                        $payment_receive_type = "Delivery";
                        $class = "success";
                        break;
                    default:
                        $payment_receive_type = "None";
                        $class = "danger";
                        break;
                }
                $payment_receive = '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $payment_receive_type . '</a>';


                $parcel_amount = number_format((float)$data_parcel->total_amount, 2, '.', '');
                $branch_amount = ($data_parcel->status == 0 || $data_parcel->status == 1) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $forward_amount = ($data_parcel->status == 2) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');
                $receive_amount = ($data_parcel->status == 3) ? number_format((float)$data_parcel->total_amount, 2, '.', '') : number_format((float)0, 2, '.', '');

                $data['total_parcel_amount'] += number_format((float)$parcel_amount, 2, '.', '');
                $data['total_branch_amount'] += number_format((float)$branch_amount, 2, '.', '');
                $data['total_forward_amount'] += number_format((float)$forward_amount, 2, '.', '');
                $data['total_receive_amount'] += number_format((float)$receive_amount, 2, '.', '');

                $data['payment_details'][] = '<tr>
                                                <td class="text-center">' . $i . '</td>
                                                <td class="text-center">' . $data_parcel->payment_date . '</td>
                                                <td class="text-center">' . $data_parcel->booking_parcels->parcel_code . '</td>
                                                <td class="text-center">' . $payment_receive . '</td>
                                                <td class="text-center">' . $parcel_amount . '</td>
                                                <td class="text-center">' . $branch_amount . '</td>
                                                <td class="text-center">' . $forward_amount . '</td>
                                                <td class="text-center">' . $receive_amount . '</td>
                                            </tr>';
            }

        }

        return view('branch.booking_parcel.response_blade.filterBookingParcelPaymentReport', $data);
    }
}
