@extends('layouts.branch_layout.branch_layout')


@push('style_css')
    <style>
        #newsbar {
            height: 40px;
            overflow: hidden;
            position: relative;
            background: #ccc;
            margin: 20px 0;
        }

        .news-item {
            line-height: 38px;
            display: inline-block;
        }
    </style>

@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('branch.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="newsbar">
                <marquee onMouseOver="stop()" onMouseOut="start()">
                    @if($news)
                        <h3 class="news-item"><a href="#" class="view-news-modal" data-toggle="modal" data-target="#viewNewsModal" details="{{ $news->short_details }}">{{ $news->title }}</a></h3>
                    @else
                        <h3 class="news-item">Don't have any news</h3>
                    @endif
                </marquee>
            </div>
        </div>
    </div>
      <div class="row admin_client_info justify-content-center">
            <div class="col-lg-3 col-6 text-right" style="padding: 0px 5px !important;">
                <button type="button" class="btn btn-primary btn-block" href="#" style=" font-family: cursive; font-size: large; border-radius: 1.5rem; margin-bottom: 15px; ">
                    Current Balance ({{$balance}}) Tk
                </button>
            </div>
        </div>


            <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- AREA CHART -->
            <div class="card card-primary" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Area Chart</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- DONUT CHART -->
            <div class="card card-danger" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Parcel Data</h3>

                <!--<div class="card-tools">-->
                <!--  <button type="button" class="btn btn-tool" data-card-widget="collapse">-->
                <!--    <i class="fas fa-minus"></i>-->
                <!--  </button>-->
                <!--  <button type="button" class="btn btn-tool" data-card-widget="remove">-->
                <!--    <i class="fas fa-times"></i>-->
                <!--  </button>-->
                <!--</div>-->
              </div>
              <div class="card-body">
                <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- PIE CHART -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Parcel Statistics</h3>

                <div class="card-tools">
               
                </div>
              </div>
              <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
          <!-- /.col (LEFT) -->
          <div class="col-md-6">
            <!-- LINE CHART -->
            <div class="card card-info" style="display: none;">
              <div class="card-header">
                <h3 class="card-title">Line Chart</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- BAR CHART -->
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">Last 7 Days Parcel</h3>

                <!--<div class="card-tools">-->
                <!--  <button type="button" class="btn btn-tool" data-card-widget="collapse">-->
                <!--    <i class="fas fa-minus"></i>-->
                <!--  </button>-->
                <!--  <button type="button" class="btn btn-tool" data-card-widget="remove">-->
                <!--    <i class="fas fa-times"></i>-->
                <!--  </button>-->
                <!--</div>-->
              </div>
              <div class="card-body">
                <div class="chart">
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- STACKED BAR CHART -->
            <!-- /.card -->

          </div>
          <!-- /.col (RIGHT) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>

    <branch-dashboard-counter :userid="{{ auth()->guard('branch')->user()->branch_id }}" :counters="{{ $counter_data }}"></branch-dashboard-counter>
    
    

    <div class="modal fade" id="viewNewsModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 id="news_title" class="modal-title">View Notice Or News Details</h4>
                    <button type="button" class="close bg-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="showResult">

                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection






<script id="fa" src="{{asset('js/Chart.min.js')}}"></script> 

    @push('script_js')
    
    <script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

    var areaChartData = {
      labels  : ['Today', 'Yesterday', '2 days ago', '3 days ago', '4 days ago', '5 days ago', '6 days ago'],
      datasets: [
        {
          label               : 'Deliverd',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [
                                {{ $today_total_delivery }}, 
                                {{ $yesterdayDeliveryComplete }}, 
                                {{ $twoDeliveryComplete }}, 
                                {{ $threeDeliveryComplete }}, 
                                {{ $fourDeliveryComplete }}, 
                                {{ $fiveDeliveryComplete }}, 
                                {{ $sixDeliveryComplete }}]
        },
        {
          label               : 'Pickup',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ $today_total_pickupcomplete }}, 
                                 {{ $yesterdayPickupcomplete }}, 
                                 {{ $towDaysAgoPickupcomplete }}, 
                                 {{ $threeDaysAgoPickupcomplete }}, 
                                 {{ $fourDaysAgoPickupcomplete }}, 
                                 {{ $fiveDaysAgoPickupcomplete }}, 
                                 {{ $sixDaysAgoPickupcomplete }}]
        },
      ]
    }

    var areaChartOptions = {
      maintainAspectRatio : false,
      responsive : true,
      legend: {
        display: false
      },
      scales: {
        xAxes: [{
          gridLines : {
            display : false,
          }
        }],
        yAxes: [{
          gridLines : {
            display : false,
          }
        }]
      }
    }

    // This will get the first returned node in the jQuery collection.
    new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
      type: 'line',
      data: lineChartData,
      options: lineChartOptions
    })



    //-------------
    //- DONUT CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: [
          'Parcel Booking ({{ $parcelbooking }})',
          'Delivered ({{ $totalDelivered }})',
          'Partially Delivered({{ $partialDelivered }})',
          'Processing ({{ $deliveryProcessing }})',
          'Cancelled ({{ $deliveryCancle }})',
          'Deleted ({{ $deleted}})',
      ],
      datasets: [
        {
          data: [
              {{ $parcelbooking }},
              {{ $totalDelivered }},
              {{ $partialDelivered }},
              {{ $deliveryProcessing }},
              {{ $deliveryCancle }},
              {{ $deleted}}],
          backgroundColor : ['#3c8dbc', '#00a65a', '#f39c12', '#00c0ef', '#f56954', '#d2d6de'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })
    
    
    

    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false
    }

    new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })
    
    
    
    

    //---------------------
    //- STACKED BAR CHART -
    //---------------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
    var stackedBarChartData = $.extend(true, {}, barChartData)

    var stackedBarChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      scales: {
        xAxes: [{
          stacked: true,
        }],
        yAxes: [{
          stacked: true
        }]
      }
    }

    new Chart(stackedBarChartCanvas, {
      type: 'bar',
      data: stackedBarChartData,
      options: stackedBarChartOptions
    })
  })
</script>
    @endpush


@push('script_js')
    <script>
        $(document).ready(function () {
            $(".view-news-modal").on("click", function () {
                var title = $(this).text();
                var details = $(this).attr('details');
                $("#news_title").html(title);
                $("#showResult").html(details);
            });
        })
    </script>
@endpush
