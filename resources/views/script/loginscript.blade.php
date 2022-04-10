<script type="text/javascript">
    $(document).ready(function() {



    });
    function login() {
        var form_data = new FormData($('form.user')[0]);
        var email=$('#exampleInputEmail').val();
        var password=$('#exampleInputPassword').val();
        // form_data.append('email',email);
        // form_data.append('password',password);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('checklogin') }}",
            data: {

                email:email,
                password:password
            },
            method: 'POST', //Post method,
            dataType: 'json',
            success: function(result) {

                var msg = '';
                if(result.error.length > 0)
                {
                    for(var count = 0; count < result.error.length; count++)
                    {
                        msg += '<div class="alert alert-danger">'+result.error[count]+'</div>';
                    }
                    $('#msg').html(msg);
                    setTimeout(function(){
                      $('#msg').html('');
                    }, 5000);
                }else{
                      if(result.success==1){

                        swal({
                        title: "Approved!",
                        text: "login success",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Ok",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            swal.close();
                            window.location.replace("{{route('dashboard')}}");

                        }
                    });

                      }else{

                        swal({
                        title: "Approved!",
                        text: "wrong password or email",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonClass: "btn-success",
                        confirmButtonText: "Ok",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            swal.close();


                        }
                    });

                      }



                }

            },
            error: function(error) {

                // $('#errormsg').show();
            }
        })
    }
</script>
