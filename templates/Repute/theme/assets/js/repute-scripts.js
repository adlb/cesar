$(document).ready( function() {

	/*----------------------------/
	/* TOP BAR COUNTRY-SELECTION
	/*---------------------------*/

	//comment code below if you want to treat the selector as link
	if($('.country-selector').length > 0) {
		$('.country-selector a').click( function(e) {
			$flagImg = $(this).clone();

			$btnToggle = $(this).parents('ul').siblings('.dropdown-toggle');
			$btnToggle.html('').append($flagImg).append(' <span class="caret"></span>');
		});
	}

    /*----------------------------/
	/* NAVBAR
	/*---------------------------*/

	if($('.navbar-fixed-top.shrinkable').length > 0) {
		$('.wrapper').css('padding-top', 97);

		$(window).scroll(function() {
			if($(document).scrollTop() > 300) {
				$('.navbar-fixed-top').addClass('shrink-active');
			}else {
				$('.navbar-fixed-top').removeClass('shrink-active');
			}
		});
	}


	/*----------------------------/
	/* CAROUSEL
	/*---------------------------*/

	if($('.slick-carousel').length > 0) {
		$('.testimonial').slick({
			speed: 500,
			fade: true,
			prevArrow: '<button type="button" data-role="none" class="btn slick-prev">Previous</button>',
			nextArrow: '<button type="button" data-role="none" class="btn slick-next">Next</button>',
		});
	}
	

	/*----------------------------/
	/* PARALLAX
	/*---------------------------*/

	if($('.parallax').length > 0) {
		$('.parallax').stellar();
	}

    /*----------------------------/
	/* BLOG
	/*---------------------------*/

	if($('.featured-video').length > 0) {
		$('.featured-video').fitVids();
	}


	/*----------------------------/
	/* BOOTSTRAP MULTISELECT
	/*---------------------------*/

	if($('.multiselect').length > 0) {
		$('.multiselect').multiselect();
	}


	/*--------------------------------/
	/* AJAX CALL FOR STATEFUL BUTTON
	/*-------------------------------*/

	if( $('#loading-example-btn').length > 0 ) {
		$('#loading-example-btn').click( function() {
			var btn = $(this);

			$.ajax({
				url: 'php/ajax-response.php',
				type: 'POST',
				dataType: 'json',
				cache: false,
				beforeSend: function(){
					btn.button('loading');
				},
				success: function(  data, textStatus, XMLHttpRequest  ) {
					// dummy delay for demo purpose only
					setTimeout( function() {
						$('#server-message').text(data['msg']).addClass('green-font');
						btn.button('reset');
					}, 1000 );
				},
				error: function( XMLHttpRequest, textStatus, errorThrown ) {
					console.log("AJAX ERROR: \n" + errorThrown);
				}
			});

			
		});
	}


	/*--------------------------------/
	/* SIDEBAR NAVIGATION TOGGLE
	/*-------------------------------*/

	$('.submenu-toggle').click( function() {
		if(!$(this).parent().hasClass('active')) {
			$(this).parent().addClass('active');
		} else {
			$(this).parent().removeClass('active');
		}
	});

	$('.sidebar-nav a').click( function() {
		$('.sidebar-nav a').removeClass('current');
		$(this).addClass('current');
	});


	/*--------------------------------/
	/* BOOTSTRAP TOOLTIP
	/*-------------------------------*/

	if($('[data-toggle="tooltip"]').length > 0) {
		$('[data-toggle="tooltip"]').tooltip({
			container: "body"
		});
	}


	/*--------------------------------/
	/* BOOTSTRAP POPOVER
	/*-------------------------------*/

	if($('.btn-popover').length > 0) {
		$('.btn-popover').popover({
            trigger: 'hover'
        });
	}

	if($('.demo-popover').length > 0) {
		$('.demo-popover #popover-title').popover({
			html: true,
			title: '<i class="fa fa-info-circle"></i> Popover Title',
			content: 'This popover has title and support HTML content. Quickly implement process-centric networks rather than compelling potentialities. Objectively reinvent competitive technologies after high standards in process improvements. Phosfluorescently cultivate 24/365.'
		});

		$('.demo-popover #popover-hover').popover({
			html: true,
			title: '<i class="fa fa-info-circle"></i> Popover Title',
			trigger: 'hover',
			content: 'Activate the popover on hover. Objectively enable optimal opportunities without market positioning expertise. Assertively optimize multidisciplinary benefits rather than holistic experiences. Credibly underwhelm real-time paradigms with.'
		});
	}


	/*--------------------------------/
	/* MASKED INPUT
	/*-------------------------------*/

	if($('#masked-input-demo').length > 0) {
		$('#phone').mask('(999) 999-9999');
		$('#phone-ex').mask('(999) 999-9999? x99999');
		$('#tax-id').mask('99-9999999');
		$('#ssn').mask('999-99-9999');
		$('#product-key').mask('a*-999-a999');
	}

	
	/*--------------------------------/
	/* DATE PICKER
	/*-------------------------------*/

	if($('#date-picker-demo').length > 0) {
		var dtp = $('#datepicker').datepicker()
			.on('changeDate', function(e) { dtp.datepicker('hide'); });

		$('#daterangepicker').daterangepicker({
			timePicker: true,
			timePickerIncrement: 10,
			format: 'MM/DD/YYYY h:mm A',
			applyClass: "btn-primary"
		});
	}


	/*--------------------------------/
	/* TEXTAREA WITH COUNTER
	/*-------------------------------*/

	if($('#textarea-demo').length > 0) {
		var textMax = 99;
		
		$('.js-textarea-help span').html(textMax + ' characters remaining');

		$('.textarea-counting').keyup(function() {
			var textLength = $('.textarea-counting').val().length;
			var textRemaining = textMax - textLength;

			$('.js-textarea-help span').html(textRemaining + ' characters remaining');
		});
	}


	/*--------------------------------/
	/* PIE CHART
	/*-------------------------------*/

	if($('.pie-chart').length > 0) {
		$('.pie-chart').easyPieChart({
			size: 180,
			barColor: '#406DA4',
			trackColor: '#eaeaea',
			scaleColor: false,
			lineWidth: 2,
			lineCap: "square",
			animate: 2000
		});
	}

	/*-----------------------------------/
	/* AJAX CONTACT FORM
	/*----------------------------------*/

	if($('#contact-form').length > 0) {
		$('#contact-form').parsley();

		$('.contact-form-wrapper form').submit( function(e) {
			e.preventDefault();
			

			if( !$(this).parsley('isValid') )
				return;

			$theForm = $(this);
			$btn = $(this).find('#submit-button');
			$btnText = $btn.text();
			$(this).parent().append('<div class="alert"></div>');
			$alert = $(this).parent().find('.alert');

			$btn.find('.loading-icon').addClass('fa-spinner fa-spin ');
			$btn.prop('disabled', true).find('span').text("Sending...");

			$url = "php/contact.php";

			$attr = $(this).attr('action');
			if (typeof $attr !== typeof undefined && $attr !== false) {
				if($(this).attr('action') != '') $url = $(this).attr('action');
			}

			$.post($url, $(this).serialize(), function(data){
				
				$message = data.message;
				
				if( data.result == true ){
					$theForm.slideUp('medium', function() {
						$alert.removeClass('alert-danger');
						$alert.addClass('alert-success').html($message).slideDown('medium');
					});
				}else {
					$alert.addClass('alert-danger').html($message).slideDown('medium');
				}

				$btn.find('.loading-icon').removeClass('fa-spinner fa-spin ');
				$btn.prop('disabled', false).find('span').text($btnText);

			})
			.fail(function() { console.log('AJAX Error'); });
		});
	}


	/*-----------------------------------/
	/* MISC
	/*----------------------------------*/

	// fix portfolio item
	$('.portfolio-item').on('click', function() {
		// do nothing, somehow this triggering portfolio overlay on mobile Safari
	});

	// indicate mobile browser
	var ua = navigator.userAgent,
	isMobileWebkit = /WebKit/.test(ua) && /Mobile/.test(ua);

	if (isMobileWebkit) {
		$('html').addClass('mobile');
	}

});

