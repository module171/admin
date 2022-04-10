@extends('layout.master')

@section('title')
    {{$title}}
@endsection
@section('content')




<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List Order</h6>
    </div>
    <div class="card-body">

        <div class="table-responsive" id="table-display">
            @include('datatable.orderstable')
        </div>

    </div>
</div>
@endsection
 @include('modal.ordermodal')
@section('script')
@include('script.orderscript')
@endsection
