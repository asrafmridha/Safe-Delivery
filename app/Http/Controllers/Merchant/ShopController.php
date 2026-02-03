<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data               = [];
        $data['main_menu']  = 'shop';
        $data['child_menu'] = 'shop-list';
        $data['page_title'] = 'Shop List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.shop.index', $data);
    }


    public function getShops()
    {
        $merchant_id    = auth('merchant')->user()->id;

        $model  = MerchantShop::where('merchant_id', $merchant_id)->get();

        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class="updateStatus text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" shop_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })
            ->addColumn('action', function ($data) {
                $button = '<button class="btn btn-secondary btn-sm view-modal" data-toggle="modal" data-target="#viewModal" shop_id="' . $data->id . '" >
                                <i class="fa fa-eye"></i> </button> &nbsp;&nbsp;';
                $button .= '<a href="' . route('merchant.shop.edit', $data->id) . '" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> </a>';
                $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-sm delete-btn" shop_id="' . $data->id . '">
                    <i class="fa fa-trash"></i> </button>';
                return $button;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data               = [];
        $data['main_menu']  = 'shop';
        $data['child_menu'] = 'shop-list';
        $data['page_title'] = 'Shop List';
        $data['collapse']   = 'sidebar-collapse';
        return view('merchant.shop.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $merchant_id = auth('merchant')->user()->id;

        $validator = Validator::make($request->all(), [
            'shop_name'             => 'required|unique:merchant_shops,shop_name,NULL,id,merchant_id,' . $merchant_id,
            'shop_address'               => 'required',
        ], [
            'shop_name.unique' => 'This shop already exists',
            'shop_address.required' => 'Shop Address Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'shop_name'                 => $request->input('shop_name'),
            'shop_address'              => $request->input('shop_address'),
            'merchant_id'               => $merchant_id,
        ];
        $check = MerchantShop::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Shop Create Successfully', 'success');
            return redirect()->route('merchant.shop.index');
        } else {
            $this->setMessage('Shop Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantShop $shop)
    {
        $shop->load('merchants');
        return view('merchant.shop.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantShop $shop)
    {
        $data               = [];
        $data['main_menu']  = 'shop';
        $data['child_menu'] = 'shop-list';
        $data['page_title'] = 'Edit Shop';
        $data['shop']       = $shop;
        return view('merchant.shop.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MerchantShop $shop)
    {
        $merchant_id = auth('merchant')->user()->id;

        $validator = Validator::make($request->all(), [
            'shop_name'             => 'required|unique:merchant_shops,shop_name,'.$shop->id.',id,merchant_id,' . $merchant_id,
            'shop_address'               => 'required',
        ], [
            'shop_name.unique' => 'This shop already exists',
            'shop_address.required' => 'Shop Address Required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'shop_name'                 => $request->input('shop_name'),
            'shop_address'              => $request->input('shop_address'),
            'status'                    => $request->input('status'),
        ];
        $check = MerchantShop::where('id', $shop->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Shop Update Successfully', 'success');
            return redirect()->route('merchant.shop.index');
        } else {
            $this->setMessage('Shop Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }



    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = MerchantShop::where('id', $request->shop_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Shop Status Update Successfully',
                        'status'  => $request->status,
                    ];
                } else {
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }

            }

        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantShop $shop) {
        $check = $shop->delete() ? true : false;

        if ($check) {
            $this->setMessage('Shop Delete Successfully', 'success');
        } else {
            $this->setMessage('Shop Delete Failed', 'danger');
        }

        return redirect()->route('merchant.shop.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'shop_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check  = MerchantShop::where('id', $request->shop_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Shop Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }

}
