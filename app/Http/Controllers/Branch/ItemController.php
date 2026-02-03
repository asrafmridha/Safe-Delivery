<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu']  = 'item';
        $data['page_title'] = 'Item List';
        $data['collapse']   = 'sidebar-collapse';
        return view('branch.item.index', $data);
    }


    public function getItem(Request $request) {
        $model = Item::with(['item_categories', 'units'])->select();
        return DataTables::of($model)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                if ($data->status == 1) {
                    $class = "success"; $status = 0; $status_name = "Active";
                } else {
                    $class = "danger"; $status = 1; $status_name = "Inactive";
                }

                return '<a class=" text-bold text-' . $class . '" href="javascript:void(0)" style="font-size:20px;" item_id="' . $data->id . '" status="' . $status . '"> ' . $status_name . '</a>';
            })

            ->addColumn('action', function ($data) {
                return "";
            })
            ->rawColumns(['image', 'status', 'action', 'image'])
            ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'item';
        $data['child_menu'] = 'item-list';
        $data['page_title'] = 'Create Item';
        $data['categories']  = ItemCategory::where('status', 1)->get();
        $data['units']  = Unit::where('status', 1)->get();
        return view('admin.item.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'item_cat_id'           => 'required',
            'item_name'             =>
                'required|unique:items,item_name,NULL,id,item_cat_id,' . $request->input('item_cat_id'),
            'unit_id'               => 'required',
        ], [
            'item_name.unique' => 'This item already exists for selected category',
            'item_cat_id.required' => 'Category Field is required',
            'unit_id.required' => 'Unit Field is required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'item_cat_id'              => $request->input('item_cat_id'),
            'item_name'             => $request->input('item_name'),
            'unit_id'                => $request->input('unit_id'),
            'od_rate'       => $request->input('od_rate'),
            'hd_rate'       => $request->input('hd_rate'),
            'transit_od'              => $request->input('transit_od'),
            'transit_hd'              => $request->input('transit_hd'),
            'created_admin_id'          => auth()->guard('admin')->user()->id,
        ];
        $check = Item::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Create Successfully', 'success');
            return redirect()->route('admin.item.index');
        } else {
            $this->setMessage('Item Create Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function edit(Request $request, Item $item) {
        $data               = [];
        $data['main_menu']  = 'item';
        $data['child_menu'] = 'item-list';
        $data['page_title'] = 'Edit Item';
        $data['categories']  = ItemCategory::where('status', 1)->get();
        $data['units']  = Unit::where('status', 1)->get();
        $data['item']     = $item;
        return view('admin.item.edit', $data);
    }

    public function update(Request $request, Item $item) {

        $validator = Validator::make($request->all(), [
            'item_cat_id'           => 'required',
            'item_name'             =>
                'required|unique:items,item_name,'.$item->id.',id,item_cat_id,' . $request->input('item_cat_id'),
            'unit_id'               => 'required',
        ], [
            'item_name.unique' => 'This item already exists for selected category',
            'item_cat_id.required' => 'Category Field is required',
            'unit_id.required' => 'Unit Field is required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data     = [
            'item_cat_id'              => $request->input('item_cat_id'),
            'item_name'             => $request->input('item_name'),
            'unit_id'                => $request->input('unit_id'),
            'od_rate'       => $request->input('od_rate'),
            'hd_rate'       => $request->input('hd_rate'),
            'transit_od'              => $request->input('transit_od'),
            'transit_hd'              => $request->input('transit_hd'),
            'updated_admin_id'          => auth()->guard('admin')->user()->id,
        ];

        $check = Item::where('id', $item->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Update Successfully', 'success');
            return redirect()->route('admin.item.index');
        } else {
            $this->setMessage('Item Update Failed', 'danger');
            return redirect()->back()->withInput();
        }

    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found ',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
                'status'    => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found ',
                ];
            } else {
                $check = Item::where('id', $request->item_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Item Status Update Successfully',
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

    public function destroy(Request $request, Item $item) {
        $check = $item->delete() ? true : false;

        if ($check) {
            $this->setMessage('Item Delete Successfully', 'success');
        } else {
            $this->setMessage('Item Delete Failed', 'danger');
        }

        return redirect()->route('admin.item.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'item_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = ['error' => 'Error Found'];
            } else {
                $check  = Item::where('id', $request->item_id)->delete() ? true : false;

                if ($check) {
                    $response = ['success' => 'Item Delete Update Successfully'];
                } else {
                    $response = ['error' => 'Database Error Found'];
                }

            }

        }

        return response()->json($response);
    }
}
