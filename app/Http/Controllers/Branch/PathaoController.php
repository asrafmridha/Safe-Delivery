<?php

namespace App\Http\Controllers\Branch;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Parcel;
use App\Models\ParcelLog;
use App\Models\PathaoOrder;
use App\Models\PathaoOrderDetail;
use App\Models\Rider;
use App\Models\RiderRun;
use App\Models\RiderRunDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PathaoController extends Controller
{
    public function pathaoOrderGenerate()
    {
        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;
        \Cart::session($branch_id)->clear();

        $data = [];
        $data['main_menu'] = 'pathaoOrder';
        $data['child_menu'] = 'pathaoOrderGenerate';
        $data['page_title'] = 'Pathao Order Generate';
        $data['collapse'] = 'sidebar-collapse';
        $data['riders'] = Rider::with(['rider_runs' => function ($query) {
            $query->select('id', 'status', 'rider_id')->orderBy('id', 'desc');
        }])->where([
            'status' => 1,
            'branch_id' => $branch_id,
        ])
            ->select('id', 'name', 'contact_number', 'address')
            ->get();

//        $access_token = pathao_access_token();
        $data['pathao_cities'] = $pathao_cities = get_pathao_cities();
//        dd($pathao_cities);

        $data['parcels'] = Parcel::with(['merchant' => function ($query) {
            $query->select('id', 'name', 'company_name', 'contact_number');
        },
        ])
            ->whereRaw('((status = 25 AND delivery_type = 3) OR status in (14,18,20)) and delivery_branch_id = ?', $branch_id)
            ->select('id', 'parcel_invoice', 'merchant_order_id', 'customer_name', 'customer_contact_number', 'customer_address', 'merchant_id', 'total_collect_amount', 'cod_charge', 'total_charge')
            ->orderBy('id', 'DESC')
            ->get();

        return view('branch.parcel.pathaoOrder.pathaoOrderGenerate', $data);
    }

    public function confirmPathaoOrderGenerate(Request $request)
    {
//        dd($request->all());
//        $access_token = pathao_access_token();
//        dd($access_token);
        // $branch_id = auth()->guard('branch')->user()->branch->id;
        //         $cart = \Cart::session($branch_id)->getContent();
        // dd($cart);
        $validator = Validator::make($request->all(), [
            'total_run_parcel' => 'required',
            'rider_id' => 'required',
            'date' => 'required',
            // 'store_id'         => 'required|numeric|not_in:0',
        ]);
        $rider_id = $request->input('rider_id');
        $store_id = $request->input('store_id');

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $branch_id = auth()->guard('branch')->user()->branch->id;
        $branch_user_id = auth()->guard('branch')->user()->id;

        \DB::beginTransaction();
        try {
            $data = [
                'order_no' => $this->returnUniquePathaoOrderNo(),
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'date' => $request->input('date'),
                'time' => date('H:i:s'),
                'total_parcel' => $request->input('total_run_parcel'),
                'note' => $request->input('note'),
            ];

            $pathaoOrder = PathaoOrder::create($data);

            $data = [
                'run_invoice' => $this->returnUniqueRiderRunInvoice(),
                'rider_id' => $rider_id,
                'branch_id' => $branch_id,
                'branch_user_id' => $branch_user_id,
                'create_date_time' => $request->input('date') . ' ' . date('H:i:s'),
                'total_run_parcel' => $request->input('total_run_parcel'),
                'note' => $request->input('note'),
                'run_type' => 2,
                'status' => 1,
            ];
            $riderRun = RiderRun::create($data);
            $total_parcel = 0;
            if ($pathaoOrder) {
                $access_token = pathao_access_token();
                $cart = \Cart::session($branch_id)->getContent();
                $cart = $cart->sortBy('id');

                foreach ($cart as $item) {
                    $parcel_id = $item->id;
                    $parcel = Parcel::where('id', $parcel_id)->first();
                    if ($parcel->area->pathao_area_id) {
                        $city_id = $parcel->area->pathao_city_id;
                        $zone_id = $parcel->area->pathao_zone_id;
                        $area_id = $parcel->area->pathao_area_id;
                        // $pathaoOrderCreate = create_pathao_order($access_token, $city_id, $zone_id, $area_id, $parcel);
                        $pathaoOrderCreate = create_pathao_order($access_token, 1, 1, 1, $parcel ,$store_id);
                        
                        if ($pathaoOrderCreate['code'] == 200) {
                            $riderRunDetail = RiderRunDetail::create([
                                'rider_run_id' => $riderRun->id,
                                'parcel_id' => $parcel_id,
                            ]);
                            PathaoOrderDetail::create([
                                'pathao_order_id' => $pathaoOrder->id,
                                'city_id' => $city_id,
                                'zone_id' => $zone_id,
                                'area_id' => $area_id,
                                'parcel_id' => $parcel_id,
                                'rider_run_detail_id' => $riderRunDetail->id,
                                'consignment_id' => $pathaoOrderCreate['data']['consignment_id'],
                                'merchant_order_id' => $pathaoOrderCreate['data']['merchant_order_id'],
                            ]);
                            $parcel = Parcel::where('id', $parcel_id)->first();

                            $parcel->update([
                                'status' => 16,
                                'parcel_date' => $request->input('date'),
                                'is_pathao' => 1,
                                'pathao_status' => "Pending",
                                'delivery_rider_id' => $rider_id,
                                'delivery_rider_accept_date' => date('Y-m-d'),
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                            ]);

                            ParcelLog::create([
                                'parcel_id' => $parcel_id,
                                'delivery_rider_id' => $rider_id,
                                'delivery_branch_id' => $branch_id,
                                'delivery_branch_user_id' => $branch_user_id,
                                'date' => date('Y-m-d'),
                                'time' => date('H:i:s'),
                                'status' => 16,
                                'delivery_type' => $parcel->delivery_type,
                            ]);
                        }
                        $total_parcel++;
                    }
                    if ($total_parcel == 0) {
                        $pathaoOrder->delete();
                        $riderRun->delete();

                        $this->setMessage('Please Setup Pathao Area', 'danger');
                        return redirect()->back()->withInput();
                    }else{
                        $pathaoOrder->update([
                            'total_parcel' => $total_parcel,
                        ]);
                        $riderRun->update([
                            'total_run_parcel' => $total_parcel,
                        ]);
                    }
                }
                \DB::commit();
                $this->setMessage('Pathao Order Insert Successfully', 'success');
                return redirect()->back();
            } else {
                $this->setMessage('Pathao Order Insert Failed', 'danger');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            \DB::rollback();
//            $this->setMessage('Database Error Found', 'danger');
            $this->setMessage($e->getMessage(), 'danger');
            return redirect()->back()->withInput();
        }
    }

    public function getPathaoZone(Request $request)
    {
        
        $access_token = pathao_access_token();
        $city_id = $request->input('city_id');
        $pathao_zones = get_pathao_zones($city_id,$access_token);

        $zoneOption = '<option value="">Select Zone</option>';
        foreach ($pathao_zones as $pathao_zone) {
            $zoneOption .= '<option value="' . $pathao_zone['zone_id'] . '">' . $pathao_zone['zone_name'] . '</option>';
        }
        return $zoneOption;
    }

    public function getPathaoArea(Request $request)
    {
        $access_token = pathao_access_token();
        $zone_id = $request->input('zone_id');
        $pathao_areas = get_pathao_areas($zone_id,$access_token);

        $option = '<option value="">Select Area</option>';
        foreach ($pathao_areas as $pathao_area) {
            $option .= '<option value="' . $pathao_area['area_id'] . '">' . $pathao_area['area_name'] . '</option>';
        }
        return $option;
    }


}
