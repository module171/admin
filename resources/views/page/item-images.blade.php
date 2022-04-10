@extends('layout.master')
@section('title')
    @parent
@endsection
@section('content')
<!-- row -->

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL::to('/admin/home')}}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Product Photos</a></li>
        </ol>
    </div>
</div>
<!-- row -->


    <!-- End Row -->
    <div class="row">
        <div class="col-lg-4 col-xl-3">
            <div class="card category-card">
                <div class="card-body">
                    <h4>Category</h4>
                    <p class="text-muted">{{$itemdetails->category_name}}</p>

                    <h4>Price</h4>
                    <p><?php echo env('CURRENCY').''.number_format($itemdetails->item_price, 2); ?></p>



                    <h4>Name</h4>
                    <p class="text-muted">{{$itemdetails->item_name}}</p>

                    <h4>Description</h4>
                    <p class="text-muted">{{$itemdetails->item_description}}</p>


                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-9">
            <div id="success-msg" class="alert alert-dismissible mt-3" style="display: none;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Message!</strong> <span id="msg"></span>
            </div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddProduct" data-whatever="@addProduct">Add Item images</button>
            <div id="card-display">
                @include('page.itemimage')
            </div>
        </div>

    <!-- End Row -->

    <!-- Edit Images -->
@include('modal.imagemodal')


    <!-- Add Ingredients Image -->

</div>
<!-- #/ container -->

<!-- #/ container -->
@endsection
@section('script')

@include('script.imagescript')
@endsection
