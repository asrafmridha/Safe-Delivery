<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
//use App\Http\Middleware\Merchant;
use App\Models\BookingItem;
use App\Models\BookingParcel;
use App\Models\Branch;
use App\Models\District;
use App\Models\Division;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\Rider;
use App\Models\Unit;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BookingParcelController extends Controller {

    private $bookingParcelObj;
    public function __construct() {
        $this->bookingParcelObj = new BookingParcel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcellist';
        $data['page_title'] = 'Booking Parcel List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.booking_parcel.bookingParcelList', $data);
    }

    public function getBookingParcelList(Request $request) {
        //dd($request->input('booking_parcel_type'));
        $model = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
            ->where('sender_branch_id', $this->branchId())
            ->where(function ($query) use ($request) {
                $booking_parcel_type = $request->input('booking_parcel_type');
                $booking_delivery_type = $request->input('delivery_type');
                $booking_status = $request->input('status');
                $from_date  = $request->input('from_date');
                $to_date    = $request->input('to_date');
                if ($request->has('booking_parcel_type') && !is_null($booking_parcel_type) && $booking_parcel_type != '') {
                    $query->where('booking_parcel_type', $booking_parcel_type);
                }
                if ($request->has('delivery_type') && !is_null($booking_delivery_type) && $booking_delivery_type != '') {
                    $query->where('delivery_type', $booking_delivery_type);
                }
                if ($request->has('status') && !is_null($booking_status) && $booking_status != '') {
                    $query->where('status', $booking_status);
                }
                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('booking_date', '>=', $from_date);
                }
                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('booking_date', '<=', $to_date);
                }
            })->get();

        //dd($model);
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('net_amount', function ($data) {
                $total_amount = $data->net_amount + $data->pickup_charge;
                return sprintf("%.2f", $total_amount);
            })
            ->editColumn('booking_parcel_type', function ($data) {
                switch ($data->booking_parcel_type) {
                    case 'cash':$booking_type  = "Cash"; $class  = "success";break;
                    case 'to_pay':$booking_type = "To Pay"; $class = "info";break;
                    case 'condition':$booking_type  = "Condition"; $class  = "primary";break;
                    case 'credit':$booking_type = "Credit"; $class = "warning";break;
                    default:$booking_type    = "None"; $class    = "danger";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $booking_type . '</a>';
            })
            ->editColumn('delivery_type', function ($data) {
                switch ($data->delivery_type) {
                    case 'hd':$delivery_type  = "HD"; $class  = "success";break;
                    case 'thd':$delivery_type = "THD"; $class = "info";break;
                    case 'od':$delivery_type  = "OD"; $class  = "primary";break;
                    case 'tod':$delivery_type = "TOD"; $class = "warning";break;
                    default:$delivery_type    = "None"; $class    = "danger";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $delivery_type . '</a>';
            })
            ->editColumn('status', function ($data) {
                //            $warehouse_name = ($data->warehouse_tbls) ? $data->warehouse_tbls->wh_name : 'Default';
                $receiver_warehouse_name = ($data->receiver_warehouses) ? $data->receiver_warehouses->name : 'Warehouse';
                switch ($data->status) {
                    case 0:$status_name = "Parcel Reject from operation"; $class = "danger";break;
                    case 1:$status_name = "Confirmed Booking"; $class = "success";break;
                    case 2:$status_name = "Vehicle Assigned"; $class = "success";break;
                    case 3:$status_name = "Assign $receiver_warehouse_name"; $class = "success";break;
                    case 4:$status_name = "Warehouse Received Parcel"; $class = "success";break;
                    case 5:$status_name = "Assign $receiver_warehouse_name"; $class = "success";break;
                    case 6:$status_name = "Wait for destination branch receive"; $class = "success";break;
                    case 7:$status_name = "Destination branch received Parcel"; $class = "success";break;
                    case 8:$status_name = "Parcel Complete Delivery"; $class = "success";break;
                    case 9:$status_name = "Parcel Return Delivery"; $class = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:16px;"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<a href="' . route('branch.bookingParcel.printBookingParcel', $data->id) . '" class="btn btn-success btn-sm" title="Print Booking Parcel" target="_blank">
                <i class="fas fa-print"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" booking_id="' . $data->id . '" >
            <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <button class="btn btn-info btn-sm float-right print-modal" title="Print Barcode" data-toggle="modal" data-target="#printBarcode" booking_id="' . $data->id . '">
                <i class="fas fa-barcode"></i>
            </button>';
                $button .= '&nbsp; <a href="#" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';

                return $button;
            })
            ->rawColumns(['booking_parcel_type', 'delivery_type', 'status', 'action'])
            ->make(true);
    }

    public function create() {
        Cart::session($this->userId())->clearCartConditions();
        Cart::session($this->userId())->clear();
        $data               = [];
        $data['main_menu']  = 'booking';
        $data['child_menu'] = 'bookingParcel';
        $data['page_title'] = 'Booking Parcel';
        $data['collapse']   = 'sidebar-collapse';

        $data['units'] = Unit::where([
            ['status', '=', 1],
        ])->get();

        $data['divisions'] = Division::where([
            ['status', '=', 1],
        ])->get();

        $data['districts'] = District::where([
            ['status', '=', 1],
        ])->get();

        $data['branches'] = Branch::where([
            ['status', '=', 1],
        ])->whereNotIn('id', [$this->branchId()])->get();

        $data['categories'] = ItemCategory::where([
            ['status', '=', 1],
        ])->get();

        $data['merchants'] = Merchant::where([
            ['branch_id', '=', $this->branchId()],
        ])->get();

        $data['riders'] = Rider::where([
            ['branch_id', '=', $this->branchId()],
        ])->get();

        return view('branch.booking_parcel.create', $data);
    }

    public function addCartItem(Request $request) {

        if ($request->ajax()) {

            $user_id       = $this->userId();
            $category_id   = $request->category_id;
            $unit_name     = $request->unit_name;
            $item_name     = $request->item_name;
            $unit_price    = $request->unit_price;
            $delivery_type = $request->delivery_type;

            if ($category_id == 'others') {
                Cart::session($this->userId())->add([
                    'id'              => $item_name,
                    'name'            => $item_name,
                    'price'           => $unit_price,
                    'quantity'        => $request->quantity,
                    'attributes'      => [
                        'unit_name' => $unit_name,
                    ],
                    'associatedModel' => []
                ]);
            } else {
                $item_data = Item::find($request->get('item_id'));

                if ($delivery_type == 'od') {
                    $price = $item_data->od_rate;
                } elseif ($delivery_type == 'tod') {
                    $price = $item_data->transit_od;
                } elseif ($delivery_type == 'hd') {
                    $price = $item_data->hd_rate;
                } elseif ($delivery_type == 'thd') {
                    $price = $item_data->transit_hd;
                } else {
                    $price = 0;
                }

                Cart::session($this->userId())->add([
                    'id'              => $item_data->id,
                    'name'            => $item_data->item_name,
                    'price'           => $price,
                    'quantity'        => $request->quantity,
                    'attributes'      => [],
                    'associatedModel' => $item_data,
                ]);
            }

            // add single condition on a cart bases
            $condition = new \Darryldecode\Cart\CartCondition([
                'name'       => 'VAT 15%',
                'type'       => 'tax',
                //'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
                'value'      => '15%',
                'attributes' => [ // attributes field is optional
                    'description' => 'Value added tax',
                    'more_data'   => 'more data here',
                ],
            ]);

            Cart::session($this->userId())->condition($condition);
            $subTotal                 = Cart::getSubTotal();
            $condition                = Cart::getCondition('VAT 15%');
            $conditionCalculatedValue = $condition->getCalculatedValue($subTotal);
            $total                    = Cart::session($this->userId())->getTotal();

            $grandTotal = $total + $conditionCalculatedValue;

            $data = (object) [
                'subTotal'   => $subTotal,
                'vatAmount'  => $conditionCalculatedValue,
                'grandTotal' => $grandTotal,
            ];

            return view('branch.booking_parcel.item_cart', compact('user_id', 'data'));
        }

    }

    public function removeCartItem(Request $request) {

        if ($request->ajax()) {

            $user_id = $this->userId();
            Cart::session($this->userId())->remove($request->id);

            $subTotal                 = Cart::getSubTotal();
            $condition                = Cart::getCondition('VAT 15%');
            $conditionCalculatedValue = $condition->getCalculatedValue($subTotal);
            $total                    = Cart::session($this->userId())->getTotal();

            $grandTotal = $total + $conditionCalculatedValue;

            $data = (object) [
                'subTotal'   => $subTotal,
                'vatAmount'  => $conditionCalculatedValue,
                'grandTotal' => $grandTotal,
            ];

            return view('branch.booking_parcel.item_cart', compact('user_id', 'data'));
        }

    }

    public function store(Request $request) {
        $inputs = $request->all();
//        $validator = Validator::make($inputs, [
//            'parcel_code'          => 'unique:booking_parcels',
//            'sender_name'          => 'required',
//            'sender_phone'         => 'required',
//            'sender_division_id'   => 'required',
//            'sender_district_id'   => 'required',
//            'sender_thana_id'      => 'required',
//            'sender_area_id'       => 'required',
//            'receiver_name'        => 'required',
//            'receiver_phone'       => 'required',
//            'receiver_address'     => 'required',
//            'receiver_division_id' => 'required',
//            'receiver_district_id' => 'required',
//            'receiver_thana_id'    => 'required',
//            'receiver_area_id'     => 'required',
//            'receiver_branch_id'   => 'required',
//            'delivery_type'        => 'required',
//            'total_amount'         => 'required',
//            'grand_amount'         => 'required',
//            'net_amount'           => 'required',
//        ], [
//            'parcel_code.unique' => 'This Parcel already booked!',
//        ]);
        $validator = Validator::make($inputs, [
            'parcel_code'          => 'unique:booking_parcels',
            'booking_parcel_type'  => 'required',
            'sender_phone'         => 'required',
            'sender_address'       => 'required',
            'receiver_phone'       => 'required',
            'receiver_address'     => 'required',
            'receiver_branch_id'   => 'required',
            'delivery_type'        => 'required',
            'total_amount'         => 'required',
            'grand_amount'         => 'required',
            'net_amount'           => 'required',
        ], [
            'parcel_code.unique' => 'This Parcel already booked!',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'errors'  => $validator->errors(),
            ];
            return response()->json($response);
        }

        $sdistrict_name = $request->get('sdistrict_name');
        $rdistrict_name = $request->get('rdistrict_name');
        $parcel_code    = $this->returnUniqueBookingParcelInvoice($sdistrict_name, $rdistrict_name);

        $inputs['paid_amount']            = $inputs['paid_amount'] ?? 0;
        $inputs['discount_percent']       = $inputs['discount_percent'] ?? 0;
        $inputs['discount_amount']        = $inputs['discount_amount'] ?? 0;
        $inputs['collection_amount']      = $inputs['collection_amount'] ?? 0;
        $inputs['pickup_charge']          = $inputs['pickup_charge'] ?? 0;
        $inputs['cod_amount']             = ($request->get('booking_parcel_type') == "condition") ? $inputs['cod_amount'] : 0;
        $inputs['parcel_code']            = $parcel_code;
        $inputs['created_branch_user_id'] = $this->userId();
        $inputs['sender_branch_id']       = $this->branchId();
        $inputs['vat_percent']            = 15;
        $inputs['booking_date']           = date('Y-m-d');


        $input_logs = [
            'created_branch_user_id' => $this->userId(),
        ];

        $count_items = Cart::session($this->userId())->getContent()->count();
        $items_array = [];

        if ($count_items > 0) {
            $items = Cart::session($this->userId())->getContent();
            $i     = 0;

            foreach ($items as $row) {

                if ($row->associatedModel) {
                    $unit_name = $row->associatedModel->units->name;
                } else {
                    $unit_name = $row->attributes->unit_name;
                }

                $items_array[] = new BookingItem([
                    'item_id'          => (is_int($row->id)) ? $row->id : 0,
                    'item_name'        => $row->name,
                    'unit_name'        => $unit_name,
                    'unit_price'       => $row->price,
                    'quantity'         => $row->quantity,
                    'total_item_price' => Cart::get($row->id)->getPriceSum(),
                ]);
            }

        } else {
            $error = [
                'parcel_item' => 'Parcel not found',
            ];
            $response = [
                'success' => false,
                'errors'  => $error,
            ];
            return response()->json($response);
        }

        /** Parcel Payment */
//        $input_payments = [];
//        if("cash" == $request->get('booking_parcel_type')) {
//            $input_payments = [
//                'payment_receive_type'   => 'booking',
//                'delivery_charge'        => $inputs['paid_amount'],
//                'total_amount'           => $inputs['paid_amount'],
//                'branch_id'              => $this->branchId(),
//                'created_branch_user_id' => $this->userId(),
//                'payment_date'           => date("Y-m-d"),
//            ];
//        }

        DB::beginTransaction();
        try {
            $parcel_save        = BookingParcel::create($inputs);
            $parcel_log_save    = $parcel_save->booking_parcel_logs()->create($input_logs);
            $parcel_item_save   = $parcel_save->booking_items()->saveMany($items_array);

//            if("cash" == $request->get('booking_parcel_type')) {
//                $parcel_payment_save    = $parcel_save->booking_parcel_payment_details()->create($input_payments);
//            }

            DB::commit();
            $response = [
                'success' => true,
                'errors'  => [],
            ];

            return response()->json($response);

        } catch (\Exception$ex) {
            DB::rollback();
            $response = [
                'success' => false,
                'errors'  => [$ex->getMessage()],
            ];
            // return $ex->getMessage();
            return response()->json($response, 500);
        }

    }

    public function viewBookingParcel(Request $request, BookingParcel $booking_parcel) {
        $booking_parcel->load(['sender_branch' => function ($query) {$query->select('id', 'name');},
            'receiver_branch'                      => function ($query) {$query->select('id', 'name');}, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items',
        ]);
        // dd($booking_parcel);
        return view('branch.booking_parcel.viewParcel', compact('booking_parcel'));
    }

    public function printBookingParcel(Request $request, BookingParcel $booking_parcel) {
        $page_title = 'Print Booking Parcel';
        $booking_parcel->load(['sender_branch' => function ($query) {$query->select('id', 'name');},
            'receiver_branch'                      => function ($query) {$query->select('id', 'name');}, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items',
        ]);

//dd($booking_parcel);

// $parcelLogs = BookingParcelLog::with('pickup_branch', 'pickup_rider', 'delivery_branch', 'delivery_rider', 'admin', 'merchant')
        //     ->where('parcel_id', $booking_parcel->id)->get();

        return view('branch.booking_parcel.printParcel', compact('booking_parcel', 'page_title'));
    }


    /** Print Booking Parcel List */
    public function bookingParcelPrintList(Request $request){

        $booking_parcels = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
            ->where('sender_branch_id', $this->branchId())
            ->where(function ($query) use ($request) {
                $booking_parcel_type = $request->input('booking_parcel_type');
                $booking_delivery_type = $request->input('delivery_type');
                $booking_status = $request->input('status');
                $from_date  = $request->input('from_date');
                $to_date    = $request->input('to_date');
                if ($request->has('booking_parcel_type') && !is_null($booking_parcel_type) && $booking_parcel_type != '') {
                    $query->where('booking_parcel_type', $booking_parcel_type);
                }
                if ($request->has('delivery_type') && !is_null($booking_delivery_type) && $booking_delivery_type != '') {
                    $query->where('delivery_type', $booking_delivery_type);
                }
                if ($request->has('status') && !is_null($booking_status) && $booking_status != '') {
                    $query->where('status', $booking_status);
                }
                if ($request->has('from_date') && !is_null($from_date) && $from_date != '') {
                    $query->whereDate('booking_date', '>=', $from_date);
                }
                if ($request->has('to_date') && !is_null($to_date) && $to_date != '') {
                    $query->whereDate('booking_date', '<=', $to_date);
                }
            })->get();

        return view('branch.booking_parcel.printBookingParcelList', compact('booking_parcels'));
    }


    /** Booking Assign Filter  */
    public function filterAssignBookingParcel(Request $request) {

        $branch_id = $this->branchId();

        $parcel_no  = $request->input('parcel_no');
        $rbranch_id = $request->input('rbranch_id');

        if (!empty($parcel_no) || !empty($rbranch_id)) {

            $data['parcels'] = BookingParcel::with(['receiver_branch' => function ($query) {
                $query->select('id', 'name');
            },
            ])->where(function ($query) use ($branch_id, $parcel_no, $rbranch_id) {
                $query->whereIn('status', [1]);

                $query->where([
                    'sender_branch_id' => $branch_id,
                ])->where([
                    'receiver_branch_id' => $rbranch_id,
                ])->orWhere([
                    'parcel_code' => $parcel_no,
                ]);

            })
                ->select('id', 'parcel_code', 'sender_phone', 'receiver_branch_id', 'net_amount', 'delivery_type')
                ->get();
        } else {
            $data['parcels'] = [];
        }

        return view('branch.booking_parcel.response_blade.filterAssignParcel', $data);
    }

    /** Assign Booking Parcel Add Cart */
    public function assignParcelAddCart(Request $request) {
        $branch_id    = $this->branchId();
        $parcel_codes = $request->input('parcel_codes');

        $parcels = BookingParcel::whereIn('id', $parcel_codes)
            ->whereIn('status', [1])
            ->get();

        if ($parcels->count() > 0) {
            $cart = Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            foreach ($parcels as $parcel) {
                $cart_id = $parcel->id;
                $flag    = 0;

                if (count($cart) > 0) {

                    foreach ($cart as $item) {

                        if ($cart_id == $item->id) {
                            $flag++;
                        }

                    }

                }

                if ($flag == 0) {
                    Cart::session($branch_id)->add([
                        'id'              => $cart_id,
                        'name'            => $parcel->parcel_code,
                        'quantity'        => 1,
                        'price'           => 1,
                        'attributes'      => [],
                        'associatedModel' => $parcel,
                    ]);
                }

            }

            $error = "";

            $cart      = Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = Cart::session($branch_id)->getContent()->count();
        } else {
            $error = "Parcel Not Found";

            $cart = Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = Cart::session($branch_id)->getContent()->count();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'error'     => $error,
        ];
        return view('branch.booking_parcel.cart_blade.assignBookingParcelCart', $data);
    }

    public function assignParcelDeleteCart(Request $request) {
        $branch_id = $this->branchId();
        Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart'      => $cart,
            'totalItem' => \Cart::session($branch_id)->getContent()->count(),
            'error'     => "",
        ];
        return view('branch.booking_parcel.cart_blade.assignBookingParcelCart', $data);
    }

    /** Booking Item Barcode */

    public function bookingParcelItems(Request $request, BookingParcel $booking_parcel) {
        $booking_parcel->load(['sender_branch' => function ($query) {$query->select('id', 'name');},
            'receiver_branch'                      => function ($query) {$query->select('id', 'name');}, 'sender_division', 'sender_district'
            , 'sender_upazila', 'sender_area', 'receiver_division', 'receiver_district', 'receiver_upazila', 'receiver_area', 'booking_items',
        ]);
        return view('branch.booking_parcel.bookingParcelItems', compact('booking_parcel'));
    }

    public function printBookingItemBarcode(Request $request) {
        $page_title = 'Print Booking Parcel Item Barcode';

        $booking_parcel    = BookingParcel::with(['booking_items'])->where('id', $request->booking_id)->first();
        $number_of_barcode = $request->number_of_barcode;
        //dd($booking_parcel);
        return view('branch.booking_parcel.printBookingParcelItemBarcode', compact('booking_parcel', 'number_of_barcode', 'page_title'));
    }

    /** Protected Function */
    protected function userId() {

        if (auth()->guard('admin')->user()) {
            $userId = auth()->guard('admin')->user()->id;
        } elseif (auth()->guard('branch')->user()) {
            $userId = auth()->guard('branch')->user()->id;
        } else {
            $userId = 0;
        }

        return $userId;
    }

    protected function branchId() {

        if (auth()->guard('branch')->user()) {
            $branchId = auth()->guard('branch')->user()->branch_id;
        } else {
            $branchId = 0;
        }

        return $branchId;
    }

}
