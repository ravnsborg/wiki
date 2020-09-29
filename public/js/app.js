$(document).ready(function () {

    /**
     * Allows ajax calls to process.
     */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    //Set cursor to searchbox by default
    $('#search_content').focus();


    /*
    Load content deletion confirmation modal
     */
    $('#deleteContentOperationModal').on('show.bs.modal', function (event) {
        var contentId = $(event.relatedTarget).data('content_id');

        $('#content-id').val(contentId);
    });

    /*
    User confirmed to delete this content
     */
    $('#delete-content-submit-button').on('click', function () {
        var contentId = $('#content-id').val();

        $('#deleteContentOperationModal').modal('hide');

        $.ajax({
            type: 'DELETE',
            url: 'wiki/content/' + contentId,
            success:function(result) {
                if (result.success) {
                    $('#item-' + contentId).hide();
                }
            }
        });

    });




    /*
     Popup Modal for editing content
     */
    $('#contentModalContent').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget),
            contentId = button.data('content_id'),
            categoryId = button.data('category_id'),
            operation = button.data('operation');

        $('#content_title , #reference_url, #edit_category_content_text').val('');
        $('#category_list').empty();

        $('#category-id').val(categoryId);
        $('#content-submit-button').data('operation', operation);

        //Load existing content to edits
        if (operation == 'edit_content'){
            $.ajax({
                method: "GET",
                url: "wiki/content/"+contentId,
                success:function(result) {
                    if (result.content_record){
                        var contentRecord = result.content_record,
                            categoryList = result.category_list;

                        // console.log(contentRecord);
                        //Load the category dropdown list
                        var dropdown = $("#category_list"),
                            selected = false;

                        $.each(categoryList, function() {
                            selected = false;
                            if (contentRecord.wiki_category_id == this.id){
                                selected = true;
                            }
                            dropdown.append($("<option />")
                                .val(this.id)
                                .text(this.title)
                                .prop('selected', selected));
                        });

                        $('#modal-title').html("Subject: " + contentRecord.title);
                        $('#content_title').val(contentRecord.title);
                        $('#reference_url').val(contentRecord.url);
                        $('#edit_category_content_text').val(contentRecord.body);
                        $('#model-id').val(contentId);
                    }
                    // if (result.html){
                    //     $('#modal-title').html("Add New Category");
                    //     $('#edit_category_content_text').html(result.html);
                    // }
                }
            });

        }else{
            var $dropdown = $("#category_list");

            $('.modal-title').text('Add New Content');

            $.ajax({
                method: "GET",
                url: "wiki/category",
                dataType: "json",
                success:function(result) {
                    if (result.category_list){
                        var categoryList = result.category_list;

                        //Load the category dropdown list
                        $.each(categoryList, function() {
                            $dropdown.append($("<option />")
                                .val(this.id)
                                .text(this.title));
                        });
                    }
                }
            });
        }
    });


    /*
    Submit edited content from modal and save into db
     */
    $('#content-submit-button').on('click', function () {
        var categoryId = $('#category-id').val(),
            content = $('#edit_category_content_text').val(),
            contentId = $('#model-id').val(),
            contentTitle =  $('#content_title').val(),
            referenceUrl =  $('#reference_url').val(),
            method = 'POST',
            operation = $('#content-submit-button').data('operation'),
            categoryId = $('#category_list').val(),
            urlPath = "wiki/content";


        if (operation == 'edit_content'){
            method = 'PUT';
            urlPath = "wiki/content/"+contentId;

        }

        $.ajax({
            type: method,
            url: urlPath,
            data: {
                category_id:  categoryId,
                wiki_content:  content,
                wiki_title:  contentTitle,
                reference_url:  referenceUrl,
            },
            async: false,
            success:function(result) {
                if (result.success) {
                    $('#content_' + contentId).html(content);
                }
            },
            complete:function(){
                $('#contentModalContent').modal('hide');
                load_content( categoryId, $('#search_content').val() );
            }
        });



    });



    /*
    Call function to load content for this entire category
     */
    $('.category_listing_item').on('click', function () {
        $('#search_content').val('').focus();
        var categoryId = $(this).data('category_id');

        load_content( categoryId, null);
//kmr stopped here. need to pass cat id to add new content to this cat
        $('#category-id').val(categoryId);
    });


    $('.alert')
        .delay(3000)
        .fadeOut('slow');

    /*
    Ajax call to search for user requested string in
    content of the categories and return how
     */
    $('#search_content').on('keyup', function (e) {
        var searchString = $(this).val();

        if (e.keyCode == 13 && searchString.length >= 1){
            $.ajax({
                type: "POST",
                url: "wiki/search",
                data: {
                    search_string:  searchString
                },
                success:function(result) {
                    $('#search_result_listing').html("");
                    if (result && result.categoryList){
                        //Load content into main body
                        $('#category_content_body').html(result.html);
                    }

                }
            });
        }

    });


});



/*
 Load category content via ajax when a category is clicked on
 */
function load_content(category_id, search_string){
    $.ajax({
        type: "POST",
        url: "wiki/search",
        data: {
            category_id:  category_id,
            search_string:  search_string
        },
        success:function(result) {
            if (result.html){
                $('#category_content_body').html(result.html);
            }
        }
    });
}


