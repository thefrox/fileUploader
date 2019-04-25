
<center>
    <div style="clear:both; display:block;padding-bottom:20px;">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileModal">Ajouter une image</button>
    </div>
</center>

<div id="content"></div>

@if ($pictures)
    @foreach($pictures as $picture)
        <div style="width:230px;height: 230px; border: 1px solid whitesmoke ;text-align: center;float: left;display:block;position:relative;" id="image">
            <img width="100%" height="100%" id="picture_{{ $picture->id }}" src="{{asset('uploads')}}/{{ $picture->url }}"/>
            <a href="javascript:removeFile('{{ $picture->id }}','{{ $picture->url }}')" class="btn btn-info btn-lg" style="position : absolute;bottom: 10px;right: 50px;padding: 0px 1rem 4px;font-size: 1.25rem;line-height: 1;">
                <span class="glyphicon glyphicon-remove"></span> Remove <span aria-hidden="true">×</span>
            </a>
        </div>
    @endforeach
 @endif

@include('popup/uploader')


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
        var form_data = new FormData();
        form_data.append('file', $('#file').get(0).files[0]);
        form_data.append('name', $( "input#name" ).val());
        form_data.append('email', $( "input#email" ).val());
        form_data.append('_token', '{{csrf_token()}}');
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
                    //ajout des images
                    var div = document.createElement('div');
                    div.innerHTML = '<div style="width:230px;height: 230px; border: 1px solid whitesmoke ;text-align: center;float: left;display:block;position:relative;" id="image"><img width="100%" height="100%" id="picture_' + data['id'] + '" src="{{asset('uploads')}}/' + data['url'] + '"/><a href="javascript:removeFile(\''+ data['id'] + '\',\''+ data['url'] + '\')" class="btn btn-info btn-lg" style="position : absolute;bottom: 10px;right: 50px;padding: 0px 1rem 4px;font-size: 1.25rem;line-height: 1;"><span class="glyphicon glyphicon-remove"></span> Remove <span aria-hidden="true">×</span></a></div></div>';
                    document.getElementById('content').appendChild(div);
                    $('#submit').hide();
                    $('.modal-body').hide();
                    $('.modal-body-success').show();
                }
                $('#loading').css('display', 'none');
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
            }
        });
    });
    
    function removeFile(id, url) {
        if (id != '' && id > 0 && url != '') {
            if (confirm('Are you sure want to remove this picture?')) {
                $('#loading').css('display', 'block');
                var form_data = new FormData();
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('id', id);
                form_data.append('url', url);
                $.ajax({
                    url: "{{url('uploader/remove')}}",
                    data: form_data,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $('#picture_' + id).parent().hide();
                    },
                    error: function (xhr, status, error) {
                        alert(xhr.responseText);
                    }
                });
            }
        }
    }
</script>