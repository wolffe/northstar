(function($){
    jQuery.fn.jConfirmAction = function(options){
        var theOptions = jQuery.extend({
            question: 'Are you sure you want to delete this image? This action is irreversible!',
            yesAnswer: 'Yes',
            cancelAnswer: 'No'
        }, options);

        return this.each(function(){
            $(this).bind('click', function(e){
                e.preventDefault();
                var thisHref = $(this).attr('href');
                if($(this).next('.question').length <= 0)
                    $(this).after('<div class="question"><i class="fas fa-exclamation-triangle"></i> ' + theOptions.question + '<br><span class="yes button noir-secondary">' + theOptions.yesAnswer + '</span><span class="cancel button noir-default">' + theOptions.cancelAnswer + '</span></div>');

                $(this).next('.question').animate({opacity: 1}, 300);
                $('.yes').bind('click', function(){
                    window.location = thisHref;
                });

                $('.cancel').bind('click', function(){
                    $(this).parents('.question').fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            });
        });
    }
})(jQuery);



jQuery.fn.extend({
    greedyScroll: function(sensitivity) {
        return this.each(function() {
            jQuery(this).bind('mousewheel DOMMouseScroll', function(evt) {
               var delta;
               if (evt.originalEvent) {
                  delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
               }
               if (delta !== null) {
                  evt.preventDefault();
                  if (evt.type === 'DOMMouseScroll') {
                     delta = delta * (sensitivity ? sensitivity : 20);
                  }
                  return jQuery(this).scrollTop(delta + jQuery(this).scrollTop());
               }
            });
        });
    }
});

function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
	if(bytes === 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	if(i === 0) return bytes + ' ' + sizes[i]; 
	return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

function ajaxReloadLike(lid) {
    jQuery.ajax({
        type: 'post',
        url: ip_ajax_var.ajaxreloadurl,
        data: 'id=' + lid,
        success: function(result) {
            jQuery('#ip-who-value').html(result);
        }
    });
}

// This one is IE10+
// https://developer.mozilla.org/en-US/docs/Web/API/Window/matchMedia
function isMobileSpy() {
    return jQuery('#mobile-spy').is(':visible');
}

jQuery(document).ready(function($) {
    if (isMobileSpy() && jQuery('.profile-hub-container').length) {
        jQuery('.ip-tabs').insertAfter(jQuery('.cinnamon-profile-sidebar'));
    }

    // Set up the "collect" action
    /**/
    jQuery('body').on('click', '.imagepress-collect', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var collect = jQuery(this),
            pid = collect.data('post-id'), // poster ID
            cid = jQuery('#ip_collections').val(), // collection ID (if existing)
            cnew = jQuery('#ip_collections_new').val(), // collection name (if new)
            cstatus = jQuery('#collection_status').val(); // collection status (if new)

        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxcollecturl,
            data: 'pid=' + pid + '&cid=' + cid + '&cnew=' + cnew + '&cstatus=' + cstatus,
            success: function(result) {
                jQuery('.showme').show().delay(2000).fadeOut(100, function() {
                    jQuery('.frontEndModal').removeClass('active').fadeOut();
                });
            }
        });
    });
    /**/

    // Set up the "like" action
    jQuery('body').on('click', '.imagepress-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var like = jQuery(this),
            pid = like.data('post_id'),
            howManyLikes = parseInt(jQuery('.ip-count-value').text()); // current number of likes

        like.html('<i class="fas fa-heart"></i> <i class="fas fa-circle-notch fa-spin"></i>');
        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ip_ajax_var.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count) {
                if(count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');
                    if(lecount === '0') {
                        lecount = ip_ajax_var.likelabel;
                    }
                    like.removeClass('liked');
                    like.html('<i class="fas fa-heart"></i> <span class="ip-count-value">' + lecount + '</span>');
                    jQuery('.ip-count-value').text(howManyLikes - 1); // decrease likes
                    ajaxReloadLike(pid);
                }
                else {
                    count = ip_ajax_var.unlikelabel;
                    like.addClass('liked');
                    like.html('<i class="far fa-heart"></i> <span class="ip-count-value">' + count + '</span>');
                    jQuery('.ip-count-value').text(howManyLikes + 1); // increase likes
                    ajaxReloadLike(pid);
                }
            }
        });
        return false;
    });

    // Set up the "like" action
    jQuery('body').on('click', '.feed-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        var like = jQuery(this),
            pid = like.data('post_id'),
            howManyLikes = jQuery(this).find('.ip-count-value').text(); // current number of likes

        like.html('<i class="fas fa-fw fa-heart"></i> <span class="ip-count-value">' + howManyLikes + '</span>');

        jQuery('[data-post_id="' + pid + '"] .fa-heart').addClass('like--on');
        jQuery('[data-post_id="' + pid + '"] .fa-heart-o').addClass('like--on');
        setTimeout(function() {
            jQuery('[data-post_id="' + pid + '"] .fa-heart').removeClass('like--on');
            jQuery('[data-post_id="' + pid + '"] .fa-heart-o').removeClass('like--on');
            console.log('one second');
        }, 1000);

        jQuery.ajax({
            type: 'post',
            url: ip_ajax_var.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ip_ajax_var.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count) {
                if (count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');

                    if (lecount === '0') {
                        lecount = ip_ajax_var.likelabel;
                    }

                    like.removeClass('liked');
                    like.html('<i class="fas fa-fw fa-heart"></i> <span class="ip-count-value">' + lecount + '</span>');
                    like.html('<i class="far fa-fw fa-heart"></i> <span class="ip-count-value">' + lecount + '</span>');
                    like.find('.ip-count-value').text(howManyLikes - 1); // decrease likes
                } else {
                    count = ip_ajax_var.unlikelabel;
                    like.addClass('liked');
                    like.html('<i class="far fa-fw fa-heart"></i> <span class="ip-count-value">' + count + '</span>');
                    like.html('<i class="fas fa-fw fa-heart"></i> <span class="ip-count-value">' + count + '</span>');
                    like.find('.ip-count-value').text(parseInt(howManyLikes) + 1); // increase likes
                }
            }
        });
        return false;
    });

	// begin upload
	/**
    jQuery('#imagepress_upload_image_form').submit(function(){
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<i class="fas fa-cog fa-spin"></i> Uploading...');
    });
    /**/

    //
	var fileInput = jQuery('#imagepress_image_file');
	var maxSize = fileInput.data('max-size');
	var maxWidth = fileInput.data('max-width');
	jQuery('#imagepress_image_file').change(function(e){
		if(fileInput.get(0).files.length){
			var fileSize = fileInput.get(0).files[0].size; // in bytes
			if(fileSize > maxSize) {
				jQuery('#imagepress-errors').append('<p>Warning: File size is too big (' + bytesToSize(fileSize) + ')!</p>');
				jQuery('#imagepress_submit').attr('disabled', true);
				return false;
			}
			else {
				jQuery('#imagepress-errors').html('');
				jQuery('#imagepress_submit').removeAttr('disabled');
			}
		}
		else {
			//alert('choose file, please');
			return false;
		}
	});

    //
    jQuery('#imagepress_upload_image_form').submit(function(e){
		jQuery('#imagepress-errors').html('');
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<i class="fas fa-cog fa-spin"></i> Uploading...');
    });
	// end upload

    jQuery(document).on('click', '#ip-editor-open', function(e){
        jQuery('.ip-editor').slideToggle('fast');
        e.preventDefault();
    });

    jQuery('.ask').jConfirmAction();

    // ip_editor() related actions
    jQuery('.delete-post').click(function(e){
        if(confirm('Delete this image?')) {
            jQuery(this).parent().parent().fadeOut();

            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ip_ajax_var.ajaxurl,
                data: {
                    action: 'ip_delete_post',
                    nonce: nonce,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        post.fadeOut(function(){
                            post.remove();
                        });
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });
    jQuery('.featured-post').click(function(e){
        if(confirm('Set this image as main image?')) {
            jQuery(this).parent().parent().css('border', '3px solid #ffffff');

            var pid = jQuery(this).data('pid');
            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            var post = jQuery(this).parents('.post:first');
            jQuery.ajax({
                type: 'post',
                url: ip_ajax_var.ajaxurl,
                data: {
                    action: 'ip_featured_post',
                    nonce: nonce,
                    pid: pid,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        /*
                        post.fadeOut(function(){
                            post.remove();
                        });
                        */
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });

    // notifications
	jQuery('.notifications-container .notification-item.unread').click(function(){
		var id = jQuery(this).data('id');
		jQuery.ajax({
			type: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'notification_read',
				id: id
			}
		});
	});

	// mark all as read
	jQuery('.ip_notification_mark').click(function(e){
		e.preventDefault();
		var userid = jQuery(this).data('userid');
		jQuery.ajax({
			type: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'notification_read_all',
				userid: userid
			}
		});

		//jQuery('.notifications-bell sup').hide();
		jQuery('.notifications-bell').html('<i class="far fa-bell"></i><sup>0</sup>');
	});

	jQuery('.notifications-container .notifications-inner').greedyScroll(25);
    jQuery('.notifications-container').hide();
    jQuery('.notifications-bell').click(function(e){
        jQuery('.notifications-bell').toggleClass('on');
        jQuery('.notifications-container').toggle();
        e.preventDefault();
    });
    jQuery('.notifications-container').mouseleave(function(e){
        jQuery('.notifications-bell').removeClass('on');
        jQuery('.notifications-container').fadeOut('fast');
        e.preventDefault();
    });
    //


    // profile specific functions
	(function($) {
		$('.ip-tab .ip-tabs').addClass('active').find('> li:eq(0)').addClass('current');
        $('.ip-tab .ip-tabs li a:not(.imagepress-button)').click(function (g) {
            var tab = $(this).closest('.ip-tab'),
                index = $(this).closest('li').index();

            tab.find('.ip-tabs > li').removeClass('current');
            $(this).closest('li').addClass('current');

            tab.find('.tab_content').find('.ip-tabs-item').not('.ip-tabs-item:eq(' + index + ')').slideUp();
            tab.find('.tab_content').find('.ip-tabs-item:eq(' + index + ')').slideDown();

            runMasonry('#ip-boxes');

            g.preventDefault();
        });
    })(jQuery);

    // portfolio specific functions
    jQuery("#cinnamon-feature").hide();
    jQuery("#cinnamon-index").hide();
    jQuery(".cinnamon-grid-blank a").click(function(e) {
        e.preventDefault();
        var image = jQuery(this).attr("rel");
        jQuery("#cinnamon-feature").html('<img src="' + image + '" alt="">');
        jQuery("#cinnamon-feature").show();
        jQuery("#cinnamon-index").fadeIn();
    });
    jQuery("#cinnamon-index a").click(function(e) {
        e.preventDefault();
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });
    jQuery(".c-index").click(function() {
        jQuery("#cinnamon-feature").hide();
        jQuery("#cinnamon-index").hide();
    });

    jQuery('#ip-tab li:first').addClass('active');
    jQuery('.tab_icerik').hide();
    jQuery('.tab_icerik:first').show();
    jQuery('#ip-tab li').click(function(e) {
        var index = jQuery(this).index();
        jQuery('#ip-tab li').removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.tab_icerik').hide();
        jQuery('.tab_icerik:eq(' + index + ')').show();
        return false
    });

    jQuery("#cinnamon_sort").change(function(){ this.form.submit(); });

	jQuery('.follow-links a').on('click', function(e) {
        e.preventDefault();
        var $this = jQuery(this);
        if(ip_ajax_var.logged_in != 'undefined' && ip_ajax_var.logged_in != 'true') {
            alert(ip_ajax_var.login_required);
            return;
        }

        var data = {
            action: $this.hasClass('follow') ? 'follow' : 'unfollow',
			user_id: $this.data('user-id'),
			follow_id: $this.data('follow-id'),
			nonce: ip_ajax_var.nonce
		};

        //$this.html('<i class="fas fa-cog fa-spin fa-fw"></i>').fadeOut();
        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').hide();

        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fas fa-check fa-fw"></i> Following');
        //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #2ECC71 !important');

        //jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').show();
        //$this('img.pwuf-ajax').show();

        jQuery.post(ip_ajax_var.ajaxurl, data, function(response) {
            /**/
			if(response == 'success') {
                console.log(data['action']);
                if (data['action'] === 'follow') {
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fas fa-check fa-fw"></i> Following');
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #2ECC71 !important');
                    jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').removeClass('follow').addClass('unfollow').addClass('followed');
                } else if (data['action'] === 'unfollow') {
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').html('<i class="fas fa-plus fa-fw"></i> Follow');
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').attr('style', 'background-color: #02b2fc !important');
                    jQuery('.unfollow[data-follow-id="' + $this.data('follow-id') + '"]').removeClass('unfollow').removeClass('followed').addClass('follow');
                }
				//$this.toggle();
            }
			//else
				//alert(ip_ajax_var.processing_error);
            /**/
			jQuery('img.pwuf-ajax').hide();
            //jQuery('.follow[data-follow-id="' + $this.data('follow-id') + '"]').hide();
        });
	});

    jQuery(document).on('mouseover', '.unfollow.followed.imagepress-button', function() {
        jQuery(this).attr('style', 'background-color: #E74C3C !important');
        jQuery(this).html('<i class="fas fa-fw fa-times"></i> Unfollow');
    });
    jQuery(document).on('mouseout', '.unfollow.followed.imagepress-button', function() {
        jQuery(this).attr('style', 'background-color: #2ECC71 !important');
        jQuery(this).html('<i class="fas fa-fw fa-check"></i> Following');
    });

    jQuery(document).on('click', '.slide', function() {
		jQuery('.view').slideToggle(100);

        return false;
	});

	jQuery('.social-hub').hide();
    jQuery(document).on('click', '#lightbox-share', function() {
		jQuery('.social-hub').slideToggle(100);

        return false;
	});



	jQuery('.initial i').addClass('teal');
    jQuery(document).on('click', '.sort', function(e){
		jQuery('.sort i').removeClass('teal');
		jQuery('i', this).addClass('teal');
	});



    jQuery(document).on('click', '.imagecategory', function(e){
		var tag = jQuery(this).data('tag')
        console.log('clicked on category ' + tag);
		jQuery('body').find('#ipsearch').val(tag);
		jQuery('body').find('#ipsearch').focus();

        jQuery('body').find('#ipsearch').trigger('keyup');
	});



	// Collections
    jQuery(document).on('click', '.changeCollection', function (event) {
        var collection = jQuery(this).data('collection-id');

        jQuery('.cde' + collection).toggleClass('active');
        event.preventDefault();
    });
    jQuery(document).on('click', '.closeCollectionEdit', function (event) {
        var collection = jQuery(this).data('collection-id');

        jQuery('.cde' + collection).toggleClass('active');
        event.preventDefault();
    });
	jQuery(document).on('click', '.toggleModal', function(e){
		jQuery('.modal').toggleClass('active');
		e.preventDefault();
	});
	jQuery(document).on('click', '.toggleFrontEndModal', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});
	jQuery(document).on('click', '.toggleFrontEndModal .close', function(e){
		jQuery('.frontEndModal').toggleClass('active');
		e.preventDefault();
	});

    jQuery('.addCollection').click(function(e){
		jQuery('.addCollection').val('Creating...');
		jQuery('.collection-progress').fadeIn();
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'addCollection',
				collection_author_id: jQuery('#collection_author_id').val(),
				collection_title: jQuery('#collection_title').val(),
				collection_status: jQuery('#collection_status').val()
			}
		}).done(function(msg) {
			jQuery('.addCollection').val('Create another collection');
			jQuery('.collection-progress').hide();
			jQuery('.showme').fadeIn();
		});

		e.preventDefault();
	});

    jQuery(document).on('click', '.deleteCollection', function(e){
        jQuery('body').find('deleteCollection').hide();
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'deleteCollection',
				collection_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ipc' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});
    jQuery(document).on('click', '.deleteCollectionImage', function(e){
        var ipc = jQuery(this).data('image-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'deleteCollectionImage',
				image_id: ipc,
			}
		}).done(function(msg) {
			jQuery('.ip_box_' + ipc).fadeOut();
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

    jQuery(document).on('click', '.saveCollection', function(e){
        var ipc = jQuery(this).data('collection-id');
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'editCollectionTitle',
				collection_id: ipc,
				collection_title: jQuery('.ct' + ipc).val(),
			}
		}).done(function(msg) {
			jQuery('.collection_details_edit').removeClass('active');
			jQuery('.imagepress-collections').trigger('click');
		});

		e.preventDefault();
	});
    jQuery(document).on('change', '.collection-status', function(e){
        var ipc = jQuery(this).data('collection-id');

		var option = this.options[this.selectedIndex];

		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'editCollectionStatus',
				collection_id: ipc,
				collection_status: jQuery(option).val()
			}
		}).done(function(msg) {
			jQuery('.cde' + ipc).fadeOut('fast');
		});

		e.preventDefault();
	});

	jQuery('.modal .close').click(function(e){
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
		});

		e.preventDefault();
	});
	jQuery('.imagepress-collections').click(function(e){
		jQuery('.ip-loadingCollections').show();
		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				action: 'ip_collections_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollections').fadeOut();
		});

		e.preventDefault();
	});

	jQuery(document).on('click', '.editCollection', function(e){
		var ipc = jQuery(this).data('collection-id');
		jQuery('.ip-loadingCollectionImages').show();

		jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				collection_id: ipc,
				action: 'ip_collection_display',
			}
		}).done(function(msg) {
			jQuery('.collections-display').html(msg);
			jQuery('.ip-loadingCollectionImages').fadeOut();
		});

		e.preventDefault();
	});
	// end collections

    // Submit button is disable by default
    jQuery('#imagepress_submit').css('opacity', '0.5');
    jQuery('#imagepress_submit').attr('disabled', true);

    // Check if agreement has been checked
    jQuery(document).on('click', '#ip-agree', function(e) {
        if(jQuery('#ip-agree').is(':checked')) {
            jQuery('#imagepress_submit').css('opacity', '1');
            jQuery('#imagepress_submit').removeAttr('disabled');
        } else {
            jQuery('#imagepress_submit').css('opacity', '0.5');
            jQuery('#imagepress_submit').attr('disabled', true);
            //jQuery('#imagepress_submit').prop('disabled', true);
        }
    })
});





function postPrivateMessage() {
    var receiver = jQuery('.pm-new #pm_to').val(),
        message = jQuery('.pm-new #pm_message').val();

    if (message.length) {
        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                receiver: receiver,
                message: message,
                action: 'ip_post_pm_thread',
            },
            success: function (data) {
                jQuery('#pm_message').val('');

                jQuery.ajax({
                    method: 'post',
                    url: ip_ajax_var.ajaxurl,
                    data: {
                        user_id: receiver,
                        action: 'ip_get_pm_thread',
                    },
                    success: function (data) {
                        jQuery('.pm-right-inner').html(data);
                        //jQuery('.pm-new').show();
                        jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                    }
                });
            }
        });
    }

    return false;
}



// AJAX call for autocomplete 
jQuery(document).ready(function () {
    if (parseInt(jQuery('#pm-enable').val()) === 0) {
        jQuery('#pm-enable').prop('checked', false);
        jQuery('#pm-message').html('<p>Message requests are currently disabled.</p>');
    } else {
        jQuery('#pm-enable').prop('checked', true);
    }

    jQuery(document).on('change', '#pm-enable', function() {
        jQuery('#pm-message').html('<p><i class="fas fa-circle-notch fa-spin"></i> Saving...</p>');

        var pm_user_id = jQuery(this).data('user-id'),
            pm_value = 0;

        if (document.getElementById('pm-enable').checked) {
            pm_value = 1;
        }

        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
                pm_user_id: pm_user_id,
                pm_value: pm_value,
                action: 'ip_user_pm_enable',
            },
            success: function (data) {
                jQuery('#pm-message').html('<p>Settings saved.</p>');
            }
        });
    });

    jQuery("#search-box").keyup(function(){
        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				username: jQuery(this).val(),
				action: 'ip_user_select',
            },
            beforeSend: function(){
                jQuery("#search-box").css("background","rgba(0, 0, 0, 0.25) url(https://posterspy.com/wp-content/plugins/imagepress/img/1-1.gif) no-repeat center right");
            },
            success: function(data){
                jQuery("#suggesstion-box").show();
                jQuery("#suggesstion-box").html(data);
                jQuery("#search-box").css("background","rgba(0, 0, 0, 0.25)");
            }
        });
    });

    jQuery(".pm-message-single").click(function () {
        var sender = jQuery(this).data('sender');

        jQuery('.pm-right-inner').html('<div class="pm-right-inner--centered"><img src="https://posterspy.com/wp-content/plugins/imagepress/img/1-1.gif" alt="Loading"></div>');

        jQuery.ajax({
			method: 'post',
			url: ip_ajax_var.ajaxurl,
			data: {
				user_id: sender,
				action: 'ip_get_pm_thread',
            },
            success: function (data) {
                jQuery('.pm-right-inner').html(data);
                jQuery('.pm-new').show();
                jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                jQuery('.pm-right').append('<span id="pm-beacon" data-sender-id="' + sender + '">[beacon]</span>');
            }
        });
    });



    jQuery(document).on('submit', 'form#pm_send_form', function (e) {
        postPrivateMessage();
    });

    jQuery(document).on('click', 'a#pm_send', function (e) {
        postPrivateMessage();

        e.preventDefault();
    });

    // Set limit on load
    //jQuery('#pm-load-limit').attr('data-limit', 5);

    jQuery(document).on('click', '#pm-load-limit', function () {
        var loadLimit = jQuery(this).attr('data-limit'),
            loadReceiver= jQuery(this).data('receiver');

        loadLimit = parseInt(loadLimit) + 5;
        console.log('should set new limit for ' + loadLimit);
        jQuery('#pm-load-limit').attr('data-limit', loadLimit);

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                user_id: loadReceiver,
                pm_message_limit: loadLimit,
                action: 'ip_get_pm_thread',
            },
            success: function (data) {
                jQuery('.pm-right-inner').html(data);
                jQuery('.pm-new').show();
                //jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
            }
        });
    });



    // Javascript to enable link to tab
    var hash = document.location.hash;
    if (hash) {
        document.querySelectorAll('.whiskey-tabs li a[href="' + hash + '"]')[0].click();
    }

    var tabLinks = document.querySelectorAll('.whiskey-tabs li a');

    for (var i = 0; i < tabLinks.length; i++) { 
        tabLinks[i].onclick = function() {
            var target = this.getAttribute('href').replace('#', '');
            var sections = document.querySelectorAll('.whiskey-tab-content');

            for (var j=0; j < sections.length; j++) {
                sections[j].style.display = 'none';
            }

            document.getElementById(target).style.display = 'block';

            for (var k=0; k < tabLinks.length; k++) {
                tabLinks[k].removeAttribute('class');
            }

            this.setAttribute('class', 'is-active');

            return false;
        }
    };
});
//To select country name
function selectUser(id, val) {
    jQuery("#search-box").val(val);
    jQuery("#pm_to").val(id);
    jQuery("#suggesstion-box").hide();
}


/**
jQuery(window).load(function(){
    jQuery('#hub-loading').fadeOut(100);
});
/**/




//console.log('init');
var intervalId = setInterval(function () {
    // Check for a specific element ID
    if (document.getElementById('pm-beacon')) {
        var senderId = jQuery('#pm-beacon').data('sender-id'),
            loadLimit = jQuery('#pm-load-limit').attr('data-limit'),
            loadData = jQuery('.pm-right-inner').html().replace(/ scale="0"/g, '');
        //console.log('abcd' + senderId + ' ' + loadLimit);
        //console.log('aaa:' + loadData);

        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                user_id: senderId,
                pm_message_limit: loadLimit,
                action: 'ip_get_pm_thread',
            },
            success: function (data) {
                if (data.replace(/ \/>/g, '>') === loadData) {
                    //console.log('the same');
                } else {
                    //console.log('not the same, refreshing');
                    jQuery('.pm-right-inner').html(data);
                    jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
                }
                //console.log('bbb:' + data.replace(/ \/>/g, '>'));
                //jQuery('.pm-right-inner').scrollTop(jQuery('.pm-right-inner')[0].scrollHeight);
            }
        });
    }
}, 500);





jQuery(function(){
	jQuery("#new_collection").hide();
	jQuery('#imagepress_collection').change(function() {
		if(jQuery(this).find('option:selected').val() == "other") {
			jQuery("#new_collection").show();
		} else {
			jQuery("#new_collection").hide();
		}
	});
});




/**
function existingTag(text) {
	var existing = false,
		text = text.toLowerCase();

	$(".tags").each(function(){
		if ($(this).text().toLowerCase() == text) 
		{
			existing = true;
			return "";
		}
	});

	return existing;
}

$(function(){
  $(".hub-skills-new input").focus();
  
  $(".hub-skills-new input").keyup(function(){

		var tag = $(this).val().trim(),
		length = tag.length;

		if((tag.charAt(length - 1) == ',') && (tag != ","))
		{
			tag = tag.substring(0, length - 1);

			if(!existingTag(tag))
			{
				$('<li class="hub-skills"><span>' + tag + '</span><i class="fas fa-times"></i></i></li>').insertBefore($(".hub-skills-new"));
				$(this).val("");	
			}
			else
			{
				$(this).val(tag);
			}
		}
	});
  
  $(document).on("click", ".hub-skills i", function(){
    $(this).parent("li").remove();
  });

});
/**/

/**
 * Check when document is ready
 *
 * Checks when document is loaded and ready to accept changes
 */
function onDOMReady(callback) {
    if (document.readyState !== 'loading') {
        callback();
    } else if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', callback);
    } else { // IE <= 8
        document.attachEvent('onreadystatechange', function() {
            if (document.readyState === 'complete') {
                callback();
            }
        });
    }
}

onDOMReady(function () {
});

function runMasonry(element) {
    if (document.querySelector('.ip-box-container')) {
        var elements = document.getElementsByClassName('ip-box-container');

        for (var i = 0; i < elements.length; i++) {
            var container = elements[i];
            var msnry = new Masonry(container, {
                itemSelector: '.ip_box ',
                columnWidth: '.ip_box',
                gutter: 0,
            });
        }
    }
    /**
    if (document.querySelector('#ip-boxes')) {
        element = '#ip-boxes';

        var container = document.querySelector(element);
        var msnry = new Masonry(container, {
            itemSelector: '.ip_box ',
            columnWidth: '.ip_box',
            gutter: 0,
        });
    }
    if (document.querySelector('.cinnamon-likes div div')) {
        element = '.cinnamon-likes div div';

        var container = document.querySelector(element);
        var msnry = new Masonry(container, {
            itemSelector: '.ip_box ',
            columnWidth: '.ip_box',
            gutter: 0,
        });
    }
    /**/
}

jQuery(window).on('load', function () {
    // Enable Masonry for poster images
    runMasonry('#ip-boxes');

    jQuery(document).on('click', '#moon-tabs a', function () {
        runMasonry('#ip-boxes');
    });

    // Load collections on settings/collection-manager page
    // https://posterspy.com/settings/collection-manager/
    if (document.querySelector('.page-id-88007')) {
        jQuery('.ip-loadingCollections').show();
        jQuery.ajax({
            method: 'post',
            url: ip_ajax_var.ajaxurl,
            data: {
                action: 'ip_collections_display',
            }
        }).done(function(msg) {
            jQuery('.collections-display').html(msg);
            jQuery('.ip-loadingCollections').fadeOut();

            // Collection link copy to clipboard
            document.querySelector('.collection-details-url').addEventListener('click', function (event) {
                var copyTextarea = document.querySelector('.collection-details-url');
                copyTextarea.select();

                try {
                    var successful = document.execCommand('copy');
                    var msg = successful ? 'successful' : 'unsuccessful';

                    var theDiv = document.querySelector('.ip-collection-clipboard');
                    var content = document.createElement('span');
                    content.innerHTML = 'Copied to clipboard!';
                    theDiv.appendChild(content);
                } catch (err) {
                    // Unable to copy
                }
            });
        });
    }

    // Sortable
    var blockContainer = document.getElementById('ip-blocks');
    var sortable = Sortable.create(blockContainer, {
        onUpdate: function (evt) {
            console.log(sortable.toArray());
            //sortable.sort(order.reverse()); // apply
        },
    });

    // select the accordion
    blockContainer.addEventListener('click', function (event) {
        if (event.target.className === 'ip-block-title') {
            var blockContent = event.target.nextElementSibling;
            blockContent.classList.toggle('ip-block-content-open');
        }
    });

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




    /**
     * Create and save a block-based poster project
     *
     * @todo Remove all content editables
     */
    jQuery(document).on('click', '#yay', function (event) {
        jQuery('#block-status').html('Creating your project...');

        var title = jQuery('#ip-block-title').val(),
            description = jQuery('#ip-block-description').val(),
            category = jQuery('#imagepress_image_category').val(),
            keywords = jQuery('#ip-block-keywords').val(),
            videoUri = jQuery('#ip-block-video-url').val(),
            purchaseUri = jQuery('#ip-block-purchase-link').val(),
            thumbnail = '',
            content = '';

        jQuery('.block').each(function (index, value) {
            jQuery(this).find('.ip-block-content').attr('contenteditable', false);
            content += jQuery(this).find('.ip-block-content')[0].outerHTML;
        });

        jQuery('.block').each(function (index, value) {
            if (jQuery(this).find('.blockImageFileType').is(':checked')) {
                thumbnail = jQuery(this).find('.blockImageFileType').data('image-id');
                console.log('thumbnail found: ' + thumbnail);
            }
        });

        console.log(content);

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
                action: 'ip_project_save',
            },
            success: function (data) {
                jQuery('#block-status').html('Done! Page should be reloaded or something.');
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
    jQuery(document).on('click', '#blockImageFile', function() {
        jQuery(this).val(null);
    });

    jQuery(document).on('change', '#blockImageFile', function(event) {
        var fileInputElement = document.getElementById('blockImageFile'),
            files = event.target.files,
            formData = new FormData(),
            blockReference = jQuery(this).parent().parent(), // .ip-block-content-uploader
            blockSiblingReference = jQuery(this).parent().parent().next(), // .ip-block-content
            blockCheckboxReference = jQuery(this).parent().parent().next().next().find('.blockImageFileType'); // the checkbox

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
                blockCheckboxReference.attr('data-image-id', response) // .blockImageFileType
                blockReference.remove();
                showBlockImage(response, blockSiblingReference);
            }
        });
    });



    jQuery(document).on('click', '.block-delete', function (event) {
        jQuery(this).parent().parent().remove();

        var previousContentBlock = jQuery(this).parent().prev().prev().prev(),
            dataImageId = jQuery(this).parent().prev().prev().data('image-id');

        if (previousContentBlock.hasClass('ip-block-content-uploader')) {
            console.log('image should also be deleted');
        }
        if (dataImageId !== undefined) {
            console.log('image is ' + dataImageId);
            deleteBlockImage(dataImageId);
        }

        event.preventDefault();
    });

});


function showBlockImage(imageId, elementReference) {
    jQuery.ajax({
        method: 'post',
        url: ip_ajax_var.ajaxurl,
        data: {
            imageId: imageId,
            action: 'ip_project_show_image',
        },
        success: function (response) {
            elementReference.html(response);
        }
    });
}

function deleteBlockImage(imageId) {
    jQuery.ajax({
        method: 'post',
        url: ip_ajax_var.ajaxurl,
        data: {
            imageId: imageId,
            action: 'ip_project_delete_image',
        },
        success: function (response) {
            //
        }
    });
}


function appendBlock(blockType) {
    var container = document.getElementById('ip-blocks'),
        block = document.createElement('li'),
        blockContent;

    if (blockType === 'image') {
        blockContent = '<div class="ip-block-content-uploader">' +
            '<form enctype="multipart/form-data"><input type="file" name="blockImageFile" id="blockImageFile"></form>' +
            '</div>' +
            '<div class="ip-block-content">' +
                '' +
            '</div>' +
            '<div><input type="checkbox" name="blockImageFileType" id="blockImageFileType" class="blockImageFileType"> <label for="blockImageFileType">Main Image (also cover image)</label></div>';
    } else if (blockType === 'video') {
        blockContent = '<div class="ip-block-title">Video Block</div>' +
            '<div class="ip-block-content">' +
                '<input type="url"> Youtube URL<br>' +
                '<div>PRO only<br><input type="file" name="video[]"></div>' +
            '</div>';
    } else if (blockType === 'heading') {
        blockContent = '<h3 class="ip-block-content ip-block-heading" contenteditable="true" data-ph="Click to edit"></h3>';
    } else if (blockType === 'paragraph') {
        blockContent = '<p class="ip-block-content ip-block-paragraph" contenteditable="true" data-ph="Click to edit"></p>';
    } else if (blockType === 'caption') {
        blockContent = '<p class="ip-block-content ip-block-caption" contenteditable="true" data-ph="Click to edit"></p>';
    }

    blockContent += '<div><a href="#" class="block-delete">Delete block</a></div>';

    block.classList.add('block');
    block.innerHTML = blockContent;

    if (blockContent.indexOf('ip-block-heading') > 0) {
        block.classList.add('heading');
    }

    container.appendChild(block);
}
