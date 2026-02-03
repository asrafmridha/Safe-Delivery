<?php

namespace App\Exports;

use App\Models\BookingParcel;
use App\Models\Parcel;
use App\Models\ParcelMerchantDeliveryPayment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;

class MerchantDeliveryPaymentExport implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    WithProperties
{

    private $count;
    protected $id;

    public function __construct($id)
    {
        $this->id        = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $id=$this->id;
        $merchant_id = auth()->guard('merchant')->user()->id;
        $parcelMerchantDeliveryPayment = ParcelMerchantDeliveryPayment::where('id',$id)->with('admin', 'merchant', 'parcel_merchant_delivery_payment_details')->first();
        // dd($parcelMerchantDeliveryPayment);

        $total_collect_amount=0;
        $collected_amount=0;
        $weight_package_charge=0;
        $cod_charge=0;
        $delivery_charge=0;
        $return_charge=0;
        $total_charge=0;
        $paid_amount=0;
        $data_parcel_array=[];
        
        foreach ($parcelMerchantDeliveryPayment->parcel_merchant_delivery_payment_details as $key => $parcel_merchant_delivery_payment_detail) {
            
            $parcelStatus = returnParcelStatusNameForMerchant($parcel_merchant_delivery_payment_detail->parcel->status, $parcel_merchant_delivery_payment_detail->parcel->delivery_type, $parcel_merchant_delivery_payment_detail->parcel->payment_type);

            $data_parcel_array[] = (object)[
                'serial' => $key + 1,
                'parcel_invoice' => $parcel_merchant_delivery_payment_detail->parcel->parcel_invoice,
                'order_id' => $parcel_merchant_delivery_payment_detail->parcel->merchant_order_id ?? "---",
                'status' => $parcelStatus['status_name'],
                'delivery_branch_date' => date('d-M-Y', strtotime($parcel_merchant_delivery_payment_detail->parcel->delivery_branch_date)),

                'customer_name' => $parcel_merchant_delivery_payment_detail->parcel->customer_name,
                'customer_contact_number' => $parcel_merchant_delivery_payment_detail->parcel->customer_contact_number,
                
                'total_collect_amount' => $parcel_merchant_delivery_payment_detail->parcel->total_collect_amount,
                'collected_amount' => $parcel_merchant_delivery_payment_detail->collected_amount,
                'weight_package_charge' => $parcel_merchant_delivery_payment_detail->weight_package_charge,
                'cod_charge' => $parcel_merchant_delivery_payment_detail->cod_charge,
                'delivery_charge' => $parcel_merchant_delivery_payment_detail->delivery_charge,
                'return_charge' => $parcel_merchant_delivery_payment_detail->return_charge,
                'total_charge' => ($parcel_merchant_delivery_payment_detail->parcel->total_charge+$parcel_merchant_delivery_payment_detail->return_charge),
                'paid_amount' => $parcel_merchant_delivery_payment_detail->paid_amount,
                
            ];
            
            $total_collect_amount+=$parcel_merchant_delivery_payment_detail->parcel->total_collect_amount;
            $collected_amount+=$parcel_merchant_delivery_payment_detail->collected_amount;
            $weight_package_charge+=$parcel_merchant_delivery_payment_detail->weight_package_charge;
            $cod_charge+=$parcel_merchant_delivery_payment_detail->cod_charge;
            $delivery_charge+=$parcel_merchant_delivery_payment_detail->delivery_charge;
            $return_charge+=$parcel_merchant_delivery_payment_detail->return_charge;
            $total_charge+=($parcel_merchant_delivery_payment_detail->parcel->total_charge+$parcel_merchant_delivery_payment_detail->return_charge);
            $paid_amount+=$parcel_merchant_delivery_payment_detail->paid_amount;
        }

        $data_parcel_array[] = (object)[
            'serial' => '',
            'parcel_invoice' => "",
            'order_id' => "",
            'status' => "",
            'delivery_branch_date' => "",
            'customer_name' => "",
            'customer_contact_number' => "Total: ",
            
            'total_collect_amount' => $total_collect_amount,
            'collected_amount' => $collected_amount,
            'weight_package_charge' => $weight_package_charge,
            'cod_charge' => $cod_charge,
            'delivery_charge' => $delivery_charge,
            'return_charge' => $return_charge,
            'total_charge' => $total_charge,
            'paid_amount' => $paid_amount,
            
        ];

        return new Collection($data_parcel_array);


    }

    public function map($row): array
    {
        return  [
            $row->serial,
            $row->parcel_invoice,
            $row->order_id,
            $row->status,
            $row->delivery_branch_date,
            $row->customer_name,
            $row->customer_contact_number,
            $row->total_collect_amount,
            $row->collected_amount,
            $row->weight_package_charge,
            $row->cod_charge,
            $row->delivery_charge,
            $row->return_charge,
            $row->total_charge,
            $row->paid_amount,
        ];
    }

    public function headings(): array
    {
        return [
            'Serial',
            'Parcel Invoice',
            'Order ID',
            'Status',
            'Delivery Date',
            'Customer Name',
            'Customer Contact Number',
            'Amount to be Collect',
            'Collected Amount',
            'Weight Charge',
            'COD Charge',
            'Delivery Charge',
            'Return Charge',
            'Total Charge',
            'Paid Amount',
        ];
    }

    public function properties(): array
    {
        return [
//            'creator'        => 'Patrick Brouwers',
//            'lastModifiedBy' => 'Patrick Brouwers',
            'title'             => 'Merchant Delivery Payment Parcel List',
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
            AfterSheet::class    => function(AfterSheet $event) {

                $event->sheet->getStyle('A1:O1')->applyFromArray([
                    'font'  => [
                        'bold'  => true,
                    ]
                ]);

//                $event->sheet->getStyle('A'.$this->count.':K'.$this->count)->applyFromArray([
//                    'font'  => [
//                        'bold'  => true,
//                    ]
//                ]);

                if('pdf' == "pdf") {

                    foreach(range('B','Z') as $columnID) {
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
