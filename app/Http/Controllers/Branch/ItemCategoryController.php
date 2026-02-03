<?php
namespace App\Http\Controllers\Branch;

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
        return view('branch.traditionalParcelSetting.itemCategory.index', $data);
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
                    return '<a class=" text-bold text-'.$class.'" href="javascript:void(0)" style="font-size:20px;" item_category_id="'.$data->id.'" status="'.$status.'"> '.$status_name.'</a>';
                })
                ->addColumn('action', function($data){
                    return "";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
    }

    public function create() {
        $data               = [];
        $data['main_menu']  = 'item_category';
        $data['child_menu'] = 'item_category_list';
        $data['page_title'] = 'Create Item Category';
        return view('admin.applicationSetting.item_category.create', $data);
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
            $this->setMessage('Item Categoty Create Successfully', 'success');
            return redirect()->route('admin.ItemCategory.index');
            //return redirect()->back();
        } else {
            $this->setMessage('Item Categoty Create Failed', 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function show(Request $request, ItemCategory $ItemCategory) { //echo $ItemCategory; exit;
        return view('admin.applicationSetting.item_category.show', compact('ItemCategory'));
    }

    public function edit(Request $request, ItemCategory $ItemCategory) {
        $data               = [];
        $data['main_menu']  = 'item_category';
        $data['child_menu'] = 'item_category_list';
        $data['page_title'] = 'Edit Item Category';
        $data['ItemCategory']      = $ItemCategory;
        return view('admin.applicationSetting.item_category.edit', $data);
    }

    public function update(Request $request, ItemCategory $ItemCategory) {
        $validator = Validator::make($request->all(), [
            'name'    => 'required',
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
        $check = ItemCategory::where('id', $ItemCategory->id)->update($data) ? true : false;

        if ($check) {
            $this->setMessage('Item Category Update Successfully', 'success');
            return redirect()->route('admin.ItemCategory.index');
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

    public function destroy(Request $request, ItemCategory $ItemCategory) {

        $check = $ItemCategory->delete() ? true : false;

        if ($check) {
            $this->setMessage('Item Category Delete Successfully', 'success');
        } else {
            $this->setMessage('Item Category Delete Failed', 'danger');
        }
        return redirect()->route('admin.ItemCategory.index');
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
