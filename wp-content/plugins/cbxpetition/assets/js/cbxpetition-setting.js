(function ($) {
	'use strict';

	$(document).ready(function () {

		//Initiate Color Picker
		$('.wp-color-picker-field').wpColorPicker();
		//add chooser
		//$(".chosen-select").chosen();
		$(".selecttwo-select").select2({
            placeholder: cbxpetition_setting.please_select,
            allowClear: false
		});


		// Switches option sections
		$('.cbxpetition_group').hide();
		var activetab = '';
		if (typeof(localStorage) != 'undefined') {
			//get
			activetab = localStorage.getItem("cbxpetitionactivetab");
		}

		//if url has section id as hash then set it as active or override the current local storage value
		if (window.location.hash) {
			if ($(window.location.hash).hasClass('cbxpetition_group')) {
				activetab = window.location.hash;
				if (typeof(localStorage) != 'undefined') {
					localStorage.setItem("cbxpetitionactivetab", activetab);
				}
			}

		}


		if (activetab != '' && $(activetab).length && $(activetab).hasClass('cbxpetition_group')) {
			$(activetab).fadeIn();
		} else {
			$('.cbxpetition_group:first').fadeIn();
		}

		$('.cbxpetition_group .collapsed').each(function () {
			$(this).find('input:checked').parent().parent().parent().nextAll().each(
				function () {
					if ($(this).hasClass('last')) {
						$(this).removeClass('hidden');
						return false;
					}
					$(this).filter('.hidden').removeClass('hidden');
				});
		});

		if (activetab != '' && $(activetab + '-tab').length) {
			$(activetab + '-tab').addClass('nav-tab-active');
		}
		else {
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		$('.nav-tab-wrapper a').on('click', function (evt) {
			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof(localStorage) != 'undefined') {
				//set
				localStorage.setItem("cbxpetitionactivetab", $(this).attr('href'));
			}
			$('.cbxpetition_group').hide();
			$(clicked_group).fadeIn();
			evt.preventDefault();
		});





		/*// Switches option sections
		$('.cbxpetition_group').hide();
		var activetab = '';
		if (typeof(localStorage) != 'undefined') {
			//get
			activetab = localStorage.getItem("cbxpetitionactivetab");
		}
		if (activetab != '' && $(activetab).length) {
			$(activetab).fadeIn();
		} else {
			$('.cbxpetition_group:first').fadeIn();
		}
		$('.cbxpetition_group .collapsed').each(function () {
			$(this).find('input:checked').parent().parent().parent().nextAll().each(
				function () {
					if ($(this).hasClass('last')) {
						$(this).removeClass('hidden');
						return false;
					}
					$(this).filter('.hidden').removeClass('hidden');
				});
		});

		if (activetab != '' && $(activetab + '-tab').length) {
			$(activetab + '-tab').addClass('nav-tab-active');
		}
		else {
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		$('.nav-tab-wrapper a').click(function (evt) {
			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof(localStorage) != 'undefined') {
				//set
				localStorage.setItem("cbxpetitionactivetab", $(this).attr('href'));
			}
			$('.cbxpetition_group').hide();
			$(clicked_group).fadeIn();
			evt.preventDefault();
		});*/

		$('.wpsa-browse').on('click', function (event) {
			event.preventDefault();

			var self = $(this);

			// Create the media frame.
			var file_frame = wp.media.frames.file_frame = wp.media({
				title   : self.data('uploader_title'),
				button  : {
					text: self.data('uploader_button_text')
				},
				multiple: false
			});

			file_frame.on('select', function () {
				var attachment = file_frame.state().get('selection').first().toJSON();

				self.prev('.wpsa-url').val(attachment.url);
			});

			// Finally, open the modal
			file_frame.open();
		});

        //sort photos
        var adjustment_photo;
        $(".multicheck_fields_sortable").sortable({
            vertical         : true,
            handle           : '.multicheck_field_handle',
            containerSelector: '.multicheck_fields',
            itemSelector     : '.multicheck_field',
            //placeholder      : '<p class="multicheck_field_placeholder"/>',
            placeholder      : 'multicheck_field_placeholder',
        });

		//make the subheading single row
		$('.setting_subheading').each(function (index, element) {
			var $element = $(element);
			var $element_parent = $element.parent('td');
			$element_parent.attr('colspan', 2);
			$element_parent.prev('th').remove();
		});

		//make the subheading single row
		$('.setting_heading').each(function (index, element) {
			var $element = $(element);
			var $element_parent = $element.parent('td');
			$element_parent.attr('colspan', 2);
			$element_parent.prev('th').remove();
		});

		$('.cbxpetition_group').each(function (index, element) {
			var $element = $(element);
			var $form_table = $element.find('.form-table');
			$form_table.prev('h2').remove();
		});


        $('.cbxpetition_group').on('click', '.checkbox', function() {
            var mainParent = $(this).closest('.checkbox-toggle-btn');
            if($(mainParent).find('input.checkbox').is(':checked')) {
                $(mainParent).addClass('active');
            } else {
                $(mainParent).removeClass('active');
            }

        });

	});

})(jQuery);
