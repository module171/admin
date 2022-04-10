<div class="modal fade text-left" id="ChangePasswordModal" tabindex="-1" role="dialog" aria-labelledby="RditProduct"
                  aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <label class="modal-title text-text-bold-600" id="RditProduct">Change Password</label>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div id="errors" style="color: red;"></div>

                        <form method="post" id="change_password_form">
                        {{csrf_field()}}
                          <div class="modal-body">
                            <label>Old Passwod: </label>
                            <div class="form-group">
                                <input type="password" placeholder="Enter Old Password" class="form-control" name="oldpassword" id="oldpassword">
                            </div>

                            <label>New Password: </label>
                            <div class="form-group">
                                <input type="password" placeholder="Enter New Password" class="form-control" name="newpassword" id="newpassword">
                            </div>

                            <label>Confirm Password: </label>
                            <div class="form-group">
                                <input type="password" placeholder="Enter Confirm Password" class="form-control" name="confirmpassword" id="confirmpassword">
                            </div>

                          </div>
                          <div class="modal-footer">
                            <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal"
                            value="close">
                            <input type="button" onclick="changePassword()" class="btn btn-outline-primary btn-lg" value="Submit">
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
<!-- Modal Settings-->
<div class="modal fade text-left" id="Selltings" tabindex="-1" role="dialog" aria-labelledby="RditProduct"
aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <label class="modal-title text-text-bold-600" id="RditProduct">Setting</label>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="errors" style="color: red;"></div>

      <form method="post" id="settings">
      {{csrf_field()}}
        <div class="modal-body">

          <label>Tax (%): </label>
          <div class="form-group">
              <input type="text" placeholder="Enter Tax in percentage (%)" value="{{{Auth::user()->tax}}}" class="form-control" name="tax" id="tax">
          </div>



          <label>Delivery Charge: </label>
          <div class="form-group">
              <input type="text" placeholder="Delivery Charge" value="{{{Auth::user()->delivery_charge}}}" class="form-control" name="delivery_charge" id="delivery_charge">
          </div>



        </div>
        <div class="modal-footer">
          <input type="reset" class="btn btn-outline-secondary btn-lg" data-dismiss="modal"
          value="close">
          <input type="button" class="btn btn-outline-primary btn-lg" onclick="settings()"  value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>
