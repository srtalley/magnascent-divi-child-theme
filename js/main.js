//version: 1.2.0


jQuery(function($) {
    $(document).ready(function(){

        // setupFixedHeader();
        window.onscroll = function() {setupFixedHeader()};
        initAddToCartPlusMinus();

        become_a_distributor();

        checkout_country_selection();
    });

    /**
     * Fixed header options
     */
    function setupFixedHeader() {
        var secondary_menu_height = $('#secondary-menu-wrapper').outerHeight();

        $(window).scroll(function() {
            if ($(this).scrollTop() > secondary_menu_height){
                $('#primary-menu-wrapper').addClass("sticky_header");
            } else {
                $('#primary-menu-wrapper').removeClass("sticky_header");
            }
        });

    }
    /**
     * Enable the plus and minus buttons on the one page checkout
     */
    function initAddToCartPlusMinus(){

      $(document).on( 'click', '.quantity .plus, .quantity .minus', function() {
        
          // Get values
          var $qty		= $(this).closest('.quantity').find('.qty'),
              currentVal	= parseFloat( $qty.val() ),
              max			= parseFloat( $qty.attr( 'max' ) ),
              min			= parseFloat( $qty.attr( 'min' ) ),
              step		= $qty.attr( 'step' );

          // Format values
          if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
          if ( max === '' || max === 'NaN' ) max = '';
          if ( min === '' || min === 'NaN' ) min = 0;
          if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

          // Change the value
          if ( $( this ).is( '.plus' ) ) {

              if ( max && ( max == currentVal || currentVal > max ) ) {
                  $qty.val( max );
              } else if( min && currentVal < min) {
                  $qty.val( min );
              } else {
                  $qty.val( currentVal + parseFloat( step ) );
              }

          } else {

              if ( min && ( min == currentVal || currentVal < min ) ) {
                  $qty.val( min );
              } else if ( currentVal > 0 ) {
                  $qty.val( currentVal - parseFloat( step ) );
              }
          }

          // Trigger change event
          $qty.trigger( 'input' );
      });
      if($('.wcopc').length) {
        $(document.body).on('after_opc_add_remove_product', function (e, data) {
            // Add up the quantities
            var quantities = $('#order_review .shop_table .quantity input[type="number"]');
            var overall_quantity = 0;
            $(quantities).each(function(){
                overall_quantity = parseInt($(this).val()) + overall_quantity;
            });

            });
        }
    } // end initAddToCartPlusMinus

    function become_a_distributor() {
        // see if the form exists
        if($('#become_distributor_form').length){
            var form = $('#become_distributor_form');
            
            
            var email = form.find($('input[name="dist-email"]'));
            var confirm_email = form.find($('input[name="dist-emailconfirm"]'));
            $(email).on('blur', function() {
                // see if it matches the value of email
                if($(this).val() != $(confirm_email).val()) {
                    $('.confirm-email').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                    $(form).addClass('email-error');
                } else {
                    $('.confirm-email').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                    $(form).removeClass('email-error');
                }
            });
            $(confirm_email).on('blur', function() {
                // remove the not selected class
                $('.confirm-email').removeClass('not-selected');
                // see if it matches the value of email
                if($(this).val() != $(email).val()) {
                    $('.confirm-email').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                    $(form).addClass('email-error');
                } else {
                    $('.confirm-email').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                    $(form).removeClass('email-error');
                }
            });

            var password = form.find($('input[name="dist-password"]'));
            var confirm_password = form.find($('input[name="dist-password2"]'));

            $(password).on('blur', function() {
                // see if it matches the value of email
                if($(this).val() != $(confirm_password).val()) {
                    $('.confirm-password').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                    $(form).addClass('password-error');
                } else {
                    $('.confirm-password').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                    $(form).removeClass('password-error');
                }
            });
            $(confirm_password).on('blur', function() {
                // remove the not selected class
                $('.confirm-password').removeClass('not-selected');
                // see if it matches the value of email
                if($(this).val() != $(password).val()) {
                    $('.confirm-password').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                    $(form).addClass('password-error');
                } else {
                    $('.confirm-password').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                    $(form).removeClass('password-error');
                }
            });

            // handle the shipping fields
            var shipping_address_different = $('input[name="dist-shipping_different[]"]');
            $(shipping_address_different).change(function() {

                if($(this).prop("checked") == true) {
                    $('.vendor-shipping-fields').slideDown();
                } else {
                    $('.vendor-shipping-fields').slideUp(function(){
                        $('.vendor-shipping-fields input').val('');
                    });
                }
              });

              $('#vendor-email-error').on('click', function(e){
                  e.preventDefault();
                  $('#dist-email').focus();
              });
              $('#vendor-password-error').on('click', function(e){
                e.preventDefault();
                $('#dist-password').focus();
            });

        }
    }
  
    /**
     * Runs on the checkout page to detect a user country
     */
    function checkout_country_selection () {
        if($('.woocommerce-checkout').length) {
            $( document.body ).on( 'updated_checkout', function(){
                mgnscnt_get_user_country();
            });
        }
    }

    /**
     * Get the woocommerce country for the user
     */
     function mgnscnt_get_user_country() {

        // element.preventDefault();
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: mgnscnt_actions.ajaxurl,
            data: {
                'action': 'mgnscnt_get_user_country',
                'nonce': mgnscnt_actions.ajaxnonce,
            },
            success: function(data) {
                console.log(data);
                $('.shop_table').removeClass('us');
                $('.shop_table').removeClass('gb');
                $('.shop_table').addClass(data.user_country.toLowerCase());

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
            }
        });
    }


});
