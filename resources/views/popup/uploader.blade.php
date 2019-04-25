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