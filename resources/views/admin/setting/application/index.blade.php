
@extends('layouts.admin_layout.admin_layout')

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Application</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
            <li class="breadcrumb-item active">Application</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="offset-md-2 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="text-right " style="margin-bottom: 20px">
                            <a href="{{ route('admin.application.edit', $application->id) }}" type="submit" class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </a>
                        </div>
                        <table class="table table-sm">
                            <tr>
                                <th width="30%">Name</th>
                                <td width="70%">
                                    {{ $application->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    {{ $application->email }}
                                </td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>
                                    {{ $application->contact_number }}
                                </td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>
                                    {{ $application->address }}
                                </td>
                            </tr>
                            <tr>
                                <th>Logo</th>
                                <td>
                                    @if (!empty($application->photo))
                                        <img src="{{ $application->photo_path }}"
                                            class="img-fluid img-thumbnail" style="height: 100px" alt="Application Photo">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Og image</th>
                                <td>
                                    @if (!empty($application->og_image))
                                        <img src="{{ $application->og_image_path }}"
                                            class="img-fluid img-thumbnail" style="height: 100px" alt="Application Photo">
                                    @endif
                                </td>
                            </tr>
                            
                            <tr>
                                <th>Favicon</th>
                                <td>
                                    @if (!empty($application->favicon))
                                        <img src="{{ $application->favicon_path }}"
                                            class="img-fluid img-thumbnail" style="height: 100px" alt="Application Photo">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Footer Logo</th>
                                <td>
                                    @if (!empty($application->logo))
                                        <img src="{{ $application->logo_path }}"
                                            class="img-fluid img-thumbnail" style="height: 100px" alt="Application Logo">
                                    @endif
                                </td>
                            </tr>
                            
                            <tr>
                                <th>Play Store App Link</th>
                                <td>
                                    {{ $application->app_link }}
                                </td>
                            </tr>
                            <tr>
                                <th>Meta Author</th>
                                <td>
                                    {{ $application->meta_author }}
                                </td>
                            </tr>
                            <tr>
                                <th>Meta Keywords</th>
                                <td>
                                    {{ $application->meta_keywords }}
                                </td>
                            </tr>
                            <tr>
                                <th>Meta Description</th>
                                <td>
                                    {{ $application->meta_description }}
                                </td>
                            </tr>
                            <tr>
                                <th>Google Map</th>
                                <td>
                                    @if(!empty($application->google_map))
                                        <iframe src="{!! $application->google_map !!}" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Action Admin</th>
                                <td>
                                    {{ $application->admin->name }}
                                </td>
                            </tr>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection


@push('script_js')
  <script>
    window.onload = function(){

      $('.view-modal').click(function(){
        var application_id = $(this).attr('application_id');
        var url = "{{ route('admin.application.show', ":application_id") }}";
        url = url.replace(':application_id', application_id);
        $('#showResult').html('');
        if(application_id.length != 0){
          $.ajax({
            cache   : false,
            // data    : {application_id: application_id, _token : "{{ csrf_token() }}"},
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
              $('#showResult').html(response);
            },

          })
        }
      });
    }
  </script>
@endpush
