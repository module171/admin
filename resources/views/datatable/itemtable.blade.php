<table class="table table-striped table-bordered zero-configuration">
    <thead>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Product Name</th>
            <th>Price</th>

            <th>Action</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($getitem as $item)
        <tr id="dataid{{$item->id}}">
            <td>{{$item->id}}</td>
            <td>{{@$item['category']->category_name}}</td>
            <td>{{$item->item_name}}</td>
            <td><?php echo env('CURRENCY').''.number_format($item->item_price, 2); ?></td>

            <td>
                <span>
                    <a href="#" data-toggle="tooltip" data-placement="top" onclick="GetData('{{$item->id}}')" title="" data-original-title="Edit">
                        <span class="badge badge-success">Edit</span>
                    </a>
                    <a data-toggle="tooltip" href="{{URL::to('admin/item-images/'.$item->id)}}" data-original-title="View">
                        <span class="badge badge-warning">View</span>
                    </a>
                    <a class="badge badge-danger px-2" onclick="Delete('{{$item->id}}')" style="color: #fff;">Delete</a>

                    @if ($item->item_status==2)
                    <a class="badge badge-primary px-2" onclick="StatusUpdate('{{$item->id}}',1)" style="color: #fff;">UnBlock</a>

                    @else
                    <a class="badge badge-primary px-2" onclick="StatusUpdate('{{$item->id}}',2)" style="color: #fff;">Block</a>
                    @endif

                </span>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
