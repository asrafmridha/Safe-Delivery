<?php

namespace App\Http\Controllers\Export;

use App\Exports\MerchantPickupParcelReportExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class MerchantPickupParcelReportExportController extends Controller
{
    private $excel;
    public function __construct(Excel $excel)
    {
        $this->excel    = $excel;
    }

    public function exportReport(Request $request)
    {

        $file_name = "merchant_pickup_parcel_report_" . Carbon::now()->format("Y_m_d") . ".xlsx";
        return $this->excel->download(new MerchantPickupParcelReportExport($request), $file_name);

    }
}
