<script type="text/javascript">
    $(document).ready(function() {

        $('#addproduct').on('submit', function(event){
            event.preventDefault();
            var form_data = new FormData(this);
            form_data.append('file',$('#file')[0].files);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ route('image-add') }}",
                method:"POST",
                data:form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    var msg = '';
                    $('div.gallery').html('');
                    if(result.error.length > 0)
                    {
                        for(var count = 0; count < result.error.length; count++)
                        {
                            msg += '<div class="alert alert-danger">'+result.error[count]+'</div>';
                        }
                        $('#iiemsg').html(msg);
                        setTimeout(function(){
                          $('#iiemsg').html('');
                        }, 5000);
                    }
                    else
                    {
                        msg += '<div class="alert alert-success mt-1">'+result.success+'</div>';
                        $('#message').html(msg);
                        $("#AddProduct").modal('hide');
                        $("#addproduct")[0].reset();
                        location.reload();
                    }
                },
            })
        });

        $('#addingredients').on('submit', function(event){
            event.preventDefault();
            var form_data = new FormData(this);
            form_data.append('ingredients',$('#ingredients')[0].files);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{ URL::to('admin/item/storeingredientsimages') }}",
                method:"POST",
                data:form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    var msg = '';
                    if(result.error.length > 0)
                    {
                        for(var count = 0; count < result.error.length; count++)
                        {
                            msg += '<div class="alert alert-danger">'+result.error[count]+'</div>';
                        }
                        $('#aiemsg').html(msg);
                        setTimeout(function(){
                          $('#aiemsg').html('');
                        }, 5000);
                    }
                    else
                    {
                        msg += '<div class="alert alert-success mt-1">'+result.success+'</div>';
                        $('#message').html(msg);
                        $("#AddIngredients").modal('hide');
                        $("#addingredients")[0].reset();
                        location.reload();
                    }
                },
            })
        });

        $('#editimg').on('submit', function(event){
            event.preventDefault();
            var form_data = new FormData(this);
            $.ajax({
                url:"{{ route('image-update') }}",
                method:'POST',
                data:form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    var msg = '';
                    if(result.error.length > 0)
                    {
                        for(var count = 0; count < result.error.length; count++)
                        {
                            msg += '<div class="alert alert-danger">'+result.error[count]+'</div>';
                        }
                        $('#emsg').html(msg);
                        setTimeout(function(){
                          $('#emsg').html('');
                        }, 5000);
                    }
                    else
                    {
                        location.reload();
                    }
                },
            });
        });

        $('#editingredients').on('submit', function(event){
            event.preventDefault();
            var form_data = new FormData(this);
            $.ajax({
                url:"{{ URL::to('admin/item/updateingredients') }}",
                method:'POST',
                data:form_data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(result) {
                    var msg = '';
                    if(result.error.length > 0)
                    {
                        for(var count = 0; count < result.error.length; count++)
                        {
                            msg += '<div class="alert alert-danger">'+result.error[count]+'</div>';
                        }
                        $('#iemsg').html(msg);
                        setTimeout(function(){
                          $('#iemsg').html('');
                        }, 5000);
                    }
                    else
                    {
                        location.reload();
                    }
                },
            });
        });
    });

    function EditDocument(id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('image-getbyid') }}",
            data: {
                id: id
            },
            method: 'POST', //Post method,
            dataType: 'json',
            success: function(response) {
                jQuery("#EditImages").modal('show');
                $('#idd').val(response.ResponseData.id);
                $('.galleryim').html("<img src="+response.ResponseData.img+" class='img-fluid' style='max-height: 200px;'>");
                $('#old_img').val(response.ResponseData.image);
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        })
    }

    function Editingredients(id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ URL::to('admin/item/showingredients') }}",
            data: {
                id: id
            },
            method: 'POST', //Post method,
            dataType: 'json',
            success: function(response) {
                jQuery("#EditIngredients").modal('show');
                $('#idds').val(response.ResponseData.id);
                $('.galleryis').html("<img src="+response.ResponseData.img+" class='img-fluid' style='max-height: 200px;'>");
                $('#old_imgs').val(response.ResponseData.image);
            },
            error: function(error) {

                // $('#errormsg').show();
            }
        })
    }

    function DeleteImage(id) {
        // dd(id);
        swal({
            title: "Are you sure?",
            text: "Do you want to delete this image?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plz!",
            closeOnConfirm: false,
            closeOnCancel: false,
            showLoaderOnConfirm: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ route('image-delete') }}",
                    data: {
                        id: id
                    },
                    method: 'POST',
                    success: function(response) {
                        if (response == 1) {
                            swal({
                                title: "Approved!",
                                text: "Image has been deleted.",
                                type: "success",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true,
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    swal.close();
                                    location.reload();
                                }
                            });
                        } else {
                            swal("Cancelled", "Something Went Wrong :(", "error");
                        }
                    },
                    error: function(e) {
                        swal("Cancelled", "Something Went Wrong :(", "error");
                    }
                });
            } else {
                swal("Cancelled", "Your record is safe :)", "error");
            }
        });
    }

    function Deleteingredients(id) {
        // dd(id);
        swal({
            title: "Are you sure?",
            text: "Do you want to delete this ingredients image?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plz!",
            closeOnConfirm: false,
            closeOnCancel: false,
            showLoaderOnConfirm: true,
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:"{{ URL::to('admin/item/destroyingredients') }}",
                    data: {
                        id: id
                    },
                    method: 'POST',
                    success: function(response) {
                        if (response == 1) {
                            swal({
                                title: "Approved!",
                                text: "Image has been deleted.",
                                type: "success",
                                showCancelButton: true,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Ok",
                                closeOnConfirm: false,
                                showLoaderOnConfirm: true,
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    swal.close();
                                    location.reload();
                                }
                            });
                        } else {
                            swal("Cancelled", "Something Went Wrong :(", "error");
                        }
                    },
                    error: function(e) {
                        swal("Cancelled", "Something Went Wrong :(", "error");
                    }
                });
            } else {
                swal("Cancelled", "Your record is safe :)", "error");
            }
        });
    }

     $(document).ready(function() {
         var imagesPreview = function(input, placeToInsertImagePreview) {
              if (input.files) {
                  var filesAmount = input.files.length;
                  $('div.gallery').html('');
                  var n=0;
                  for (i = 0; i < filesAmount; i++) {
                      var reader = new FileReader();
                      reader.onload = function(event) {
                           $($.parseHTML('<div>')).attr('class', 'imgdiv').attr('id','img_'+n).html('<img src="'+event.target.result+'" class="img-fluid"><span id="remove_"'+n+' onclick="removeimg('+n+')">&#x2716;</span>').appendTo(placeToInsertImagePreview);
                          n++;
                      }
                      reader.readAsDataURL(input.files[i]);
                 }
              }
          };

         $('#file').on('change', function() {
             imagesPreview(this, 'div.gallery');
         });

    });
    var images = [];
    function removeimg(id){
        images.push(id);
        $("#img_"+id).remove();
        $('#remove_'+id).remove();
        $('#removeimg').val(images.join(","));
    }
</script>
