<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use App\Models\District;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use App\Models\Upazila;
use App\Models\WeightPackage;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;

class DeliveryBranchTransferParcelController extends Controller
{


    public function deliveryBranchTransferList()
    {
        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'deliveryBranchTransferList';
        $data['page_title'] = 'Delivery Branch Transfer List';
        $data['collapse'] = 'sidebar-collapse';
        return view('branch.parcel.pickupParcel.deliveryBranchTransferList', $data);
    }

    public function getDeliveryBranchTransferList(Request $request)
    {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = DeliveryBranchTransfer::with(
            [
                'to_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('from_branch_id = ? ', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('create_date_time', function ($data) {
                return date('d-m-Y H:i:s', strtotime($data->create_date_time));
            })
            ->editColumn('reject_date_time', function ($data) {
                return ($data->reject_date_time) ? date('d-m-Y H:i:s', strtotime($data->reject_date_time)) : "";
            })
            ->editColumn('received_date_time', function ($data) {
                return ($data->received_date_time) ? date('d-m-Y H:i:s', strtotime($data->received_date_time)) : "";
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" delivery_branch_transfer_id="' . $data->id . '" title="View Delivery Branch Transfer" >
                <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <a href="' . route('branch.parcel.editDeliveryBranchTransfer', $data->id) . '" class="btn btn-info btn-sm" title="Edit Delivery Branch Transfer" >
                        <i class="fas fa-edit"></i> </a>';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" delivery_branch_transfer_id="' . $data->id . '" title="Print Delivery Branch Transfer" >
                <i class="fa fa-print"></i> </button>';
                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-danger delivery-branch-transfer-cancel-btn btn-sm" delivery_branch_transfer_id="' . $data->id . '"  title="Cancel Delivery Branch Transfer" >
                    <i class="fa fa-window-close"></i> </button> ';

                    $button .= '&nbsp; <a href="' . route('branch.parcel.editDeliveryBranchTransfer', $data->id) . '" class="btn btn-info btn-sm" title="Edit Pickup Run" >
                        <i class="fas fa-edit"></i> </a>';
                }

                return $button;
            })
            ->rawColumns(['action', 'create_date_time', 'reject_date_time', 'received_date_time'])
            ->make(true);
    }


    public function printDeliveryBranchTransferList(Request $request)
    {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id = auth()->guard('branch')->user()->branch->id;

        $model = DeliveryBranchTransfer::with(
            [
                'to_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('from_branch_id = ? ', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $deliveryBranchTransfers = $model->get();
        return view('branch.parcel.pickupParcel.printDeliveryBranchTransferList', compact('deliveryBranchTransfers'));
    }


    public function viewDeliveryBranchTransfer(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer)
    {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details'
        ]);
        return view('branch.parcel.pickupParcel.viewDeliveryBranchTransfer', compact('deliveryBranchTransfer'));
    }

    public function printDeliveryBranchTransfer(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer)
    {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details'
        ]);
        return view('branch.parcel.pickupParcel.printDeliveryBranchTransfer', compact('deliveryBranchTransfer'));
    }

    public function cancelDeliveryBranchTransfer(Request $request)
    {
        $response = ['error' => 'Error Found'];
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'delivery_branch_transfer_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                \DB::beginTransaction();
                try {
                    $check = DeliveryBranchTransfer::where('id', $request->delivery_branch_transfer_id)->update([
                        'cancel_date_time' => date('Y-m-d H:i:s'),
                        'status' => 2,
                    ]);
                    if ($check) {
                        $deliveryBranchTransferDetails = DeliveryBranchTransferDetail::where('delivery_branch_transfer_id', $request->delivery_branch_transfer_id)->get();
                        foreach ($deliveryBranchTransferDetails as $deliveryBranchTransferDetail) {
                            $parcel = Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->first();
                            $parcel->update([
                                'status' => 13,
                                'parcel_date' => date('Y-m-d'),
                            ]);
                            ParcelLog::create([
                                'parcel_id' => $deliveryBranchTransferDetail->parcel_id,
                                'pickup_branch_id' => auth()->guard('branch')->user()->branch->id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 13,
                                'delivery_type' => $parcel->delivery_type,
                            ]);
                            DeliveryBranchTransferDetail::where('id', $deliveryBranchTransferDetail->id)->update([
                                'status' => 2,
                            ]);

                            $parcel = Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->first();

                            $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                            // $merchant_user->notify(new MerchantParcelNotification($parcel));

                            // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                            // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                        }

                        \DB::commit();

                        // $this->adminDashboardCounterEvent();
                        $response = ['success' => 'Delivery Branch Transfer Cancel Successfully'];
                    } else {
                        $response = ['error' => 'Database Error Found'];
                    }
                } catch (\Exception $e) {
                    \DB::rollback();
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }


    public function deliveryBranchTransferGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'deliveryBranchTransferGenerate';
        $data['page_title'] = 'Delivery Branch Transfer Generate List';
        $data['collapse'] = 'sidebar-collapse';
        $data['branches'] = Branch::where([
            'status' => 1,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'company_name', 'contact_number');
            },
            'district' => function ($query) {
                $query->select('id', 'name');
            },
            'upazila' => function ($query) {
                $query->select('id', 'name');
            },
            'area' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->whereRaw("(pickup_branch_id = ? AND status in (11, 13, 15)) OR (delivery_branch_id = ? AND status in (11,14,15))", [$branch_id,$branch_id])
            // ->whereRaw("(pickup_branch_id = ? AND status in (11, 12 , 13, 15)) OR (delivery_branch_id = ? AND status in (14,20) or (delivery_branch_id = ? AND status in (25,28) AND delivery_type in (3,4)))", [$branch_id, $branch_id, $branch_id])
          
            ->select('id', 'parcel_invoice', 'merchant_order_id','product_details', 'customer_name', 'customer_contact_number', 'merchant_id', 'district_id', 'upazila_id', 'area_id')
            ->get();

        return view('branch.parcel.pickupParcel.deliveryBranchTransferGenerate', $data);
    }

    public function returnDeliveryBranchTransferParcel(Request $request)
    {

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice) || !empty($merchant_order_id)) {


            $data['parcels'] = Parcel::with([
                'merchant' => function ($query) {
                    $query->select('id', 'name', 'contact_number');
                },
                'district' => function ($query) {
                    $query->select('id', 'name');
                },
                'upazila' => function ($query) {
                    $query->select('id', 'name');
                },
                'area' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
                ->where(function ($query) use ($branch_id, $parcel_invoice, $merchant_order_id) {
                    $query->whereRaw(" ((pickup_branch_id = ? AND status in (11, 13, 15)) OR (delivery_branch_id = ? AND status in (14,20) or (delivery_branch_id = ? AND status in (25,28) AND delivery_type in (3,4))))", [$branch_id, $branch_id, $branch_id]);
                    if (!empty($parcel_invoice)) {
                        $query->where([
                            'parcel_invoice' => $parcel_invoice,
                        ]);
                    } else {
                        $query->where([
                            'merchant_order_id' => $merchant_order_id,
                        ]);
                    }
                })
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'district_id', 'upazila_id', 'area_id')
                ->get();

        } else {
            $data['parcels'] = [];
        }
        return view('branch.parcel.pickupParcel.deliveryBranchTransferParcel', $data);
    }

    public function deliveryBranchTransferParcelAddCart(Request $request)
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },])
            ->whereIn('id', $request->parcel_invoices)
            ->orWhereIn('parcel_invoice', $request->parcel_invoices)
            ->whereRaw("((pickup_branch_id = ? AND status in (11, 13, 15)) OR (delivery_branch_id = ? AND status in (14,20)) or (delivery_branch_id = ? AND status in (25,28) AND delivery_type in (3,4)))", [$branch_id, $branch_id, $branch_id])
            ->get();

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
                        'name' => $parcel->merchant->name,
                        'price' => 1,
                        'quantity' => 1,
                        'target' => 'subtotal',
                        'attributes' => [
                            'parcel_invoice' => $parcel->parcel_invoice,
                            'customer_name' => $parcel->customer_name,
                            'customer_address' => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name' => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option' => [],
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
            'getTotal' => $getTotal,
            'error' => $error,
        ];
        return view('branch.parcel.pickupParcel.deliveryBranchTransferParcelCart', $data);
    }

    public function deliveryBranchTransferParcelDeleteCart(Request $request)
    {

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart' => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal' => \Cart::session($branch_id)->getTotal(),
            'error' => "",
        ];
        return view('branch.parcel.pickupParcel.pickupRiderRunParcelCart', $data);
    }

    public function confirmDeliveryBranchTransferGenerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'total_transfer_parcel' => 'required',
            'branch_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {

            if ($branch_id == $request->input('branch_id')) {
                $data = [
                    'delivery_transfer_invoice' => $this->returnUniqueBranchTransferInvoice(),
                    'from_branch_id' => $branch_id,
                    'from_branch_user_id' => $branch_user_id,
                    'to_branch_id' => $request->input('branch_id'),
                    'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'received_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel' => $request->input('total_transfer_parcel'),
                    'total_transfer_received_parcel' => $request->input('total_transfer_parcel'),
                    'note' => $request->input('note'),
                    'status' => 3,
                ];
            } else {
                $data = [
                    'delivery_transfer_invoice' => $this->returnUniqueBranchTransferInvoice(),
                    'from_branch_id' => $branch_id,
                    'from_branch_user_id' => $branch_user_id,
                    'to_branch_id' => $request->input('branch_id'),
                    'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel' => $request->input('total_transfer_parcel'),
                    'note' => $request->input('note'),
                    'status' => 1,
                ];
            }
            $deliveryBranchTransfer = DeliveryBranchTransfer::create($data);

            if ($deliveryBranchTransfer) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;
                    if ($branch_id == $request->input('branch_id')) {
                        DeliveryBranchTransferDetail::create([
                            'delivery_branch_transfer_id' => $deliveryBranchTransfer->id,
                            'parcel_id' => $parcel_id,
                            'status' => 3,
                        ]);
                        $parcel_code = mt_rand(100000, 999999);
                        $parcel = Parcel::where('id', $parcel_id)->first();
                        $parcel->update([
                            'status' => 14,
                            'parcel_code' => $parcel_code,
                            'parcel_date' => $request->input('date'),
                            'pickup_branch_user_id' => $branch_user_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                        ]);

                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'pickup_branch_id' => $branch_id,
                            'pickup_branch_user_id' => $branch_user_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 14,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                        // $message = "Hello this is your code $parcel_code. Please keep it screate.";
                        // $this->send_sms($item->attributes->customer_contact_number, $message);

                    } else {
                        DeliveryBranchTransferDetail::create([
                            'delivery_branch_transfer_id' => $deliveryBranchTransfer->id,
                            'parcel_id' => $parcel_id,
                            'status' => 1,
                        ]);
                        $parcel = Parcel::where('id', $parcel_id)->first();
                        $parcel->update([
                            'status' => 12,
                            'parcel_date' => $request->input('date'),
                            'delivery_branch_id' => $request->input('branch_id'),
                        ]);

                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'pickup_branch_id' => $branch_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 12,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }

                    $parcel = Parcel::where('id', $parcel_id)->first();

                    // $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($request->input('branch_id'));


                }
                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Delivery Branch Transfer Insert Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryBranchTransferList');
            } else {
                $this->setMessage('Delivery Branch Transfer Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }


    public function editDeliveryBranchTransfer(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer)
    {
        $deliveryBranchTransfer->load('from_branch', 'to_branch', 'delivery_branch_transfer_details');

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'pickupParcel';
        $data['child_menu'] = 'deliveryBranchTransferGenerate';
        $data['page_title'] = 'Delivery Branch Transfer Generate List';
        $data['collapse'] = 'sidebar-collapse';
        $data['deliveryBranchTransfer'] = $deliveryBranchTransfer;
        $data['branches'] = Branch::where([
            'status' => 1,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $branch_id = auth()->guard('branch')->user()->branch->id;
        \Cart::session($branch_id)->clear();

        foreach ($deliveryBranchTransfer->delivery_branch_transfer_details as $delivery_branch_transfer_detail) {
            $cart_id = $delivery_branch_transfer_detail->parcel->id;

            \Cart::session($branch_id)->add([
                'id' => $cart_id,
                'name' => $delivery_branch_transfer_detail->parcel->merchant->name,
                'price' => 1,
                'quantity' => 1,
                'target' => 'subtotal',
                'attributes' => [
                    'parcel_invoice' => $delivery_branch_transfer_detail->parcel->parcel_invoice,
                    'customer_name' => $delivery_branch_transfer_detail->parcel->customer_name,
                    'customer_address' => $delivery_branch_transfer_detail->parcel->customer_address,
                    'customer_contact_number' => $delivery_branch_transfer_detail->parcel->customer_contact_number,
                    'merchant_name' => $delivery_branch_transfer_detail->parcel->merchant->name,
                    'merchant_contact_number' => $delivery_branch_transfer_detail->parcel->merchant->contact_number,
                    'option' => [],
                ],
                'associatedModel' => $delivery_branch_transfer_detail->parcel,
            ]);
        }


        $cart = \Cart::session($branch_id)->getContent();

        $data['cart'] = $cart->sortBy('id');;
        $data['totalItem'] = \Cart::session($branch_id)->getTotalQuantity();
        $data['getTotal'] = \Cart::session($branch_id)->getTotal();


        $data['parcels'] = Parcel::with([
            'merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number');
            },
            'district' => function ($query) {
                $query->select('id', 'name');
            },
            'upazila' => function ($query) {
                $query->select('id', 'name');
            },
            'area' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->whereRaw("(pickup_branch_id = ? AND status in (11, 13, 15)) OR (delivery_branch_id = ? AND status in (14))", [$branch_id, $branch_id])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'district_id', 'upazila_id', 'area_id')
            ->get();

        return view('branch.parcel.pickupParcel.deliveryBranchTransferGenerateEdit', $data);
    }


    public function confirmDeliveryBranchTransferGenerateEdit(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer)
    {
        $validator = Validator::make($request->all(), [
            'total_transfer_parcel' => 'required',
            'branch_id' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {

            if ($branch_id == $request->input('branch_id')) {
                $data = [
                    'from_branch_id' => $branch_id,
                    'from_branch_user_id' => $branch_user_id,
                    'to_branch_id' => $request->input('branch_id'),
                    'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'received_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel' => $request->input('total_transfer_parcel'),
                    'total_transfer_received_parcel' => $request->input('total_transfer_parcel'),
                    'note' => $request->input('note'),
                    'status' => 3,
                ];
            } else {
                $data = [
                    'from_branch_id' => $branch_id,
                    'from_branch_user_id' => $branch_user_id,
                    'to_branch_id' => $request->input('branch_id'),
                    'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                    'received_date_time' => null,
                    'total_transfer_parcel' => $request->input('total_transfer_parcel'),
                    'note' => $request->input('note'),
                    'status' => 1,
                ];
            }

            $check = DeliveryBranchTransfer::where('id', $deliveryBranchTransfer->id)->update($data);
            if ($check) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                $deliveryBranchTransferDetails = DeliveryBranchTransferDetail::where('delivery_branch_transfer_id', $deliveryBranchTransfer->id)->get();


                foreach ($deliveryBranchTransferDetails as $deliveryBranchTransferDetail) {
                    Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->update([
                        'status' => 11,
                    ]);
                }

                DeliveryBranchTransferDetail::where('delivery_branch_transfer_id', $deliveryBranchTransfer->id)->delete();


                foreach ($cart as $item) {
                    $parcel_id = $item->id;
                    if ($branch_id == $request->input('branch_id')) {
                        DeliveryBranchTransferDetail::create([
                            'delivery_branch_transfer_id' => $deliveryBranchTransfer->id,
                            'parcel_id' => $parcel_id,
                            'status' => 3,
                        ]);
                        $parcel_code = mt_rand(100000, 999999);
                        $parcel = Parcel::where('id', $parcel_id)->first();
                        $parcel->update([
                            'status' => 14,
                            'parcel_code' => $parcel_code,
                            'parcel_date' => $request->input('date'),
                            'pickup_branch_user_id' => $branch_user_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                        ]);

                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'pickup_branch_id' => $branch_id,
                            'pickup_branch_user_id' => $branch_user_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 14,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                      
                       // $message = "Hello this is your code $parcel_code. Please keep it screate. Faster logistics ltd";
                      //  $this->send_sms($item->attributes->customer_contact_number, $message);

                    } else {
                        DeliveryBranchTransferDetail::create([
                            'delivery_branch_transfer_id' => $deliveryBranchTransfer->id,
                            'parcel_id' => $parcel_id,
                            'status' => 1,
                        ]);
                        $parcel = Parcel::where('id', $parcel_id)->first();
                        $parcel->update([
                            'status' => 12,
                            'parcel_date' => $request->input('date'),
                            'delivery_branch_id' => $request->input('branch_id'),
                        ]);

                        ParcelLog::create([
                            'parcel_id' => $parcel_id,
                            'pickup_branch_id' => $branch_id,
                            'delivery_branch_id' => $request->input('branch_id'),
                            'date' => date('Y-m-d'),
                            'time' => date('H:i:s'),
                            'status' => 12,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }

                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($request->input('branch_id'));

                }
                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Delivery Branch Transfer Insert Successfully', 'success');
                return redirect()->route('branch.parcel.deliveryBranchTransferList');
            } else {
                $this->setMessage('Delivery Branch Transfer Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }


}
