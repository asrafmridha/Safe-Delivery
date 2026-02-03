<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceivedBranchTransferParcelController extends Controller {

    public function receivedBranchTransferList() {
        $data               = [];
        $data['main_menu']  = 'deliveryParcel';
        $data['child_menu'] = 'receivedBranchTransferList';
        $data['page_title'] = 'Delivery Branch Transfer List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.deliveryParcel.receivedBranchTransferList', $data);
    }

    public function getReceivedBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = DeliveryBranchTransfer::with(
            [
                'from_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('to_branch_id = ? and status != 2', [$branch_id])
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
//                $button .= '&nbsp; <button class="btn btn-success received-branch-transfer-received-btn btn-sm" data-toggle="modal" data-target="#viewModal"  delivery_branch_transfer_id="' . $data->id . '"  title=" Received Branch Transfer Received" >
//                    <i class="fa fa-check"></i> </button> ';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" delivery_branch_transfer_id="' . $data->id . '" title="Print Delivery Branch Transfer" >
                <i class="fa fa-print"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success received-branch-transfer-received-btn btn-sm" data-toggle="modal" data-target="#viewModal"  delivery_branch_transfer_id="' . $data->id . '"  title=" Received Branch Transfer Received" >
                    <i class="fa fa-check"></i> </button> ';

                    $button .= '&nbsp; <button class="btn btn-danger received-branch-transfer-reject-btn btn-sm" data-toggle="modal" data-target="#viewModal"  delivery_branch_transfer_id="' . $data->id . '"  title="Received Branch Transfer Cancel" >
                    <i class="fa fa-window-close"></i> </button> ';
                }

                return $button;
            })
            ->rawColumns(['action', 'create_date_time', 'reject_date_time', 'received_date_time'])
            ->make(true);
    }

    public function printReceivedBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = DeliveryBranchTransfer::with(
            [
                'from_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('to_branch_id = ? and status != 2', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $deliveryBranchTransfers = $model->get();
        return view('branch.parcel.deliveryParcel.printReceivedBranchTransferList', compact('deliveryBranchTransfers'));
    }

    public function viewReceivedBranchTransfer(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.viewReceivedBranchTransfer', compact('deliveryBranchTransfer'));
    }
    public function printReceivedBranchTransfer(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.printReceivedBranchTransfer', compact('deliveryBranchTransfer'));
    }

    public function receivedBranchTransferReceived(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.receivedBranchTransferReceived', compact('deliveryBranchTransfer'));
    }

    public function confirmReceivedBranchTransferReceived(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {

        $validator = Validator::make($request->all(), [
            'transfer_note'                  => 'sometimes',
            'received_date'                  => 'required',
            'total_transfer_received_parcel' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {
            $total_transfer_received_parcel = $request->input('total_transfer_received_parcel');

            if ($total_transfer_received_parcel != 0) {
                $data = [
                    'received_date_time'             => $request->input('received_date') . ' ' . date('H:i:s'),
                    'total_transfer_received_parcel' => $total_transfer_received_parcel,
                    'to_branch_user_id'              => $branch_user_id,
                    'note'                           => $request->input('transfer_note'),
                    'status'                         => 3,
                ];
            } else {
                $data = [
                    'reject_date_time'               => $request->input('received_date') . ' ' . date('H:i:s'),
                    'total_transfer_received_parcel' => 0,
                    'to_branch_user_id'              => $branch_user_id,
                    'note'                           => $request->input('transfer_note'),
                    'status'                         => 4,
                ];
            }

            $check = DeliveryBranchTransfer::where('id', $deliveryBranchTransfer->id)->update($data);

            if ($check) {
                $delivery_branch_transfer_details_id = $request->delivery_branch_transfer_details_id;

                $delivery_branch_transfer_status = $request->delivery_branch_transfer_status;
                $parcel_id                       = $request->parcel_id;
                $received_note                   = $request->received_note;
                $count                           = count($delivery_branch_transfer_details_id);

                for ($i = 0; $i < $count; $i++) {
                    DeliveryBranchTransferDetail::where('id', $delivery_branch_transfer_details_id[$i])->update([
                        'note'   => $received_note[$i],
                        'status' => $delivery_branch_transfer_status[$i],
                    ]);

                    if ($delivery_branch_transfer_status[$i] == 3) {
                        $parcel_code = mt_rand(100000, 999999);

                        Parcel::where('id', $parcel_id[$i])->update([
                            'status'                  => 14,
                            'parcel_code'             => $parcel_code,
                            'parcel_date'             => date('Y-m-d'),
                            'delivery_branch_date'    => date('Y-m-d'),
                            'delivery_branch_id'      => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
                        $parcel=Parcel::where('id', $parcel_id[$i])->first();
                        ParcelLog::create([
                            'parcel_id'               => $parcel_id[$i],
                            'delivery_branch_id'      => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date'                    => date('Y-m-d'),
                            'time'                    => date('H:i:s'),
                            'status'                  => 14,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                        $parcel = Parcel::where('id', $parcel_id[$i])->first();

                       // $message = "Hello this is your code $parcel_code. Please keep it screate. Faster logistics ltd";
                      //  $this->send_sms($parcel->customer_contact_number, $message);
                    } else {
                        Parcel::where('id', $parcel_id[$i])->update([
                            'status'                  => 15,
                            'parcel_code'             => null,
                            'parcel_date'             => date('Y-m-d'),
                            'delivery_branch_date'    => date('Y-m-d'),
                            'delivery_branch_id'      => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                        ]);
                        $parcel=Parcel::where('id', $parcel_id[$i])->first();
                        ParcelLog::create([
                            'parcel_id'               => $parcel_id[$i],
                            'delivery_branch_id'      => $branch_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'date'                    => date('Y-m-d'),
                            'time'                    => date('H:i:s'),
                            'status'                  => 15,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }

                    $parcel = Parcel::where('id', $parcel_id[$i])->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $response = ['success' => 'Received Branch Transfer Received Successfully'];
            } else {
                $response = ['error' => 'Received Branch Transfer Received Failed'];
            }
        }
        catch (\Exception $e){
            \DB::rollback();
            $response = ['error' => 'Database Error Found'];
        }

        return response()->json($response);
    }

    public function receivedBranchTransferReject(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {
        $deliveryBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'delivery_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.receivedBranchTransferReject', compact('deliveryBranchTransfer'));
    }

    public function confirmReceivedBranchTransferReject(Request $request, DeliveryBranchTransfer $deliveryBranchTransfer) {

        $validator = Validator::make($request->all(), [
            'transfer_note'               => 'sometimes',
            'reject_date'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {
            $data = [
                'reject_date_time'  => $request->input('reject_date') . ' ' . date('H:i:s'),
                'note'              => $request->input('transfer_note'),
                'to_branch_user_id' => $branch_user_id,
                'status'            => 4,
            ];

            $check = DeliveryBranchTransfer::where('id', $deliveryBranchTransfer->id)->update($data);

            if ($check) {
                $deliveryBranchTransferDetails = DeliveryBranchTransferDetail::where('delivery_branch_transfer_id', $deliveryBranchTransfer->id)->get();

                foreach ($deliveryBranchTransferDetails as $deliveryBranchTransferDetail) {
                    DeliveryBranchTransferDetail::where('id', $deliveryBranchTransferDetail->id)->update([
                        'status' => 4,
                    ]);

                    Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->update([
                        'status'                  => 15,
                        'parcel_date'             => date('Y-m-d'),
                        'delivery_branch_date'    => date('Y-m-d'),
                        'delivery_branch_id'      => $branch_id,
                        'delivery_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel=Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'               => $deliveryBranchTransferDetail->parcel_id,
                        'delivery_branch_id'      => $branch_id,
                        'delivery_branch_user_id' => $branch_user_id,
                        'date'                    => date('Y-m-d'),
                        'time'                    => date('H:i:s'),
                        'status'                  => 15,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    $parcel = Parcel::where('id', $deliveryBranchTransferDetail->parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $response = ['success' => 'Received Branch Transfer Reject Successfully'];
            } else {
                $response = ['error' => 'Received Branch Transfer Reject Failed'];
            }
        }
        catch (\Exception $e){
            \DB::rollback();
            $response = ['error' => 'Database Error Found' ];
        }
        return response()->json($response);
    }

    public function deliveryBranchTransferGenerate() {
        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data               = [];
        $data['main_menu']  = 'pickupParcel';
        $data['child_menu'] = 'deliveryBranchTransferGenerate';
        $data['page_title'] = 'Delivery Branch Transfer Generate List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where([
            'status' => 1,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number');
        },
        ])
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [10, 12])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
            ->get();

        return view('branch.parcel.pickupParcel.deliveryBranchTransferGenerate', $data);
    }

    public function returnDeliveryBranchTransferParcel(Request $request) {

        $branch_id = auth()->guard('branch')->user()->id;

        $parcel_invoice    = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice) || !empty($merchant_order_id)) {

            $data['parcels'] = Parcel::with(['merchant' => function ($query) {
                $query->select('id', 'name', 'contact_number');
            },
            ])
                ->where(function ($query) use ($branch_id, $parcel_invoice, $merchant_order_id) {
                    $query->whereIn('status', [10, 12]);

                    $query->where([
                        'pickup_branch_id' => $branch_id,
                    ]);

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
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id')
                ->get();
        } else {
            $data['parcels'] = [];
        }

        return view('branch.parcel.pickupParcel.deliveryBranchTransferParcel', $data);
    }

    public function deliveryBranchTransferParcelAddCart(Request $request) {
        $branch_id = auth()->guard('branch')->user()->id;

        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])
            ->whereIn('id', $request->parcel_invoices)
            ->where([
                'pickup_branch_id' => $branch_id,
            ])
            ->whereIn('status', [10, 12])
            ->get();

        if ($parcels->count() > 0) {
            $cart = \Cart::session($branch_id)->getContent();
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
                    \Cart::session($branch_id)->add([
                        'id'              => $cart_id,
                        'name'            => $parcel->merchant->name,
                        'price'           => 1,
                        'quantity'        => 1,
                        'target'          => 'subtotal',
                        'attributes'      => [
                            'parcel_invoice'          => $parcel->parcel_invoice,
                            'customer_name'           => $parcel->customer_name,
                            'customer_address'        => $parcel->customer_address,
                            'customer_contact_number' => $parcel->customer_contact_number,
                            'merchant_name'           => $parcel->merchant->name,
                            'merchant_contact_number' => $parcel->merchant->contact_number,
                            'option'                  => [],
                        ],
                        'associatedModel' => $parcel,
                    ]);
                }

            }

            $error = "";

            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        } else {
            $error = "Parcel Invoice Not Found";

            $cart = \Cart::session($branch_id)->getContent();
            $cart = $cart->sortBy('id');

            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'getTotal'  => $getTotal,
            'error'     => $error,
        ];
        return view('branch.parcel.pickupParcel.deliveryBranchTransferParcelCart', $data);
    }

    public function deliveryBranchTransferParcelDeleteCart(Request $request) {

        $branch_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));

        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');

        $data = [
            'cart'      => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal'  => \Cart::session($branch_id)->getTotal(),
            'error'     => "",
        ];
        return view('branch.parcel.pickupParcel.pickupRiderRunParcelCart', $data);
    }

}
