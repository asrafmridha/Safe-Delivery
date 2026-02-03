<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\DeliveryBranchTransfer;
use App\Models\DeliveryBranchTransferDetail;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\ReturnBranchTransfer;
use App\Models\ReturnBranchTransferDetail;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReceivedReturnBranchTransferParcelController extends Controller {

    public function receivedReturnBranchTransferList() {
        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'receivedReturnBranchTransferList';
        $data['page_title'] = 'Received Return Branch Transfer List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.returnParcel.receivedReturnBranchTransferList', $data);
    }

    public function getReceivedReturnBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = ReturnBranchTransfer::with(
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
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 : $status_name  = "Return Request"; $class  = "success";break;
                    case 2 : $status_name  = "Return Request Cancel"; $class  = "danger";break;
                    case 3 : $status_name  = "Return Request Accept"; $class  = "success";break;
                    case 4 : $status_name  = "Return Request Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<p class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</p>';
            })
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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" return_branch_transfer_id="' . $data->id . '" title="View Delivery Return Branch Transfer" >
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success received-return-branch-transfer-received-btn btn-sm" data-toggle="modal" data-target="#viewModal"  return_branch_transfer_id="' . $data->id . '"  title=" Received Return Branch Transfer Received" >
                    <i class="fa fa-check"></i> </button> ';

                    $button .= '&nbsp; <button class="btn btn-danger received-return-branch-transfer-reject-btn btn-sm" data-toggle="modal" data-target="#viewModal"  return_branch_transfer_id="' . $data->id . '"  title="Received Return Branch Transfer Cancel" >
                    <i class="fa fa-window-close"></i> </button> ';
                }

                return $button;
            })
            ->rawColumns(['status', 'action', 'create_date_time', 'reject_date_time', 'received_date_time'])
            ->make(true);
    }

    public function printReceivedReturnBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = ReturnBranchTransfer::with(
            [
                'from_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('to_branch_id = ? and status != 2', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $returnBranchTransfers=$model->get();
        return view('branch.parcel.returnParcel.printReceivedReturnBranchTransferList', compact('returnBranchTransfers'));

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                switch ($data->status) {
                    case 1 : $status_name  = "Return Request"; $class  = "success";break;
                    case 2 : $status_name  = "Return Request Cancel"; $class  = "danger";break;
                    case 3 : $status_name  = "Return Request Accept"; $class  = "success";break;
                    case 4 : $status_name  = "Return Request Reject"; $class  = "danger";break;
                    default:$status_name = "None"; $class = "success";break;
                }
                return '<p class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</p>';
            })
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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" return_branch_transfer_id="' . $data->id . '" title="View Delivery Return Branch Transfer" >
                <i class="fa fa-eye"></i> </button>';

                if ($data->status == 1) {
                    $button .= '&nbsp; <button class="btn btn-success received-return-branch-transfer-received-btn btn-sm" data-toggle="modal" data-target="#viewModal"  return_branch_transfer_id="' . $data->id . '"  title=" Received Return Branch Transfer Received" >
                    <i class="fa fa-check"></i> </button> ';

                    $button .= '&nbsp; <button class="btn btn-danger received-return-branch-transfer-reject-btn btn-sm" data-toggle="modal" data-target="#viewModal"  return_branch_transfer_id="' . $data->id . '"  title="Received Return Branch Transfer Cancel" >
                    <i class="fa fa-window-close"></i> </button> ';
                }

                return $button;
            })
            ->rawColumns(['status', 'action', 'create_date_time', 'reject_date_time', 'received_date_time'])
            ->make(true);
    }

    public function viewReceivedReturnBranchTransfer(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        return view('branch.parcel.returnParcel.viewReceivedReturnBranchTransfer', compact('returnBranchTransfer'));
    }

    public function receivedReturnBranchTransferReceived(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        return view('branch.parcel.returnParcel.receivedReturnBranchTransferReceived', compact('returnBranchTransfer'));
    }

    public function confirmReceivedReturnBranchTransferReceived(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $validator = Validator::make($request->all(), [
            'transfer_note'                  => 'sometimes',
            'received_date'                  => 'required',
            'total_transfer_received_parcel' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        \DB::beginTransaction();
        try {

            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            $total_transfer_received_parcel = $request->input('total_transfer_received_parcel');

            if($total_transfer_received_parcel != 0){
                $data = [
                    'received_date_time'             => $request->input('received_date') . ' ' . date('H:i:s'),
                    'total_transfer_received_parcel' => $request->input('total_transfer_received_parcel'),
                    'to_branch_user_id'              => $branch_user_id,
                    'note'                           => $request->input('transfer_note'),
                    'status'                         => 3,
                ];
            }
            else{
                $data = [
                    'cancel_date_time'               => $request->input('received_date') . ' ' . date('H:i:s'),
                    'total_transfer_received_parcel' => 0,
                    'to_branch_user_id'              => $branch_user_id,
                    'note'                           => $request->input('transfer_note'),
                    'status'                         => 4,
                ];
            }

            $check = ReturnBranchTransfer::where('id', $returnBranchTransfer->id)->update($data);

            if ($check) {
                $return_branch_transfer_details_id = $request->return_branch_transfer_details_id;

                $return_branch_transfer_status = $request->return_branch_transfer_status;
                $parcel_id                     = $request->parcel_id;
                $received_note                 = $request->received_note;
                $count                         = count($return_branch_transfer_details_id);

                for ($i = 0; $i < $count; $i++) {
                    ReturnBranchTransferDetail::where('id', $return_branch_transfer_details_id[$i])->update([
                        'note'   => $received_note[$i],
                        'status' => $return_branch_transfer_status[$i],
                    ]);

                    $status = 29 ;
                    if ($return_branch_transfer_status[$i] == 3) {
                        $status = 28 ;
                    }

                    Parcel::where('id', $parcel_id[$i])->update([
                        'status'                => $status,
                        'parcel_date'           => date('Y-m-d'),
                        'return_branch_date'    => date('Y-m-d'),
                        'return_branch_id'      => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                    ]);
                    $parcel=Parcel::where('id', $parcel_id[$i])->first();
                    ParcelLog::create([
                        'parcel_id'             => $parcel_id[$i],
                        'return_branch_id'      => $branch_id,
                        'return_branch_user_id' => $branch_user_id,
                        'date'                  => date('Y-m-d'),
                        'time'                  => date('H:i:s'),
                        'status'                => $status,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    $parcel = Parcel::where('id', $parcel_id[$i])->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->return_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $response = ['success' => 'Received Return Branch Transfer Received Successfully'];
            } else {
                $response = ['error' => 'Received Return Branch Transfer Received Failed'];
            }
        } catch (\Exception$e) {
            \DB::rollback();
            $response = ['error' => 'Database Error'];
        }
        return response()->json($response);

    }

    public function receivedReturnBranchTransferReject(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        return view('branch.parcel.returnParcel.receivedReturnBranchTransferReject', compact('returnBranchTransfer'));
    }

    public function confirmReceivedReturnBranchTransferReject(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $validator = Validator::make($request->all(), [
            'transfer_note'               => 'sometimes',
            'reject_date'                 => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        \DB::beginTransaction();
        try {
            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            $data = [
                'reject_date_time'  => $request->input('reject_date') . ' ' . date('H:i:s'),
                'note'              => $request->input('transfer_note'),
                'to_branch_user_id' => $branch_user_id,
                'status'            => 4,
            ];

            $check = ReturnBranchTransfer::where('id', $returnBranchTransfer->id)->update($data);

            if ($check) {
                $returnBranchTransferDetails = ReturnBranchTransferDetail::where('return_branch_transfer_id', $returnBranchTransfer->id)->get();

                foreach ($returnBranchTransferDetails as $returnBranchTransferDetail) {

                    DeliveryBranchTransferDetail::where('id', $returnBranchTransferDetail->id)->update([
                        'status' => 4,
                    ]);

                    Parcel::where('id', $returnBranchTransferDetail->parcel_id)->update([
                        'status'                    => 29,
                        'parcel_date'               => date('Y-m-d'),
                        'return_branch_date'        => date('Y-m-d'),
                        'return_branch_id'          => $branch_id,
                        'return_branch_user_id'     => $branch_user_id,
                    ]);
                    $parcel=Parcel::where('id', $returnBranchTransferDetail->parcel_id)->first();
                    ParcelLog::create([
                        'parcel_id'                 => $returnBranchTransferDetail->parcel_id,
                        'return_branch_id'          => $branch_id,
                        'return_branch_user_id'     => $branch_user_id,
                        'date'                      => date('Y-m-d'),
                        'time'                      => date('H:i:s'),
                        'status'                    => 29,
                        'delivery_type' => $parcel->delivery_type,
                    ]);

                    $parcel = Parcel::where('id', $returnBranchTransferDetail->parcel_id)->first();
                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->return_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $response = ['success' => 'Received Return Branch Transfer Reject Successfully'];
            } else {
                $response = ['error' => 'Received Return Branch Transfer Reject Failed'];
            }
        } catch (\Exception$e) {
            \DB::rollback();
            $response = ['error' => 'Database Error'];
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
