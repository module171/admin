@extends('layout.master')
@section('title')
    {{$title}}
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">List item</h6>
    </div>
    <div class="card-body">

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct" data-whatever="@addProduct">Add Item</button>
        <div class="table-responsive" id="table-display">
            @include('datatable.itemtable')
        </div>

    </div>
</div>
@endsection
@include('modal.itemmodal')
@section('script')
@include('script.itemscript')
@endsection
