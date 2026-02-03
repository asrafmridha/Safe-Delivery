<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\Item;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemCategoryController extends Controller
{

    public function index() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'itemCategory';
        $data['page_title'] = 'Item Category';
        return view('admin.traditionalParcelSetting.itemCategory.index', $data);
    }

    public function getItemCategories(Request $request){
        $model  = ItemCategory::select();
        return DataTables::of($model)
                ->addIndexColumn()
                ->editColumn('status', function($data){
                    if($data->status  == 1){
                        $class =  "success"; $status = 0; $status_name = "Active";
                    }
                    else{
                        $class =  "danger";  $status = 1; $status_name = "Inactive";
                    }
                    return '<a class="updateStatus text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" item_category_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    $button = "";
                    // $button = '<button class="btn btn-secondary view-modal" data-toggle="modal" data-target="#viewModal" item_category_id="'.$data->id.'}" >
                    //             <i class="fa fa-eye"></i> </button>';
                    $button .= '&nbsp;&nbsp;&nbsp; <a href="'.route('admin.itemCategory.edit', $data->id).'" class="btn btn-success"> <i class="fa fa-edit"></i> </a>';
                    $button .= '&nbsp;&nbsp;&nbsp; <button class="btn btn-danger delete-btn" item_category_id="'.$data->id.'">
                                <i class="fa fa-trash"></i> </button>';
                    return $button;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function printItemCategories(Request $request){
        $itemCategories  = ItemCategory::all();
        return view('admin.traditionalParcelSetting.itemCategory.print', compact('itemCategories'));
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'itemCategory';
        $data['page_title'] = 'Create Item Category';
        return view('admin.traditionalParcelSetting.itemCategory.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'    => ['required','unique:item_categories'],
            'details'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'                  => $request->input('name'),
            'details'               => $request->input('details'),
            'created_admin_id'      => auth()->guard('admin')->user()->id,
        ];

        $check = ItemCategory::create($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Category Create Successfully', 'success');
            return redirect()->route('admin.itemCategory.index');
        } else {
            $this->setMessage('Item Category Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, ItemCategory $itemCategory) {
        //echo $itemCategory; exit;
        return view('admin.traditionalParcelSetting.itemCategory.show', compact('itemCategory'));
    }

    public function edit(Request $request, ItemCategory $itemCategory) {
        $data               = [];
        $data['main_menu']  = 'traditionalParcelSetting';
        $data['child_menu'] = 'itemCategory';
        $data['page_title'] = 'Edit Item Category';
        $data['itemCategory']      = $itemCategory;
        return view('admin.traditionalParcelSetting.itemCategory.edit', $data);
    }

    public function update(Request $request, ItemCategory $itemCategory) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:item_categories,name,' . $itemCategory->id,
            'details'  => 'sometimes',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $data = [
            'name'              => $request->input('name'),
            'details'           => $request->input('details'),
            'updated_admin_id'   => auth()->guard('admin')->user()->id,
        ];
        $check = ItemCategory::where('id', $itemCategory->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Category Update Successfully', 'success');
            return redirect()->route('admin.itemCategory.index');
        } else {
            $this->setMessage('Item Category Update Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function updateStatus(Request $request) {
        $response = [
            'error' => 'Error Found',
        ];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'item_category_id' => 'required',
                'status'   => 'required',
            ]);

            if ($validator->fails()) {
                $response = [
                    'error' => 'Error Found',
                ];
            }
            else{
                $check = ItemCategory::where('id', $request->item_category_id)->update(['status' => $request->status]) ? true : false;

                if ($check) {
                    $response = [
                        'success' => 'Item Category Status Update Successfully',
                        'status'  => $request->status,
                    ];
                }
                else{
                    $response = [
                        'error' => 'Database Error Found',
                    ];
                }
            }
        }
        return response()->json($response);
    }

    public function destroy(Request $request, ItemCategory $itemCategory) {

        $check = $itemCategory->delete() ? true : false;

        if ($check) {
            $this->setMessage('Item Category Delete Successfully', 'success');
        } else {
            $this->setMessage('Item Category Delete Failed', 'danger');
        }
        return redirect()->route('admin.itemCategory.index');
    }

    public function delete(Request $request) {
        $response = ['error' => 'Error Found'];

        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'item_category_id' => 'required',
            ]);

            if ($validator->fails()) {
                $response = [ 'error' => 'Error Found'];
            }
            else{
                $check      = ItemCategory::where('id', $request->item_category_id)->delete() ? true : false;
                if ($check) {
                    $response = [ 'success' => 'Item Category Delete Update Successfully'];
                }
                else {
                    $response = ['error' => 'Database Error Found'];
                }
            }
        }
        return response()->json($response);
    }

    public function getItemByCategory(Request $request) {

        if ($request->ajax()) {
            $option   = '<option value="0">Select Item</option>';
            $items = Item::where('item_cat_id', $request->item_cat_id)->get();

            foreach ($items as $item) {
                $option .= '<option value="' . $item->id . '">' . $item->item_name . '</option>';
            }

            return response()->json(['option' => $option]);
        }

        return redirect()->back();
    }
}
