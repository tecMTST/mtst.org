(function ($) {
	'use strict';

	jQuery(document).ready(function ($) {

		//Initiate Color Picker
		$('.wp-color-picker-field').wpColorPicker();

		// Switches option sections
		$('.group').hide();
		var activetab = '';
		if (typeof(localStorage) != 'undefined') {
			//get
			activetab = localStorage.getItem("cbxpetitionmetaboxactivetab");
		}
		if (activetab != '' && $(activetab).length) {
			$(activetab).fadeIn();
		} else {
			$('.group:first').fadeIn();
		}
		$('.group .collapsed').each(function () {
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
			evt.preventDefault();
			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof(localStorage) != 'undefined') {
				//set
				localStorage.setItem("cbxpetitionmetaboxactivetab", $(this).attr('href'));
			}
			$('.group').hide();
			$(clicked_group).fadeIn();
			evt.preventDefault();
		});

		$('.wpsa-browse').on('click', function (event) {
			event.preventDefault();

			var self = $(this);

			// Create the media frame.
			var file_frame = wp.media.frames.file_frame = wp.media({
				title   : self.data('uploader_title'),
				button  : {
					text: self.data('uploader_button_text')
				},
				multiple: false,
				library: {
					type: [ 'image' ]
				},
			});

			file_frame.on('select', function (event) {
				var attachment = file_frame.state().get('selection').first().toJSON();

				self.prev('.wpsa-url').val(attachment.url);
			});

			// Finally, open the modal
			file_frame.open();
		});


		//for shortcode copy to clipboard
		/*var clipboard = new Clipboard('.cbxpetitionshortcodetrigger');
		clipboard.on('success', function (e) {
			e.clearSelection();
		});*/

		//select all text on click of shortcode text
		$('.cbxpetitionshortcode').on("click", function () {

			var text = $(this).text();
			var $this = $(this);
			var $input = $('<input class="cbxpetitionshortcode-text" type="text">');
			$input.prop('value', text);
			$input.insertAfter($(this));
			$input.focus();
			$input.select();
			try {
				document.execCommand("copy");
			} catch (err) {

			}

			$this.hide();
			$input.focusout(function () {
				$this.show();
				$input.remove();
			});
		});

		// get data from id cbxpetition_banner
		/*var petition_banner = $('#cbxpetition_banner');
		// set initial banner in petition form
		if (petition_banner.val() != null) {
			$('.banner').css({
				'background-image' : 'url(' + petition_banner.val() + ')',
				'background-repeat': 'no-repeat',
				'background-size'  : '100%'
			})
		}*/

		//apply flatpickr javascript
		$(".cbxpetition-add-date").flatpickr({
			disableMobile: "true",
			//minDate   : new Date(),
			enableTime: true,
			dateFormat: 'Y-m-d H:i',
			time_24hr    : true,
			defaultHour  : 0,
			defaultMinute: 0
		});

		/*var $petitionbanner = $('#petition_banner_wrapper');
		// click on banner and upload image
		$petitionbanner.on('click', '.cbxpetition-add-banner', function (e) {
			e.preventDefault();

			var $this = $(this);

			// Create the media frame.
			var file_frame = wp.media.frames.file_frame = wp.media({
				title   : $this.data('uploader_title'),
				button  : {
					text: $this.data('uploader_button_text')
				},
				multiple: false,
				library: {
					type: [ 'image' ]
				},
			});

			file_frame.on('select', function () {
				var attachment = file_frame.state().get('selection').first().toJSON();
				$petitionbanner.css({
					'background-image' : 'url(' + attachment.url + ')'
				});
				$petitionbanner.find('#cbxpetition_banner').val(attachment.id);
				$petitionbanner.find('.cbxpetition-remove-banner').show();
			});

			// Finally, open the modal
			file_frame.open();
		});

		$petitionbanner.on('click', '.cbxpetition-remove-banner', function (e) {
			e.preventDefault();
			var $this = $(this);

			if(confirm(cbxpetitionObj.delete_confirm)){
				$petitionbanner.css({
					'background-image' : ''
				});
				$petitionbanner.find('#cbxpetition_banner').val('');

				$this.hide();
			}

		});*/




		if(parseInt(cbxpetitionObj.photo_mode)){
			var $form = $('#cbxpetition_meta_media');
			var $form_id = parseInt($form.data('petition_id'));

			// photo upload file browser trigger
			$('.cbxpetition_photo_upload').on('click', function (e) {
				e.preventDefault();

				var $this   = $(this);
				var $parent = $this.parent('.cbxpetition_photo_add');

				$parent.find('.cbxpetition_photo_file_browser').trigger('click');
			});

			//petition photo attachment

			var $photos_wrapper   = $form.find('.cbxpetition_photos');
			var $photos_wrapperP  = $form.find('.cbxpetition_photos_wrap');



			var $photo_file_browser     = $form.find('.cbxpetition_photo_file_browser');

			var $photo_maxFiles         = cbxpetitionObj.photo_max_number_of_files;
			var $photo_file_count = parseInt($photo_file_browser.data('count'));

			var $photo_deleteButton = $('<a/>').addClass('cbxpetition_photo_delete').prop('title', cbxpetitionObj.delete_text).prop('disabled', true).attr('data-busy', 0);
			var $photo_dragButton = $('<a/>').addClass('cbxpetition_photo_drag').prop('title', cbxpetitionObj.sort_text);
			var $photo_hiddenField = $('<input class="cbxpetition_photo_hidden" type="hidden" name="cbxpetitionmeta[petition-photos][]" value="" />');

			var imgPreviewField = $('<img class="cbxpetition_photo_preview" src="" alt="Preview"  />');

			// delete single photo
			$photos_wrapper.on('click', 'a.cbxpetition_photo_delete', function (e) {
				e.preventDefault();

				var $this = $(this);
				var $photo_handle = $this ;

				var $busy = parseInt($this.data('busy'));

				if ($busy == 0) {
					$this.data('busy', 1);

					var $filename = $this.data('name');

					var request = $.ajax({
						url     : cbxpetitionObj.ajaxurl,
						type    : 'post',
						data    : {
							action  : "petition_admin_photo_delete",
							security: cbxpetitionObj.nonce,
							filename: $filename, //only input
							//log_id: $log_id,
							form_id: $form_id
						},
						dataType: 'json',
					});

					request.done(function (data) {
						$this.parents('.cbxpetition_photo').fadeOut("slow", function () {
							$this.parents('.cbxpetition_photo').remove();
						});

						//decrease current file count
						$photo_file_count--;
						// console.log('decrease file count');
						// console.log('count = '+$photo_file_count+' maxcount = '+$photo_maxFiles);

						//if after delete the current count is less than the max count then enable the add button
						if ($photo_file_count < $photo_maxFiles) {
							//$photos_wrapper.find('.cbxpetition_photo_add').css({'display': 'inline-block'});
							$form.find('.cbxpetition_photo_add').css({'display': 'inline-block'});
						}

					});

					request.fail(function () {
						alert(cbxpetitionObj.delete_error);
						$photo_handle.data('busy', 0);
					});
				}


				return false;
			});//end photo delete event


			// handle photo upload
			$($photo_file_browser).fileupload({
				url     : cbxpetitionObj.ajaxurl,
				formData: {
					action  : "petition_admin_photo_upload",
					security: cbxpetitionObj.nonce,
					//log_id: $log_id,
					form_id: $form_id
				},
				dataType: 'json',
				messages: {
					maxNumberOfFiles: cbxpetitionObj.blueimp.maxNumberOfFiles,
					acceptFileTypes : cbxpetitionObj.blueimp.acceptFileTypes,
					maxFileSize     : cbxpetitionObj.blueimp.maxFileSize,
					minFileSize     : cbxpetitionObj.blueimp.minFileSize
				},

				// acceptFileTypes   : /(\.|\/)(gif|jpe?g|png)$/i,
				acceptFileTypes   : new RegExp(cbxpetitionObj.photo_accept_file_types, 'i'),
				autoUpload        : true,
				maxNumberOfFiles  : cbxpetitionObj.photo_max_number_of_files,
				maxFileSize       : cbxpetitionObj.photo_max_filesize, // 10MB = 10000000
				// Enable image resizing, except for Android and Opera,
				// which actually support image resizing, but fail to
				// send Blob objects via XHR requests:
				//disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
				disableImageResize: false,
				imageMaxWidth     : 800,
				imageMaxHeight    : 800,
				previewMaxWidth   : 300,
				previewMaxHeight  : 300,
				previewCrop       : false
			}).on('fileuploadadd', function (e, data) {
				// all time single upload

				//increase current file count for add file
				$photo_file_count++;
				//take care file count , if >= max count then abort the process
				// console.log('current count = ', $photo_file_count);

				if ($photo_file_count <= $photo_maxFiles) {
					data.context = $('<div class="cbxpetition_photo"/>').prependTo('.cbxpetition_photos');
				} else {
					return false;
				}

				//console.log(data.files);

				$.each(data.files, function (index, file) {

					if (!index) {
						//var node_btn2 = $photo_hiddenField.clone(true).data(data);
						var node_btn_hiddenfield = $photo_hiddenField.clone(true);
						node_btn_hiddenfield.appendTo(data.context);

						//var node_btn = $photo_deleteButton.clone(true).data(data);
						var node_btn_delete = $photo_deleteButton.clone(true);
						node_btn_delete.appendTo(data.context);

						var node_btn_sort = $photo_dragButton.clone(true);
						node_btn_sort.appendTo(data.context);

					}

				});

			}).on('fileuploadprocessalways', function (e, data) {


				var index = data.index,
					file  = data.files[index],
					node  = $(data.context.children()[index]);

				if (file.preview) {
					node.prepend(file.preview);
				}
				if (file.error) {
					/*$photos_wrapper
						.append('<br>')
						.append($('<span class="text-danger"/>').text(file.error));*/

					$photos_wrapperP.find('.cbxpetition_photos_error').show().text(file.error);

					data.context.remove();
				}
				if (index + 1 === data.files.length) {
					data.context.find('a.cbxpetition_photo_delete')
						.prop('disabled', !!data.files.error);
				}

			}).on('fileuploadprogressall', function (e, data) {
				//$photos_wrapper.find('.cbxpetition_photo_add').addClass('cbxpetition_photo_loading');
				$form.find('.cbxpetition_photo_add').addClass('cbxpetition_photo_loading');
				/*var progress = parseInt(data.loaded / data.total * 100, 10);
				 $('#cbxpetition_photos_progress .progress-bar').css(
				 'width',
				 progress + '%'
				 );*/
			}).on('fileuploaddone', function (e, data) {
				var $this = $(this);
				//$photos_wrapper.find('.cbxpetition_photo_add').removeClass('cbxpetition_photo_loading');
				$form.find('.cbxpetition_photo_add').removeClass('cbxpetition_photo_loading');
				$.each(data.result.cbxpetitionmeta_photo_files, function (index, file) {

					var node_preview_img = imgPreviewField.clone(true);
					node_preview_img.attr('src', file.thumbnailUrl);
					node_preview_img.prependTo(data.context);

					//data.context.css('background-image', 'url(' + file.thumbnailUrl + ')');


					data.context.find('a.cbxpetition_photo_delete')
						.attr({
							'data-name': file.name,
						});

					data.context.find('.cbxpetition_photo_hidden')
						.attr({'value': file.name});

					if (file.url) {
						// console.log('Done event: count = ' + $photo_file_count + ' maxcount = ' + $photo_maxFiles);

						//disable the add buttton or upload process if current count >= max count
						if ($photo_file_count >= $photo_maxFiles) {
							//$photos_wrapper.find('.cbxpetition_photo_add').css({'display': 'none'});
							$form.find('.cbxpetition_photo_add').css({'display': 'none'});
						}

					} else if (file.error) {
						//var error = $('<span class="text-danger"/>').text(file.error);
						//$photos_wrapper.append(error);

						$photos_wrapperP.find('.cbxpetition_photos_error').show().text(file.error);
					}
				});
			}).on('fileuploadfail', function (e, data) {
				$.each(data.files, function (index) {
					/*var error = $('<span class="text-danger"/>').text(cbxpetitionObj.file_upload_failed);
					 $photos_wrapper
					 .append('<br>')
					 .append(error);*/

					$photos_wrapperP.find('.cbxpetition_photos_error').show().text(cbxpetitionObj.file_upload_failed);
				});
			}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');//end photo upload

            //sort photos
            var adjustment_photo;
            $("div.cbxpetition_photos").sortable({
                //group: 'simple_with_animation',
                containerSelector: '.cbxpetition_photos',
                itemSelector     : '.cbxpetition_photo',
                handle: '.cbxpetition_photo_drag',
                //placeholder      : '<div class="cbxpetition_photo_placeholder"></div>',
                placeholder      : 'cbxpetition_photo_placeholder',
                //pullPlaceholder  : true,
                // animation on drop
                onDrop           : function ($item, container, _super) {
                    var $clonedItem = $('<div />').css({height: 0});
                    $item.before($clonedItem);
                    $clonedItem.animate({'height': $item.height()});

                    $item.animate($clonedItem.position(), function () {
                        $clonedItem.detach();
                        _super($item, container);
                    });
                },

                // set $item relative to cursor position
                onDragStart: function ($item, container, _super) {
                    var offset  = $item.offset(),
                        pointer = container.rootGroup.pointer;

                    adjustment_photo = {
                        left: pointer.left - offset.left,
                        top : pointer.top - offset.top
                    };

                    _super($item, container);
                },
                onDrag     : function ($item, position) {
                    $item.css({
                        left: position.left - $adjustment.left,
                        top : position.top - $adjustment.top
                    });
                }
            });

            //photo section ends

            //petition banner attachment

            //var $banner_wrapper   = $form.find('.cbxpetition_photos');
            var $banner_wrapperP  = $form.find('#petition_banner_wrapper');



            var $banner_file_browser     = $form.find('.cbxpetition_banner_file_browser');




            var $banner_file_count       = parseInt($banner_file_browser.data('count'));
			var $banner_maxFiles         = 1;

            //var $banner_deleteButton = $('<a/>').addClass('cbxpetition_photo_delete').prop('title', cbxpetitionObj.delete_text).prop('disabled', true).attr('data-busy', 0);
            //var $banner_dragButton = $('<a/>').addClass('cbxpetition_photo_drag').prop('title', cbxpetitionObj.sort_text);
            //var $photo_hiddenField = $('<input class="cbxpetition_photo_hidden" type="hidden" name="cbxpetitionmeta[petition-photos][]" value="" />');

            //var imgPreviewField = $('<img class="cbxpetition_photo_preview" src="" alt="Preview"  />');

            // delete banner
			$banner_wrapperP.on('click', 'a.cbxpetition-remove-banner', function (e) {
                e.preventDefault();

               // console.log('delete clicked');

                var $this = $(this);
                var $photo_handle = $this ;

                var $busy = parseInt($this.data('busy'));

                if ($busy == 0) {
                    $this.data('busy', 1);

                    var $filename = $this.data('name');

                    var request = $.ajax({
                        url     : cbxpetitionObj.ajaxurl,
                        type    : 'post',
                        data    : {
                            action  : "petition_admin_banner_delete",
                            security: cbxpetitionObj.nonce,
                            filename: $filename, //only input
                            //log_id: $log_id,
                            form_id: $form_id
                        },
                        dataType: 'json',
                    });

                    request.done(function (data) {

                        //decrease current file count
	                    $banner_file_count--;
                        // console.log('decrease file count');
                        // console.log('count = '+$photo_file_count+' maxcount = '+$photo_maxFiles);

                        //if after delete the current count is less than the max count then enable the add button
                        if ($banner_file_count < $banner_maxFiles) {
                            //$photos_wrapper.find('.cbxpetition_photo_add').css({'display': 'inline-block'});
                            $form.find('.cbxpetition-add-banner').css({'display': 'block'});
                            $form.find('.cbxpetition-remove-banner').css({'display': 'none'});

	                        $banner_wrapperP.css({
		                        'background-image' : ''
	                        });

	                        $banner_wrapperP.find('#cbxpetition_banner').attr({'value': ''});
                        }

                    });

                    request.fail(function () {
                        alert(cbxpetitionObj.delete_error);
	                    $this.data('busy', 0);
                    });
                }


                return false;
            });//end photo delete event


			$form.find('.cbxpetition-add-banner').on('click', function (e) {
				e.preventDefault();

				//var $this   = $(this);
				//var $parent = $this.parent('.cbxpetition_photo_add');

				$form.find('.cbxpetition_banner_file_browser').trigger('click');

				//console.log('banner upload clicked');
			});

			//console.log(cbxpetitionObj.banner_max_filesize);

            // handle photo upload
            $($banner_file_browser).fileupload({
                url     : cbxpetitionObj.ajaxurl,
                formData: {
                    action  : "petition_admin_banner_upload",
                    security: cbxpetitionObj.nonce,
                    //log_id: $log_id,
                    form_id: $form_id
                },
                dataType: 'json',
                messages: {
                    maxNumberOfFiles: cbxpetitionObj.blueimp.maxNumberOfFiles,
                    acceptFileTypes : cbxpetitionObj.blueimp.acceptFileTypes,
                    maxFileSize     : cbxpetitionObj.blueimp.maxFileSize,
                    minFileSize     : cbxpetitionObj.blueimp.minFileSize
                },

                // acceptFileTypes   : /(\.|\/)(gif|jpe?g|png)$/i,
                acceptFileTypes   : new RegExp(cbxpetitionObj.banner_accept_file_types, 'i'),
                autoUpload        : true,
                maxNumberOfFiles  : 1,
                maxFileSize       : cbxpetitionObj.banner_max_filesize, // 10MB = 10000000
                // Enable image resizing, except for Android and Opera,
                // which actually support image resizing, but fail to
                // send Blob objects via XHR requests:
                //disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
                disableImageResize: false,
                imageMaxWidth     : 800,
                imageMaxHeight    : 800,
                previewMaxWidth   : 300,
                previewMaxHeight  : 300,
                previewCrop       : false
            }).on('fileuploadadd', function (e, data) {
	           // console.log('banner upload clicked 1');
                // all time single upload

                //increase current file count for add file
	            $banner_file_count++;
                //take care file count , if >= max count then abort the process
                // console.log('current count = ', $photo_file_count);

                if ($banner_file_count <= $banner_maxFiles) {
                   // data.context = $('<div class="cbxpetition_photo"/>').prependTo('.cbxpetition_photos');
                } else {
                    return false;
                }

                //console.log(data.files);

                $.each(data.files, function (index, file) {

                    if (!index) {
                        //var node_btn2 = $photo_hiddenField.clone(true).data(data);
                        //var node_btn_hiddenfield = $photo_hiddenField.clone(true);
                        //node_btn_hiddenfield.appendTo(data.context);

                        //var node_btn = $photo_deleteButton.clone(true).data(data);
                        //var node_btn_delete = $photo_deleteButton.clone(true);
                        //node_btn_delete.appendTo(data.context);

                        //var node_btn_sort = $photo_dragButton.clone(true);
                        //node_btn_sort.appendTo(data.context);

                    }

                });

            }).on('fileuploadprocessalways', function (e, data) {


                var index = data.index,
                    file  = data.files[index];
	                //node  = $(data.context.children()[index]);

                /*if (file.preview) {
                    node.prepend(file.preview);
                }*/
                if (file.error) {

                    $form.find('.cbxpetition_banner_error').show().text(file.error);

                   // data.context.remove();
                }
               /* if (index + 1 === data.files.length) {
                    data.context.find('a.cbxpetition_photo_delete')
                        .prop('disabled', !!data.files.error);
                }
*/
            }).on('fileuploadprogressall', function (e, data) {

                $form.find('.cbxpetition-add-banner').addClass('cbxpetition-banner-loading');
            }).on('fileuploaddone', function (e, data) {
                var $this = $(this);

                $form.find('.cbxpetition-add-banner').removeClass('cbxpetition-banner-loading');

                $.each(data.result.cbxpetitionmeta_banner_file, function (index, file) {
                	//console.log(file);

                    //var node_preview_img = imgPreviewField.clone(true);
                    //node_preview_img.attr('src', file.thumbnailUrl);
                    //node_preview_img.prependTo(data.context);

	                $banner_wrapperP.css({
		                'background-image' : 'url('+file.url+')'
	                });

                    //data.context.css('background-image', 'url(' + file.thumbnailUrl + ')');


                    //data.context.find('a.cbxpetition_photo_delete').attr({'data-name': file.name});

                    //data.context.find('.cbxpetition_photo_hidden').attr({'value': file.name});

	                $banner_wrapperP.find('#cbxpetition_banner').attr({'value': file.name});

                    if (file.url) {
                        // console.log('Done event: count = ' + $photo_file_count + ' maxcount = ' + $photo_maxFiles);

                        //disable the add buttton or upload process if current count >= max count
                        if ($banner_file_count >= $banner_maxFiles) {

                            $form.find('.cbxpetition-add-banner').css({'display': 'none'});
                            $form.find('.cbxpetition-remove-banner').css({'display': 'block'});
                        }

                    } else if (file.error) {
                        //var error = $('<span class="text-danger"/>').text(file.error);
                        //$photos_wrapper.append(error);

                        $form.find('.cbxpetition_banner_error').show().text(file.error);
                    }
                });
            }).on('fileuploadfail', function (e, data) {
                $.each(data.files, function (index) {

                    $form.find('.cbxpetition_banner_error').show().text(cbxpetitionObj.file_upload_failed);
                });
            }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');//end photo upload
		}



		/*var $petition_photos = $('#cbxpetition_photos');
		var $cbxpetition_photos_wrap = $('#cbxpetition_photos_wrap');
		// click on add photos and upload photos
		$cbxpetition_photos_wrap.on('click', '.cbxpetition_photo_add_temp', function (e) {
			e.preventDefault();

			var self = $(this);

			// Create the media frame.
			var file_frame = wp.media.frames.file_frame = wp.media({
				title   : self.data(cbxpetitionObj.upload_title),
				button  : {
					text: self.data(cbxpetitionObj.upload_btn)
				},
				multiple: false,
				library: {
					type: [ 'image' ]
				},
			});

			// check for previous values

			// var photos          =   [];
			file_frame.on('select', function () {
				var attachment = file_frame.state().get('selection').first().toJSON();
				var $single_photo = '<div class="cbxpetition_photo cbxpetition_photo_sort"><div class="cbxpetition_photo_background" style="background-image: url('+attachment.url+');"></div><input type="hidden" name="cbxpetitionmeta[petition-photos][]" value="'+attachment.id+'"/><a href="#" title="' +cbxpetitionObj.delete+'" class="dashicons dashicons-post-trash trash-repeat delete_photo"></a></div>';
				$petition_photos.prepend($single_photo);
			});

			// Finally, open the modal
			file_frame.open();
		});

		//on click delete photos
		$petition_photos.on('click', '.delete_photo', function (e) {
			e.preventDefault();

			if(confirm(cbxpetitionObj.delete_confirm)){
				var $this = $(this);
				$this.parent('.cbxpetition_photo').remove();
			}

		});*/



		//letter section starts

		// add recipient template
		var recipientlists_template = $('#cbx_recipientlists_template').html();
		Mustache.parse(recipientlists_template);   // optional, speeds up future uses


		//add new recipient row
		var $cbxpetition_letter_section = $('.cbxpetition_letter_section');

		var $cbxpetition_repeat_fields_recipient = $('#cbxpetition_repeat_fields_recipient');

		$cbxpetition_letter_section.on('click', '.cbx_recipient_add', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $recipientcount = parseInt($this.data('recipientcount'));

			//add a new blank recipient row
			var recipientlists_template_rendered = Mustache.render(recipientlists_template, {index: $recipientcount});
			$cbxpetition_repeat_fields_recipient.append(recipientlists_template_rendered);

			//now increase the count
			$recipientcount++;
			$this.data('recipientcount', $recipientcount);
		});

		//delete recipient row
		$cbxpetition_letter_section.on('click', '.recipient_delete_icon', function (e) {
			e.preventDefault();

			if(confirm(cbxpetitionObj.delete_confirm)){
				var $this = $(this);
				$this.parents('.recipientlist_wrap').remove();

				$this.parents('.recipientlist_wrap').fadeOut("slow", function () {
					$(this).remove();
				});
			}
		});

		//sort receipent and photos

		//sort receipents
		var adjustment_recipient;
		$("#cbxpetition_repeat_fields_recipient").sortable({
			vertical         : true,
			handle           : '.move-recipient',
			//containerSelector: '#cbxpetition_repeat_fields_recipient',
			containerSelector: 'ul',
			itemSelector     : 'li',
			//placeholder      : '<li class="cbxpetition_repeat_field_recipient_placeholder"/>',
			placeholder      : 'cbxpetition_repeat_field_recipient_placeholder',
			// animation on drop
			onDrop           : function ($item, container, _super) {
				var $clonedItem = $('<li />').css({height: 0});
				$item.before($clonedItem);
				$clonedItem.animate({'height': $item.height()});

				$item.animate($clonedItem.position(), function () {
					$clonedItem.detach();
					_super($item, container);
				});
			},

			// set $item relative to cursor position
			onDragStart: function ($item, container, _super) {
				var offset = $item.offset(),
					pointer = container.rootGroup.pointer;

				adjustment_recipient = {
					left: pointer.left - offset.left,
					top : pointer.top - offset.top
				};

				_super($item, container);
			},
			onDrag     : function ($item, position) {
				$item.css({
					left: position.left - adjustment_recipient.left,
					top : position.top - adjustment_recipient.top
				});
			}

		});
		//letter section ends

	});

})(jQuery);
