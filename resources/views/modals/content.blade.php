<div class="modal fade" id="contentModalContent" tabindex="-1" role="dialog" aria-labelledby="contentModalContentLabel" aria-hidden="true">
    <input type="hidden" id="category-id"/>
    <input type="hidden" id="model-id"/>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="needs-validation" novalidate>

            <div class="modal-header">
                <h5 class="modal-title">Edit Content</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="category_select">Category</label>
                    <select id="category_list" class="custom-select"></select>

                    <div class="invalid-feedback">
                        Please provide a content title.
                    </div>
                </div>
                <div class="form-group">
                    <label for="content_title">Content Title</label>
                    <input type="content_title" class="form-control" name="content_title" id="content_title" required>
                    <div class="invalid-feedback">
                        Please provide a content title.
                    </div>
                </div>
                <div class="form-group">
                    <label for="reference_url">Reference URL</label>
                    <input type="reference_url" class="form-control" name="reference_url" id="reference_url">
                </div>
                <div class="form-group">
                    <label for="content_text">Content</label>
                    <textarea id="edit_category_content_text"  name="content_text" type="content_text" rows="10" cols="100"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="content-submit-button" class="btn btn-primary">Save</button>
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
