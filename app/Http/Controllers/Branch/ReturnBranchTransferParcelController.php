<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\ReturnBranchTransfer;
use App\Models\ReturnBranchTransferDetail;
use App\Notifications\MerchantParcelNotification;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReturnBranchTransferParcelController extends Controller {

    public function returnBranchTransferList() {
        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnBranchTransferList';
        $data['page_title'] = 'Return Branch Transfer List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.parcel.deliveryParcel.returnBranchTransferList', $data);
    }

    public function getReturnBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = ReturnBranchTransfer::with(
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
                $button = '<button class="btn btn-secondary view-modal btn-sm" data-toggle="modal" data-target="#viewModal" return_branch_transfer_id="' . $data->id . '" title="View Return Branch Transfer" >
                <i class="fa fa-eye"></i> </button>';
                $button .= '&nbsp; <button class="btn btn-primary print-modal btn-sm" return_branch_transfer_id="' . $data->id . '"  title="Print Return Branch Transfer" >
                    <i class="fa fa-print"></i> </button> ';
                if ($data->status == 1) {
                    $button .= '&nbsp; <a href="' . route('branch.parcel.returnBranchTransferGenerateEdit', $data->id) . '" class="btn btn-info btn-sm" title="Edit Return Branch Transfer" >
                        <i class="fas fa-edit"></i> </a>';

                    $button .= '&nbsp; <button class="btn btn-danger return-branch-transfer-cancel-btn btn-sm" data-target="#viewModal" return_branch_transfer_id="' . $data->id . '"  title="Cancel Return Branch Transfer" >
                    <i class="fa fa-window-close"></i> </button> ';
                }

                return $button;
            })
            ->rawColumns(['status', 'action', 'create_date_time', 'reject_date_time', 'received_date_time'])
            ->make(true);
    }

    public function printReturnBranchTransferList(Request $request) {
        $branch_user_id = auth()->guard('branch')->user()->id;
        $branch_id      = auth()->guard('branch')->user()->branch->id;

        $model = ReturnBranchTransfer::with(
            [
                'to_branch' => function ($query) {
                    $query->select('id', 'name', 'contact_number', 'address');
                },
            ]
        )
            ->whereRaw('from_branch_id = ? ', [$branch_id])
            ->orderBy('id', 'desc')
            ->select();
        $returnBranchTransfers = $model->get();
        return view('branch.parcel.deliveryParcel.printReturnBranchTransferList', compact('returnBranchTransfers'));
    }

    public function viewReturnBranchTransfer(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.viewReturnBranchTransfer', compact('returnBranchTransfer'));
    }
    public function printReturnBranchTransfer(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        return view('branch.parcel.deliveryParcel.printReturnBranchTransfer', compact('returnBranchTransfer'));
    }

    public function cancelReturnBranchTransfer(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'return_branch_transfer_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check = ReturnBranchTransfer::where('id', $request->return_branch_transfer_id)->update([
                    'cancel_date_time' => date('Y-m-d H:i:s'),
                    'status'           => 2,
                ]);

                if ($check) {
                    $returnBranchTransferDetails = ReturnBranchTransferDetail::where('return_branch_transfer_id', $request->return_branch_transfer_id)->get();

                    foreach ($returnBranchTransferDetails as $returnBranchTransferDetail) {
                        Parcel::where('id', $returnBranchTransferDetail->parcel_id)->update([
                            'status'                  => 27,
                            'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                            'delivery_branch_id'      => auth()->guard('branch')->user()->branch->id,
                            'parcel_date'             => date('Y-m-d'),
                        ]);
                        $parcel=Parcel::where('id', $returnBranchTransferDetail->parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'               => $returnBranchTransferDetail->parcel_id,
                            'delivery_branch_user_id' => auth()->guard('branch')->user()->id,
                            'delivery_branch_id'      => auth()->guard('branch')->user()->branch->id,
                            'date'                    => date('Y-m-d'),
                            'time'                    => date('H:i:s'),
                            'status'                  => 27,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                        ReturnBranchTransferDetail::where('id', $returnBranchTransferDetail->id)->update([
                            'status' => 2,
                        ]);

                        $parcel = Parcel::where('id', $returnBranchTransferDetail->parcel_id)->first();
                        // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                        // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    }

                    // $this->adminDashboardCounterEvent();
                    $response = ['success' => 'Return Branch Transfer Cancel Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function returnBranchTransferGenerate() {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \Cart::session($branch_id)->clear();

        $data               = [];
        $data['main_menu']  = 'returnParcel';
        $data['child_menu'] = 'returnBranchTransferGenerate';
        $data['page_title'] = 'Return Branch Transfer Generate List';
        $data['collapse']   = 'sidebar-collapse';
        $data['branches']   = Branch::where([
            'status' => 1,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with([
                'merchant:id,name,company_name,contact_number',
                'area:id,name'
            ])
            ->where([
                'delivery_branch_id' => $branch_id,
            ])
            ->whereIn('status', [25, 27, 29])
            ->whereIn('delivery_type', [2, 4])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'area_id')
            ->get();

        return view('branch.parcel.deliveryParcel.returnBranchTransferGenerate', $data);
    }

    public function returnReturnBranchTransferParcel(Request $request) {

        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcel_invoice    = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if (!empty($parcel_invoice) || !empty($merchant_order_id)) {

            $data['parcels'] = Parcel::with([
                    'merchant' => function ($query) {
                        $query->select('id', 'name', 'contact_number');
                    },
                    'area'     => function ($query) {
                        $query->select('id', 'name');
                    },
                ])
                ->where([
                    'delivery_branch_id' => $branch_id,
                ])
                ->whereIn('status', [25, 27, 29])
                ->whereIn('delivery_type', [2, 4])
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {

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
                ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'area_id')
                ->get();
        } else {
            $data['parcels'] = [];
        }

        return view('branch.parcel.deliveryParcel.returnBranchTransferParcel', $data);
    }

    public function returnBranchTransferParcelClearCart() {
        try {
            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            \Cart::session($branch_id)->clear();

            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();

            $data = [
                'cart'      => $cart,
                'totalItem' => $totalItem,
                'getTotal'  => $getTotal,
                'error'     => "",
            ];
            return view('branch.parcel.deliveryParcel.returnBranchTransferParcelCart', $data);
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

    }

    public function returnBranchTransferParcelAddCart(Request $request) {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $parcels = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'contact_number', 'address');
        },
        ])

            ->whereIn('id', $request->parcel_invoices)
            ->orWhereIn('parcel_invoice', $request->parcel_invoices)
            ->where([
                'delivery_branch_id' => $branch_id,
                'pickup_branch_id'   => $request->branch_id,
            ])
            ->whereIn('status', [25, 27, 29])
            ->whereIn('delivery_type', [2, 4])
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

            $error     = "";
            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        } else{
            $error     = "Parcel Invoice Not Found";
            $cart      = \Cart::session($branch_id)->getContent();
            $cart      = $cart->sortBy('id');
            $totalItem = \Cart::session($branch_id)->getTotalQuantity();
            $getTotal  = \Cart::session($branch_id)->getTotal();
        }

        $data = [
            'cart'      => $cart,
            'totalItem' => $totalItem,
            'getTotal'  => $getTotal,
            'error'     => $error,
        ];
        return view('branch.parcel.deliveryParcel.returnBranchTransferParcelCart', $data);
    }

    public function returnBranchTransferParcelDeleteCart(Request $request) {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->remove($request->input('itemId'));
        $cart = \Cart::session($branch_id)->getContent();
        $cart = $cart->sortBy('id');
        $data = [
            'cart'      => $cart,
            'totalItem' => \Cart::session($branch_id)->getTotalQuantity(),
            'getTotal'  => \Cart::session($branch_id)->getTotal(),
            'error'     => "",
        ];
        return view('branch.parcel.deliveryParcel.returnBranchTransferParcelCart', $data);
    }

    public function confirmReturnBranchTransferGenerate(Request $request) {
        $validator = Validator::make($request->all(), [
            'total_transfer_parcel' => 'required',
            'branch_id'             => 'required',
            'date'                  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        \DB::beginTransaction();
        try {

            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            if ($branch_id == $request->input('branch_id')) {
                $data = [
                    'return_transfer_invoice'        => $this->returnUniqueReturnTransferInvoice(),
                    'from_branch_id'                 => $branch_id,
                    'from_branch_user_id'            => $branch_user_id,
                    'to_branch_id'                   => $request->input('branch_id'),
                    'create_date_time'               => $request->input('date') . ' ' . date('H:i:s'),
                    'received_date_time'             => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel'          => $request->input('total_transfer_parcel'),
                    'total_transfer_received_parcel' => $request->input('total_transfer_parcel'),
                    'note'                           => $request->input('note'),
                    'status'                         => 3,
                ];
            } else{
                $data = [
                    'return_transfer_invoice' => $this->returnUniqueReturnTransferInvoice(),
                    'from_branch_id'          => $branch_id,
                    'from_branch_user_id'     => $branch_user_id,
                    'to_branch_id'            => $request->input('branch_id'),
                    'create_date_time'        => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel'   => $request->input('total_transfer_parcel'),
                    'note'                    => $request->input('note'),
                    'status'                  => 1,
                ];
            }

            $returnBranchTransfer = ReturnBranchTransfer::create($data);

            if ($returnBranchTransfer) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    if ($branch_id == $request->input('branch_id')) {
                        ReturnBranchTransferDetail::create([
                            'return_branch_transfer_id' => $returnBranchTransfer->id,
                            'parcel_id'                 => $parcel_id,
                            'status'                    => 3,
                        ]);
                        Parcel::where('id', $parcel_id)->update([
                            'status'                    => 28,
                            'parcel_date'               => $request->input('date'),
                            'delivery_branch_user_id'   => $branch_user_id,
                            'delivery_branch_id'        => $branch_id,
                            'return_branch_id'          => $request->input('branch_id'),
                            'return_branch_user_id'     => $branch_user_id,
                        ]);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'                 => $parcel_id,
                            'delivery_branch_user_id'   => $branch_user_id,
                            'delivery_branch_id'        => $branch_id,
                            'return_branch_id'          => $request->input('branch_id'),
                            'return_branch_user_id'     => $branch_user_id,
                            'date'                      => date('Y-m-d'),
                            'time'                      => date('H:i:s'),
                            'status'                    => 28,
                            'delivery_type' => $parcel->delivery_type,
                        ]);

                    } else {
                        ReturnBranchTransferDetail::create([
                            'return_branch_transfer_id' => $returnBranchTransfer->id,
                            'parcel_id'                 => $parcel_id,
                            'status'                    => 1,
                        ]);
                        Parcel::where('id', $parcel_id)->update([
                            'status'                  => 26,
                            'parcel_date'             => $request->input('date'),
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_branch_id'      => $branch_id,
                            'return_branch_id'          => $request->input('branch_id'),
                        ]);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'                 => $parcel_id,
                            'delivery_branch_user_id'   => $branch_user_id,
                            'delivery_branch_id'        => $branch_id,
                            'return_branch_id'          => $request->input('branch_id'),
                            'date'                      => date('Y-m-d'),
                            'time'                      => date('H:i:s'),
                            'status'                    => 26,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }

                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

//                    $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                    // $this->branchDashboardCounterEvent($parcel->return_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Return Branch Transfer Insert Successfully', 'success');
                return redirect()->route('branch.parcel.returnBranchTransferList');
            } else {
                $this->setMessage('Delivery Branch Transfer Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }

        }
        catch (\Exception $e){
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }



    public function returnBranchTransferGenerateEdit(Request $request, ReturnBranchTransfer $returnBranchTransfer) {
        $branch_id      = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        $returnBranchTransfer->load([
            'from_branch' => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'to_branch'   => function ($query) {
                $query->select('id', 'name', 'contact_number', 'address');
            },
            'return_branch_transfer_details',
        ]);
        \Cart::session($branch_id)->clear();


        foreach ($returnBranchTransfer->return_branch_transfer_details as $return_branch_transfer_detail) {
            $cart_id = $return_branch_transfer_detail->parcel_id;
            \Cart::session($branch_id)->add([
                'id'              => $cart_id,
                'name'            => $return_branch_transfer_detail->parcel->merchant->name,
                'price'           => 1,
                'quantity'        => 1,
                'target'          => 'subtotal',
                'attributes'      => [
                    'parcel_invoice'          => $return_branch_transfer_detail->parcel->parcel_invoice,
                    'customer_name'           => $return_branch_transfer_detail->parcel->customer_name,
                    'customer_address'        => $return_branch_transfer_detail->parcel->customer_address,
                    'customer_contact_number' => $return_branch_transfer_detail->parcel->customer_contact_number,
                    'merchant_name'           => $return_branch_transfer_detail->parcel->merchant->name,
                    'merchant_contact_number' => $return_branch_transfer_detail->parcel->merchant->contact_number,
                    'option'                  => [],
                ],
                'associatedModel' => $return_branch_transfer_detail->parcel,
            ]);
        }
        $cart      = \Cart::session($branch_id)->getContent();
        $cart      = $cart->sortBy('id');
        $totalItem = \Cart::session($branch_id)->getTotalQuantity();
        $getTotal  = \Cart::session($branch_id)->getTotal();

        $data               = [];
        $data['main_menu']  = 'deliveryParcel';
        $data['child_menu'] = 'returnBranchTransferGenerate';
        $data['page_title'] = 'Return Branch Transfer Generate List';
        $data['collapse']   = 'sidebar-collapse';
        $data['returnBranchTransfer']       = $returnBranchTransfer;
        $data['cart']       = $cart;
        $data['totalItem']   = $totalItem;
        $data['getTotal']   = $getTotal;
        $data['branches']   = Branch::where([
            'status' => 1,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

        $data['parcels'] = Parcel::with([
                'merchant' => function ($query) {
                    $query->select('id', 'name', 'contact_number');
                },
                'area'     => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->where([
                'delivery_branch_id' => $branch_id,
            ])
            ->whereIn('status', [25, 27, 29])
            ->whereIn('delivery_type', [2, 4])
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'merchant_id', 'area_id')
            ->get();

        return view('branch.parcel.deliveryParcel.returnBranchTransferGenerateEdit', $data);
    }



    public function confirmReturnBranchTransferGenerateEdit(Request $request, ReturnBranchTransfer $returnBranchTransfer) {

        $validator = Validator::make($request->all(), [
            'total_transfer_parcel' => 'required',
            'branch_id'             => 'required',
            'date'                  => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        \DB::beginTransaction();
        try {
            $branch_id      = auth()->guard('branch')->user()->branch->id;
            $branch_user_id = auth()->guard('branch')->user()->id;

            if ($branch_id == $request->input('branch_id')) {
                $data = [
                    'from_branch_id'                 => $branch_id,
                    'from_branch_user_id'            => $branch_user_id,
                    'to_branch_id'                   => $request->input('branch_id'),
                    'create_date_time'               => $request->input('date') . ' ' . date('H:i:s'),
                    'received_date_time'             => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel'          => $request->input('total_transfer_parcel'),
                    'total_transfer_received_parcel' => $request->input('total_transfer_parcel'),
                    'note'                           => $request->input('note'),
                    'status'                         => 3,
                ];
            } else{
                $data = [
                    'from_branch_id'          => $branch_id,
                    'from_branch_user_id'     => $branch_user_id,
                    'to_branch_id'            => $request->input('branch_id'),
                    'create_date_time'        => $request->input('date') . ' ' . date('H:i:s'),
                    'total_transfer_parcel'   => $request->input('total_transfer_parcel'),
                    'note'                    => $request->input('note'),
                    'status'                  => 1,
                ];
            }

            $check = ReturnBranchTransfer::where('id', $returnBranchTransfer->id)->update($data);

            if ($check) {
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');
                $returnBranchTransferDetails = ReturnBranchTransferDetail::where('return_branch_transfer_id', $returnBranchTransfer->id)->get();

                foreach($returnBranchTransferDetails as $returnBranchTransferDetail){
                    Parcel::where('id', $returnBranchTransferDetail->parcel_id)->update([
                        'status'  => 25,
                    ]);
                }
                ReturnBranchTransferDetail::where('return_branch_transfer_id', $returnBranchTransfer->id)->delete();

                foreach ($cart as $item) {
                    $parcel_id = $item->id;

                    if ($branch_id == $request->input('branch_id')) {
                        ReturnBranchTransferDetail::create([
                            'return_branch_transfer_id' => $returnBranchTransfer->id,
                            'parcel_id'                 => $parcel_id,
                            'status'                    => 3,
                        ]);
                        Parcel::where('id', $parcel_id)->update([
                            'status'                  => 28,
                            'parcel_date'             => $request->input('date'),
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_branch_id'      => $branch_id,
                            'return_branch_user_id'   => $request->input('branch_id'),
                            'return_branch_user_id'   => $branch_user_id,
                        ]);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'               => $parcel_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_branch_id'      => $branch_id,
                            'return_branch_user_id'   => $request->input('branch_id'),
                            'return_branch_user_id'   => $branch_user_id,
                            'date'                    => date('Y-m-d'),
                            'time'                    => date('H:i:s'),
                            'status'                  => 28,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    } else{
                        ReturnBranchTransferDetail::create([
                            'return_branch_transfer_id' => $returnBranchTransfer->id,
                            'parcel_id'                 => $parcel_id,
                            'status'                    => 1,
                        ]);
                        Parcel::where('id', $parcel_id)->update([
                            'status'                  => 26,
                            'parcel_date'             => $request->input('date'),
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_branch_id'      => $branch_id,
                            'return_branch_user_id'   => $request->input('branch_id'),
                        ]);
                        $parcel=Parcel::where('id', $parcel_id)->first();
                        ParcelLog::create([
                            'parcel_id'               => $parcel_id,
                            'delivery_branch_user_id' => $branch_user_id,
                            'delivery_branch_id'      => $branch_id,
                            'return_branch_user_id'   => $request->input('branch_id'),
                            'date'                    => date('Y-m-d'),
                            'time'                    => date('H:i:s'),
                            'status'                  => 26,
                            'delivery_type' => $parcel->delivery_type,
                        ]);
                    }

                    $parcel = Parcel::where('id', $parcel_id)->first();

                    $merchant_user = Merchant::where('id', $parcel->merchant_id)->first();
                    // $merchant_user->notify(new MerchantParcelNotification($parcel));

                    // $this->merchantDashboardCounterEvent($parcel->merchant_id);

                    // $this->branchDashboardCounterEvent($parcel->delivery_branch_id);
                }

                \DB::commit();

                // $this->adminDashboardCounterEvent();
                $this->setMessage('Return Branch Transfer Insert Successfully', 'success');
                return redirect()->route('branch.parcel.returnBranchTransferList');
            } else {
                $this->setMessage('Delivery Branch Transfer Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }

        }
        catch (\Exception $e){
            \DB::rollback();
            $this->setMessage('Database Error Found', 'danger');
            return redirect()->back()->withInput();
        }
    }


}
