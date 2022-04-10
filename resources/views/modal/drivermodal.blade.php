<div class="modal fade" id="addDriver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Driver</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="add_driver" enctype="multipart/form-data">
            <div class="modal-body">
                <span id="msg"></span>
                @csrf
                <div class="form-group">
                    <label for="name" class="col-form-label">Driver Name:</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Driver Name">
                </div>
                <div class="form-group">
                    <label for="email" class="col-form-label">Email:</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="mobile" class="col-form-label">Mobile:</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile Number">
                </div>
                <div class="form-group">
                    <label for="password" class="col-form-label">Password:</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Driver -->
<div class="modal fade" id="EditDriver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabeledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" name="editdriver" class="editdriver" id="editdriver" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabeledit">Edit Driver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <span id="emsg"></span>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <input type="hidden" class="form-control" id="old_img" name="old_img">
                    <div class="form-group">
                        <label for="driver_id" class="col-form-label">Driver Name:</label>
                        <input type="text" class="form-control" id="get_name" name="name" placeholder="Driver Name">
                    </div>
                    <div class="form-group">
                        <label for="get_email" class="col-form-label">Email:</label>
                        <input type="text" class="form-control" name="email" id="get_email">
                    </div>
                    <div class="form-group">
                        <label for="get_mobile" class="col-form-label">Mobile:</label>
                        <input type="text" class="form-control" name="mobile" id="get_mobile">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
