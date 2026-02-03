
<!DOCTYPE html>
<html>
    <head>
        <title>Income/Expense  | {{ session()->get('company_name') ?? config('app.name', 'Flier Express') }}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link href='https://fonts.googleapis.com/css?family=Anaheim' rel='stylesheet'>
        <link href='https://fonts.googleapis.com/css?family=IBM Plex Mono' rel='stylesheet'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>

            body{
                font-size: 10px !important;
            }

            .col-md-1 {width:8%;  float:left;}
            .col-md-2 {width:16%; float:left;}
            .col-md-3 {width:25%; float:left;}
            .col-md-4 {width:33%; float:left;}
            .col-md-5 {width:42%; float:left;}
            .col-md-6 {width:50%; float:left;}
            .col-md-7 {width:58%; float:left;}
            .col-md-8 {width:66%; float:left;}
            .col-md-9 {width:75%; float:left;}
            .col-md-10{width:83%; float:left;}
            .col-md-11{width:92%; float:left;}
            .col-md-12{width:100%; float:left;}

            .table>tbody>tr>td,
            .table>tbody>tr>th,
            .table>tfoot>tr>td,
            .table>tfoot>tr>th,
            .table>thead>tr>td,
            .table>thead>tr>th {
                padding: 2px;
                line-height: 1;
            }
            .table {
                margin-bottom: .0rem;
            }

            .table td, .table th {
                padding: .0rem;
            }

        </style>
    </head>
	<script type="text/javascript">
        window.print();
        window.onafterprint = function(event){
			window.close();
        };
	</script>

    <body>
        <div class="col-md-12" style="margin-top: 60px;">
            <div class="col-md-4">
                <table width="100%" style="margin-top: 3rem">
                    <thead>
                        <tr>
                            <td class="text-center text-bold">
                                <img src="{{ asset('uploads/application/') . '/' . session()->get('company_photo') }}" style="width: 65%; height: 60px">
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
           <div class="col-md-4">
                <table class="table table-bordered" width="100%" style="margin-top: 3rem" >
                    <caption class="text-center text-bold">
                        <span style="font-size: 16px; font-weight: bold">
                            Income & Expense 
                        </span>
                       <p> <B>Eyecon Courier</B></p>
                        <p>Date :  @php
                          echo  date('Y-m-d');
                        @endphp </p>
                    </caption>
                    
                   
                    
                </table>
            </div> 
            {{-- <div class="col-md-4">
                <table class="table table-bordered" width="100%" style="margin-top: 3rem" >
                    <caption class="text-center text-bold">
                        <span style="font-size: 16px; font-weight: bold">
                            Merchant
                        </span>
                    </caption>
                    <tr>
                        <th style="width: 40%"> Name </th>
                        <td style="width: 10%"> : </td>
                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->company_name }} </td>
                    </tr>
                    <tr>
                        <th style="width: 40%"> Contact </th>
                        <td style="width: 10%"> : </td>
                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->contact_number }} </td>
                    </tr>
                    <tr>
                        <th style="width: 40%"> Address </th>
                        <td style="width: 10%"> : </td>
                        <td style="width: 50%"> {{ $parcelMerchantDeliveryPayment->merchant->address }} </td>
                    </tr>
                </table>
            </div> --}}
        </div>

        <div class="col-md-12" style="margin-top: 20px;">
          
                <table class="table table-bordered" width="100%" style="margin-top: 3rem">
                    <thead>
                        <tr>
        
                            <th width="10%" class="text-center"> Date</th>
                            <th width="10%" class="text-center"> Type</th>
                            <th width="15%" class="text-center">  Expense Head Name </th>
                            <th width="10%" class="text-right"> Amount </th>
                             <th width="10%" class="text-center"> Note </th>
                            
                         
                           
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"> {{$model->date }} </td>
                            <td>
                                @if ( $model->type == 1)
                                    <span
                                        class="text-center">Expense</span>
                                @elseif ( $model->type == 2)
                                    <span
                                        class="text-center">Income</span>
                                @else
                                    <span class="text-center">N/A</span>
                                @endif
                            </td>
                            <td class="text-center"> {{$model->expense_heads->name }} </td>
                           
                            <td class="text-center"> {{$model->amount }} </td>
                            <td class="text-center"> {{$model->note}} </td>
                          
                        </tr>
                       
                    </tbody>
                </table>

                <table class="table" width="100%" >
                    <tbody>
                        <tr>
                            {{-- <th width="33%" class="text-center">
                                <br><br><br>
                                <span style="border-top:2px solid black; font-weight:bold ">
                                   &nbsp;&nbsp;&nbsp; Merchant Signature &nbsp;&nbsp;&nbsp;
                                </span>
                            </th> --}}
                            <th width="33%" class="text-center"> </th>
                            <th width="33%" class="text-center">
                                <br><br><br>
                                <span style="border-top:2px solid black; font-weight:bold ">
                                    &nbsp;&nbsp;&nbsp; Authority &nbsp;&nbsp;&nbsp;
                                </span>
                            </th>
                        </tr>
                    </tbody>
                </table>
        
        </div>

         

    </body>
</html>
