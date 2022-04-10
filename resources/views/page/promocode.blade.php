
@extends('layout.master')
@section('title')
    {{$title}}
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List promocode</h6>
    </div>
    <div class="card-body">

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPromocode" data-whatever="@addPromocode">Add Promocode</button>
        <div class="table-responsive" id="table-display">
            @include('datatable.promocodetable')
        </div>

    </div>
</div>
@endsection
@include('modal.promomodal')
@section('script')
@include('script.promoscript')
@endsection
