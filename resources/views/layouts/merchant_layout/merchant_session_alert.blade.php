@if(session()->has('alert-message'))
    {{--Sweet Alert--}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.js"></script>
    <script>
        $(function() {

            Swal.fire({
                type: '{{ session('alert-type') }}',
                title: '{{ session('alert-title') }}',
                text: '{{ session('alert-message') }}',
                showConfirmButton: true,
                timer: 5000
            });
        });
    </script>
@endif

@if(session()->has('message'))
    <div class="alert alert-{{ session('type')}} alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('message') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        <ul>
        @foreach ($errors->all() as $error)
            <li> {{  $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

