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
                                            class="fas fa-home"></i></span> Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Info Setting</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- data table start -->
    <div class="data_table my-4">
        <div class="content_section">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Key</th>
                    <th scope="col">Value</th>
                    <th scope="col">Text</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($settings as $key=>$setting)
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$setting->key}}</td>
                        <td>{{$setting->value}}</td>
                        <td>{{substr($setting->text, 0, 25) . '...'}}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#specificationModal-{{$setting->id}}">
                                <i class="fa fa-edit"></i>
                            </button>

                            <div class="modal fade" id="specificationModal-{{$setting->id}}"
                                 tabindex="-1"
                                 aria-labelledby="specificationModalLabel-{{$setting->id}}"
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"
                                                id="specificationModalLabel-{{$setting->id}}">
                                                Edit Info - {{$setting->key}}</h5>
                                            <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form
                                                action="{{route('setting.edit',$setting->id)}}"
                                                method="post">
                                                @csrf
                                                @if($setting->value)
                                                    <div class="mb-3">
                                                        <label for="value-{{$setting->id}}"
                                                               class="form-label">Value</label>
                                                        <textarea name="value"
                                                                  id="value-{{$setting->id}}"
                                                                  class="form-control"
                                                                  rows="3">{{$setting->value}}</textarea>
                                                    </div>
                                                @endif
                                                @if($setting->text)
                                                    <div class="mb-3">
                                                        <label for="text-{{$setting->id}}"
                                                               class="form-label">Text</label>
                                                        <textarea name="text"
                                                                  id="text-{{$setting->id}}"
                                                                  class="form-control"
                                                                  rows="3">{{$setting->text}}</textarea>
                                                    </div>
                                                @endif
                                                @if($setting->type == "img")
                                                    <div class="mb-3">
                                                        <label for="img-{{$setting->id}}"
                                                               class="form-label">Image</label>
                                                        <input type="file" name="img"
                                                               id="img-{{$setting->id}}"
                                                               class="form-control">
                                                    </div>
                                                @endif
                                                <button type="submit" class="btn btn-primary">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .nav-link {
            color: #1f1f1f;
        }

    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
@endsection

