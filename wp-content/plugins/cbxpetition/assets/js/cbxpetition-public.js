(function ($) {
	'use strict';

	jQuery(document).ready(function ($) {

		$.extend($.validator.messages, {
			required   : cbxpetition.validation.required,
			remote     : cbxpetition.validation.remote,
			email      : cbxpetition.validation.email,
			url        : cbxpetition.validation.url,
			date       : cbxpetition.validation.date,
			dateISO    : cbxpetition.validation.dateISO,
			number     : cbxpetition.validation.number,
			digits     : cbxpetition.validation.digits,
			creditcard : cbxpetition.validation.creditcard,
			equalTo    : cbxpetition.validation.equalTo,
			maxlength  : $.validator.format(cbxpetition.validation.maxlength),
			minlength  : $.validator.format(cbxpetition.validation.minlength),
			rangelength: $.validator.format(cbxpetition.validation.rangelength),
			range      : $.validator.format(cbxpetition.validation.range),
			max        : $.validator.format(cbxpetition.validation.max),
			min        : $.validator.format(cbxpetition.validation.min)
		});

		//for each petition sign form
		$(".cbxpetition_signform_wrapper").each(function (index, elem) {

			var $form_wrapper = $(elem);
			var $element = $form_wrapper.find('.cbxpetition-signform');
			var $ajax = parseInt($element.data('ajax'));
			var $busy = parseInt($element.data('busy'));

			var $formvalidator = $element.validate({
				errorPlacement: function (error, element) {
					error.appendTo(element.closest('.cbxpetition-signform-field'));
				},
				errorElement  : 'p',
				rules         : {
					/*'cbxpetition-fname': {
						required : true,
						minlength: 2
					},
					'cbxpetition-lname': {
						required : true,
						minlength: 2
					},
					'cbxpetition-email': {
						required: true,
						email   : true
					},*/

					'cbxpetition-privacy': {
						required: true
					}
				},
				messages      : {
				}
			});

			// prevent double click form submission

			$element.submit(function (e) {

				var $form = $(this);



				if ($formvalidator.valid()) {



					if ($ajax && !$busy) {
						e.preventDefault();

						$element.data('busy', 1);
						$element.find('.cbxpetition_ajax_icon').show();
						$element.find('.cbxpetition-submit').prop("disabled", true);

						$form_wrapper.find('.cbxpetition-error-messages').empty();
						$form_wrapper.find('.cbxpetition-error-messages').empty();


						var data = $form.serialize();

						// process the form
						var request = $.ajax({
							type    : 'POST', // define the type of HTTP verb we want to use (POST for our form)
							url     : cbxpetition.ajaxurl, // the url where we want to POST
							data    : data + '&action=cbxpetition_sign_submit' + '&ajax=true', // our data object
							security: cbxpetition.nonce,
							dataType: 'json' // what type of data do we expect back from the server
						});

						request.done(function (data) {
							if ($.isEmptyObject(data.error)) {
								var $success_messages = data.success_arr.messages;
								var $error_messages = data.error_arr.messages;

								var $show_form = parseInt(data.show_form);

								$form_wrapper.find('.cbxpetition-success-messages').empty();
								$form_wrapper.find('.cbxpetition-error-messages').empty();

								$.each($success_messages, function (key, $message) {
									$form_wrapper.find('.cbxpetition-success-messages').append('<p class="cbxpetition-alert cbxpetition-alert-' + $message['type'] + '">' + $message['text'] + '</p>');
								});

								$.each($error_messages, function (key, $message) {
									$form_wrapper.find('.cbxpetition-error-messages').append('<p class="cbxpetition-alert cbxpetition-alert-' + $message['type'] + '">' + $message['text'] + '</p>');
								});

								if ($show_form === 1) {
									$formvalidator.resetForm();
									$element[0].reset();

									$element.data('busy', 0);
									$element.find('.cbxpetition_ajax_icon').hide();
									$element.find('.cbxpetition-submit').prop("disabled", false);

								} else {
									$element.remove();
								}

							} else {
								// validation error


								$.each(data.error, function (key, valueObj) {
									if (key === 'top_errors') {
										$.each(valueObj, function (key2, valueObj2) {
											var error_msg_for_hidden_type = '<p class="cbxpetition-alert cbxpetition-alert-danger">' + valueObj2 + '</p>';
											$form_wrapper.find('.cbxpetition-error-messages').append(error_msg_for_hidden_type);
										});
									}
									else{
										if ($element.find("#" + key).attr('type') == 'hidden') {
											//for hidden field show at top
											var error_msg_for_hidden_type = '<p class="cbxpetition-alert cbxpetition-alert-danger" id="' + key + "-error" + '">' + valueObj + '</p>';
											$form_wrapper.find('.cbxpetition-error-messages').append(error_msg_for_hidden_type);
										} else {
											//for regular field show after field
											$form.find("#" + key).addClass('error');
											$form.find("#" + key).remove('valid');
											var $field_parent = $form.find("#" + key).closest('.cbxpetition-signform-field');
											if($field_parent.find('p.error').length > 0){
												$field_parent.find('p.error').html(valueObj).show();
											}
											else{
												$('<p for="'+key+'" class="error">' + valueObj + '</p>').appendTo($field_parent);
											}
										}
									}

								});

								$element.data('busy', 0);
								$element.find('.cbxpetition_ajax_icon').hide();
								$element.find('.cbxpetition-submit').prop("disabled", false);


							}


						});

						request.fail(function (jqXHR, textStatus) {
							$element.data('busy', 0);
							$element.find('.cbxpetition_ajax_icon').hide();
							$element.find('.cbxpetition-submit').prop("disabled", false);

							$form_wrapper.find('.cbxpetition-error-messages').append('<p class="cbxpetition-alert cbxpetition-alert-danger">' + cbxpetition.ajax.fail + '</p>');
						});
					}//end if ajax and not busy
				}
			});
		}); //end each form


		$('.cbxpetition_signature_wrapper').on('click', '.cbxpetition_load_more_signs', function (e) {
			e.preventDefault();

			var $this = $(this);

			var $wrapper 			= $this.closest('.cbxpetition_signature_wrapper');
			var $listing_wrapper 	= $wrapper.find('.cbxpetition_signature_items');

			var $petition_id 	= parseInt($this.data('petition-id'));
			var $maxpage 		= parseInt($this.data('maxpage'));
			var $page 			= parseInt($this.data('page'));
			var $perpage 			= parseInt($this.data('perpage'));
			var $order 			= $this.data('order');

			var $orderby		= $this.data('orderby');
			$page++;

			var $busy 			= parseInt($this.data('busy'));
			if(!$busy){
				$this.data('busy', 1);

				$.ajax({
					type    : "post",
					dataType: 'json',
					url     : cbxpetition.ajaxurl,
					data    : {
						action     : "cbxpetition_load_more_signs",
						security   : cbxpetition.nonce,
						petition_id: $petition_id,
						page  : $page,
						perpage  : $perpage,
						order  : $order,
						orderby  : $orderby
					},
					success : function (data) {
						$listing_wrapper.append(data.listing);
						$this.data('busy', 0);


                        $listing_wrapper.find('.signature-message').readmore({
                            speed: 75,
                            moreLink: cbxpetition.readmore.moreLink,
                            lessLink: cbxpetition.readmore.lessLink
                        });

						if($maxpage == $page) {
							$this.closest('.cbxpetition_load_more_signs_wrap').remove();
						}
						else{
							$this.data('page', $page);
						}
					}
				});
			}


		});

		//add readmore to signature text
		new Readmore('.signature-message', {
			speed: 75,
			moreLink: cbxpetition.readmore.moreLink,
			lessLink: cbxpetition.readmore.lessLink,
			collapsedHeight: 100
		});

		//add gallery feature to
		$('.cbxpetition_photo_background').venobox({

		});



	});

})(jQuery);
