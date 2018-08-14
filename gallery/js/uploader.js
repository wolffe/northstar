/**
 * Poster Uploader
 *
 * An advanced block-based module for poster project creation.
 * Uses Sortable.js and jQuery.
 *
 */

/* eslint-env browser */
/* jslint-env browser */
/* global window, document console, jQuery, ip_ajax_var, swal */

function showBlockImage(imageId, elementReference) {
    'use strict';

    elementReference.html('<p><i class="fas fa-circle-notch fa-spin"></i> Fetching image...</p>');

    jQuery.ajax({
        method: 'post',
        url: ip_ajax_var.ajaxurl,
        data: {
            imageId: imageId,
            action: 'ip_project_show_image'
        },
        success: function (response) {
            elementReference.html(response);
        }
    });
}

function deleteBlockImage(imageId) {
    'use strict';

    jQuery.ajax({
        method: 'post',
        url: ip_ajax_var.ajaxurl,
        data: {
            imageId: imageId,
            action: 'ip_project_delete_image'
        },
        success: function () {
            //
        }
    });
}




function appendBlock(blockType) {
    'use strict';

    var container = document.getElementById('ip-blocks'),
        block = document.createElement('li'),
        showToggle = '',
        blockContent;

    if (blockType === 'image') {
        blockContent = '<div class="ip-block-description"><input type="checkbox" name="blockImageFileType" id="blockImageFileType" class="blockImageFileType"> <label for="blockImageFileType">Thumbnail image</label></div>' +
            '<div class="ip-block-content-uploader">' +
            '<form enctype="multipart/form-data" class="blockImageForm"><input type="file" name="blockImageFile" id="blockImageFile"></form>' +
            '</div>' +
            '<div class="ip-block-content"></div>';
        showToggle = '<a href="#" class="ip-block-toggle"><i class="fas fa-fw fa-expand"></i></a>';
    } else if (blockType === 'video') {
        blockContent = '<div class="ip-block-title">Video Block</div>' +
            '<div class="ip-block-content">' +
                '<input type="url"> Youtube URL<br>' +
                '<div>PRO only<br><input type="file" name="video[]"></div>' +
            '</div>';
    } else if (blockType === 'heading') {
        blockContent = '<h3 class="ip-block-content ip-block-heading" contenteditable="true" data-ph="Edit Heading"></h3>';
    } else if (blockType === 'paragraph') {
        blockContent = '<p class="ip-block-content ip-block-paragraph" contenteditable="true" data-ph="Edit Paragraph"></p>';
    } else if (blockType === 'caption') {
        blockContent = '<p class="ip-block-content ip-block-caption" contenteditable="true" data-ph="Edit Caption"></p>';
    }

    blockContent += '<div class="ip-block-delete">' + showToggle + '<a href="#" class="block-delete"><i class="fas fa-fw fa-times"></i></a></div>';

    block.classList.add('block');
    block.innerHTML = blockContent;

    if (blockContent.indexOf('ip-block-heading') > 0) {
        block.classList.add('heading');
    }

    container.appendChild(block);
}


jQuery(window).on('load', function () {
    if (document.querySelector('#ip-blocks')) {
        /*
         * Initialise Sortable.js
         */
        var blockContainer = document.getElementById('ip-blocks');
        var sortable = Sortable.create(blockContainer);



        /*
         * Initialise accordion behaviour for blocks
         */
        jQuery(document).on('click', '.ip-block-toggle', function (event) {
            jQuery(this).parent().prev().toggleClass('ip-block-content-collapsed');

            event.preventDefault();
        });



        /*
         * Add blocks based on current selection
         */
        document.getElementById('ip-block-image').addEventListener('click', function (event) {
            appendBlock('image');

            event.preventDefault();
        });
        document.getElementById('ip-block-heading').addEventListener('click', function (event) {
            appendBlock('heading');

            event.preventDefault();
        });
        document.getElementById('ip-block-paragraph').addEventListener('click', function (event) {
            appendBlock('paragraph');

            event.preventDefault();
        });
        document.getElementById('ip-block-caption').addEventListener('click', function (event) {
            appendBlock('caption');

            event.preventDefault();
        });

        /*
         * Only allow one thumbnail image per project
         */
        jQuery(document).on('click', '#blockImageFileType', function () {
            jQuery('.block').each(function () {
                jQuery(this).find('.blockImageFileType').prop('checked', false);
            });
            jQuery(this).prop('checked', true);
        });
    }

    /*
     * Clean up contenteditable areas
     */
    jQuery(document).on('blur', '.ip-block-content[contenteditable="true"]', function () {
        jQuery(this).html(jQuery(this).text());
    });

    /**
     * Create and save a block-based poster project
     */
    jQuery(document).on('click', '#yay', function (event) {
        var title = jQuery('#ip-block-title').val(),
            description = jQuery('#ip-block-description').val(),
            category = jQuery('#imagepress_image_category').val(),
            keywords = jQuery('#ip-block-keywords').val(),
            videoUri = jQuery('#ip-block-video-url').val(),
            purchaseUri = jQuery('#ip-block-purchase-link').val(),
            thumbnail = 0,
            content = '',
            errors = false;

        jQuery('.block').each(function () {
            if (jQuery(this).find('.blockImageFileType').is(':checked')) {
                thumbnail = jQuery(this).find('.ip-block-content').data('image-id');
            }

            jQuery(this).find('.ip-block-content').attr('contenteditable', false);
            content += jQuery(this).find('.ip-block-content')[0].outerHTML;
        });

        if (thumbnail === 0) {
            errors = true;

            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Your project needs a thumbnail image!',
            });
        }
        if (parseInt(category, 10) === 0) {
            errors = true;

            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Your project needs a category!',
            });
        }
        if (title === '') {
            errors = true;

            swal({
                type: 'error',
                title: 'Oops...',
                text: 'Your project needs a title!',
            });
        }

        if (errors === false) {
            jQuery('#block-status').html('Creating your project...');

            jQuery.ajax({
                method: 'post',
                url: ip_ajax_var.ajaxurl,
                data: {
                    blockTitle: title,
                    blockDescription: description,
                    blockCategory: category,
                    blockKeywords: keywords,
                    blockVideoUri: videoUri,
                    blockPurchaseUri: purchaseUri,
                    blockContent: content,
                    blockThumbnail: thumbnail,
                    action: 'ip_project_save'
                },
                success: function () {
                    var htmlBlock = '<p><i class="far fa-smile-beam"></i></p><p>Great! Your project has been uploaded!<br>Now share it with the world!</p><p id="project-success"></p><p><a href="https://posterspy.com/upload-beta/" class="btn btn-primary">Create another project</a></p>';

                    jQuery('.ip-block-uploader').addClass('ip-flex-fix');
                    jQuery('.ip-block-uploader').html(htmlBlock);
                    showBlockImage(thumbnail, jQuery('#project-success'));
                }
            });
        }

        event.preventDefault();
    });

    /**
     * Edit and save a block-based poster project
     *
     * @todo Remove all content editables
     */
    jQuery(document).on('click', '#yay-edit', function (event) {
        jQuery('#block-status').html('Saving your project...');

        var title = jQuery('#ip-block-title').val(),
            description = jQuery('#ip-block-description').val(),
            category = jQuery('#imagepress_image_category').val(),
            keywords = jQuery('#ip-block-keywords').val(),
            videoUri = jQuery('#ip-block-video-url').val(),
            purchaseUri = jQuery('#ip-block-purchase-link').val(),
            projectId = jQuery('#project_id').val(),
            thumbnail = 0,
            content = '';

        // Clean up contenteditable areas
        [].forEach.call(document.querySelectorAll('.ip-block-content[contenteditable="true"]'), function (element) {
            element.addEventListener('paste', function (event) {
                event.preventDefault();
                var text = event.clipboardData.getData('text/plain');
                document.execCommand('insertHTML', false, text);
            }, false);
        });

        jQuery('.block').each(function () {
            if (jQuery(this).find('.blockImageFileType').is(':checked')) {
                thumbnail = jQuery(this).find('.blockImageFileType').data('image-id');
            }

            jQuery(this).find('.ip-block-content').attr('contenteditable', false);
            content += jQuery(this).find('.ip-block-content')[0].outerHTML;
        });

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                blockTitle: title,
                blockDescription: description,
                blockCategory: category,
                blockKeywords: keywords,
                blockVideoUri: videoUri,
                blockPurchaseUri: purchaseUri,
                blockContent: content,
                blockThumbnail: thumbnail,
                projectId: projectId,
                action: 'ip_project_edit'
            },
            success: function () {
                //var htmlBlock = '<p><i class="far fa-smile-beam"></i></p><p>Great! Your project has been uploaded!<br>Now share it with the world!</p><p id="project-success"></p><p><a href="https://posterspy.com/upload-beta/" class="btn btn-primary">Create another project</a></p>';
                alert('saved');

                //jQuery('.ip-block-uploader').addClass('ip-flex-fix');
                //jQuery('.ip-block-uploader').html(htmlBlock);
                //showBlockImage(thumbnail, jQuery('#project-success'));
            }
        });

        event.preventDefault();
    });



    /**
     * Delete a poster project
     */
    jQuery(document).on('click', '#yay-delete', function (event) {
        jQuery('#block-status').html('Deleting your project...');

        var projectId = jQuery('#project_id').val(),
            thumbnailId = jQuery('#project_thumbnail_id').val(),
            dataAttachmentId;

        // Delete attached images
        jQuery('.block').each(function (index, value) {
            dataAttachmentId = jQuery(this).find('img').data('attachment-id');

            if (dataAttachmentId !== undefined) {
                deleteBlockImage(dataAttachmentId);
            }
        });

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (result.value) {
                jQuery.ajax({
                    method: 'post',
                    url: ip_ajax_var.ajaxurl,
                    data: {
                        thumbnailId: thumbnailId,
                        projectId: projectId,
                        action: 'ip_project_delete'
                    },
                    success: function () {
                        window.location.href = 'https://posterspy.com/';
                    }
                });

                swal(
                    'Deleted!',
                    'Your project has been deleted.',
                    'success'
                );
            }
        });

        event.preventDefault();
    });



    /**
     * Upload image automatically, on selection
     */
    /*
    This is due to element being dynamically created and event delegation should be used to handle event.
    document.addEventListener('click',function(e){
        if(e.target && e.target.id== 'brnPrepend'){//do something}
    })
    jQuery makes it easier:
    $(document).on('click','#btnPrepend',function(){//do something})
    */
    jQuery(document).on('click', '#blockImageFile', function () {
        jQuery(this).val(null);
    });

    jQuery(document).on('change', '#blockImageFile', function (event) {
        var fileInputElement = document.getElementById('blockImageFile'),
            files = event.target.files,
            formData = new FormData(),
            blockReference = jQuery(this).parent().parent(), // .ip-block-content-uploader
            blockSiblingReference = jQuery(this).parent().parent().next(), // .ip-block-content
            blockCheckboxReference = jQuery(this).parent().parent().next().next().find('.blockImageFileType'); // the checkbox

        blockReference.append('<p><i class="fas fa-circle-notch fa-spin"></i> Uploading image...</p>');

        formData.append('action', 'ip_project_save_image');
        formData.append('async-upload', fileInputElement.files[0]);
        formData.append('name', fileInputElement.files[0].name);

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: formData,
            contentType: false,
            dataType: 'json',
            cache: false,
            processData: false,
            success: function (response) {
                blockSiblingReference.attr('data-image-id', response);
                blockCheckboxReference.attr('data-image-id', response); // .blockImageFileType
                blockReference.remove();
                showBlockImage(response, blockSiblingReference);
            }
        });
    });



    jQuery(document).on('click', '.block-delete', function (event) {
        jQuery(this).parent().parent().remove();

        var dataImageId = jQuery(this).parent().prev().data('image-id');

        if (dataImageId !== undefined) {
            deleteBlockImage(dataImageId);
        }

        event.preventDefault();
    });




    // only to show where is the drop-zone:
    jQuery(document).on('dragenter', '#blockImageFile', function () {
        this.classList.add('dragged-over');
    }).on('dragend drop dragexit', '#blockImageFile', function () {
        this.classList.remove('dragged-over');
    });


    if (document.querySelector('.page-id-88154')) {
        window.onbeforeunload = function () {
            return "You're not finished your upload, leaving this page will delete your progress";
        };
    }



    /**
     * Project editor functions
     */
    if (document.querySelector('.ip-editor .ip-block-container')) {
        var project = jQuery('#ip-imagepress-project').val();

        var dom_nodes = jQuery(jQuery.parseHTML(project));
        
        jQuery(dom_nodes).each(function (index, value) {
            // Restore contenteditable parameter
            jQuery(value).attr('contenteditable', true);

            // Check if block is an image block and add checkbox
            /**
            if (jQuery(value).attr('data-image-id')) {
                value.outerHTML += '<div class="ip-block-description"><input type="checkbox" name="blockImageFileType" id="blockImageFileType" class="blockImageFileType"> <label for="blockImageFileType">Thumbnail image</label></div>';
            }
            /**/

            jQuery('#ip-blocks').append('<li class="block">' + value.outerHTML + '<div class="ip-block-delete"><a href="#" class="block-delete"><i class="fas fa-times"></i></a></div></li>');
        });
    }
});
