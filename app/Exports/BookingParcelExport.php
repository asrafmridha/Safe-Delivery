<?php

namespace App\Exports;

use App\Models\BookingParcel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Events\AfterSheet;

class BookingParcelExport implements
    FromCollection,
    ShouldAutoSize,
    WithMapping,
    WithHeadings,
    WithEvents,
    WithProperties
{

    private $count;
    protected $branch_id,
        $booking_type,
        $delivery_type,
        $status,
        $from_date,
        $to_date,
        $download_type;

    public function __construct($request)
    {
            $this->branch_id        = $request->branch_id;
            $this->booking_type     = $request->booking_type;
            $this->delivery_type    = $request->delivery_type;
            $this->status           = $request->status;
            $this->from_date        = $request->from_date;
            $this->to_date          = $request->to_date;
            $this->download_type    = $request->download_type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $data = [
            'branch_id'     => $this->branch_id,
            'booking_type'     => $this->booking_type,
            'delivery_type'     => $this->delivery_type,
            'status'     => $this->status,
            'from_date'     => $this->from_date,
            'to_date'     => $this->to_date,
        ];
        $booking_parcel_lists  = BookingParcel::with(['sender_branch', 'receiver_branch', 'receiver_warehouses', 'sender_warehouses'])
            ->where(function ($query) use ($data) {
                $branch_id = $data['branch_id'];
                $booking_parcel_type = $data['booking_type'];
                $booking_delivery_type = $data['delivery_type'];
                $booking_status = $data['status'];
                $from_date  = $data['from_date'];
                $to_date    = $data['to_date'];

                if (!is_null($branch_id) && $branch_id != '') {
                    $query->where('sender_branch_id', $branch_id);
                }
                if (!is_null($booking_parcel_type) && $booking_parcel_type != '') {
                    $query->where('booking_parcel_type', $booking_parcel_type);
                }
                if (!is_null($booking_delivery_type) && $booking_delivery_type != '') {
                    $query->where('delivery_type', $booking_delivery_type);
                }
                if (!is_null($booking_status) && $booking_status != '') {
                    $query->where('status', $booking_status);
                }
                if (!is_null($from_date) && $from_date != '') {
                    $query->whereDate('booking_date', '>=', $from_date);
                }
                if (!is_null($to_date) && $to_date != '') {
                    $query->whereDate('booking_date', '<=', $to_date);
                }
            })
            ->get();
        $data_parcel_array  = [];
        if(count($booking_parcel_lists) > 0) {

            foreach ($booking_parcel_lists as $booking_parcel) {

                switch ($booking_parcel->delivery_type) {
                    case 'hd':$delivery_type  = "HD"; $class="success"; break;
                    case 'thd':$delivery_type  = "THD"; $class="info"; break;
                    case 'od':$delivery_type  = "OD"; $class="primary"; break;
                    case 'tod':$delivery_type  = "TOD"; $class="warning"; break;
                    default:$delivery_type = "None"; $class = "danger";break;
                }
                $receiver_warehouse_name = ($booking_parcel->receiver_warehouses) ? $booking_parcel->receiver_warehouses->name : 'Warehouse';
                switch ($booking_parcel->status) {
                    case 0:$status_name    = "Parcel Reject from operation"; $class  = "danger";break;
                    case 1:$status_name    = "Confirmed Booking"; $class  = "success";break;
                    case 2:$status_name    = "Vehicle Assigned"; $class   = "success";break;
                    case 3:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 4:$status_name    = "Warehouse Received Parcel"; $class  = "success";break;
                    case 5:$status_name    = "Assign $receiver_warehouse_name"; $class  = "success";break;
                    case 6:$status_name    = "Wait for destination branch receive"; $class  = "success";break;
                    case 7:$status_name    = "Destination branch received parcel"; $class  = "success";break;
                    case 8:$status_name    = "Parcel Complete Delivery"; $class = "success";break;
                    case 9:$status_name    = "Parcel Return Delivery"; $class = "success";break;
                    //    case 10:$status_name = "Delivery Branch Assign Rider"; $class = "success";break;
                    //    case 11:$status_name = "Delivery  Rider Accept"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Complete"; $class = "success";break;
                    //    case 12:$status_name = "Delivery Rider Reschedule"; $class = "success";break;
                    default:$status_name = "None"; $class = "success";break;
                }

                $data_parcel_array[]    = (object)[
                    'booking_date'      => $booking_parcel->booking_date,
                    'parcel_code'       => $booking_parcel->parcel_code,
                    'sender_phone'      => $booking_parcel->sender_phone,
                    'sender_branch'     => $booking_parcel->sender_branch->name,
                    'receiver_phone'    => $booking_parcel->receiver_phone,
                    'receiver_branch'   => $booking_parcel->receiver_branch->name,
                    'delivery_type'     => $delivery_type,
                    'status'            => $status_name,
                    'net_amount'        => number_format((float) $booking_parcel->net_amount, 2, '.', ''),
                ];
            }
        }


        return new Collection($data_parcel_array);


    }

    public function map($row): array
    {
        return  [
            $row->booking_date,
            $row->parcel_code,
            $row->sender_phone,
            $row->sender_branch,
            $row->receiver_phone,
            $row->receiver_branch,
            $row->delivery_type,
            $row->status,
            $row->net_amount,
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'C/N No',
            'Sender Phone',
            'Sender Branch',
            'Receiver Phone',
            'Receiver Branch',
            'Delivery Type',
            'Status',
            'Amount',
        ];
    }

    public function properties(): array
    {
        return [
//            'creator'        => 'Patrick Brouwers',
//            'lastModifiedBy' => 'Patrick Brouwers',
            'title'             => 'Booking Parcel List',
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

                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font'  => [
                        'bold'  => true,
                    ]
                ]);

//                $event->sheet->getStyle('A'.$this->count.':K'.$this->count)->applyFromArray([
//                    'font'  => [
//                        'bold'  => true,
//                    ]
//                ]);

                if('pdf' == $this->download_type) {

                    foreach(range('B','K') as $columnID) {
                        $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(false);
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
