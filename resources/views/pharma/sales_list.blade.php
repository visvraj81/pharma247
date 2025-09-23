@extends('pharma.layout.main')
@section('main')
@if(Session::has('error'))
<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
@endif
@if(Session::has('success'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
@endif

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">

        </div>
    </div>
</div>

@endsection