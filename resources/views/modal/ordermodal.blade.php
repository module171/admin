<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="post" id="assign">
            {{csrf_field()}}
            <div class="modal-body">
                <div class="form-group">
                    <label for="category_id" class="col-form-label">Order ID:</label>
                    <input type="text" class="form-control" id="bookId" name="bookId" readonly="">
                </div>
                <div class="form-group">
                    <label for="category_id" class="col-form-label">Select Driver:</label>
                    <select class="form-control" name="driver_id" id="driver_id" required="">
                        <option value="">Select Driver</option>
                        @foreach ($getdriver as $driver)
                            <option value="{{$driver->id}}">{{$driver->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="assign()" data-dismiss="modal">Save</button>
            </div>
            </form>
        </div>

    </div>
</div>
