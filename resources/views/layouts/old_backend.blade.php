<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--    <title>{{$st['name']['value']}}</title>--}}
    <title>ES Trading Account</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap.min.css')}}">
    @yield('style')
    <!-- Fontawsome -->
    <link rel="stylesheet" href="{{asset('assets/backend/vendor/fontawesome/all.css')}}">
    <!-- Main css -->
    <link rel="stylesheet" href="{{asset('assets/backend/css/main.css')}}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{asset('assets/backend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/select2.min.css')}}">
    <style>
        .note-editable ul {
            list-style: disc inside !important;
        }

        .note-editable ol {
            list-style: decimal inside !important;
        }

        .modal {
            z-index: 99999;
        }

        fieldset.scheduler-border {
            border: 1px solid #0062cc !important;
            padding: 10px !important;
            margin: 10px !important;
            border-radius: 5px;
        }

        legend.scheduler-border {
            font-customer: 1.2em !important;
            /* font-weight: bold !important; */
            text-align: center !important;
            background: #2A3F54;
            width: 50%;
            color: rgb(255, 255, 255);
            border-radius: 5px;
        }

        @media (min-width: 768px) {
            .modal-xl {
                width: 90%;
                max-width: 1200px;
            }
        }

        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #bacae3;
        }
        .navbar-nav .nav-link {
            padding-right: 0;
            padding-left: 0;
            background: #bacae3;
        }
        li .item-active {
            background: #9eabc1;
        }
        ul.navbar-nav > li > a {
            margin: -1px 0.5rem;
            border: 1px solid #000;
        }
        .nav-link.active {
            border-radius: 0;
            background: #5897fb;
        }
    </style>
</head>

<body>
<!-- sideBar wrapper -->
@include('backend.partials.sidebar')

<!-- content wrapper-->
<div class="content-wrapper sideBars_open">
    <!-- top head start -->
    @include('backend.partials.header')

    <!-- Main content start -->
    <div class="main_content">
        <!-- content area -->
        <div class="container-fluid">
            @yield('main')

            {{--            <audio id="yourAudioTag" src="{{asset('audio/notification.mp3')}}" ></audio>--}}
            {{--            <video id="yourAudioTag" src="{{asset('audio/notification.mp3')}}" autoplay></video>--}}
        </div>
    </div>
</div>

<!-- footer section -->
<div class="footer_section">
    <audio id="yourAudioTag">
        <source src="{{asset('audio/notification.mp3')}}" type="audio/mpeg">
    </audio>
    Copyright @2022 all reserved by <a href="https://estradingcorporation.com/" target="_blank">ES Trading
        Corporation. </a> Developed by <a href="https://stitbd.com/" target="_blank">STITBD</a>
</div>

<!--Start javascript -->
<!-- jQuery.min.js -->
<script type="text/javascript" src="{{asset('assets/backend/js/jquery.min.js')}}"></script>
<!-- Bootstrap js -->
<script type="text/javascript" src="{{asset('assets/backend/js/popper.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/backend/js/bootstrap.min.js')}}"></script>
<!-- Optional js  -->
<script type="text/javascript" src="{{asset('assets/backend/js/main.js')}}"></script>
<script src="{{asset('assets/backend/js/toastr.min.js')}}"></script>
<script src="{{asset('assets/backend/js/select2.min.js')}}"></script>

{!! Toastr::message() !!}
<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
@yield('script')

<script>

    (function worker() {
        var url = "{{ route('getDashboardCounter') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#totalPending').text(data.totalPending);
                $('#totalApproved').text(data.totalApproved);
                $('#totalOrder').text(data.totalOrder);
            },
            complete: function () {
                setTimeout(worker, 5000);
            }
        });
    })();

    (function order_notification() {
        var url = "{{ route('getOrderNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#order-notification').html(data);
            },
            complete: function () {
                setTimeout(order_notification, 5000);
            }
        });
    })();
    (function pending_notification() {
        var url = "{{ route('getPendingNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#pending-notification').html(data);
            },
            complete: function () {
                setTimeout(pending_notification, 5000);
            }
        });
    })();
    (function approved_notification() {
        var url = "{{ route('getApprovedNotification') }}";
        $.ajax({
            url: url,
            success: function (data) {
                // console.log(data)
                $('#approved-notification').html(data);
            },
            complete: function () {
                setTimeout(approved_notification, 5000);
            }
        });
    })();

    function numberWithCommas(y) {
        x = (Math.round(y * 100) / 100).toFixed(2);
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
</script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    // Pusher.logToConsole = true;
    var pusher = new Pusher('980a393ed57cf973b1bf', {
        cluster: 'ap2'
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('transaction-alert', function (data) {
        notifyMe(JSON.parse(data.hello));
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }
        if (Notification.permission !== 'granted')
            Notification.requestPermission();
    });
    function notifyMe(data) {
        // console.log(data)
        if (Notification.permission !== 'granted')
            Notification.requestPermission();
        else {
            var notification = new Notification(data.title, {
                icon: 'https://estradingcorporation.com/upload/images/setting/setting16538878495495.jpeg',
                body: data.message,
            });
            notification.onclick = function() {
                window.open(data.url);
            };
        }
    }
</script>
</body>

</html>
