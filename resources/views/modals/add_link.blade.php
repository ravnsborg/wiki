
<div class="modal fade" id="modalAddLink" tabindex="-1" role="dialog" aria-labelledby="modalAddLinkLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="needs-validation" novalidate id="add-link-form" method="post" action="/link">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="title" class="form-control" name="title" id="title" required>
                        <div class="invalid-feedback">
                            Please provide a link title.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="url">Url</label>
                        <input type="url" class="form-control" name="url" id="url" required>
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
