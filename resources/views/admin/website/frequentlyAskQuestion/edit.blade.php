@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Frequently Ask Questions</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.teamMember.index') }}">Frequently Ask Question</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Edit Frequently Ask Question </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.frequentlyAskQuestion.update', $frequentlyAskQuestion->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')

                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="question">Question</label>
                                                <input type="text" name="question" id="question" value="{{ $frequentlyAskQuestion->question ?? old('question') }}" class="form-control" placeholder="Question" >
                                            </div>
                                            <div class="form-group">
                                                <label for="answer">Answer</label>
                                                <textarea name="answer" id="answer" class="form-control"
                                                    placeholder="Enter Answer">{{ $frequentlyAskQuestion->answer ??  old('answer') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('style_css')

@endpush

@push('script_js')
    <script>
        function editForm() {

        }

    </script>

@endpush
