<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="add_product" enctype="multipart/form-data">
            <div class="modal-body">
                <span id="msg"></span>
                @csrf
                <div class="form-group">
                    <label for="cat_id" class="col-form-label">Category:</label>
                    <select name="cat_id" class="form-control" id="cat_id">
                        <option value="">Select Category</option>
                        <?php
                                foreach ($getcategory as $category) {
                                ?>
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                                <?php
                                }
                                ?>

                    </select>
                </div>
                <div class="form-group">
                    <label for="type" class="col-form-label">Type food:</label>
                    <select name="type_id" class="form-control" id="type_id">
                        <option value="">Select Type</option>

                      <option value="1">Food</option>
                      <option value="2">Topping</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="item_name" class="col-form-label">Item Name:</label>
                    <input type="text" class="form-control" name="item_name" id="item_name" placeholder="Item Name">
                </div>
                <div class="form-group">
                    <label for="price" class="col-form-label">Price:</label>
                    <input type="text" class="form-control" name="price" id="price" placeholder="Price">
                </div>

                <div class="form-group">
                    <label for="getprice" class="col-form-label">Description:</label>
                    <textarea class="form-control" rows="5" name="description" id="description" placeholder="Product Description"></textarea>
                </div>
                <div class="form-group">
                    <label for="colour" class="col-form-label">Select Item images:</label>
                    <input type="file" multiple="true" class="form-control" name="file[]" id="file" required="" accept=".png, .jpg, .jpeg">
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

<!-- Edit Item -->
<div class="modal fade" id="EditProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabeledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" name="editproduct" class="editproduct" id="editproduct" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabeledit">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <span id="emsg"></span>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <div class="form-group">
                        <label for="getcat_id" class="col-form-label">Category:</label>
                        <select name="getcat_id" class="form-control" id="getcat_id">
                            <option value="">Select Category</option>
                            <?php
                            foreach ($getcategory as $category) {
                            ?>
                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-form-label">Type food:</label>
                        <select name="gettype_id" class="form-control" id="gettype_id">
                            <option value="">Select Type</option>

                          <option value="1">Food</option>
                          <option value="2">Topping</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="getitem_name" class="col-form-label">Item Name:</label>
                        <input type="text" class="form-control" id="getitem_name" name="item_name" placeholder="Item Name">
                    </div>
                    <div class="form-group">
                        <label for="getprice" class="col-form-label">Price:</label>
                        <input type="text" class="form-control" name="getprice" id="getprice" placeholder="Price">
                    </div>

                    <div class="form-group">
                        <label for="getprice" class="col-form-label">Description:</label>
                        <textarea class="form-control" rows="5" name="getdescription" id="getdescription" placeholder="Product Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btna-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
