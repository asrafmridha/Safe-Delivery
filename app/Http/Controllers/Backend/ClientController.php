<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Country;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    public function datatable(Request $request)
    {
        if (!check_access("client list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
//        if ($request->ajax()) {
        $data = Client::orderBy('id', 'desc')
            ->with('country')->get();
        return Datatables::of($data)->addIndexColumn()
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                if (check_permission('client edit') && $row->is_default != 1) {
                    $btn .= '<a href="' . route("client.edit", $row->id) . '" class="btn btn-primary"><i class="fa fa-edit"></i></a>';
                }
                if (check_permission('client delete') && $row->is_default != 1) {
                    $btn .= " <a href='" . route("client.delete", $row->id) . "' class='btn btn-danger' onclick=" . '"' . "return confirm('Are you sure you want to delete?');" . '"' . "><i class='fa fa-trash'></i></a>";
                }
                return $btn;
            })
            ->addColumn('balance', function ($row) {
                return number_format($row->balance,2,".",',');
            })
            ->addColumn('country', function ($row) {
                return $row->country ? $row->country->name : "---";
            })
            ->rawColumns(['action', 'balance', 'country'])
            ->make(true);
//        }
    }

    public function index(Request $request)
    {
        if (!check_access("client list")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        return view('backend.clients.index');
    }

    public function create()
    {
        if (!check_access("client create")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $countries = Country::all();
        return view('backend.clients.create', compact('countries'));
    }

    public function store(Request $request)
    {
        try {
            if (!check_access("client create")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:clients,email',
                'phone' => 'required',
                'country_id' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'country_id' => $request->input('country_id'),
                'address' => $request->input('address'),
            ];
            Client::create($inputs);
            Toastr::success("Client Created!");
            return redirect()->route('client');
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        if (!check_access("client edit")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $data = Client::find($id);
        $countries = Country::all();
        return view('backend.clients.edit', compact('data', 'countries'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!check_access("client edit")) {
                Toastr::error("You don't have permission!");
                return redirect()->route('dashboard');
            }
//            dd($request->all());
            $client = Client::find($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:clients,email,' . $client->id,
                'phone' => 'required',
                'country_id' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                Toastr::error("Invalid Data given!");
                return redirect()->back()->withErrors($validator->getMessageBag())->withInput();
            }
            $inputs = [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'country_id' => $request->input('country_id'),
                'address' => $request->input('address'),
            ];
            $client->update($inputs);
            Toastr::success("Client Updated!");
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage());
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        if (!check_access("client delete")) {
            Toastr::error("You don't have permission!");
            return redirect()->route('dashboard');
        }
        $item = Client::find($id);
        $item->delete();
        Toastr::success("Client Deleted!");
        return redirect()->back();
    }
}
