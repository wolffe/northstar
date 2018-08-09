(function ($) {
    $.fn.imageLens = function (options) {

        var defaults = {
            lensSize: 100,
            borderSize: 4,
            borderColor: "#888",
            lensCss: "lensItem"
        };
        var options = $.extend(defaults, options);
        var lensStyle = "background-position: 0px 0px;width: " + String(options.lensSize) + "px;height: " + String(options.lensSize)
            + "px;float: left;display: none;border-radius: " + String(options.lensSize / 2 + options.borderSize)
            + "px;border: " + String(options.borderSize) + "px solid " + options.borderColor 
            + ";background-repeat: no-repeat;position: absolute;";

        return this.each(function () {
            obj = $(this);

            var offset = $(this).offset();

            // Creating lens
            var target = $("<div style='" + lensStyle + "' class='" + options.lensCss + "'>&nbsp;</div>").appendTo($("body"));
            var targetSize = target.size();

            // Calculating actual size of image
            var imageSrc = options.imageSrc ? options.imageSrc : $(this).attr("src");
            var imageTag = "<img class='lensMeHelper' style='display: none;' src='" + imageSrc + "' />";

            var widthRatio = 0;
            var heightRatio = 0;

            $(imageTag).load(function () {
                widthRatio = $(this).width() / obj.width();
                heightRatio = $(this).height() / obj.height();
            }).appendTo($(this).parent());

            target.css({ backgroundImage: "url('" + imageSrc + "')" });

            target.mousemove(setPosition);
            $(this).mousemove(setPosition);

            function setPosition(e) {

                var leftPos = parseInt(e.pageX - offset.left);
                var topPos = parseInt(e.pageY - offset.top);

                if (leftPos < 0 || topPos < 0 || leftPos > obj.width() || topPos > obj.height()) {
                    target.hide();
                }
                else {
                    target.show();

                    leftPos = String(((e.pageX - offset.left) * widthRatio - target.width() / 2) * (-1));
                    topPos = String(((e.pageY - offset.top) * heightRatio - target.height() / 2) * (-1));
                    target.css({ backgroundPosition: leftPos + 'px ' + topPos + 'px' });

                    leftPos = String(e.pageX - target.width() / 2);
                    topPos = String(e.pageY - target.height() / 2);
                    target.css({ left: leftPos + 'px', top: topPos + 'px' });
                }
            }
        });
    };
})(jQuery);
/**/



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



jQuery.fn.infiniteCarousel = function(config){
    config = jQuery.extend({
        duration : 0
    }, config);

    var viewportEl = this.find('.viewport'), listEl = viewportEl.find('.list');
    var first = listEl.children(":first"), last = listEl.children(":last");

    var distance, prevProp, nextProp;
    distance = Math.max(first.outerWidth(true), last.outerWidth(true));

    function move(config) {
        if(config.dir === 'next') {
            viewportEl.animate({ left : '+=' + distance }, config.duration, function(){
                listEl.children(":last").after(listEl.children(":first"));
            });
        }

        if(config.dir === 'pre') {
            viewportEl.animate({ left : '-=' + distance }, config.duration, function(){
                listEl.prepend(listEl.children(":last"));
            });
        }
    }

    this.find('.pre').click(function() {
        move(jQuery.extend(config,{
            dir: "pre"
        }));
    });
    this.find('.next').click(function() {
        move(jQuery.extend(config,{
            dir: "next"
        }));
    });

    return this;
};



// New posters page (sorting and filtering)
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};






jQuery(document).ready(function() {
    jQuery(document).on('click', '.cinnamon-dropdown', function(e) {
        jQuery('.cinnamon-dropdown-content').hide();
        jQuery(this).find('.cinnamon-dropdown-content').show();
    });

    jQuery(document).on('click', '.cinnamon-dropdown-content a', function() {
        jQuery('.cinnamon-dropdown-content').hide();
    });

    jQuery("body:not(.dropbtn)").click(function() {
        jQuery('.cinnamon-dropdown-content').hide();
    });

    var sort = getUrlParameter('sort');
    var range = getUrlParameter('range');

    if(sort !== undefined) {
        if(sort === 'comments')
            jQuery('.dropsort').html('Most Commented <i class="fas fa-chevron-down"></i>');
        if(sort === 'views')
            jQuery('.dropsort').html('Most Viewed <i class="fas fa-chevron-down"></i>');
        if(sort === 'likes')
            jQuery('.dropsort').html('Most Loved <i class="fas fa-chevron-down"></i>');
        if(sort === 'newest')
            jQuery('.dropsort').html('Newest <i class="fas fa-chevron-down"></i>');
        if(sort === 'oldest')
            jQuery('.dropsort').html('Oldest <i class="fas fa-chevron-down"></i>');
    }
    if(range !== undefined ) {
        if(range === 'lastday')
            jQuery('.droprange').html('Today <i class="fas fa-chevron-down"></i>');
        if(range === 'lastweek')
            jQuery('.droprange').html('This week <i class="fas fa-chevron-down"></i>');
        if(range === 'lastmonth')
            jQuery('.droprange').html('This month <i class="fas fa-chevron-down"></i>');
        if(range === 'alltime')
            jQuery('.droprange').html('All time <i class="fas fa-chevron-down"></i>');
    }

    // Posters per page // Cookie crumble
    jQuery(document).on('click', '.cinnamon-dropdown-options #ppp1', function() {
        document.cookie = "psppp=1";
        location.reload();
    });
    jQuery(document).on('click', '.cinnamon-dropdown-options #ppp2', function() {
        document.cookie = "psppp=2";
        location.reload();
    });
    jQuery(document).on('click', '.cinnamon-dropdown-options #ppp3', function() {
        document.cookie = "psppp=3";
        location.reload();
    });

    jQuery(document).on('click', '#showpurchase', function() {
        var pscookies = document.cookie;
        if(pscookies.indexOf('pspurchase=1') > -1) {
            document.cookie = "pspurchase=0";
            location.reload();
        } else {
            document.cookie = "pspurchase=1";
            location.reload();
        }
    });

    var pspurchase = document.cookie;
    if(pspurchase.indexOf('pspurchase=1') > -1) {
        jQuery('#showpurchase').prop('checked', true);
    } else {
        jQuery('#showpurchase').prop('checked', false);
    }

    if(jQuery('.poster-taxonomy-details').length) {
        var term = jQuery('.poster-taxonomy-details').data('term');
        jQuery('.term-' + term).addClass('current-term');
    } else {
        jQuery('.term-all').addClass('current-term');
    }

    var pscookies = document.cookie;
    if(pscookies.indexOf('psppp=1') > -1) {
        jQuery('.cinnamon-dropdown-options #ppp1').addClass('current-term');
    } else if(pscookies.indexOf('psppp=2') > -1) {
        jQuery('.cinnamon-dropdown-options #ppp2').addClass('current-term');
    } else if(pscookies.indexOf('psppp=3') > -1) {
        jQuery('.cinnamon-dropdown-options #ppp3').addClass('current-term');
    }
});
//


jQuery(document).ready(function($){
    $('.dropdown-toggle').click(function() {
        $(this).siblings('.dropdown-menu').toggleClass('collapsed expanded');
    });

    jQuery('.mmenu-trigger').click(function(){
        jQuery('.mmenu').toggleClass('mmenu-on');
        jQuery('.overlay').toggleClass('mmenu-enabled');
    });

	// show first content by default
	jQuery('#tabs-nav li:first-child').addClass('active');
	jQuery('#tabs .content').hide();
	jQuery('#tabs .content:first').show();

	// click function
	jQuery('#tabs-nav li').click(function(){
		jQuery('#tabs-nav li').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('#tabs .content').hide();

		var activeTab = jQuery(this).find('a').attr('href');
		jQuery(activeTab).fadeIn();
		return false;
	});

	jQuery('.infinite-carousel-upcoming').infiniteCarousel();
	jQuery('.infinite-carousel-bestsellers').infiniteCarousel();
	jQuery('.infinite-carousel-new').infiniteCarousel();

	jQuery('<div class="background-gradient"></div>').appendTo('.item');
	jQuery('.item').each(function(){
		var max = 20,
			hue = Math.floor(Math.random() * max),
			half = hue - 90;
		jQuery(this).find('.background-gradient').css({ backgroundImage: 'linear-gradient(90deg, #0cb8fc 0%, #0cb8fc 100%)' });
	});

    jQuery('.page-template-jobs h1.entry-title').empty();
    jQuery('.page-template-jobs h1.entry-title').prepend('<div class="jobs-splash"><div class="jobs-caption"><h2>FIND CREATIVE JOBS<br> <span style="font-weight: 700;">From companies across the world</span></h2><div class="jobs-caption-content"><p>We work closely with studios and agencies to bring you exciting new jobs, check back regularly for new opportunities.</p></div>');

    jQuery('.single-job_listing h1.entry-title').append('<div class="jobs-back-link"><i class="fas fa-angle-double-left"></i> <a href="https://posterspy.com/jobs">Back to all jobs</a></div>');

    jQuery('.page-template-jobs div.job_listings').append('<p class="jobs-contact">Are you an agency or employer? <a href="mailto:contact@posterspy.com">Contact us</a> to list your job!</p>');
});

/**
jQuery(window).load(function(){
    //jQuery('#hub-loading').fadeOut(100);
    //jQuery('.poster-container .wp-post-image').addClass('lensMe');
    //jQuery('.poster-container .lensMe').imageLens({ lensSize: 200 });
});

var num = 140; //number of pixels before modifying styles

jQuery(window).bind('scroll', function () {
    if (jQuery(window).scrollTop() > num) {
        jQuery('.hmenu').addClass('fixed');
    } else {
        jQuery('.hmenu').removeClass('fixed');
    }
});
/**/


jQuery(document).keyup(function(e) {
    if(e.keyCode == 27) { // ESC key
        jQuery('.mmenu').toggleClass('mmenu-on');
        jQuery('.overlay').toggleClass('mmenu-enabled');
    }
});




function loadFitPoster() {
    jQuery("#single-post-container .poster-container .wp-post-image").fadeIn();
}

jQuery(document).ready(function(){
    jQuery(document).on('click', '.moon-lightbox-close', function() {
        var page_link = jQuery('#lightbox-original-url').val();

        window.history.pushState("poster", "Poster", page_link);

        jQuery('#single-post-container').hide();
        jQuery("html").css({'overflow': 'scroll'});
    });

    jQuery.ajaxSetup({cache:false});

    jQuery(".desktop .ip_box > a").click(function(e) {
        var post_link = jQuery(this).attr("href");
        ga('send', 'pageview', post_link);

        jQuery('#lightbox-original-url').val(location.href);

        jQuery("html").css({'overflow': 'hidden'});

        window.history.pushState("poster", "Poster", post_link);

        jQuery("#single-post-container").html('<i class="fas fa-circle-notch fa-spin fa-fw"></i>');
        jQuery("#single-post-container").load(post_link);
        jQuery("#single-post-container").fadeIn();

        setTimeout(loadFitPoster, 1500);

        return false;
    });

    jQuery("textarea#comment").attr("placeholder", "Add a comment...");

    /*
     * Sticky sidebar
     *
     * @url https://abouolia.github.io/sticky-sidebar/
     */
    if (jQuery('.sidebar-feed').length) {
        var sidebar = new StickySidebar('.sidebar-feed', {
            containerSelector: '#content-wide',
            innerWrapperSelector: '.sidebar__inner',
            topSpacing: 128,
            bottomSpacing: 20,
            resizeSensor: true,
        });
    }
});












// Define variables
var tabLabels = document.querySelectorAll("#moon-tabs li");
var tabPanes = document.getElementsByClassName("moon-tab-contents");

function activateTab(e) {
  e.preventDefault();
  
  // Deactivate all tabs
  tabLabels.forEach(function(label, index){
    label.classList.remove("active");
  });
  [].forEach.call(tabPanes, function(pane, index){
    pane.classList.remove("active");
  });
  
  // Activate current tab
  e.target.parentNode.classList.add("active");
  var clickedTab = e.target.getAttribute("href");
  document.querySelector(clickedTab).classList.add("active");
}

// Apply event listeners
tabLabels.forEach(function(label, index){
  label.addEventListener("click", activateTab);
});

/**
var hash = $.trim( window.location.hash );

if (hash) {
  $('.inner-nav a[href$="'+hash+'"]').trigger('click');
}
/**/

















window.onload = function () {
    jQuery(document).on('click', '#pm-settings', function () {
        swal({
            title: "Message Settings",
            html: '<p><input type="checkbox" id="pm-read" disabled> Allow people to see when I\'ve read their messages (coming soon)</p>' + 
            '<p><input type="checkbox" id="pm-sounds" disabled> Play message sounds (coming soon)</p>' + 
            '<p><input type="checkbox" id="pm-requests" disabled> Automatically accept message requests (coming soon)</p>' + 
            '<p><input type="checkbox" id="pm-chat"> Disable chat</p>' + 
            '<h3>Visuals</h3>' + 
            '<p><b>Chat size</b></p>' +
            '<p><input type="checkbox" id="pm-chat-sm" disabled> Small (coming soon)</p>' +
            '<p><input type="checkbox" id="pm-chat-md" checked> Medium</p>' +
            '<p><input type="checkbox" id="pm-chat-lg" disabled> Large (coming soon)</p>',
            width: 720,
            padding: 16,
            confirmButtonText: 'Save Changes',
            //showConfirmButton: false,
            //timer: 5000,
            //onOpen: () => {
            //    swal.showLoading()
            //}
        }).then((result) => {
            if (
                // Read more about handling dismissals
                result.dismiss === swal.DismissReason.timer
            ) {
                console.log("I was closed by the timer");
            }
        });
        /**/
    });

    /**
    jQuery(document).on('click', '.pm-message-single', function () {
        var caption = '<h3 class="pm-caption">' + jQuery(this).data('caption') + '</h3>',
            message = jQuery(this).data('message');

        jQuery('.pm-right-inner').html(caption + message);
        jQuery('.pm-new').show();
    });
    /**/
                };
