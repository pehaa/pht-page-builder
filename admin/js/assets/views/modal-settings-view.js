var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

		peHaaThemesPageBuilder.ModalSettingsView = peHaaThemesPageBuilder.ModalView.extend( {

		events: function() {
			return _.extend({},peHaaThemesPageBuilder.ModalView.prototype.events,{
				'click .phtpb_address-to-geocode' : 'addressToGeocode',
				'click .phtpb_upload-button' : 'openMediaFrame',
				'click .phtpb_icons-list__icon' : 'chooseIcon',
				'change :checkbox' : 'conditionalFields',
				'change select' : 'conditionalFields',
			});
		},		

		initialize : function() {
			
			this.listenTo( peHaaThemesPageBuilder_Events, 'phtpb_map-marker:modified', this.rerenderMap );
			this.listenTo( peHaaThemesPageBuilder_Events, 'phtpb_module:afterDelete', this.rerenderMap );
			
			this.innerTemplate = _.template( $( this.collection.generateTemplateName( this.model.get( 'module_type' ), true ) ).html() );
			this.innerTemplateData = { phtpb_attributes: this.model.attributes };
		},

		conditionalFields : function( event ) {
			var $target = $(event.target), dataValue;
			if ( $target.is(':checkbox') ) {

				$target.parent().toggleClass('checked').toggleClass('unchecked');
				
			} else if ( $target.is('select') ) {
				dataValue = $target.find('option:selected').val();
				
				var newclass = $target.parent().attr('class').replace(/select-[^\s]+/, 'select-'+ dataValue);

				$target.parent().attr( 'class', newclass );
			}
		},

		chooseIcon : function( event ) {
			var $target = $(event.target);
			event.preventDefault();
			
			$target.siblings('.phtpb_icons-list__icon--active').removeClass('phtpb_icons-list__icon--active');

			$target.addClass('phtpb_icons-list__icon--active').parent().next('.phtpb_shortcode-attribute').val( $target.attr( 'data-icon-class' ) );
			
		},
		
		openMediaFrame : function( event ) {
			
			event.preventDefault();

			function remove_unneeded_gallery_settings( $el ) {
				setTimeout(function(){
					$el.find( '.gallery-settings' ).remove();
				}, 10 );
			}
			
			var that = this,
				$button = $(event.target), 
				phtpb_file_frame;
			
			if ( undefined !== phtpb_file_frame ) {
				phtpb_file_frame.open();
				return;
			}			
			
			if ( $button.is('.phtpb_gallery-button') ) {
				
				var $gallery_ids = $button.siblings( '.phtpb_gallery-ids-field' ),
					gallery_ids = $gallery_ids.val().length ? $gallery_ids.val() : "0",
					gallery_shortcode = '[gallery ids="' + gallery_ids + '"]';
				
				phtpb_file_frame = wp.media.frames.phtpb_file_frame = wp.media.gallery.edit( gallery_shortcode );

				if ( !$gallery_ids.val().length ) {
					phtpb_file_frame.setState('gallery-library');
				}

				// Remove the 'Columns' and 'Link To' unneeded settings
				
				// Remove initial unneeded settings
				remove_unneeded_gallery_settings( phtpb_file_frame.$el );
				// Remove unneeded settings upon re-viewing edit view
				phtpb_file_frame.on( 'content:render:browse', function( browser ){
					remove_unneeded_gallery_settings( browser.$el );
				});

				phtpb_file_frame.state( 'gallery-edit' ).on( 'update', function( selection ) {

					var shortcode_atts = wp.media.gallery.shortcode( selection ).attrs.named;
					if ( shortcode_atts.ids ) {
						$gallery_ids.val( shortcode_atts.ids );
					}
					
					that.displayGalleryCount( $button, true );
				});
				
				
			} else {
				
				phtpb_file_frame = wp.media.frames.phtpb_file_frame = wp.media({
					title: $button.data( 'choose' ),
					library: {
						type: $button.data( 'type' )
					},
					button: {
						text: $button.data( 'update' ),
					},
					multiple: false,
				});

				phtpb_file_frame.on( 'select', function() {
					var attachment = phtpb_file_frame.state().get('selection').first().toJSON(),
						url;

					url = !_.isUndefined( attachment.sizes ) && !_.isUndefined( attachment.sizes.thumbnail ) ? attachment.sizes.thumbnail.url : attachment.url;
					$button.siblings( '.phtpb_upload-field' ).val( url );
					$button.parent().next('.phtpb_option').find('.phtpb_upload-field-id').val( attachment.id );
					that.previewImage( $button );
					that.previewVideo( $button );
				});

				phtpb_file_frame.open();
			
			}
				
		},
		
		previewImage : function( $upload_button ) {
			var $upload_field = $upload_button.siblings( '.phtpb_upload-field' ),
				$preview = $upload_field.siblings( '.phtpb_upload-preview' ),
				image_url = $upload_field.val().trim();

			if ( $upload_button.data( 'type' ) !== 'image' ) { 
				return;
			}
			
			if ( image_url === '' ) {
				if ( $preview.length ) {
					$preview.remove();
				}

				return;
			}

			if ( ! $preview.length ) {
				$upload_button.after( '<div class="phtpb_upload-preview">' + '<img src=""/><a href="#" class="fa fa-times-circle phtpb_remove_image phtpb_close_icon" title="'+phtpb_data.rmv_img+'"></a></div>' );
				$preview = $upload_field.siblings( '.phtpb_upload-preview' );
				this.$el.on( 'click', '.phtpb_remove_image', function( event ){
					event.preventDefault();
					$(this).parents('.phtpb_option').find('.phtpb_upload-field' ).val( '' )
						.end().next('.phtpb_option').find('.phtpb_upload-field-id').val( '' );
					$(this).parent().remove();	
					
				});
			}

			$preview.find( 'img' ).attr( 'src', image_url );
		},


		previewVideo : function( $upload_button ) {

			var $upload_field = $upload_button.siblings( '.phtpb_upload-field' ),
				$preview = $upload_field.siblings( '.phtpb_upload-preview' ),
				video_url = $upload_field.val().trim();

			if ( $upload_button.data( 'type' ) !== 'video' ) { 
				return;
			}
			if ( video_url === '' ) {
				if ( $preview.length ) {
					$preview.remove();
				}

				return;
			}
			if ( ! $preview.length ) {
				$upload_button.after( '<div class="phtpb_upload-preview">' + '<span></span><a href="#" class="fa fa-times-circle phtpb_remove_video phtpb_close_icon" title="'+phtpb_data.rmv_img+'"></a></div>' );
				$preview = $upload_field.siblings( '.phtpb_upload-preview' );
				this.$el.on( 'click', '.phtpb_remove_video', function( event ){
					event.preventDefault();
					$(this).parents('.phtpb_option').find('.phtpb_upload-field' ).val( '' )
						.end().next('.phtpb_option').find('.phtpb_upload-field-id').val( '' );
					$(this).parent().remove();	
					
				});
			}

			$preview.find( 'span' ).html( video_url );

		},
		
		displayGalleryCount : function( $upload_button, just_updated ) {
			var ids = $upload_button.siblings('.phtpb_gallery-ids-field').val(),
				count;
			if ( '' !== ids ) {
				count = ids.split(",").length;
				$upload_button.siblings('.phtpb_visual-feedback').addClass( just_updated ? 'phtpb_just-updated' : ' ').find('.phtpb_count').html( count );
			}
			
		},

		advancedRender : function() {
			var that = this,
				$this_el = this.$el,
				content = '',
				$content_textarea,
				$content_textarea_container,
				$upload_button,
				$gallery_button,
				$color_picker,
				$date_picker;

			$content_textarea = this.$el.find( '#phtpb_content_new' );
			$upload_button = this.$el.find('.phtpb_upload-button');
			$gallery_button = this.$el.find('.phtpb_gallery-button');
			$color_picker = this.$el.find('.phtpb_color-picker-hex');
			$date_picker = this.$el.find('.phtpb_datepicker');
			$color_picker.wpColorPicker();
			$date_picker.datetimepicker();
			
			$upload_button.siblings( '.phtpb_upload-field' ).on( 'input', function() {
				
				that.previewImage( $(this).siblings( '.phtpb_upload-button' ) );

				that.previewVideo( $(this).siblings( '.phtpb_upload-button' ) );
			} ).each( function() {
				that.previewImage( $(this).siblings( '.phtpb_upload-button' ) );
				that.previewVideo( $(this).siblings( '.phtpb_upload-button' ) );
			} );
			

			$gallery_button.each( function() {
				that.displayGalleryCount( $(this), false );
			} );

			if ( $content_textarea.length ) {
				$content_textarea_container = $content_textarea.closest( '.phtpb_option' );
				content = $content_textarea.html();
				$content_textarea.remove();
				if ( $content_textarea_container.children('label').length ) {
					$content_textarea_container.children('label').after( peHaaThemesPageBuilder.initial_content );
				} else {
					$content_textarea_container.prepend( peHaaThemesPageBuilder.initial_content );
				}
				setTimeout( function() {
					if ( !_.isUndefined( window.switchEditors ) ) {
						window.switchEditors.go( 'phtpb_content_new', 'html' === getUserSetting( 'editor' ) ? 'html' : 'tinymce' );
					}
					peHaaThemesPageBuilder.phtpb_set_content( 'phtpb_content_new', content );
					window.wpActiveEditor = 'phtpb_content_new';
				}, 10 );
			}

			var $toPrepend = $('.phtpb_module-content[data-phtpb_cid="'+this.model.attributes.phtpb_cid+'"]');
			this.$('.phtpb_option-advanced-module-settings').prepend($toPrepend);
			this.copy = $toPrepend.clone();
			this.renderMap( true );

			setTimeout( function() {
				$this_el.find('select, input, textarea, radio').filter(':eq(0)').focus();
			}, 1 );

		},

		doModalAction : function( event ) {
			var attributes = {}, 
				attributesToString = '';
				
			event.preventDefault();
			
			this.$( '.phtpb_shortcode-attribute' ).each( function() {
				var $this_el = $(this),
					setting_value = $this_el.val(),
					name = $this_el.attr('id'),
					defaultData = $this_el.attr('data-default');

				if ( $this_el.is( 'input[type="checkbox"]' ) ) {	
					if ( $this_el.is(':checked') ) {
						setting_value = 'yes';
					} else {
						setting_value = '';
					}

				} else if ( $this_el.is( 'input' ) && setting_value !== '' ) {
					setting_value = setting_value.replace(/("|\[|\])/g, "");
					setting_value = _.escape( setting_value );
				}

				attributes[name] = setting_value;
				
				if ( _.isUndefined( defaultData ) || setting_value !== defaultData ) {
					attributesToString += name + '="' + setting_value +'" ';
				}
				
			});

			this.$( 'textarea, #phtpb_content_main' ).each( function() {
				var $this_el = $(this),
					setting_value = '';

				if ( $this_el.is( '#phtpb_content_main' ) ) {
					setting_value = $this_el.html();
				} else if ( $this_el.is('textarea#phtpb_content_new') ) {
					setting_value = peHaaThemesPageBuilder.phtpb_get_content( 'phtpb_content_new' );
				}
				attributes.phtpb_content_new = setting_value;
			} );
			
			attributes.shortcodeAttributesString = '';
			if ( 'column' === this.model.attributes.phtpb_admin_type ) {
				attributes.shortcodeAttributesString ='layout="'+this.model.attributes.layout+'" ';
			}
			attributes.shortcodeAttributesString +=  attributesToString;

			$('.phtpb_parent[data-phtpb_cid="'+this.model.attributes.phtpb_cid+'"]').find('.phtpb_controls').after(this.$('.phtpb_js-container'));
			this.model.set(attributes);
			if ( _.isUndefined( this.options.from ) || 'inner' !== this.options.from  ) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			}

			this.resetFields();			
			this.remove();
			this.removeOverlay();
			
			if ( 'phtpb_marker' === this.model.attributes.module_type) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_map-marker:modified' );
			} 
		},		

		doInnerModalAction : function( event ) {

			var module_type = $( event.currentTarget ).attr( 'data-module_type' );
			event.preventDefault();

			if ( _.isUndefined( module_type ) || _.isUndefined( phtpb_data.elements[module_type] ) ) {
				return;
			}
			this.collection.add( [ {
				phtpb_admin_type : 'module',
				phtpb_cid : this.collection.generateNewId(),
				module_type : module_type,
				admin_label : phtpb_data.elements[module_type].title,
				parent : this.model.attributes.phtpb_cid,
				view : this,
				phtpb_admin_mode : phtpb_data.elements[module_type].phtpb_admin_mode,
				child : phtpb_data.elements[module_type].child,
				from : 'inner',
				fromAction : 'addChild'
			} ] );
		},

		addressToGeocode : function( event ) {

			var that = this, 
				$modal_window = $(event.target).closest( '.phtpb_modal_settings_container' );

			if ( $modal_window.length ) {
				var $address = $modal_window.find("input[class*='map_address']"),
					address = $address.val();

				if ( ! $address.length || '' === address ) { 
					return;
				}

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode( { 'address': address}, function(results, status) {

					if (status === google.maps.GeocoderStatus.OK) {
						var result = results[0];
						$address.val( result.formatted_address );
						$modal_window.find("input[class*='lat']").val(result.geometry.location.lat());
						$modal_window.find("input[class*='lng']").val(result.geometry.location.lng());
						if ( 'phtpb_google_map' === that.model.attributes.module_type )	{
							that.map.setCenter(result.geometry.location);
						}
					} else {
						alert( 'Geocode was not successful for the following reason: ' + status );
					}
				});
			} 
		},

		rerenderMap : function() {
			if ( 'phtpb_google_map' !== this.model.attributes.module_type ) {
				return;
			}
			this.renderMap( false );
		},

		addMarkers : function( onOpen ) {

			var that = this,
				children = that.collection.getChildViews(that.model.attributes.phtpb_cid), 
				markers = {}, 
				bounds = new google.maps.LatLngBounds(),
				extended = false;
				if ( typeof that.map.getBounds() !== 'undefined') {
					bounds = that.map.getBounds();
				}
			_.each( children, function( child, index ) {

				var lat = child.model.attributes.marker_lat, 
					lng = child.model.attributes.marker_lng,
					marker_title = _.isUndefined(child.model.attributes.marker_title) || '' === child.model.attributes.marker_title.trim() ? child.model.attributes.map_address : child.model.attributes.marker_title,
					position = new google.maps.LatLng( parseFloat( lat ) , parseFloat( lng ) );

				markers[index] = new google.maps.Marker({
					map: that.map,
					position: position,
					title: marker_title,
				});
				
			});

		},

		renderMap: function( onOpen ) {
			if ( typeof google !== 'object' || typeof google.maps !== 'object' ) {
				return;
			}
			var that = this, $map = this.$el.find('.phtpb_js-map'),
			$lat = this.$el.find('#lat'),
			$lng = this.$el.find('#lng'),
			$zoom = this.$el.find('#zoom');

			if ( $map.length ) {
				setTimeout( function() {
					that.map = new google.maps.Map( $map[0], {
						center: new google.maps.LatLng( $lat.val(), $lng.val()),
						zoom: parseInt($zoom.val()),
						mapTypeId: google.maps.MapTypeId.ROADMAP,
						scrollwheel: false, 
					});
					setTimeout( function(){
						that.addMarkers( onOpen );
					}, 100);
					google.maps.event.addListener( that.map, 'center_changed', function() {
						var center = that.map.getCenter();
						$lat.val( center.lat() );
						$lng.val( center.lng() );
					});
					google.maps.event.addListener( that.map, 'zoom_changed', function() {
						$zoom.val( that.map.getZoom() );
					});
				}, 10);
			}
		}	
	
	});


})( jQuery );