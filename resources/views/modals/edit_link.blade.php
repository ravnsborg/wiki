
<div class="modal fade" id="modalEditLink" tabindex="-1" role="dialog" aria-labelledby="modalEditLinkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="needs-validation" novalidate id="edit-link-form" method="post" >

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                    {{ csrf_field() }}
                    @method('PUT')

                    <div class="form-group">
                        <label for="edit_title">Title</label>
                        <input type="edit_title" class="form-control" name="edit_title" id="edit_title" required>
                        <div class="invalid-feedback">
                            Please provide a link title.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_url">Url</label>
                        <input type="edit_url" class="form-control" name="edit_url" id="edit_url" required>
                        <div class="invalid-feedback">
                            Please provide a link url.
                        </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
