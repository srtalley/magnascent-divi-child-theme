//version: 1.1.7

// end the zendesk chat if the agent goes offline
zE('webWidget:on', 'chat:status', function (status) {
  if (status === 'offline') {
    zE('webWidget', 'chat:end')
  } else {
    console.log('This chat session is now', status);
  }
});

jQuery(function($) {
  $(document).ready(function(){

    $('.video-lightbox .et_pb_video_overlay').unbind('click');
    var videoLinks = $('.et_pb_video_overlay').parent().find('.et_pb_video_box video');
    $('.video-lightbox .et_pb_video_overlay').magnificPopup({
      items: {
        src: videoLinks[0],
        type: 'inline'
      },
      mainClass: 'video-lightbox',
      midClick: true,
      closeBtnInside: false,
      callbacks: {
        open: function(item) {
          var current_video = this.currItem.data.src;
          current_video.play();
          $(current_video).on('ended', function(){
            $('.video-lightbox .et_pb_video_overlay').magnificPopup('close');
          })
        },
        close: function(item) {
          var current_video = this.currItem.data.src;
          current_video.pause();
        }
      }
    });

    // Add simple lightbox
    $('.et_pb_lightbox_image').unbind('click');
    var imageLinks = $("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").not('.social-share-link').not('.et_pb_gallery_image a, .envira-gallery-wrap a, .ngg-gallery-thumbnail a').attr('rel', 'gallery');

    imageLinks.magnificPopup({
      type: 'image',
      mainClass: 'mfp-fade',
      gallery:{
        enabled: true
      },
      midClick: true
    });

    // READ MORE LINKS
    $('.read-more .et_pb_testimonial_description_inner').each(function(){
      var et_testimonial_author = $(this).find('.et_pb_testimonial_author').text();
        $(this).readmore({
          speed: 500,
          blockCSS: 'height: 20em; max-height: 20em;',
          moreLink: '<a href="#"><span class="read-more-link">Read more</span><br /><span class="read-more-author" style="color: #666;"><strong>' + et_testimonial_author + '</strong></a>',
          lessLink: '<a href="#"><span class="read-more-link close-read-more-link">Close</span></a>',
          // Add the class 'transitioning' before toggling begins.
          beforeToggle: function(trigger, element, expanded) {
            element.addClass('transitioning');
            if(expanded) { // The "Close" link was clicked
              $('html, body').animate( { scrollTop: element.offset().top - 240}, {duration: 100 } );
            }
          },
          afterToggle: function(trigger, element, expanded) {
            element.removeClass('transitioning');
          }
        });
    });
    // $('.toggle_item').hide();
    $('.reveal_button').on('click', function(e) {
        e.preventDefault();
        button_classes = $(this).attr('class');
        // handle situations where there's more than one class
        toggle_classes = /(show_)\w+/.exec(button_classes)[0];
        $('.toggle_item.' + toggle_classes).slideToggle();
        $('.reveal_button').toggleClass('opened closed');
    });
  }); // end document ready

  $(window).load(function(){
    //Scrolling animation for anchor tags
    if(window.location.hash) {
      smooth_scroll_to_anchor_top($(window.location.hash));
    }
    setTimeout(function() {
      setup_collapsible_submenus();
    }, 700);

  }); // end window load 

  //check if an anchor was clicked and scroll to the proper place
  $('a[href*=\\#]').on('click', function () {
    if(this.hash != '') {
      if(this.pathname === window.location.pathname){
        smooth_scroll_to_anchor_top($(this.hash));
      } 
    }
  });
  
  function handle_mobile_nav(element) {

  } // end function handle_mobile_nav
  // scroll to the top of the anchor with an offset on desktops
  function smooth_scroll_to_anchor_top(anchor){
    if($(anchor) != 'undefined' ) {
      var window_media_query_980 = window.matchMedia("(max-width: 980px)")
      if(window_media_query_980.matches) {
        var offset_amount = 0;
      } else {
        var top_header_height = $('#top-header').height();
        var main_header_height = $('#main-header').height();
        var admin_bar_height = $('#wpadminbar').height();
        var offset_amount = top_header_height + main_header_height + admin_bar_height;
      }

      $('html,body').animate({scrollTop:($(anchor).offset().top - offset_amount) + 'px'}, 1000);
    }
  } // end function

  function setup_collapsible_submenus() {
    var $menu = $('#mobile_menu'),
        top_level_link = '#mobile_menu .menu-item-has-children > a';
         
    $menu.find('a').each(function() {
        $(this).off('click');
          
        if ( $(this).is(top_level_link) ) {
            $(this).attr('href', '#');
        }
          
        if ( ! $(this).siblings('.sub-menu').length ) {
            $(this).on('click', function(event) {
                $(this).parents('.mobile_nav').trigger('click');
            });
        } else {
            $(this).on('click', function(event) {
                event.preventDefault();
                $(this).parent().toggleClass('visible');
            });
        }
    });
  }


});
