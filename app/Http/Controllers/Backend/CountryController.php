<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("country list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Country::orderBy('name')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('country edit')) {
                    $btn .= '<a href="' . route("country.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('country delete')) {
                    $btn .= " <a href='" . route("country.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("country list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.countries.index');
    }

    public function create()
    {
        if (!check_access("country create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.countries.create');
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("country create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:countries,name',
                'code' => 'required|unique:countries,code',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ];
            Country::create($inputs);
            Toastr::success("Country Created!");
            return redirect()->route('country');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("country edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = Country::find($id);
        return view('backend.countries.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("country edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
            $country = Country::find($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:countries,name,'.$country->id,
                'code' => 'required|unique:countries,code,'.$country->id,
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ];
            $country->update($inputs);
            Toastr::success("Country Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }
    public function delete($id)
    {
        if (!check_access("country delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Country::find($id);
        $item->delete();
        Toastr::success("Country Deleted!");
        return redirect()->back();
    }
}
