<div class="modal fade" id="addPromocode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Promocode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="add_promocode">
            <div class="modal-body">
                <span id="msg"></span>
                @csrf
                <div class="form-group">
                    <label for="offer_name" class="col-form-label">Offer Name:</label>
                    <input type="text" class="form-control" name="offer_name" id="offer_name">
                </div>
                <div class="form-group">
                    <label for="offer_code" class="col-form-label">Offer Code:</label>
                    <input type="text" class="form-control" name="offer_code" id="offer_code">
                </div>
                <div class="form-group">
                    <label for="offer_amount" class="col-form-label">Offer in percentage (%):</label>
                    <input type="text" class="form-control" name="offer_amount" id="offer_amount">
                </div>
                <div class="form-group">
                    <label for="description" class="col-form-label">Offer Description:</label>
                    <textarea class="form-control" name="description" id="description" placeholder="Offer Description"></textarea>
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

<!-- Edit Promocode -->
<div class="modal fade" id="EditPromocode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabeledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" name="editpromocode" class="editpromocode" id="editpromocode">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabeledit">Edit Promocode</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <span id="emsg"></span>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="id" name="id">
                    <div class="form-group">
                        <label for="getoffer_name" class="col-form-label">Offer Name:</label>
                        <input type="text" class="form-control" name="getoffer_name" id="getoffer_name">
                    </div>
                    <div class="form-group">
                        <label for="getoffer_code" class="col-form-label">Offer Code:</label>
                        <input type="text" class="form-control" name="getoffer_code" id="getoffer_code">
                    </div>
                    <div class="form-group">
                        <label for="getoffer_amount" class="col-form-label">Offer in percentage (%):</label>
                        <input type="text" class="form-control" name="getoffer_amount" id="getoffer_amount">
                    </div>
                    <div class="form-group">
                        <label for="get_description" class="col-form-label">Offer Description:</label>
                        <textarea class="form-control" name="get_description" id="get_description" placeholder="Offer Description"></textarea>
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
