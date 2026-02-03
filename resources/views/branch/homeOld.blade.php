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
