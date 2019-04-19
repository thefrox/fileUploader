<!DOCTYPE html>
<html>
<head>
    <title>BlueMega - FileUploader</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body style="background: lightgrey">
    <div id="content"></div>
    
    <div style="width:350px;height: 350px; border: 1px solid whitesmoke ;text-align: center;float: left" id="image"><img width="100%" href="javascript:removeFile()" height="100%" id="" src=""/></div>
    
    <center>
        <div style="clear:both; display:block">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileModal">Ajouter une image</button>
        </div>
    </center>
    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">FileUploader</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group" id="filetemp">
                            <label for="recipient-name" class="col-form-label">Ajouter une image :</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Entrer le nom de l'image :</label>
                            <input type="text" class="form-control" id="name">
                       </div>
                       <div class="form-group">
                            <label for="message-text" class="col-form-label">Entrer l'email du client :</label>
                            <input type="text" class="form-control" id="email">
                       </div>
                    </form>
                </div>
                <div class="modal-body-success" style="display: none">
                    <div class="form-group" id="filetemp">
                        <center><label for="recipient-name" class="col-form-label">Image ajouté avec succés</label></center>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="submit" type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>
</center>

<script> 
    $('#fileModal').on('show.bs.modal', function (event) {     
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('whatever'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('.modal-body input').val(recipient);
        $('#filetemp').html('<label for="recipient-name" class="col-form-label">Ajouter une image :&nbsp;</label><input type="file" id="file">'); //fix security problem
        $('#submit').show();
        $('.modal-body').show();
        $('.modal-body-success').hide();
    })
    $('#fileModal').on('hidden.bs.modal', function (event) {
        $('#filetemp').html(''); //fix security problem
    });
</script>

<!-- JavaScripts -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/2c7a93b259.js"></script>
<script>
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    $('#submit').click(function () {
        if (!isEmail($('#email').val()) || $('#email').val() == '' ){
            alert('Email address incorrect');
            return false;
        }
        if ($('#name').val() == '' ){
            alert('Name is empty');
            return false;
        }
        if ($('#file').val() == '' ){
            alert('File is empty');
            return false;
        }
        var newIDSuffix = 1;
        var form_data = new FormData();
        form_data.append('file', $('#file').get(0).files[0]);
        form_data.append('name', $( "input#name" ).val());
        form_data.append('email', $( "input#email" ).val());
        form_data.append('_token', '{{csrf_token()}}');
        $('#loading').css('display', 'block');
        $.ajax({
            url: "{{url('uploader/add')}}",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                    alert(data.errors['file']);
                }
                else {
                    $('#file_name').val(data);                    
                    //ajout des images
                    var div = document.createElement('div');
                    div.innerHTML = '<div style="width:350px;height: 350px; border: 1px solid whitesmoke ;text-align: center;float: left" id="image"><img width="100%" href="javascript:removeFile(\''+ data + '\')" height="100%" id="preview_image_' + newIDSuffix + '" src="{{asset('uploads')}}/' + data + '"/><i id="loading" class="fa fa-spinner fa-spin fa-3x fa-fw" style="position: absolute;left: 40%;top: 40%;display: none"></i></div>';
                    document.getElementById('content').appendChild(div);
                    $('#submit').hide();
                    $('.modal-body').hide();
                    $('.modal-body-success').show();
                    newIDSuffix++;
                }
                $('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    });
    
    function removeFile() {
        if ($('#file_name').val() != '')
            if (confirm('Are you sure want to remove this picture?')) {
                $('#loading').css('display', 'block');
                var form_data = new FormData();
                form_data.append('_method', 'DELETE');
                form_data.append('token', '{{csrf_token()}}');
                $.ajax({
                    url: "uploader/remove",
                    data: form_data,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
                        $('#file_name').val('');
                        $('#loading').css('display', 'none');
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }
    }
</script>
</body>
</html>