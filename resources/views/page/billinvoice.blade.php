@extends('layout.master')


<style type="text/css">
    @media  print {
      @page  { margin: 0; }
      body { margin: 1.6cm; }
    }
</style>
@section('content')
<!-- row -->

<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL::to('/admin/home')}}">Dashboard</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Invoice</a></li>
        </ol>
    </div>
</div>
<!-- row -->

<div class="container-fluid">
    <!-- End Row -->
    <div class="card" id="printDiv">
        <div class="card-header">
            Invoice
            <strong>{{$getinvoice['order']->order_number}}</strong>
            <span class="float-right"> <strong>Status:</strong>
                @if($getinvoice['order']->status == '1')
                    Order Received
                @elseif ($getinvoice['order']->status == '2')
                    On the way
                @elseif ($getinvoice['order']->status == '3')
                    Delivered
                @else
                    Cancelled
                @endif
            </span>

        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h6 class="mb-3">To:</h6>
                    <div>
                        <strong>{{$getinvoice['order']['user']->name}}</strong>
                    </div>
                    <div>Address: {{$getinvoice->address}}</div>
                    <div>Email: {{$getinvoice['order']['user']->email}}</div>
                    <div>Phone: {{$getinvoice['order']['user']->mobile}}</div>
                </div>


                @if ($getinvoice['order']->order_notes !="")
                <div class="col-sm-4">
                    <h6 class="mb-3">Order Note:</h6>
                    <div>{{$getinvoice['order']->order_notes}}</div>
                </div>
                @endif

            </div>

            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Item</th>
                            <th class="right">Unit Cost</th>
                            <th class="center">Qty</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        foreach ($getitem['order']['item'] as $orders) {
                        ?>
                        <tr>

                            <td class="center">{{$i}}</td>
                            <td class="left strong">
                                {{$orders->item_name}}


                                 @foreach ($gettopping['order']['item'] as $topping)
                                 <div class="cart-addons-wrap">
                                     <div class="cart-addons">
                                         <b>{{$topping->item_name}}</b> : <?php echo env('CURRENCY'); ?>{{number_format($topping->item_price, 2)}}
                                     </div>
                                 </div>
                                 @endforeach

                                @if ($orders->item_notes != "")
                                    <b>Item Notes</b> : {{$orders->item_notes}}
                                @endif
                            </td>
                            <td class="left"><?php echo env('CURRENCY').''.number_format($orders->item_price, 2); ?></td>
                            <td class="center">{{$orders->pivot->qly}}</td>
                            <td class="right"><?php echo env('CURRENCY'); ?>{{number_format($getinvoice['order']->order_total, 2)}}</td>


                        </tr>
                        <?php
                            $data[] = array(
                                "total_price" => $orders->total_price
                            );
                        ?>
                        <?php
                        $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-lg-4 col-sm-5">

                </div>

                <div class="col-lg-4 col-sm-5 ml-auto">
                    <table class="table table-clear">
                        <tbody>
                            <tr>
                                <td class="left">
                                    <strong>Tax</strong> ({{$gettax->tax}}%)
                                </td>
                                <td class="right">
                                    <strong><?php echo env('CURRENCY').''.number_format($getinvoice['order']->discount_tax, 2); ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="left">
                                    <strong>Delivery Charge</strong>
                                </td>
                                <td class="right">
                                    <strong><?php echo env('CURRENCY').''.number_format($getinvoice['order']->delivery_charge, 2); ?></strong>
                                </td>
                            </tr>

                            <tr>
                                <td class="left">
                                    <strong>Discount</strong> ({{$getinvoice['order']->promotecode}})
                                </td>
                                <td class="right">
                                    <strong><?php echo env('CURRENCY').''.number_format($getinvoice['order']->discount_amount, 2); ?></strong>
                                </td>
                            </tr>

                            <tr>
                                <td class="left">
                                    <strong>Total</strong>
                                </td>
                                <td class="right">
                                    <strong><?php echo env('CURRENCY').''.number_format($getinvoice['order']->order_total, 2); ?></strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>
    </div>
    <!-- End Row -->
    <button type="button" class="btn btn-primary float-right" id="doPrint">
        <i class="fa fa-print" aria-hidden="true"></i> Print
    </button>
</div>
<!-- #/ container -->

<!-- #/ container -->
@endsection
@section('script')
<script type="text/javascript">
    document.getElementById("doPrint").addEventListener("click", function() {
         var printContents = document.getElementById('printDiv').innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
    });
</script>
@endsection
