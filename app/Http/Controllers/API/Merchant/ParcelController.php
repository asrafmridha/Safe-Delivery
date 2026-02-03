<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\MerchantServiceAreaCodCharge;
use App\Models\Parcel;
use App\Models\ParcelLog;
use Illuminate\Http\Request;
use App\Models\ServiceArea;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantServiceAreaCharge;
use App\Models\MerchantServiceAreaReturnCharge;
use App\Models\WeightPackage;

class ParcelController extends Controller
{
    
    
       public function createParcel(Request $request)
    {
        $merchant = auth()->guard('merchant_api')->user();
        //        dd($merchant);

        if (is_null($merchant->branch_id)) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => "You are waiting for authorization",
            ], 401);
        }


        $validator = Validator::make($request->all(), [
            'merchant_order_id' => 'sometimes',
            'weight_package_id' => 'required',
            'product_details' => 'sometimes',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'area_id' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        try {
            // Set District, Upazila, Area ID and Merchant Service Area Charge
            $merchant_service_area_charge = 0;
            $delivery_charge = 0;
            $merchant_service_area_return_charge = 0;
            $weight_package_charge = 0;
            $cod_charge = 0;
            $service_area_id = 0;
            $cod_percent = 0;

            $item_type_charge = 0;
            $service_type_charge = 0;



            $merchant_cod_percent = $merchant->cod_charge ?? 0;
            $district_id = $request->input('district_id');
            $weight_id = $request->input('weight_package_id');
            $collection_amount = $request->input('total_collect_amount');

            $district = District::with('service_area:id,cod_charge,default_charge')->where('id', $district_id)->first();

            if ($district) {

                $service_area_id = $district->service_area_id;
                //Service Area Default Charges
                $delivery_charge = $district->service_area ? $district->service_area->default_charge : 0;


                // Check Merchant COD Percent
                if ($district->service_area->cod_charge != 0) {
                    $cod_percent = ($merchant_cod_percent != 0) ? $merchant_cod_percent : $district->service_area->cod_charge;
                }

                $code_charge_percent = $district->service_area->cod_charge;
                if ($code_charge_percent != 0) {
                    $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                        'service_area_id' => $district->service_area_id,
                        'merchant_id'     => $merchant->id,
                    ])->first();

                    if ($merchantServiceAreaCodCharge) {
                        $cod_percent = $merchantServiceAreaCodCharge->cod_charge;
                    }
                }


                $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                    $merchant_service_area_charge = $merchantServiceAreaCharge->charge;
                }


                //new update for same city
                if ($merchant->district_id == $request->input('district_id')) {
                    $serviceArea = ServiceArea::where('id', 1)->first();
                    $charge = $serviceArea->default_charge;

                    $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                        'service_area_id' => 1,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                } else {
                    $serviceArea = ServiceArea::where('id', 3)->first();
                    $charge = $serviceArea->default_charge;

                    //                        $charge = $district->service_area->default_charge;

                    $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                        'service_area_id' => 3,
                        'merchant_id' => $request->merchant_id,
                    ])->first();
                }
                if ($merchantServiceAreaCharge) {
                    $charge = $merchantServiceAreaCharge->charge;
                }

                $merchant_service_area_charge = $charge;



                //Set Default Return Charge 1/2 of Delivery Charge
                $merchant_service_area_return_charge = $merchant_service_area_charge / 2;
                if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                    //Set Return Charge Merchant Wise
                    $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                }
            }


            // Weight Package Charge
            if ($weight_id) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
            }

            if (empty($weightPackage) || is_null($weight_id)) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
                $weight_id = $weightPackage->id;
            }

            /**
             * Set Parcel Delivery Charge
             * If Merchant service area is not 0 then check District Area default Delivery charge
             */
            $delivery_charge = $merchant_service_area_charge != 0 ? $merchant_service_area_charge : $delivery_charge;


            $collection_amount = $collection_amount ?? 0;
            if ($collection_amount != 0 && $cod_percent != 0) {
                $cod_charge = ($collection_amount / 100) * $cod_percent;
            }

            $delivery_charge =  $delivery_charge + $item_type_charge + $service_type_charge;
            $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;

            $data = [
                'parcel_invoice' => $this->returnUniqueParcelInvoice(),
                'merchant_id' => $merchant->id,
                'date' => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'pickup_address' => $merchant->address,
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'product_value' => $request->input('product_value') ?? 0,
                'district_id' => $request->input('district_id'),
                'shop_id'     => $request->input('shop_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'weight_package_id' => $weight_id,
                'delivery_charge' => $delivery_charge,
                'weight_package_charge' => $weight_package_charge,
                'merchant_service_area_charge' => $merchant_service_area_charge,
                'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                'total_collect_amount' => $collection_amount,
                'cod_percent' => $cod_percent,
                'cod_charge' => $cod_charge,
                'total_charge' => $total_charge,
                //            'delivery_option_id'           => $request->input('delivery_option_id'),
                'delivery_option_id' => 1,
                'parcel_note' => $request->input('parcel_note'),
                'pickup_branch_id' => $merchant->branch_id,
                'parcel_date' => date('Y-m-d'),
                'status' => 1,
                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'item_type_charge' => $item_type_charge,
                'service_type_charge' => $service_type_charge,
                /*  'service_type_id' => $request->input('service_type_id') ?? null,
                  'item_type_id' => $request->input('item_type_id') ?? null,
                  'item_type_charge' => $request->input('item_type_charge'),
                  'service_type_charge' => $request->input('service_type_charge'),*/
            ];
            $parcel = Parcel::create($data);

            if ($parcel) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'merchant_id' => $merchant->id,
                    'pickup_branch_id' => $merchant->branch_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 1,
                ];
                ParcelLog::create($data);

                return response()->json([
                    'success' => 200,
                    'message' => "Parcel Add Successfully",
                    'parcel_id' => $parcel->id,
                    'parcel_invoice' => $parcel->parcel_invoice,
                ], 200);
            }

            return response()->json([
                'success' => 401,
                'message' => "Parcel Add Unsuccessfully",
                'error' => "Parcel Add Unsuccessfully",
            ], 401);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => 401,
                'message' => "Parcel Add Unsuccessfully",
                'error' => $exception->getMessage(),
            ], 401);
        }
    }

    public function addParcel(Request $request)
    {
        $merchant = auth()->guard('merchant_api')->user();

        if (is_null($merchant->branch_id)) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => "You are waiting for authorization",
            ], 401);
        }


        $validator = Validator::make($request->all(), [
            'merchant_order_id' => 'sometimes',
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
//            'delivery_option_id' => 'required',
            'product_details' => 'sometimes',
//            'product_value' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required',
            'customer_address' => 'required',
            'district_id' => 'required',
            'item_type_charge' => 'required',
            'service_type_charge' => 'required',
            // 'upazila_id'                            => 'required',
            'area_id' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        try {
            // Set District, Upazila, Area ID and Merchant Service Area Charge
            $merchant_service_area_charge = 0;
            $delivery_charge = 0;
            $merchant_service_area_return_charge = 0;
            $weight_package_charge = 0;
            $cod_charge = 0;
            $service_area_id = 0;
            $cod_percent = 0;
            $merchant_cod_percent = $merchant->cod_charge ?? 0;
            $district_id = $request->input('district_id');
            $weight_id = $request->input('weight_package_id');
            $collection_amount = $request->input('total_collect_amount');

            $district = District::with('service_area:id,cod_charge,default_charge')->where('id', $district_id)->first();

            if ($district) {

                $service_area_id = $district->service_area_id;
                //Service Area Default Charges
                $delivery_charge = $district->service_area ? $district->service_area->default_charge : 0;


                // Check Merchant COD Percent
                if ($district->service_area->cod_charge != 0) {
                    $cod_percent = ($merchant_cod_percent != 0) ? $merchant_cod_percent : $district->service_area->cod_charge;
                }

                $code_charge_percent = $district->service_area->cod_charge;
                if($code_charge_percent != 0){
                    $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                        'service_area_id' => $district->service_area_id,
                        'merchant_id'     => $merchant->id,
                    ])->first();

                    if ($merchantServiceAreaCodCharge) {
                        $cod_percent = $merchantServiceAreaCodCharge->cod_charge;
                    }
                }


                $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                    $merchant_service_area_charge = $merchantServiceAreaCharge->charge;

                }
                
                           
                                         //new update for same city
                                        //  dd($merchant->district_id);
                    if($merchant->district_id!=1){
                        if ($merchant->district_id == $request->input('district_id')) {
                            $serviceArea = ServiceArea::where('id', 1)->first();
                            $charge = $serviceArea->default_charge;
        
                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id' => 1,
                                'merchant_id' => $request->merchant_id,
                            ])->first();
                        } else {
                            $serviceArea = ServiceArea::where('id', 3)->first();
                            $charge = $serviceArea->default_charge;
        
                            //                        $charge = $district->service_area->default_charge;
        
                            $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                                'service_area_id' => 3,
                                'merchant_id' => $request->merchant_id,
                            ])->first();
                        }
                        if ($merchantServiceAreaCharge) {
                            $charge = $merchantServiceAreaCharge->charge;
                        }
        
                        $merchant_service_area_charge = $charge;

                    }                                                       
                    


                //Set Default Return Charge 1/2 of Delivery Charge
                $merchant_service_area_return_charge = $merchant_service_area_charge / 2;
                if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                    //Set Return Charge Merchant Wise
                    $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                }

            }


            // Weight Package Charge
            if ($weight_id) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
            }

            if (empty($weightPackage) || is_null($weight_id)) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
                $weight_id = $weightPackage->id;
            }

            /**
             * Set Parcel Delivery Charge
             * If Merchant service area is not 0 then check District Area default Delivery charge
             */
            $delivery_charge = $merchant_service_area_charge != 0 ? $merchant_service_area_charge : $delivery_charge;


            $collection_amount = $collection_amount ?? 0;
            if ($collection_amount != 0 && $cod_percent != 0) {
                $cod_charge = ($collection_amount / 100) * $cod_percent;
            }

            $item_type_charge = $request->input('item_type_charge')??0;
            $service_type_charge=$request->input('service_type_charge')??0;
            $delivery_charge =  $delivery_charge+$item_type_charge+$service_type_charge;
            $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;

            $data = [
                'parcel_invoice' => $this->returnUniqueParcelInvoice(),
                'merchant_id' => $merchant->id,
                'date' => date('Y-m-d'),
                'merchant_order_id' => $request->input('merchant_order_id'),
                'pickup_address' => $request->input('pickup_address'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'product_value' => $request->input('product_value')??0,
                'district_id' => $request->input('district_id'),
                // 'upazila_id'                   => $request->input('upazila_id'),
                'upazila_id' => 0,
                'area_id' => $request->input('area_id') ?? 0,
                'weight_package_id' => $weight_id,
                'delivery_charge' => $delivery_charge,
                'weight_package_charge' => $weight_package_charge,
                'merchant_service_area_charge' => $merchant_service_area_charge,
                'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                'total_collect_amount' => $collection_amount,
                'cod_percent' => $cod_percent,
                'cod_charge' => $cod_charge,
                'total_charge' => $total_charge,
//            'delivery_option_id'           => $request->input('delivery_option_id'),
                'delivery_option_id' => 1,
                'parcel_note' => $request->input('parcel_note'),
                'pickup_branch_id' => $merchant->branch_id,
                'parcel_date' => date('Y-m-d'),
                'status' => 1,
                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'item_type_charge' => $request->input('item_type_charge'),
                'service_type_charge' => $request->input('service_type_charge'),
                /*  'service_type_id' => $request->input('service_type_id') ?? null,
                  'item_type_id' => $request->input('item_type_id') ?? null,
                  'item_type_charge' => $request->input('item_type_charge'),
                  'service_type_charge' => $request->input('service_type_charge'),*/
            ];
            $parcel = Parcel::create($data);

            if ($parcel) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'merchant_id' => $merchant->id,
                    'pickup_branch_id' => $merchant->branch_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 1,
                ];
                ParcelLog::create($data);

                return response()->json([
                    'success' => 200,
                    'message' => "Parcel Add Successfully",
                    'parcel_id' => $parcel->id,
                ], 200);

            }

            return response()->json([
                'success' => 401,
                'message' => "Parcel Add Unsuccessfully",
                'error' => "Parcel Add Unsuccessfully",
            ], 401);
        }catch (\Exception $exception ){
            return response()->json([
                'success' => 401,
                'message' => "Parcel Add Unsuccessfully",
                'error' => $exception->getMessage(),
            ], 401);
        }

    }


    public function getParcelList(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $parcels = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package'
        ])
            ->whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {
                $parcel_status = $request->input('parcel_status');
                $parcel_invoice = $request->input('parcel_invoice');
                $merchant_order_id = $request->input('merchant_order_id');
                $customer_contact_number = $request->input('customer_contact_number');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');

                if (($request->has('parcel_status') && !is_null($parcel_status))
                    || ($request->has('parcel_invoice') && !is_null($parcel_invoice))
                    || ($request->has('customer_contact_number') && !is_null($customer_contact_number))
                    || ($request->has('merchant_order_id') && !is_null($merchant_order_id))
                    || ($request->has('from_date') && !is_null($from_date))
                    || ($request->has('to_date') && !is_null($to_date))
                ) {
                    if ((!is_null($parcel_invoice) && !is_null($parcel_invoice))
                        || (!is_null($merchant_order_id) && !is_null($merchant_order_id))
                        || (!is_null($customer_contact_number) && !is_null($customer_contact_number))
                    ) {
                        if (!is_null($parcel_invoice) && !is_null($parcel_invoice)) {
                            $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                             $query->orWhere('merchant_order_id', 'like', "%$parcel_invoice");
                            $query->orWhere('customer_contact_number', 'like', "%$parcel_invoice");
                        } elseif (!is_null($merchant_order_id) && !is_null($merchant_order_id)) {
                            $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                        } elseif (!is_null($customer_contact_number) && !is_null($customer_contact_number)) {
                            $query->where('customer_contact_number', 'like', "%$customer_contact_number");
                        }
                    } else {
                        if ($request->has('parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
                            if ($parcel_status == 1) {
                                $query->whereRaw('status >= 25 and delivery_type in (1,2)');
                            } elseif ($parcel_status == 2) {
                                //    $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                                $query->whereRaw('status > 11 and delivery_type in (?)', [3]);
                            } elseif ($parcel_status == 3) {
                                //    $query->whereRaw('status = 3');
                                $query->whereRaw('status >= ? and delivery_type in (4)', [25]);
                            } elseif ($parcel_status == 4) {
                                $query->whereRaw('status >= 25 and payment_type = 5 and delivery_type = 1 or delivery_type = 2');
                            } elseif ($parcel_status == 5) {
                                $query->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type = 1 or delivery_type = 2');
                            } elseif ($parcel_status == 6) {
                                //    $query->whereRaw('status = 36 and delivery_type = 4');
                                $query->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
                            } elseif ($parcel_status == 7) {
                                $query->whereRaw('status in (1) and delivery_type IS NULL or delivery_type = ""');
                            }
                        }
                        if ($request->has('from_date') && !is_null($from_date)) {
                            $query->whereDate('date', '>=', $from_date);
                        }
                        if ($request->has('to_date') && !is_null($to_date)) {
                            $query->whereDate('date', '<=', $to_date);
                        }
                    }
                } else {
                    $query->where('status', '!=', 0);
                }
            })
            ->orderBy('id', 'desc')
            ->select(
                'id',
                'parcel_invoice',
                'customer_name',
                'customer_address',
                'customer_contact_number',
                'area_id',
                'district_id',
                'upazila_id',
                'product_details',
                'weight_package_charge',
                'cod_percent',
                'total_collect_amount',
                'customer_collect_amount',
                'cod_charge',
                'delivery_charge',
                'total_charge',
                'status',
                'delivery_type',
                'payment_type'
            )
            ->get();

        $new_parcels = [];

        foreach ($parcels as $parcel) {
            $parcelStatus = returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);
            $status_name = $parcelStatus['status_name'];
            
            
            $class = $parcelStatus['class'];
            
            $paymentParcelStatus = returnPaymentStatusForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);
            $payment_status_name = $paymentParcelStatus['status_name'];
            
            
            

            //return '<span class=" text-bold text-' . $class . '" style="font-size:16px;"> ' . $status_name . '</span>';

            $new_parcels[] = [
                'id' => $parcel->id,
                'parcel_invoice' => $parcel->parcel_invoice,
                'merchant_order_id' => $parcel->merchant_order_id,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                'district_name' => $parcel->district->name,
                'upazila_name' => $parcel->upazila->name,
                'area_name' => $parcel->area->name,
                'product_details' => $parcel->product_details,

                'weight_package_name' => $parcel->weight_package->name,
                'weight_package_charge' => $parcel->weight_package_charge,
                'cod_percent' => $parcel->cod_percent,
                'collectable_amount' => $parcel->total_collect_amount ?? 0,
                'collected_amount' => $parcel->customer_collect_amount ?? 0,
                'cod_charge' => $parcel->cod_charge ?? 0,
                'delivery_charge' => $parcel->delivery_charge,
                'total_charge' => $parcel->total_charge,
                'parcel_status' => $status_name,
                'parcel_payment_status' => $payment_status_name,
                'status' => $parcel->status,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }


    /** For dashboard status base parcel filtering */
    public function filterParcelList(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $parcels = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package'
        ])
            ->whereRaw('merchant_id = ?', [$merchant_id])
            ->where(function ($query) use ($request) {

                if ($request->has('filter_action') && $request->input('filter_action') != "" && !is_null($request->input('filter_action'))) {
                    if (strtolower($request->input('filter_action')) == "cancel") {
                        $query->whereRaw('status = ?', [3]);
                    }

                    if (strtolower($request->input('filter_action')) == "waiting_pickup") {
                        $query->whereRaw('status != ? and status < ?', [3, 11]);
                    }

                    if (strtolower($request->input('filter_action')) == "waiting_delivery") {
                        $query->whereRaw('status != ? and status >= ? and status <= ? and (delivery_type is null or delivery_type = "") or delivery_type in (?)', [3, 11, 24,3]);
                    }

                    /*                    if (strtolower($request->input('filter_action')) == "delivery") {
                                            $query->whereRaw('status != ? and delivery_type in (?,?,?,?)', [3, 1, 2, 3, 4]);
                                        }*/
                    
                    if (strtolower($request->input('filter_action')) == "delivery") {
                        $query->whereRaw('status != ? and delivery_type in (?,?,?)', [3, 1, 2, 4]);
                    }
                    
                    if (strtolower($request->input('filter_action')) == "in_transit") {
                         $query->whereRaw('status > ? and status < ?', [11, 15]);
                    }
                    
                    if (strtolower($request->input('filter_action')) == "canceled") {
                        $query->whereRaw('status >= ? and delivery_type in (?)', [25, 4]);
                    }

                    if (strtolower($request->input('filter_action')) == "delivery_complete") {
                        $query->whereRaw('status >= ? and delivery_type in (?,?) and payment_type = ?', [25, 1, 2, 5]);
                    }
                    
                    if (strtolower($request->input('filter_action')) == "partial_delivery") {
                        $query->whereRaw('status >= ? and delivery_type in (?) ', [25, 2]);
                    }

                    if (strtolower($request->input('filter_action')) == "return") {
                        $query->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
                    }

                    if (strtolower($request->input('filter_action')) == "return_complete") {
                        $query->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
                    }
                }
            })
            ->orderBy('id', 'desc')
            ->select(
                'id',
                'parcel_invoice',
                'customer_name',
                'customer_address',
                'customer_contact_number',
                'district_id',
                'upazila_id',
                'product_details',
                'weight_package_charge',
                'cod_percent',
                'total_collect_amount',
                'customer_collect_amount',
                'cod_charge',
                'delivery_charge',
                'total_charge',
                'status',
                'delivery_type',
                'payment_type'
            )
            ->get();

        $new_parcels = [];

        foreach ($parcels as $parcel) {
           // $parcelStatus = returnParcelStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
             $parcelStatus = returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);
            $status_name = $parcelStatus['status_name'];
            $class = $parcelStatus['class'];
            
            $paymentParcelStatus = returnPaymentStatusForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);
            $payment_status_name = $paymentParcelStatus['status_name'];


            $new_parcels[] = [
                'id' => $parcel->id,
                'parcel_invoice' => $parcel->parcel_invoice,
                'customer_name' => $parcel->customer_name,
                'customer_address' => $parcel->customer_address,
                'customer_contact_number' => $parcel->customer_contact_number,
                'district_name' => $parcel->district->name,
                'upazila_name' => $parcel->upazila->name,
                'area_name' => $parcel->area->name,
                'product_details' => $parcel->product_details,

                'weight_package_name' => $parcel->weight_package->name,
                'weight_package_charge' => $parcel->weight_package_charge,
                'cod_percent' => $parcel->cod_percent,
                'collectable_amount' => $parcel->total_collect_amount ?? 0,
                'collected_amount' => $parcel->customer_collect_amount ?? 0,
                'cod_charge' => $parcel->cod_charge ?? 0,
                'delivery_charge' => $parcel->delivery_charge,
                'total_charge' => $parcel->total_charge,
                'parcel_payment_status' => $payment_status_name,
                'parcel_status' => $status_name,
            ];
        }

        return response()->json([
            'success' => 200,
            'message' => "Parcel Results",
            'parcels' => $new_parcels,
        ], 200);
    }


    public function getOrderTrackingResult(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'parcel_invoice' => 'sometimes',
            'merchant_order_id' => 'sometimes',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error.",
                'error' => $validator->errors(),
            ], 401);
        }

        $parcel_invoice = $request->input('parcel_invoice');
        $merchant_order_id = $request->input('merchant_order_id');

        if ((!is_null($parcel_invoice) && $parcel_invoice != '') || (!is_null($merchant_order_id) && $merchant_order_id != '')) {
            $parcel = Parcel::with([
                'district:id,name',
                'upazila:id,name',
                'area:id,name,post_code',
                'merchant' => function ($query) {
                    $query->select('m_id', 'name', 'email', 'image', 'company_name', 'address', 'contact_number', 'cod_charge', 'status');
                },
                'weight_package:id,name,title,weight_type',
                'pickup_branch:id,name,email,address,contact_number',
                'pickup_rider:id,name,email,address,contact_number',
                'delivery_branch:id,name,email,address,contact_number',
                'delivery_rider:id,name,email,address,contact_number',
                'return_branch:id,name,email,address,contact_number',
                'return_rider:id,name,email,address,contact_number',
            ])
                ->where('merchant_id', auth()->guard('merchant_api')->user()->id)
                ->where(function ($query) use ($parcel_invoice, $merchant_order_id) {
                    if (!is_null($parcel_invoice)) {
                        $query->where('parcel_invoice', 'like', "%$parcel_invoice");
                        $query->orWhere('merchant_order_id', 'like', "%$parcel_invoice");
                        $query->orWhere('customer_contact_number', 'like', "%$parcel_invoice");
                    } elseif (!is_null($merchant_order_id)) {
                        $query->where('merchant_order_id', 'like', "%$merchant_order_id");
                    }
                })
                ->first();

            if ($parcel) {

                $parcel_status = "";
                
                switch ($parcel->status) {
                    case 1 :
                        $parcel_status = "Parcel Send Pick Request";
                        break;
                    case 2 :
                        $parcel_status = "Parcel Hold";
                        break;
                    case 3 :
                        $parcel_status = "Parcel Cancel";
                        break;
                    case 4 :
                        $parcel_status = "Parcel Reschedule";
                        break;
                    case 5 :
                        $parcel_status = "Pickup Run Start";
                        break;
                    case 6 :
                        $parcel_status = "Pickup Run Create";
                        break;
                    case 7 :
                        $parcel_status = "Pickup Run Cancel";
                        break;
                    case 8 :
                        $parcel_status = "Pickup Run Accept Rider";
                        break;
                    case 9 :
                        $parcel_status = "Pickup Run Cancel Rider";
                        break;
                    case 10 :
                        $parcel_status = "Pickup Run Complete Rider";
                        break;
                    case 11 :
                        $parcel_status = "Pickup Complete";
                        break;
                    case 12 :
                        $parcel_status = "Assign Delivery Branch";
                        break;
                    case 13 :
                        $parcel_status = "Assign Delivery Branch Cancel";
                        break;
                    case 14 :
                        $parcel_status = "Assign Delivery Branch Received";
                        break;
                    case 15 :
                        $parcel_status = "Assign Delivery Branch Reject";
                        break;
                    case 16 :
                        $parcel_status = "Delivery Run Create";
                        break;
                    case 17 :
                        $parcel_status = "Delivery Run Start";
                        break;
                    case 18 :
                        $parcel_status = "Delivery Run Cancel";
                        break;
                    case 19 :
                        $parcel_status = "Delivery Run Rider Accept";
                        break;
                    case 20 :
                        $parcel_status = "Delivery Run Rider Reject";
                        break;
                    case 21 :
                        $parcel_status = "Delivery Rider Delivery";
                        break;
                    case 22 :
                        $parcel_status = "Delivery Rider Partial Delivery";
                        break;
                    case 23 :
                        $parcel_status = "Delivery Rider Reschedule";
                        break;
                    case 24 :
                        $parcel_status = "Delivery Rider Return";
                        break;
                    case 25 :
                        $parcel_status = "Delivery Complete";
                        break;
                    case 26 :
                        $parcel_status = "Return Branch Assign";
                        break;
                    case 27 :
                        $parcel_status = "Return Branch Assign Cancel";
                        break;
                    case 28 :
                        $parcel_status = "Return Branch Assign Received";
                        break;
                    case 29 :
                        $parcel_status = "Return Branch Assign Reject";
                        break;
                    case 30 :
                        $parcel_status = "Return Branch Run Create";
                        break;
                    case 31 :
                        $parcel_status = "Return Branch Run Start";
                        break;
                    case 32 :
                        $parcel_status = "Return Branch Run Cancel";
                        break;
                    case 33 :
                        $parcel_status = "Return Rider Accept";
                        break;
                    case 34 :
                        $parcel_status = "Return Rider Reject";
                        break;
                    case 35 :
                        $parcel_status = "Return Rider Complete";
                        break;
                    case 36 :
                        $parcel_status = "Return Branch Run Complete";
                        break;
                    default :
                        break;
                }

                $parcel->makeHidden([
                    'id', 'parcel_code', 'weight_package_id', 'merchant_id', 'district_id',
                    'upazila_id', 'area_id', 'pickup_branch_id', 'pickup_branch_user_id', 'pickup_rider_id',
                    'delivery_branch_id', 'delivery_branch_user_id', 'delivery_rider_id',
                    'return_branch_id', 'return_branch_user_id', 'return_rider_id',
                    'reschedule_parcel_date', 'created_admin_id', 'updated_admin_id', 'created_at', 'updated_at',
                ]);


                $parcelLogs = ParcelLog::with([
                    'pickup_branch:id,name',
                    'pickup_rider:id,name',
                    'delivery_branch:id,name',
                    'delivery_rider:id,name',
                    'return_branch:id,name',
                    'return_rider:id,name',
                    'admin:id,name',
                    'merchant:id,name',
                ])
                    ->where('parcel_id', $parcel->id)
                    // ->orderBy('id', 'desc')
                    ->get();

                $new_parcelLogs = [];

                foreach ($parcelLogs as $parcelLog) {
                    $to_user = "";
                    $from_user = "";
                    $status = "";

                    switch ($parcelLog->status) {
                        case 1 :
                            $status = "Parcel Send Pick Request";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                $to_user = "Merchant : " . $parcelLog->merchant->name;
                                $from_user = (!empty($parcelLog->pickup_branch)) ? "Pickup Branch : " . $parcelLog->pickup_branch->name : " ";
                            }
                            break;
                        case 2 :
                            $status = "Parcel Hold";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                $to_user = "Merchant : " . $parcelLog->merchant->name;
                            }
                            break;
                        case 3 :
                            $status = "Parcel Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->merchant)) {
                                    $to_user = "Merchant : " . $parcelLog->merchant->name;
                                }
                            }
                            break;
                        case 4 :
                            $status = "Parcel Reschedule";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 5 :
                            $status = "Pickup Run Start";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 6 :
                            $status = "Pickup Run Create";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 7 :
                            $status = "Pickup Run Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 8 :
                            $status = "Pickup Run Accept Rider";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                            }
                            break;
                        case 9 :
                            $status = "Pickup Run Cancel Rider";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                            }
                            break;
                        case 10 :
                            $status = "Pickup Run Complete Rider";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 11 :
                            $status = "Pickup Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 12 :
                            $status = "Assign Delivery Branch";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                                if (!empty($parcelLog->delivery_branch)) {
                                    $from_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 13 :
                            $status = "Assign Delivery Branch Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 14 :
                            $status = "Assign Delivery Branch Received";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 15 :
                            $status = "Assign Delivery Branch Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 16 :
                            $status = "Delivery Run Create";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 17 :
                            $status = "Delivery Run Start";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 18 :
                            $status = "Delivery Run Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 19 :
                            $status = "Delivery Run Rider Accept";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 20 :
                            $status = "Delivery Run Rider Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 21 :
                            $status = "Delivery Rider Delivery";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 22 :
                            $status = "Delivery Rider Partial Delivery";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 23 :
                            $status = "Delivery Rider Reschedule";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name . " (Reschedule Date : " . \Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') . ")";
                                }
                            }
                            break;
                        case 24 :
                            $status = "Delivery Rider Return";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 25 :
                            $status = "Delivery Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 26 :
                            $status = "Return Branch Assign";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                                if (!empty($parcelLog->return_branch)) {
                                    $from_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 27 :
                            $status = "Return Branch Assign Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 28 :
                            $status = "Return Branch Assign Received";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 29 :
                            $status = "Return Branch Assign Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 30 :
                            $status = "Return Branch Run Create";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 31 :
                            $status = "Return Branch Run Start";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 32 :
                            $status = "Return Branch Run Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 33 :
                            $status = "Return Rider Accept";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 34 :
                            $status = "Return Rider Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 35 :
                            $status = "Return Rider Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 36 :
                            $status = "Return Branch Run Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                    }

                    $new_parcelLogs[] = [
                        'to_user' => $to_user,
                        'from_user' => $from_user,
                        'status' => $status,
                        'date' => $parcelLog->date,
                        'time' => $parcelLog->time,
                        'note' => $parcelLog->note,
                    ];
                }

                $parcel->status = $parcel_status;

                return response()->json([
                    'success' => 200,
                    'message' => "Parcel found",
                    'parcel' => $parcel,
                    'parcelLogs' => $new_parcelLogs,
                ], 200);
            }

            return response()->json([
                'success' => 401,
                'message' => "Parcel Not found",
                'error' => "Parcel Not found",
            ], 401);
        }

        return response()->json([
            'success' => 401,
            'message' => "Validation Error.",
            'error' => "Parcel Invoice or Merchant Order id required",
        ], 401);
    }


    public function parcelStart(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        $data = [
            'status' => 1,
            'parcel_date' => date('Y-m-d'),
        ];
        $parcel = Parcel::where('id', '=', $request->parcel_id)
            ->whereRaw("(status = 2 or status = 4) and merchant_id = ?", [$merchant_id])
            ->update($data);
        if ($parcel) {
            $data = [
                'parcel_id' => $request->parcel_id,
                'merchant_id' => $merchant_id,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'status' => 1,
                'delivery_type' => $parcel->delivery_type,
            ];
            ParcelLog::create($data);

            return response()->json([
                'success' => 200,
                'message' => "Parcel Start Successfully",
                'parcel_id' => $parcel->id,
            ], 200);
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Parcel Start Unsuccessfully",
                'message' => "Parcel Start Unsuccessfully",
            ], 401);
        }
    }


    public function parcelHold(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        $data = [
            'status' => 2,
            'parcel_date' => date('Y-m-d'),
        ];
        $parcel = Parcel::where('id', '=', $request->parcel_id)
            ->whereRaw("(status = 1 or status = 4) and merchant_id = ?", [$merchant_id])
            ->update($data);
        if ($parcel) {
            $data = [
                'parcel_id' => $request->parcel_id,
                'merchant_id' => $merchant_id,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'status' => 2,
                'delivery_type' => $parcel->delivery_type,
            ];
            ParcelLog::create($data);

            return response()->json([
                'success' => 200,
                'message' => "Parcel Hold Successfully",
                'parcel_id' => $request->parcel_id,
            ], 200);
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Parcel Hold Unsuccessfully",
                'message' => "Parcel Hold Unsuccessfully",
            ], 401);
        }
    }


    public function parcelCancel(Request $request)
    {
        $merchant_id = auth()->guard('merchant_api')->user()->id;

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        $data = [
            'status' => 3,
            'parcel_date' => date('Y-m-d'),
        ];
        $parcel = Parcel::where('id', '=', $request->parcel_id)
            ->whereRaw("status < 10 and merchant_id = ?", [$merchant_id])
            ->update($data);
        if ($parcel) {
            $data = [
                'parcel_id' => $request->parcel_id,
                'merchant_id' => $merchant_id,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'status' => 3,
                'delivery_type' => $parcel->delivery_type,
            ];
            ParcelLog::create($data);

            return response()->json([
                'success' => 200,
                'message' => "Parcel Cancel Successfully",
                'parcel_id' => $parcel->id,
            ], 200);
        } else {
            return response()->json([
                'success' => 401,
                'message' => "Parcel Cancel Unsuccessfully",
                'message' => "Parcel Cancel Unsuccessfully",
            ], 401);
        }


    }


    public function viewParcel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }

        $parcel = Parcel::find($request->parcel_id);
        $parcel->load(
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'merchant:id,name,contact_number,address',
            'weight_package',
            'pickup_branch',
            'pickup_rider',
            'delivery_branch',
            'delivery_rider'
        );


        // $delivery_type = $parcel->delivery_type;
        // switch ($parcel->status) {
        //     case 1 :
        //         $parcel_status = "Pickup Request";
        //         break;
        //     case 2 :
        //         $parcel_status = "Parcel Hold";
        //         break;
        //     case 3 :
        //         $parcel_status = "Deleted";
        //         break;
        //     case 4 :
        //         $parcel_status = "Parcel Reschedule";
                
        //         break;
        //     case 5 :
        //         $parcel_status = "Assign for pickup";
        //         // $parcel_status = "Pickup Run Create";
        //         break;
        //     case 6 :
        //         $parcel_status = "Pickup Processing";
        //         // $parcel_status = "Pickup Run Start";
                
        //         break;
        //     case 7 :
        //         $parcel_status = "Pickup Processing";
        //         // $parcel_status = "Pickup Run Cancel";
                
        //         break;
        //     case 8 :
        //         $parcel_status = "Pickup Processing";
        //         // $parcel_status = "Pickup Run Accept Rider";

        //         break;
        //     case 9 :
        //         $parcel_status = "Pickup Processing";
        //         // $parcel_status = "Pickup Run Cancel Rider";

        //         break;
        //     case 10 :
        //         $parcel_status = "Picked Up";
        //         // $parcel_status = "Pickup Run Complete Rider";

        //         break;
        //     case 11 :
        //         $parcel_status = "Picked Up";
        //         break;
        //     case 12 :
        //         $parcel_status = "In Transit";
        //         // $parcel_status = "Assign Delivery Branch";
        //         break;
        //     case 13 :
        //         // $parcel_status = "Assign Delivery Branch Cancel";
        //         $parcel_status = "In Transit";
        //         break;
        //     case 14 :
        //         $parcel_status = "Branch Transfer"; 
        //         // $parcel_status = "Assign Delivery Branch Received";
        //         break;
        //     case 15 :
        //          $parcel_status = "In Transit";
        //         // $parcel_status = "Assign Delivery Branch Reject";
        //         break;
        //     case 16 :
        //         $parcel_status = "Rider Assigned";
        //         // $parcel_status = "Delivery Run Create";
        //         break;
        //     case 17 :
        //         $parcel_status = "Rider Assigned";
        //         // $parcel_status = "Delivery Run Start";
        //         break;
        //     case 18 :
        //         $parcel_status = "Out For Delivery";
        //         // $parcel_status = "Delivery Run Cancel";
        //         break;
        //     case 19 :
        //         $parcel_status = "Out For Delivery"; 
        //         // $parcel_status = "Delivery Run Rider Accept";
        //         break;
        //     case 20 :
        //         $parcel_status = "Rider Assigned";
        //         // $parcel_status = "Delivery Run Rider Reject";
        //         break;
        //     case 21 :
        //         $parcel_status = "Delivered";
        //         // $parcel_status = "Delivery Rider Delivery";
        //         break;
        //     case 22 :
        //         $parcel_status = "Partially Delivered";
        //         // $parcel_status = "Delivery Rider Partial Delivery";
        //         break;
        //     case 23 :
        //         $parcel_status = "Rescheduled";
        //         // $parcel_status = "Delivery Rider Reschedule";
        //         break;
        //     case 24 :
        //         $parcel_status = "Cancelled";
        //         // $parcel_status = "Delivery Rider Return";
        //         break;
                
                
        //     case 25 :
        //         // $parcel_status = "Delivered";
        //         // $parcel_status = "Delivery  Complete";
                
        //         if ($delivery_type == 1){
        //     $parcel_status  = "Delivered";
            
        //         }
        //         elseif($delivery_type == 2){
        //     $parcel_status  = "Partially Delivered";
          
        //         }
        //         elseif( $delivery_type == 3){
        //     $parcel_status  = "Rescheduled";
           
        //         }
        //     elseif( $delivery_type == 4){
        //     $parcel_status  = "Cancelled";
           
        //         } ;   
        //         break;
                
        //     case 26 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch Assign";
        //         break;
        //     case 27 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch Assign Cancel";
        //         break;
        //     case 28 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch Assign Received";
        //         break;
        //     case 29 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch Assign Reject";
        //         break;
        //     case 30 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch   Run Create";
        //         break;
        //     case 31 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch  Run Start";
        //         break;
        //     case 32 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Branch  Run Cancel";
        //         break;
        //     case 33 :
        //         $parcel_status = "Return on way to Merchant";
        //         // $parcel_status = "Return Rider Accept";
        //         break;
        //     case 34 :
        //         $parcel_status = "Returned Processing";
        //         // $parcel_status = "Return Rider Reject";
        //         break;
        //     case 35 :
        //         $parcel_status = "Returned";
        //         // $parcel_status = "Return Rider Complete";
        //         break;
        //     case 36 :
        //         $parcel_status = "Returned";
        //         // $parcel_status = "Return Branch  Run Complete";
        //         break;
        //     default :
        //         break;
        // }
                
        $parcelStatus = returnParcelStatusNameForMerchant($parcel->status, $parcel->delivery_type, $parcel->payment_type);
        $parcel_status = $parcelStatus['status_name'];
            
        
        
        // switch ($parcel->status) {
        //     case 1 :
        //         $parcel_status = "Parcel Send Pick Request";
        //         break;
        //     case 2 :
        //         $parcel_status = "Parcel Hold";
        //         break;
        //     case 3 :
        //         $parcel_status = "Parcel Cancel";
        //         break;
        //     case 4 :
        //         $parcel_status = "Parcel Reschedule";
        //         break;
        //     case 5 :
        //         $parcel_status = "Pickup Run Start";
        //         break;
        //     case 6 :
        //         $parcel_status = "Pickup Run Create";
        //         break;
        //     case 7 :
        //         $parcel_status = "Pickup Run Cancel";
        //         break;
        //     case 8 :
        //         $parcel_status = "Pickup Run Accept Rider";
        //         break;
        //     case 9 :
        //         $parcel_status = "Pickup Run Cancel Rider";
        //         break;
        //     case 10 :
        //         $parcel_status = "Pickup Run Complete Rider";
        //         break;
        //     case 11 :
        //         $parcel_status = "Pickup Complete";
        //         break;
        //     case 12 :
        //         $parcel_status = "Assign Delivery Branch";
        //         break;
        //     case 13 :
        //         $parcel_status = "Assign Delivery Branch Cancel";
        //         break;
        //     case 14 :
        //         $parcel_status = "Assign Delivery Branch Received";
        //         break;
        //     case 15 :
        //         $parcel_status = "Assign Delivery Branch Reject";
        //         break;
        //     case 16 :
        //         $parcel_status = "Delivery Run Create";
        //         break;
        //     case 17 :
        //         $parcel_status = "Delivery Run Start";
        //         break;
        //     case 18 :
        //         $parcel_status = "Delivery Run Cancel";
        //         break;
        //     case 19 :
        //         $parcel_status = "Delivery Run Rider Accept";
        //         break;
        //     case 20 :
        //         $parcel_status = "Delivery Run Rider Reject";
        //         break;
        //     case 21 :
        //         $parcel_status = "Delivery Rider Delivery";
        //         break;
        //     case 22 :
        //         $parcel_status = "Delivery Rider Partial Delivery";
        //         break;
        //     case 23 :
        //         $parcel_status = "Delivery Rider Reschedule";
        //         break;
        //     case 24 :
        //         $parcel_status = "Delivery Rider Return";
        //         break;
        //     case 25 :
        //         $parcel_status = "Delivery  Complete";
        //         break;
        //     case 26 :
        //         $parcel_status = "Return Branch Assign";
        //         break;
        //     case 27 :
        //         $parcel_status = "Return Branch Assign Cancel";
        //         break;
        //     case 28 :
        //         $parcel_status = "Return Branch Assign Received";
        //         break;
        //     case 29 :
        //         $parcel_status = "Return Branch Assign Reject";
        //         break;
        //     case 30 :
        //         $parcel_status = "Return Branch   Run Create";
        //         break;
        //     case 31 :
        //         $parcel_status = "Return Branch  Run Start";
        //         break;
        //     case 32 :
        //         $parcel_status = "Return Branch  Run Cancel";
        //         break;
        //     case 33 :
        //         $parcel_status = "Return Rider Accept";
        //         break;
        //     case 34 :
        //         $parcel_status = "Return Rider Reject";
        //         break;
        //     case 35 :
        //         $parcel_status = "Return Rider Complete";
        //         break;
        //     case 36 :
        //         $parcel_status = "Return Branch  Run Complete";
        //         break;
        //     default :
        //         break;
        // }

        $new_parcel = [
            'id' => $parcel->id,
            'parcel_invoice' => $parcel->parcel_invoice,
            'merchant_order_id' => $parcel->merchant_order_id,
            'merchant_name' => $parcel->merchant->name,
            'merchant_contact_number' => $parcel->merchant->contact_number,
            'merchant_address' => $parcel->merchant->address,
            'customer_name' => $parcel->customer_name,
            'customer_address' => $parcel->customer_address,
            'customer_contact_number' => $parcel->customer_contact_number,
            'district_name' => $parcel->district->name,
            'upazila_name' => $parcel->upazila->name,
            'area_name' => $parcel->area->name,
            'weight_package_name' => $parcel->weight_package->name,
            'weight_package_charge' => $parcel->weight_package_charge,
            'cod_percent' => $parcel->cod_percent,
            'collectable_amount' => $parcel->total_collect_amount ?? 0,
            'cod_charge' => $parcel->cod_charge ?? 0,
            // 'cod_charge' => isset($parcel->cod_charge) ? (float)$parcel->cod_charge : 0.0,
            'delivery_charge' => isset($parcel->delivery_charge) ? (float)$parcel->delivery_charge : 0.0,


            // 'delivery_charge' => $parcel->delivery_charge,
            'total_charge' => $parcel->total_charge,
            'parcel_note' => $parcel->parcel_note,
            'parcel_status' => $parcel_status,
        ];
        
        
        
        
        
        
        
        
        
        
        
        
        
                $parcelLogs = ParcelLog::with([
                    'pickup_branch:id,name',
                    'pickup_rider:id,name',
                    'delivery_branch:id,name',
                    'delivery_rider:id,name',
                    'return_branch:id,name',
                    'return_rider:id,name',
                    'admin:id,name',
                    'merchant:id,name',
                ])
                    ->where('parcel_id', $parcel->id)
                    // ->orderBy('id', 'desc')
                    ->get();

                $new_parcelLogs = [];

                foreach ($parcelLogs as $parcelLog) {
                    $to_user = "";
                    $from_user = "";
                    $status = "";

                    switch ($parcelLog->status) {
                        case 1 :
                            $status = "Pickup Request";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                $to_user = "Merchant : " . $parcelLog->merchant->name;
                                $from_user = (!empty($parcelLog->pickup_branch)) ? "Pickup Branch : " . $parcelLog->pickup_branch->name : " ";
                            }
                            break;
                        case 2 :
                            $status = "Parcel Hold";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                $to_user = "Merchant : " . $parcelLog->merchant->name;
                            }
                            break;
                        case 3 :
                            $status = "Deleted";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->merchant)) {
                                    $to_user = "Merchant : " . $parcelLog->merchant->name;
                                }
                            }
                            break;
                            
                        case 4 :
                            $status = "Parcel Reschedule";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break; 
                            
                        case 5 :
                            $status = "Assign for pickup";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                            
                        case 6 :
                            $status = "Way for pickup";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                            
                            
                        case 7 :
                            $status = "Pickup Processing";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 8 :
                            $status = "Pickup Processing";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                            }
                            break;
                        case 9 :
                            $status = "Pickup Processing";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                            }
                            break;
                        case 10 :
                            $status = "Rider Picked Up";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_rider)) {
                                    $to_user = "Pickup Rider : " . $parcelLog->pickup_rider->name;
                                }
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 11 :
                            $status = "Picked Up";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user .= "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 12 :
                            $status = "Assign Delivery Branch";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                                if (!empty($parcelLog->delivery_branch)) {
                                    $from_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 13 :
                            $status = "Delivery Branch Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->pickup_branch)) {
                                    $to_user = "Pickup Branch : " . $parcelLog->pickup_branch->name;
                                }
                            }
                            break;
                        case 14 :
                            $status = "At Destination Hub";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 15 :
                            $status = "Delivery Branch Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 16 :
                            $status = "Delivery Rider Assign";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 17 :
                            $status = "Delivery Run Start";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 18 :
                            $status = "Delivery Run Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 19 :
                            $status = "Rider Accept";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 20 :
                            $status = "Rider Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 21 :
                            $status = "Rider Delivery";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 22 :
                            $status = "Partial Delivery";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                        case 23 :
                            $status = "Rider Reschedule";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name . " (Reschedule Date : " . \Carbon\Carbon::parse($parcelLog->reschedule_parcel_date)->format('d/m/Y') . ")";
                                }
                            }
                            break;
                        case 24 :
                            $status = "Rider Return";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_rider)) {
                                    $to_user = "Delivery Rider : " . $parcelLog->delivery_rider->name;
                                }
                            }
                            break;
                            
                            
                            case 25 :
                                        
                                        
                                            if($parcelLog->delivery_type == 1){
                                                $status  = "Delivered";
                                                $class        = "success";
                                                if(!empty($parcelLog->admin)){
                                                    $to_user    = "Admin : ".$parcelLog->admin->name;
                                                }
                                                elseif($parcelLog->delivery_branch){
                                                    $to_user    = !empty($parcelLog->delivery_branch)? "Delivery Branch : ".$parcelLog->delivery_branch->name : "";
                                                }
                                    
                                            }
                                            elseif($parcelLog->delivery_type == 2){
                                                $status  = "Partial Delivered";
                                                $class        = "success";
                                    
                                    
                                            }
                                            elseif($parcelLog->delivery_type == 3){
                                                $status  = "Rescheduled";
                                                $class        = "success";
                                            }
                                            elseif($parcelLog->delivery_type == 4){
                                                $status  = "Delivery Cancel";
                                                $class        = "success";
                                            } else{
                                                $status  = "Delivery Rider Run Complete(unknown)";
                                                $class        = "success";
                                            }
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                        
                                            break;
                                            
                                            
                                            
                      
                            
                            
                        case 26 :
                            $status = "Return Branch Assign";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                                if (!empty($parcelLog->return_branch)) {
                                    $from_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 27 :
                            $status = "Return Branch Assign Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->delivery_branch)) {
                                    $to_user = "Delivery Branch : " . $parcelLog->delivery_branch->name;
                                }
                            }
                            break;
                        case 28 :
                            $status = "Return Branch Received";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 29 :
                            $status = "Return Branch Assign Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 30 :
                            $status = "Return Branch Run Create";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 31 :
                            $status = "Return Rider Assign";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 32 :
                            $status = "Return Branch Run Cancel";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                        case 33 :
                            $status = "Return Rider Accept";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 34 :
                            $status = "Return Rider Reject";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 35 :
                            $status = "Return Rider Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_rider)) {
                                    $to_user = "Return Rider : " . $parcelLog->return_rider->name;
                                }
                            }
                            break;
                        case 36 :
                            $status = "Return Branch Run Complete";
                            if (!empty($parcelLog->admin)) {
                                $to_user = "Admin : " . $parcelLog->admin->name;
                            } else {
                                if (!empty($parcelLog->return_branch)) {
                                    $to_user = "Return Branch : " . $parcelLog->return_branch->name;
                                }
                            }
                            break;
                    }
            if($status!="Delivery Run Start" 
            && $status!="" 
            && $status!="Delivery Run Rider Accept" 
            && $status!="Rider Accept"  
            && $status!="Rider Accept"
            && $status!="Rider Reject" 
            && $status!="Rider Delivery" 
            && $status!="Rider Reschedule" 
            && $status!="Rider Picked Up"
            && $status!="Return Branch Run Create"
            && $status!="Return Rider Reject"
            && $status!="Return Branch Assign"
            && $status!="Assign Delivery Branch"
            && $status!="Delivery Run Cancel" 
            
            
            ){
                    $new_parcelLogs[] = [
                        'to_user' => $to_user,
                        'from_user' => $from_user,
                        'status' => $status,
                        'date' => $parcelLog->date,
                        'time' => \Carbon\Carbon::parse($parcelLog->time)->format('h:i:s A'),
                        'note' => $parcelLog->note,
                    ];
                }}
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
      $new_parcelLogs = array_reverse($new_parcelLogs);
  
        

        return response()->json([
            'success' => 200,
            'message' => "Parcel Results",
            'parcels' => $new_parcel,
            'parcel' => $parcel,
            'parcelLogs' => $new_parcelLogs,
        ], 200);

    }

    public function editParcel(Request $request)
    {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'parcel_id' => 'required',
            'merchant_order_id' => 'sometimes',
            'cod_percent' => 'required',
            'cod_charge' => 'required',
            'delivery_charge' => 'required',
            'weight_package_charge' => 'required',
            'merchant_service_area_charge' => 'required',
//            'merchant_service_area_return_charge' => 'required',
            'total_charge' => 'required',
            'weight_package_id' => 'required',
//            'delivery_option_id' => 'required',
            'product_details' => 'required',
            'total_collect_amount' => 'sometimes',
            'customer_name' => 'required',
            'customer_contact_number' => 'required|numeric|digits:11',
            'customer_address' => 'required',
            'district_id' => 'required',
            // 'upazila_id'                   => 'required',
            'area_id' => 'sometimes',
            'parcel_note' => 'sometimes',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => 401,
                'message' => "Validation Error",
                'error' => $validator->errors(),
            ], 401);
        }
        \DB::beginTransaction();
        try {
            $parcel = Parcel::find($request->input("parcel_id"));
            $merchant = auth()->guard('merchant_api')->user();
            // Set District, Upazila, Area ID and Merchant Service Area Charge
            $merchant_service_area_charge = 0;
            $delivery_charge = 0;
            $merchant_service_area_return_charge = 0;
            $weight_package_charge = 0;
            $cod_charge = 0;
            $service_area_id = 0;
            $cod_percent = 0;
            $merchant_cod_percent = $merchant->cod_charge ?? 0;
            $district_id = $request->input('district_id');
            $weight_id = $request->input('weight_package_id');
            $collection_amount = $request->input('total_collect_amount');

            $district = District::with('service_area:id,cod_charge,default_charge')->where('id', $district_id)->first();

            if ($district) {

                $service_area_id = $district->service_area_id;
                //Service Area Default Charges
                $delivery_charge = $district->service_area ? $district->service_area->default_charge : 0;


                // Check Merchant COD Percent
                if ($district->service_area->cod_charge != 0) {
                    $cod_percent = ($merchant_cod_percent != 0) ? $merchant_cod_percent : $district->service_area->cod_charge;
                }

                $code_charge_percent = $district->service_area->cod_charge;
                if($code_charge_percent != 0){
                    $merchantServiceAreaCodCharge = MerchantServiceAreaCodCharge::where([
                        'service_area_id' => $district->service_area_id,
                        'merchant_id'     => $merchant->id,
                    ])->first();

                    if ($merchantServiceAreaCodCharge) {
                        $cod_percent = $merchantServiceAreaCodCharge->cod_charge;
                    }
                }


                $merchantServiceAreaCharge = MerchantServiceAreaCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                $merchantServiceAreaReturnCharge = MerchantServiceAreaReturnCharge::where([
                    'service_area_id' => $service_area_id,
                    'merchant_id' => $merchant->id,
                ])->first();


                if ($merchantServiceAreaCharge && !empty($merchantServiceAreaCharge->charge)) {
                    $merchant_service_area_charge = $merchantServiceAreaCharge->charge;

                }


                //Set Default Return Charge 1/2 of Delivery Charge
                $merchant_service_area_return_charge = $merchant_service_area_charge / 2;
                if ($merchantServiceAreaReturnCharge && !empty($merchantServiceAreaReturnCharge->return_charge)) {
                    //Set Return Charge Merchant Wise
                    $merchant_service_area_return_charge = $merchantServiceAreaReturnCharge->return_charge;
                }

            }


            // Weight Package Charge
            if ($weight_id) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
            }

            if (empty($weightPackage) || is_null($weight_id)) {
                $weightPackage = WeightPackage::with([
                    'service_area' => function ($query) use ($service_area_id) {
                        $query->where('service_area_id', '=', $service_area_id);
                    },
                ])
                    ->where(['id' => $weight_id])
                    ->first();

                $weight_package_charge = $weightPackage->rate;
                if (!empty($weightPackage->service_area)) {
                    $weight_package_charge = $weightPackage->service_area->rate;
                }
                $weight_id = $weightPackage->id;
            }

            /**
             * Set Parcel Delivery Charge
             * If Merchant service area is not 0 then check District Area default Delivery charge
             */
            $delivery_charge = $merchant_service_area_charge != 0 ? $merchant_service_area_charge : $delivery_charge;


            $collection_amount = $collection_amount ?? 0;
            if ($collection_amount != 0 && $cod_percent != 0) {
                $cod_charge = ($collection_amount / 100) * $cod_percent;
            }

            $item_type_charge = $request->input('item_type_charge')??0;
            $service_type_charge=$request->input('service_type_charge')??0;
            $delivery_charge =  $delivery_charge+$item_type_charge+$service_type_charge;
            $total_charge = $delivery_charge + $cod_charge + $weight_package_charge;

            $data = [
                'merchant_order_id' => $request->input('merchant_order_id'),
                'pickup_address' => $request->input('pickup_address'),
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'customer_contact_number' => $request->input('customer_contact_number'),
                'product_details' => $request->input('product_details'),
                'district_id' => $request->input('district_id'),
                'area_id' => $request->input('area_id') ?? 0,
                'weight_package_id' => $weight_id,
                'delivery_charge' => $delivery_charge,
                'weight_package_charge' => $weight_package_charge,
                'merchant_service_area_charge' => $merchant_service_area_charge,
                'merchant_service_area_return_charge' => $merchant_service_area_return_charge,
                'total_collect_amount' => $collection_amount,
                'cod_percent' => $cod_percent,
                'cod_charge' => $cod_charge,
                'total_charge' => $total_charge,
                'parcel_note' => $request->input('parcel_note'),
                'service_type_id' => $request->input('service_type_id') == 0 ? null : $request->input('service_type_id'),
                'item_type_id' => $request->input('item_type_id') == 0 ? null : $request->input('item_type_id'),
                'item_type_charge' => $request->input('item_type_charge'),
                'service_type_charge' => $request->input('service_type_charge'),
            ];

            $check = Parcel::where('id', $parcel->id)->update($data);


            if ($check) {
                $data = [
                    'parcel_id' => $parcel->id,
                    'merchant_id' => $merchant->id,
                    'pickup_branch_id' => $merchant->branch_id,
                    'date' => date('Y-m-d'),
                    'time' => date('H:i:s'),
                    'status' => 1,
                ];
                ParcelLog::create($data);

                \DB::commit();

                return response()->json([
                    'success' => 200,
                    'message' => "Parcel updated Successfully",
                    'parcel_id' => $parcel->id,
                ], 200);
            } else {
                return response()->json([
                    'success' => 401,
                    'message' => "Parcel update Unsuccessfully",
                    'error' => "Parcel update Unsuccessfully",
                ], 401);
            }

        } catch (\Exception $exception) {
            \DB::rollback();
            return response()->json([
                'success' => 401,
                'message' => "Parcel update Unsuccessfully",
                'error' => $exception->getMessage(),
            ], 401);
        }
    }

}
