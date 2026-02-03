<?php

namespace App\Http\Controllers;

use App\Exports\BookingParcelExport;
use App\Models\BookingParcel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class BookingParcelExportController extends Controller
{
    private $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function bookingParcelListExport(Request $request)
    {
//        dd($request->all());
        if("pdf" == $request->download_type) {
            $file_name = "booking_parcel_list_" . Carbon::now()->format("Y_m_d") . ".pdf";
            return $this->excel->download(new BookingParcelExport($request), $file_name, Excel::DOMPDF);
        }else {
            $file_name = "booking_parcel_list_" . Carbon::now()->format("Y_m_d") . ".xlsx";
            return $this->excel->download(new BookingParcelExport($request), $file_name);
        }
    }
}
