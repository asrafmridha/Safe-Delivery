<?php

namespace App\Exports;

use App\Models\BookingParcel;
use App\Models\Parcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;

class AdminParcelExport implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    WithProperties
{

    private $count;
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $request = $this->request;


        $model = Parcel::with([
            'district:id,name',
            'upazila:id,name',
            'area:id,name',
            'weight_package:id,name',
            'merchant:id,name,company_name,address',
            'parcel_logs' => function ($query) {
                $query->select('id', 'note');
            },
        ])
            ->orderBy('id', 'desc')
            ->select();

        $filter = [];

        $parcel_status = $request->ex_parcel_status;
        if ($request->has('ex_parcel_status') && !is_null($parcel_status) && $parcel_status != 0) {
            if ($parcel_status == 1) {
                // $model->whereRaw('status >= 25 and delivery_type in (1,2) and payment_type IS NULL');
                $model->whereRaw('delivery_branch_id != "" and status >= 25 and delivery_type in (1,2)');
            } elseif ($parcel_status == 2) {
                // $query->whereRaw('status in (14,16,17,18,19,20,21,22,23,24 ) and delivery_type not in (1,2,4)');
                // $model->whereRaw('status > 11 and delivery_type in (?)', [3]);
                $model->whereRaw('delivery_branch_id != "" and status >= 11 and status <= 25 and delivery_type IS NULL OR (status in (23,25) and delivery_type = 3)');
            } elseif ($parcel_status == 3) {
                // $query->whereRaw('status = 3');
                $model->whereRaw('status >= ? and delivery_type in (?,?)', [25, 2, 4]);
            } elseif ($parcel_status == 4) {
                $model->whereRaw('status >= 25 and payment_type = 5 and delivery_type in(1,2)');
            } elseif ($parcel_status == 5) {
                $model->whereRaw('status >= 25 and payment_type >= 4  and payment_type in (4, 6) and delivery_type in(1,2)');
            } elseif ($parcel_status == 6) {
                // $query->whereRaw('status = 36 and delivery_type = 4');
                $model->whereRaw('status = ? and delivery_type in (?,?)', [36, 2, 4]);
            } elseif ($parcel_status == 7) {
                $model->whereRaw('status in (1) and delivery_type IS NULL');
            } elseif ($parcel_status == 8) {
                $model->whereRaw('status in (14)');
            } elseif ($parcel_status == 9) {
                $model->whereRaw('status in (11,13,15)');
            } elseif ($parcel_status == 10) {
                $model->whereRaw('status in (12)');
            } elseif ($parcel_status == 11) {
                $model->whereRaw('status in (21)');
            } elseif ($parcel_status == 12) {
                $model->whereRaw('status >= 25 and delivery_type in(3)');
            }
            $filter['parcel_status'] = $parcel_status;
        }

        if ($request->has('ex_merchant_id') && !is_null($request->get('ex_merchant_id')) && $request->get('ex_merchant_id') != 0) {
            $model->where('merchant_id', $request->get('ex_merchant_id'));
            $filter['merchant_id'] = $request->get('ex_merchant_id');
        }
        if ($request->has('ex_delivery_branch_id') && !is_null($request->get('ex_delivery_branch_id')) && $request->get('ex_delivery_branch_id') != 0) {
            $model->where('delivery_branch_id', $request->get('ex_delivery_branch_id'));
            $filter['delivery_branch_id'] = $request->get('ex_delivery_branch_id');
        }

        if ($request->has('ex_parcel_invoice') && !is_null($request->get('ex_parcel_invoice')) && $request->get('ex_parcel_invoice') != 0) {
            $model->where('parcel_invoice', $request->get('ex_parcel_invoice'));
            $filter['parcel_invoice'] = $request->get('ex_parcel_invoice');
        }

        if ($request->has('ex_merchant_order_id') && !is_null($request->get('ex_merchant_order_id')) && $request->get('ex_merchant_order_id') != 0) {
            $model->where('merchant_order_id', $request->get('ex_merchant_order_id'));
            $filter['merchant_order_id'] = $request->get('ex_merchant_order_id');
        }

        if ($request->has('ex_customer_contact_number') && !is_null($request->get('ex_customer_contact_number')) && $request->get('ex_customer_contact_number') != 0) {
            $model->where('customer_contact_number', $request->get('ex_customer_contact_number'));
            $filter['customer_contact_number'] = $request->get('ex_customer_contact_number');
        }

        if ($request->has('ex_from_date') && !is_null($request->get('ex_from_date')) && $request->get('ex_from_date') != 0) {
            $model->whereDate('date', '>=', $request->get('ex_from_date'));
            $filter['from_date'] = $request->get('ex_from_date');
        }

        if ($request->has('ex_to_date') && !is_null($request->get('ex_to_date')) && $request->get('ex_to_date') != 0) {
            $model->whereDate('date', '<=', $request->get('ex_to_date'));
            $filter['to_date'] = $request->get('ex_to_date');
        }

        $parcels = $model->get();
        $data_parcel_array = [];
        if (count($parcels) > 0) {
            foreach ($parcels as $key => $parcel) {
                $parcelStatus = returnParcelStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $status_name = $parcelStatus['status_name'];
                $parcelPaymentStatus = returnPaymentStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $payment_status_name = $parcelPaymentStatus['status_name'];
                $parcelReturnStatus = returnReturnStatusForAdmin($parcel->status, $parcel->delivery_type, $parcel->payment_type);
                $return_status_name = $parcelReturnStatus['status_name'];

                $logs_note = "";
                if ($parcel->parcel_logs) {
                    foreach ($parcel->parcel_logs as $parcel_log) {
                        if ("" != $logs_note && $parcel_log->note) {
                            $logs_note .= ", \n";
                        }
                        $logs_note .= $parcel_log->note;
                    }
                }

                $data_parcel_array[] = (object)[
                    // 'serial' => $key + 1,
                    'parcel_invoice' => $parcel->parcel_invoice,
                    'merchant_order_id' => $parcel->merchant_order_id,
                    'date' => date('d M Y', strtotime($parcel->date)),
                    'status' => $status_name,
                    'parcel_date' => date('d M Y', strtotime($parcel->parcel_date)),
//                    'parcel_code' => $parcel->parcel_code,
                    'company_name' => $parcel->merchant->company_name,
                    'customer_name' => $parcel->customer_name,
                    'customer_contact_number' => $parcel->customer_contact_number,
                    'customer_address' => $parcel->customer_address,
                    'district_name' => $parcel->district->name,
                    'area_name' => $parcel->area->name,
                    'service_type' => optional($parcel->service_type)->title,
                    'item_type' => optional($parcel->item_type)->title,
                    'total_collect_amount' => $parcel->total_collect_amount,
                    'customer_collect_amount' => $parcel->customer_collect_amount,
                    'cod_charge' => $parcel->cod_charge,
                    'total_charge' => $parcel->total_charge,
                    'parcel_note' => $parcel->parcel_note,
                    'logs_note' => $logs_note,
                    'payment_status_name' => $payment_status_name,
                    'return_status_name' => $return_status_name,
                ];
            }
        }


        return new Collection($data_parcel_array);


    }

    public function map($row): array
    {
        return [
            // $row->serial,
            $row->parcel_invoice,
            $row->merchant_order_id,
            $row->date,
            $row->status,
            $row->parcel_date,
//            $row->parcel_code,
            $row->company_name,
            $row->customer_name,
            $row->customer_contact_number,
            $row->customer_address,
            $row->district_name,
            $row->area_name,
            $row->service_type,
            $row->item_type,
            $row->total_collect_amount,
            $row->customer_collect_amount,
            $row->cod_charge,
            $row->total_charge,
            $row->parcel_note,
            $row->logs_note,
            $row->payment_status_name,
            $row->return_status_name,

        ];
    }

    public function headings(): array
    {
        return [
            // 'serial',
            'Parcel Invoice',
            'Merchant Order Id',
            'Parcel Date',
            'Status',
            'Last Update Date',
//            'parcel_code',
            'Company Name',
            'Customer Name',
            'Customer Contact Number',
            'Customer Address',
            'District Name',
            'Area Name',
            'Service Type',
            'Item Type',
            'Amount to be Collect',
            'Collected',
            'COD Charge',
            'Total Charge',
            'Parcel Note',
            'Logs Note',
            'Payment status',
            'Return Status',
        ];
    }

    public function properties(): array
    {
        return [
//            'creator'        => 'Patrick Brouwers',
//            'lastModifiedBy' => 'Patrick Brouwers',
            'title' => 'Admin Parcel List',
//            'description'    => 'Latest Invoices',
//            'subject'        => 'Invoices',
//            'keywords'       => 'invoices,export,spreadsheet',
//            'category'       => 'Invoices',
//            'manager'        => 'Patrick Brouwers',
//            'company'        => 'Maatwebsite',
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->getStyle('A1:Z1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ]
                ]);

//                $event->sheet->getStyle('A'.$this->count.':K'.$this->count)->applyFromArray([
//                    'font'  => [
//                        'bold'  => true,
//                    ]
//                ]);

                if ('pdf' == "pdf") {

                    foreach (range('B', 'Z') as $columnID) {
                        $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                    }

                    $event->sheet->getDelegate()->getPageSetup()
                        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                }

//                $event->sheet->getStyle(
//                    'B2:G8',
//                    [
//                        'borders' => [
//                            'outline' => [
//                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
//                                'color' => ['argb' => 'FFFF0000'],
//                            ],
//                        ]
//                    ]
//                );

            },
        ];
    }
}
