//version: 1.1.7


jQuery(function($) {
    $(document).ready(function(){

        // setupFixedHeader();
        window.onscroll = function() {setupFixedHeader()};
      initAddToCartPlusMinus();

      become_a_distributor();
    });

    /**
     * Fixed header options
     */
    function setupFixedHeader() {
        var secondary_menu_height = $('#secondary-menu-wrapper').outerHeight();

        $(window).scroll(function() 
        {
        if ($(this).scrollTop() > secondary_menu_height)
        {
        $('#primary-menu-wrapper').addClass("sticky_header");
        }
        else
        {
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
          $qty.trigger( 'change' );
      });
      if($('.wcopc').length) {
        $(document.body).on('after_opc_add_remove_product', function (e, data) {
            console.log(data);
            // Add up the quantities
            var quantities = $('#order_review .shop_table .quantity input[type="number"]');
            var overall_quantity = 0;
            $(quantities).each(function(){
                // console.log('value');
                // console.log($(this).val());
                overall_quantity = parseInt($(this).val()) + overall_quantity;
            });
            console.log(overall_quantity);

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
                } else {
                    $('.confirm-email').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                }
            });
            $(confirm_email).on('blur', function() {
                // remove the not selected class
                $('.confirm-email').removeClass('not-selected');
                // see if it matches the value of email
                if($(this).val() != $(email).val()) {
                    $('.confirm-email').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                } else {
                    $('.confirm-email').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                }
            });

            var password = form.find($('input[name="dist-password"]'));
            var confirm_password = form.find($('input[name="dist-password2"]'));

            $(password).on('blur', function() {
                // see if it matches the value of email
                if($(this).val() != $(confirm_password).val()) {
                    $('.confirm-password').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                } else {
                    $('.confirm-password').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                }
            });
            $(confirm_password).on('blur', function() {
                // remove the not selected class
                $('.confirm-password').removeClass('not-selected');
                // see if it matches the value of email
                if($(this).val() != $(password).val()) {
                    $('.confirm-password').addClass('show-error');
                    $('.wpcf7-submit').prop('disabled', true);
                } else {
                    $('.confirm-password').removeClass('show-error ');
                    $('.wpcf7-submit').prop('disabled', false);
                }
            });




        }
    }
  
    function disable_enable_confirm_email() {

        // wpcf7-submit
    }

});
