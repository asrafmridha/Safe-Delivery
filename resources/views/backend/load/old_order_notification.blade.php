<button class="dropdown-toggle notification" type="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
    Order <i class="far fa-bell"></i><sup class="bg-danger px-1  rounded-circle">{{count($orders)}}</sup>
</button>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-xl">
    <div class="dropdown_heading">
        <p>You have <strong class="text-primary">{{count($orders)}}</strong> orders.</p>
        <!-- list group -->
        @foreach($orders as $key=>$order)
            <a href="{{route('transaction.order')}}" class="list-group-item">
                <div class="clearfix">
                    <div class="float-left">
                        <h6>{{$order->transaction_no}}</h6>
                    </div>
                    <div class="float-right">
                        <p class="text-muted text-sm">{{nicetime($order->updated_at)}}</p>
                    </div>
                </div>
                <p>Client Name: {{optional($order->client)->name}},
                    Amount: {{$order->amount." ".$order->currency->code}} </p>
            </a>
            @php
                if ($key>=4){
                 break;
                }
            @endphp
        @endforeach
        <!-- view all -->
        <a href="{{route('transaction.order')}}" class="view_all">view all</a>
    </div>
</div>
