@extends('layouts.backend')
@section('main')
    <!-- breadcame start -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <div class="page-breadcrumb">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}" class="breadcrumb-link"><span
                                        class="p-1 text-sm text-light bg-success rounded-circle"><i
                                            class="fa fa-home"></i></span> Dashboard</a></li>
                            {{--                            <li class="breadcrumb-item active" aria-current="page">{{$st['name']['value']}}</li>--}}
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- dashboard body content -->
    {{--<div class="dashboard mb-4">
        <div class="content_section">
            @if(check_permission('overview counter'))
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Overview</legend>
                    <div class="row">
                        @if(check_permission('dashboard client transaction'))
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        Client Transaction
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table  table-striped table-info table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                --}}{{--                                                <th scope="col">T. No</th>--}}{{--
                                                <th scope="col">Client Name</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($clientTransactions as $key=>$clientTransaction )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    --}}{{--                                                    <td>{{$clientTransaction->transaction_no}}</td>--}}{{--
                                                    <td>{{$clientTransaction->client->name}}</td>
                                                    <td class=" {{$clientTransaction->transaction_type == "debit"?'text-danger':''}}">{{number_format($clientTransaction->bdt_amount,2)}}
                                                        BDT
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{route('transaction.client')}}">
                                        <div class="card-footer text-muted">
                                            <p>
                                                View All(<span
                                                    class="text-danger">{{$countClientTransaction>99?"99+":$countClientTransaction}}</span>)
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(check_permission('dashboard internal transaction'))
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        Internal Transaction
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table  table-striped table-warning table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                --}}{{--                                                <th scope="col">T. No</th>--}}{{--
                                                <th scope="col">U. Name</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($internalTransactions as $key=>$internalTransaction )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    --}}{{--                                                    <td>{{$internalTransaction->transaction_no}}</td>--}}{{--
                                                    <td>{{$internalTransaction->user->name}}</td>
                                                    <td class=" {{$internalTransaction->transaction_type == "debit"?'text-danger':''}}">{{number_format($internalTransaction->bdt_amount,2)}}
                                                        BDT
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{route('transaction.internal')}}">
                                        <div class="card-footer text-muted">
                                            <p>
                                                View All(<span
                                                    class="text-danger">{{$countInternalTransaction>99?"99+":$countInternalTransaction}}</span>)
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(check_permission('dashboard other transaction'))
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        Other Transaction
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table  table-striped table-secondary table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
--}}{{--                                                <th scope="col">T. No</th>--}}{{--
                                                <th scope="col">Remarks</th>
                                                <th scope="col">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($otherTransactions as $key=>$otherTransaction )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
--}}{{--                                                    <td>{{$otherTransaction->transaction_no}}</td>--}}{{--
                                                    <td>{{$otherTransaction->remarks}}</td>
                                                    <td class=" {{$otherTransaction->transaction_type == "debit"?'text-danger':''}}">{{number_format($otherTransaction->bdt_amount,2)}}
                                                        BDT
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{route('transaction.other')}}">
                                        <div class="card-footer text-muted">
                                            <p>
                                                View All(<span
                                                    class="text-danger">{{$countOtherTransaction>99?"99+":$countOtherTransaction}}</span>)
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(check_permission('dashboard client overview'))
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        Client Overview
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped table-success table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Client Name</th>
                                                <th scope="col">Credit</th>
                                                <th scope="col">Debit</th>
                                                <th scope="col">Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $c_total_debit = 0;
                                                $c_total_credit = 0;
                                                $c_total_balance = 0;
                                            @endphp
                                            @foreach($clients as $key=>$client )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    <td>{{$client->name}}</td>
                                                    <td>{{number_format($client->credit,2)}} BDT</td>
                                                    <td class="text-danger">{{number_format($client->debit,2)}} BDT</td>
                                                    <td class=" {{($client->credit-$client->debit) < 0?'text-danger':''}}">{{number_format($client->credit-$client->debit,2)}}
                                                        BDT
                                                    </td>
                                                </tr>
                                                @php
                                                    $c_total_debit += $client->debit;
                                                    $c_total_credit += $client->credit;
                                                    $c_total_balance += ($client->credit-$client->debit);
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <th scope="col" colspan="2" class="text-right">Totals</th>
                                                <th scope="col">{{number_format($c_total_credit,2)}} BDT</th>
                                                <th scope="col">{{number_format($c_total_debit,2)}} BDT</th>
                                                <th scope="col">{{number_format($c_total_balance,2)}} BDT</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{route('client')}}">
                                        <div class="card-footer text-muted">
                                            <p>
                                                View All
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(check_permission('dashboard user overview'))
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        User Overview
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped table-danger table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">User Name</th>
                                                <th scope="col">Credit</th>
                                                <th scope="col">Debit</th>
                                                <th scope="col">Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $u_total_debit = 0;
                                                $u_total_credit = 0;
                                                $u_total_balance = 0;
                                            @endphp
                                            @foreach($users as $key=>$user )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{number_format($user->credit+$user->internal_credit,2)}} BDT</td>
                                                    <td class="text-danger">{{number_format($user->debit+$user->internal_debit,2)}} BDT</td>
                                                    <td class=" {{(($user->credit+$user->internal_credit)-($user->debit+$user->internal_debit)) < 0?'text-danger':''}}">{{number_format(($user->credit+$user->internal_credit)-($user->debit+$user->internal_debit),2)}}
                                                        BDT
                                                    </td>
                                                </tr>
                                                @php
                                                    $u_total_debit += $user->debit+$user->internal_debit;
                                                    $u_total_credit += $user->credit+$user->internal_credit;
                                                    $u_total_balance += (($user->credit + $user->internal_credit)-($user->debit+$user->internal_debit));
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <th scope="col" colspan="2" class="text-right">Totals</th>
                                                <th scope="col">{{number_format($u_total_credit,2)}} BDT</th>
                                                <th scope="col">{{number_format($u_total_debit,2)}} BDT</th>
                                                <th scope="col">{{number_format($u_total_balance,2)}} BDT</th>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <a href="{{route('user')}}">
                                        <div class="card-footer text-muted">
                                            <p>
                                                View All
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if(check_permission('dashboard currency transaction'))
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
                                <div class="card text-center">
                                    <div class="card-header">
                                        Currency Transactions
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-striped table-primary table-bordered"
                                               style="white-space: nowrap">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Currency</th>
                                                <th scope="col">Credit</th>
                                                <th scope="col">Debit</th>
                                                <th scope="col">Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($currencies as $key=>$currency )
                                                <tr>
                                                    <th scope="row">{{$key+1}}</th>
                                                    <td>{{$currency->name}}</td>
                                                    <td>{{number_format(($currency->credit+$currency->internal_credit),2)." ".$currency->code}}</td>
                                                    <td class="text-danger">{{number_format($currency->debit+$currency->internal_debit,2)." ".$currency->code}}</td>
                                                    <td class=" {{(($currency->credit+$currency->internal_credit)-($currency->debit+$currency->internal_debit)) < 0?'text-danger':''}}">{{number_format(($currency->credit+$currency->internal_credit)-($currency->debit+$currency->internal_debit),2)." ".$currency->code}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        --}}{{--   <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                           <div class="card bg-info p-4 my-2">
                               <div class="card_left text-center">
                                   <p class="text-light">Today Pending Transaction</p>
                                   <h5 class="text-light">{{$pendingTodayTransaction}}</h5>
                               </div>
                           </div>
                       </div>
                       <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                           <div class="card bg-primary p-4 my-2">
                               <div class="card_left text-center">
                                   <p class="text-light">Today Pending Credit</p>
                                   <h5 class="text-light">{{$pendingTodayCredit}}</h5>
                               </div>
                           </div>
                       </div>
                       <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                           <div class="card bg-danger p-4 my-2">
                               <div class="card_left text-center">
                                   <p class="text-light">Today Pending Debit</p>
                                   <h5 class="text-light">{{$pendingTodayDebit}}</h5>
                               </div>
                           </div>
                       </div>
                       <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                           <div class="card bg-success p-4 my-2">
                               <div class="card_left text-center">
                                   <p class="text-light">Today Pending Balance</p>
                                   <h5 class="text-light">{{$pendingTodayCredit-$pendingTodayDebit}}</h5>
                               </div>
                           </div>
                       </div>--}}{{--
                    </div>
                    --}}{{--

                                        <div class="row">
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-info p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Today Approved Transaction</p>
                                                        <h5 class="text-light">{{$todayApprovedTransaction}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-primary p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Credit</p>
                                                        <h5 class="text-light">{{$todayApprovedCredit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-danger p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Debit</p>
                                                        <h5 class="text-light">{{$todayApprovedDebit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-success p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Balance</p>
                                                        <h5 class="text-light">{{$totalApprovedCredit-$totalApprovedDebit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-info p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Transaction</p>
                                                        <h5 class="text-light">{{$totalApprovedTransaction}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-primary p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Credit</p>
                                                        <h5 class="text-light">{{$totalApprovedCredit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-danger p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Debit</p>
                                                        <h5 class="text-light">{{$totalApprovedDebit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                                <div class="card bg-success p-4 my-2">
                                                    <div class="card_left text-center">
                                                        <p class="text-light">Total Approved Balance</p>
                                                        <h5 class="text-light">{{$totalApprovedCredit-$totalApprovedDebit}}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                    --}}{{--

                </fieldset>
            @endif
            --}}{{--
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Your Overview</legend>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-info p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Today Pending Transaction</p>
                                            <h5 class="text-light">{{$yourTodayPendingTotalTransaction}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-primary p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Today Pending Total Credit</p>
                                            <h5 class="text-light">{{$yourTodayPendingTotalCredit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-danger p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Today Pending Total Debit</p>
                                            <h5 class="text-light">{{$yourTodayPendingTotalDebit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-success p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Today Pending Balance</p>
                                            <h5 class="text-light">{{$yourTodayPendingTotalCredit-$yourTodayPendingTotalDebit}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- card content start -->
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-info p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Pending Transaction</p>
                                            <h5 class="text-light">{{$yourPendingTotalTransaction}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-primary p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Pending Credit</p>
                                            <h5 class="text-light">{{$yourPendingTotalCredit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-danger p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Pending Debit</p>
                                            <h5 class="text-light">{{$yourPendingTotalDebit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-success p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Pending Balance</p>
                                            <h5 class="text-light">{{$yourPendingTotalCredit - $yourPendingTotalDebit}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-info p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Total Transaction</p>
                                            <h5 class="text-light">{{$yourTotalTransaction}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-primary p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Total Credit</p>
                                            <h5 class="text-light">{{$yourTotalCredit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-danger p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Total Debit</p>
                                            <h5 class="text-light">{{$yourTotalDebit}}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                                    <div class="card bg-success p-4 my-2">
                                        <div class="card_left text-center">
                                            <p class="text-light">Your Rejected Transaction</p>
                                            <h5 class="text-light">{{$yourRejectedTransaction}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </fieldset>--}}{{--
        </div>
    </div>--}}
    <!-- end -->
@endsection
