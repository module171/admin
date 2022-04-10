<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="add_category" enctype="multipart/form-data">
            <div class="modal-body">
                <span id="msg"></span>
                @csrf
                <div class="form-group">
                    <label for="category_name" class="col-form-label">Category Name:</label>
                    <input type="text" class="form-control" name="category_name" id="category_name">
                </div>
                <div class="form-group">
                    <label for="image" class="col-form-label">Image:</label>
                    <input type="file" class="form-control" name="image" id="image" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="removeimg" id="removeimg">
                </div>
                <div class="gallery"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category -->
<div class="modal fade" id="EditCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabeledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" name="editcategory" class="editcategory" id="editcategory" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabeledit">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <span id="emsg"></span>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <input type="hidden" class="form-control" id="old_img" name="old_img">
                    <div class="form-group">
                        <label for="category_id" class="col-form-label">Category Name:</label>
                        <input type="text" class="form-control" id="getcategory_name" name="category_name" placeholder="Category Name">
                    </div>
                    <div class="form-group">
                        <label for="image" class="col-form-label">Select image:</label>
                        <input type="file" class="form-control" name="image" id="image" accept=".png, .jpg, .jpeg">
                    </div>
                    <div class="gallerys"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
