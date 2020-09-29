
<div class="modal fade" id="contentModalAddCategory" tabindex="-1" role="dialog" aria-labelledby="contentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="needs-validation" novalidate id="new_category_formx" method="post" action="wiki/category">

            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="new_category_title">Category Title</label>
                        <input type="new_category_title" class="form-control" name="new_category_title" id="new_category_title" required>
                        <div class="invalid-feedback">
                            Please provide a category.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_content_title">Content Title</label>
                        <input type="new_content_title" class="form-control" name="new_content_title" id="new_content_title" required>
                        <div class="invalid-feedback">
                            Please provide a content title.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new_content">Content</label>
                        <textarea class="form-control" name="new_content" id="new_content" rows="3"></textarea>
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
