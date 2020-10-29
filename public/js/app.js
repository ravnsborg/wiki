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
    refocusSearchBox(true);


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
                dataType: "json",
                success:function(result) {
                    if (result.content_record){
                        var contentRecord = result.content_record,
                            categoryList = result.category_list;

                        //Load the category dropdown list
                        var dropdown = $("#category_list"),
                            selected = false;

                        $.each(categoryList, function() {
                            selected = false;
                            if (contentRecord.categories_id == this.id){
                            // if (contentRecord.categories_id == categoryId){
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

            $('.modal-title').text('Add New Article');

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
                reference_url:  referenceUrl
            },
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
	
	refocusSearchBox();

    });


    /*
    Call function to load content for this entire category
     */
    $('.category_listing_item').on('click', function () {
        refocusSearchBox(true);
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
        var searchString = $(this).val(),
            searchAll = 0;

        if (e.keyCode == 13 && searchString.length >= 1){

            if ( $('#search_all_user_entities').is(':checked') ){
                searchAll = 1;
            }

            $.ajax({
                type: "POST",
                url: "wiki/search",
                data: {
                    search_string:  searchString,
                    search_all_entities:  searchAll
                },
                success:function(result) {
                    $('#search_result_listing').html("");
                    if (result && result.categoryList){
                        //Load content into main body
                        $('#category_content_body').html(result.html);
                    }
                    $('#search_all_user_entities').prop('checked', false);
                }
            });
        }

    });

    //------------------------------
    // Links management
    //------------------------------

    /*
    Populate edit link modal text inputs and set action url
     */
    $('#modalEditLink').on('show.bs.modal', function(event) {
        var targetEvent = $(event.relatedTarget),
            linkId = targetEvent.data('link-id'),
            linkTitle = targetEvent.data('link-title'),
            linkUrl = targetEvent.data('link-url');

        $('#edit-link-form').attr('action', '/link/' + linkId);

        $('#edit_title').val(linkTitle);
        $('#edit_url').val(linkUrl);
    });

    /*
    Delete Link
    */
    $('.delete-link').on('click', function () {
        var trParent = $(this).closest('tr');
        linkId = $(this).closest('tr').attr('data-link-id');

        if ( confirm("Delete this link?") ){
            $.ajax({
                type: 'DELETE',
                url: '/link/' + linkId,
                dataType: "json",
                success:function(result) {
                    if (result.success) {
                        trParent.hide();
                    }
                }
            });
        }
    });

    //------------------------------
    // Entities management
    //------------------------------

    /*
    Populate entities edit modal entity title and set action url
     */
    $('#modalEditEntity').on('show.bs.modal', function(event) {
        var targetEvent = $(event.relatedTarget),
            entityId = targetEvent.data('entity-id'),
            entityTitle = targetEvent.data('entity-title');

        $('#edit-entity-form').attr('action', '/entity/' + entityId);

        $('#edit_title').val(entityTitle);
    });

    /*
    Delete Link
    */
    $('.delete-entity').on('click', function () {
        var trParent = $(this).closest('tr');
        entityId = $(this).closest('tr').attr('data-entity-id');

        alert('Not allowing entity deletion at this time');

        // if ( confirm("Delete this link?") ){
        //     $.ajax({
        //         type: 'DELETE',
        //         url: '/entity/' + entityId,
        //         dataType: "json",
        //         success:function(result) {
        //             if (result.success) {
        //                 trParent.hide();
        //             }
        //         }
        //     });
        // }

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

function refocusSearchBox(clearContents){
    if (clearContents){
       $('#search_content').val('');
    }

    $('#search_content').focus();
}

