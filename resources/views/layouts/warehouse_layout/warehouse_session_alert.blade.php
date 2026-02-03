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

