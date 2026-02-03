<a data-toggle="dropdown" class="dropdown-toggle" href="#">
    <i class="ace-icon fa fa-bell icon-animated-bell"></i>
    <span class="badge badge-important" style="background-color: #ffffff;color: green">{{count($orders)}}</span>
</a>

<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
    <li class="dropdown-content">
        <ul class="dropdown-menu dropdown-navbar">
            <li>
                <p>You have <strong class="text-primary">{{count($orders)}}</strong> approved. <a
                        href="{{route('transaction.approved')}}">View All</a></p>
            </li>
            @foreach($orders as $key=>$order)
                <li>
                    <a href="#" class="clearfix">
                        <span class="msg-body" style="margin-left: 0">
                            <span class="msg-title">
                                <span class="blue">{{$order->transaction_no}}:</span>
                                Client Name: {{optional($order->client)->name}}, Amount: {{$order->amount." ".$order->currency->code}}
                            </span>
                            <span class="msg-time">
                                <i class="ace-icon fa fa-clock-o"></i>
                                <span>{{nicetime($order->updated_at)}}</span>
                            </span>
                        </span>
                    </a>
                </li>
                @php
                    if ($key>=4){
                     break;
                    }
                @endphp
            @endforeach
            {{--<li>
                <a href="{{route('transaction.approved')}}">View All</a>
            </li>--}}
        </ul>
    </li>
</ul>

