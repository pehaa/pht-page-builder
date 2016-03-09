var 
	peHaaThemesPageBuilder = peHaaThemesPageBuilder || {}, 
	peHaaThemesPageBuilder_Events = _.extend( {}, Backbone.Events ),
	contentSource = phtpb_data.save_to;
	
( function($) {

'use strict';
	
	var 
	peHaaThemesPageBuilder_shortcodes = _.keys( phtpb_data.elements ),
	peHaaThemesPageBuilder_shortcodes_regex = peHaaThemesPageBuilder_shortcodes.join('|');
		
	/*----------------- Model - Module -----------------*/
	
	peHaaThemesPageBuilder.Module = Backbone.Model.extend( {

		defaults: {
			type : 'element'
		}

	} );
	
	/*--END------------ Model - Module -----------------*/
	
	/*----------------- Collection - Modules -----------*/	
	
	peHaaThemesPageBuilder.Modules = Backbone.Collection.extend( {

		model : peHaaThemesPageBuilder.Module,			
		moduleNumber : 0,			
		views : [],

		addView : function( module_phtpb_cid, view ) {
			var views = this.views;
			views[module_phtpb_cid] = view;
			this.views = views;
		},

		getView : function( phtpb_cid ) {
			return this.views[phtpb_cid];
		},

		getChildViews : function( parent_id ) {
			var views = this.views,
				child_views = {};
			_.each( views, function( view, key ) {
				if ( view.model.attributes.parent === parent_id ) {
					child_views[key] = view;
				}						
			} );
			return child_views;
		},

		setNewParentID : function( phtpb_cid, new_parent_id ) {

			var views = this.views;
			views[phtpb_cid].model.attributes.parent = new_parent_id;
			this.views = views;
		},

		removeViewAndModel : function( model ) {
			var phtpb_cid = model.attributes.phtpb_cid, 
				viewToDelete = this.getView( phtpb_cid );
			this.removeView( phtpb_cid );
			viewToDelete.remove();
			this.remove( model );	
		},

		removeView : function( phtpb_cid ) {
			var views = this.views,
				new_views = {};		
			_.each( views, function( value, key ) {
				if ( key.toString() !== phtpb_cid.toString() ) {
					new_views[key] = value;
				}			
			} );
			this.views = new_views;
		},

		generateNewId : function() {
			var moduleNumber = this.moduleNumber + 1;
			this.moduleNumber = moduleNumber;
			return moduleNumber;
		},

		generateTemplateName : function( name, isModal ) {
			var modelType = isModal ? '-modal' : ''; 
			return '#phtpb_builder-' + name + modelType +'-template';
		},

		getNumberOfModules : function( module_name ) {
			var views = this.views,
				num = 0;
			_.each( views, function( view ) {
				if ( view.model.attributes.phtpb_admin_type === module_name ) {
					num++;
				}		
			} );
			return num;
		},
		selfReset : function() {
						
		this.moduleNumber = 0;			
		this.views = [];
		this.models = [];

		return this;
		}	
	} );
	
	/*--END------------ Collection - Modules -----------*/
	
	/*----------------- View - Element  ----------------*/
	
	peHaaThemesPageBuilder.ElementView = Backbone.View.extend( {
		
		events : {
			'click .phtpb_open-modal' : 'openModal',
			'click .phtpb_remove' : 'removeMe',
			'click .phtpb_clone' : 'cloneMe',
		},
		
		connectWith : false,
		sortableItems : "> *",
		cancelDrag : ".phtpb_drag_disabled, .phtpb_drag_disabled--hard",

		initialize : function() {	

			this.$el.attr( 'data-phtpb_cid', this.model.get( 'phtpb_cid' ) );		
			this.elementClass = 'phtpb_' + this.model.attributes.phtpb_admin_type + ' phtpb_block phtpb_block--small';							
			this.addMoreClassesToEl();							
			this.addEventsAndListeners();
			
		},
		
		addMoreClassesToEl : function() {},
		
		addEventsAndListeners : function() {},		
		
		renameElement : function() {
			
			this.$el.children( '.phtpb_js-module-title' ).find('.phtpb_js-module-title__string').html( this.model.get( 'admin_label' ) );
		},
		
		render : function() {

			

			if ( _.isUndefined( this.template ) ) {
				this.template =  _.template( $( this.collection.generateTemplateName( this.model.get( 'phtpb_admin_type' ), false ) ).html() );

			}

			this.$el.addClass( this.elementClass ).html( this.template( this.model.toJSON() ) );
			this.makeChildrenSortable();
			return this;
		},		
		
		makeChildrenSortable : function() {
			
			var that = this;			
			if ( _.isUndefined( this.sortableElement ) ) {
				return;
			}

			this.$el.find( this.sortableElement ).sortable( {
				
				connectWith: this.connectWith,
				cancel : this.cancelDrag,
				items : this.sortableItems,
				placeholder: "sortable-placeholder",
				tolerance: "pointer",
				cursorAt: { left: 5 },
			
				start : function( event, ui ) {
					window.hasToUpdateContent = false;
					
					if ( $(ui.item).is('.phtpb_module') ) {
						$("#phtpb").addClass("phtpb_disable-section-module");
					}
					if ( $(ui.item).is('.phtpb_row') ) {
						$("#phtpb").addClass("phtpb_disable-row-row-row");
						if ( $(ui.item).find('.phtpb_row').length ) {
							$("#phtpb").addClass("phtpb_disable-row-row");
						}
					}
				},
				stop : function( event, ui ) {
					$("#phtpb").removeClass("phtpb_disable-section-module").removeClass("phtpb_disable-row-row-row").removeClass("phtpb_disable-row-row");
					
					if ( window.hasToUpdateContent ) {

						that.collection.setNewParentID( ui.item.data( 'phtpb_cid' ), window.newParentId );
						if ( ! $(that.sortableElement).parent().is( '.phtpb_option-advanced-module-settings' ) ) {
							peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
						}
					}
					window.hasToUpdateContent = false;
				},

				update : function( event, ui ) {	

					if ( ! $( ui.item ).closest( event.target ).length ) {
						return;
					}
					if ( ui.item.find('.phtpb_column').length && ui.item.parents('.phtpb_column').parents('.phtpb_column').length ) {
						$(ui.sender).sortable('cancel');
						return;
					}
					if ( ui.item.is('.phtpb_module') && ! ui.item.parents('.phtpb_column').length ) {
						$(ui.sender).sortable('cancel');
						return;
					}
					if ( ui.item.is('.phtpb_row') && ui.item.find('.phtpb_row').length && ui.item.parents('.phtpb_column').length ) {
						$(ui.sender).sortable('cancel');
						return;
					}
					window.hasToUpdateContent = true;
					window.newParentId = ui.item.parent()[0].attributes['data-phtpb_cid'].value;								
				}
			} );
							
		},
		
		checkEventOrigin : function( event ) {
			var $event_target = $( event.target ), buttonID = $event_target.closest( '[data-phtpb_cid]' ).data( 'phtpb_cid' );
		
			if ( buttonID !== this.model.attributes.phtpb_cid ) {
				return false;
			}	else {
				return $event_target.closest( '.phtpb_modal_settings_container' ).length ? 'modal' : 'normal';
			}			
		},
		
		removeMe : function( event ) {			
			var mychildren = this.collection.getChildViews( this.model.get('phtpb_cid') ), 
				force = false, 
				event_origin = false;
			if ( event ) {
				event.preventDefault();	
				event_origin = this.checkEventOrigin( event );
				if ( ! event_origin ) {
					return;
				} 
			} else {
				force = true;
			}			
			if ( this.model.attributes.phtpb_admin_type !== 'section' ) {
				force = true;
			} else if ( this.collection.getNumberOfModules( 'section' ) > 1 ) {
				force = true;
			}			
			_.each( mychildren, function( mychild ) {
					mychild.removeMe();					
			} );					
			if ( force ) {	
				this.collection.removeViewAndModel( this.model );
			} else {
				var phtpb_cid = this.model.attributes.phtpb_cid;
				this.model.attributes = {
					phtpb_admin_type : 'section',
					admin_label: phtpb_data.elements.phtpb_section.title,
					created: 'manually',
					phtpb_cid: phtpb_cid,
					module_type: 'phtpb_section',
					admin_collapsed: false					
				};
			}			
			if ( event && 'normal' === event_origin ) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			}	
			peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:afterDelete' );														
		},
		
		cloneMe : function( event ) {
				 
			if ( event ) {
				event.preventDefault();
				if ( ! this.checkEventOrigin( event ) ) {
					return;
				}					
			}

			if ( !_.isUndefined(phtpb_data.elements[this.model.attributes.module_type].pht_themes_only) && phtpb_data.elements[this.model.attributes.module_type].pht_themes_only ) {
							return;
						}
						
			var $cloned_element = this.$el.clone();
			this.$el.after( $cloned_element );
			peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:cloned' );		
			
		},
		
		openModal : function( event ) {
			
			if ( event ) {
				event.preventDefault();
				if ( ! this.checkEventOrigin( event ) ) {
					return;
				}						
			}
			
			var modal_window = $(event.target).closest('[data-modal-window]').data('modal-window'),
				modal_view,
				view_settings = {
					model : this.model,
					collection : this.collection,
					attributes : {
						'data-parent_phtpb_cid' : this.model.get( 'phtpb_cid' )
					},
					view : this,
					modal_window : modal_window,
					nested : false
				};
			if ( $(event.target).parents( '.phtpb_js-container' ).parent().is( '.phtpb_option-advanced-module-settings' ) ) {
				view_settings.from = 'inner';
			}
			if ( $(event.target).parents( '.phtpb_row' ).parents( '.phtpb_column' ).length ) {
				view_settings.nested = true;
			}
			if ( !_.isUndefined( modal_window ) ) {
				switch ( modal_window ) {
					case "all_modules":
						modal_view = new peHaaThemesPageBuilder.ModalAllModulesView( view_settings );
					break;
					case "columns_layout":
						if ( view_settings.nested ) return;
						modal_view = new peHaaThemesPageBuilder.ModalColumnsLayoutView( view_settings );
					break;
					default:
						if ( !_.isUndefined(phtpb_data.elements[this.model.attributes.module_type].pht_themes_only) && phtpb_data.elements[this.model.attributes.module_type].pht_themes_only ) {
							return;
						}
						modal_view = new peHaaThemesPageBuilder.ModalSettingsView( view_settings );		
					break;
				}
			}
			
			$('body').append( modal_view.render().el );
		},
						
	});
	
	/*--END------------ View - Element  ----------------*/
	
	/*----------------- View - Section  ----------------*/
	
	peHaaThemesPageBuilder.SectionView = peHaaThemesPageBuilder.ElementView.extend( {

		sortableElement : '.phtpb_section-content',
		connectWith : '.phtpb_section-content, .phtpb_column-content',
		cancelDrag : 'phtpb_column-content, .phtpb_drag_disabled, .phtpb_drag_disabled--hard',

		addMoreClassesToEl : function() {
			
			this.elementClass += ' phtpb_border--none';
			this.elementClass += !_.isUndefined( this.model.attributes.admin_collapsed ) && "true" === this.model.attributes.admin_collapsed ? ' js-slide-up' : '';
		},

		addEventsAndListeners : function() {
			_.extend( this.events, { 'click .phtpb_add-section' : 'addSection' } );
			_.extend( this.events, { 'click .phtpb_js_handlediv' : 'toggleSection' } );
			this.listenTo( this.model, 'change:admin_label', this.renameElement );
		},

		addSection : function( event ) {
			var module_id = this.collection.generateNewId();
			event.preventDefault();
			this.collection.add( [ {
				phtpb_admin_type : 'section',
				module_type : 'phtpb_section',
				phtpb_cid : module_id,
				admin_label : phtpb_data.elements.phtpb_section.title,
				view : this,
				created : 'auto',
				from : 'create_settings',
				phtpb_admin_mode : phtpb_data.elements.phtpb_section.phtpb_admin_mode,
				admin_collapsed : false
			} ] );
		},


		toggleSection : function( event ) {
			event.preventDefault();
			this.$el.toggleClass('js-slide-up');
			peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			this.model.attributes.admin_collapsed = this.$el.is('.js-slide-up');
		}

	} );

	/*--END------------ View - Section  ----------------*/
	
	/*----------------- View - Row ---------------------*/
	
	peHaaThemesPageBuilder.RowLayoutView = peHaaThemesPageBuilder.ElementView.extend( {
		
		addMoreClassesToEl : function() {
			if ( -1 !== this.model.attributes.module_type.indexOf( '_inner' ) ) {
				this.elementClass += ' ' + this.model.attributes.module_type;
			}
			this.elementClass += ' phtpb_border--none';
			this.elementClass += !_.isUndefined( this.model.attributes.admin_collapsed ) && "true" === this.model.attributes.admin_collapsed ? ' js-slide-up' : '';
		},

		addEventsAndListeners : function() {
			_.extend( this.events, { 'click .phtpb_js_handlediv' : 'toggleRow' } );
			this.listenTo( this.model, 'change:admin_label', this.renameElement );
		},

		toggleRow : function( event ) {
			event.preventDefault();
			event.stopPropagation();
			this.$el.toggleClass('js-slide-up');
			peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			this.model.attributes.admin_collapsed = this.$el.is('.js-slide-up');
		}
		
	} );
	
	/*--END------------ View - Row ---------------------*/
	
	/*----------------- View - Column ------------------*/

	peHaaThemesPageBuilder.ColumnView = peHaaThemesPageBuilder.ElementView.extend( { 

		sortableElement : '.phtpb_column-content',
		connectWith : '.phtpb_column-content, .phtpb_section-content',
		
		addMoreClassesToEl : function() {
			if ( -1 !== this.model.attributes.module_type.indexOf( '_inner' ) ) {
				this.elementClass += ' ' + this.model.attributes.module_type;
			}
		},

	} );

	/*--END------------ View - Column ------------------*/
	
	/*----------------- View - Module ------------------*/

	peHaaThemesPageBuilder.ModuleView = peHaaThemesPageBuilder.ElementView.extend( {

		sortableElement : '.phtpb_js-container',
		connectWith : false,
		sortableItems : ".phtpb_advanced_twin:not('.phtpb_not-sortable')",

		addMoreClassesToEl : function() {

			this.elementClass += ' ' + this.model.attributes.module_type;
			if ( this.model.attributes.phtpb_admin_mode === 'parent' ) {
				this.elementClass += ' phtpb_parent';
				this.elementClass += ' phtpb_first-level';
			} else if ( this.model.attributes.phtpb_admin_mode === 'advanced_twin' || this.model.attributes.phtpb_admin_mode === 'advanced_child' ) {
					this.elementClass += ' phtpb_advanced ' + 'phtpb_' + this.model.attributes.phtpb_admin_mode;
			} else if ( this.model.attributes.phtpb_admin_mode === 'simple' ) {
				this.elementClass += ' phtpb_first-level';
			}
			if ( this.model.attributes.module_type === 'phtpb_marker' ) {
				this.elementClass += ' phtpb_not-sortable';
			}
			this.elementClass += ' phtpb_border--tiny';
			if ( !_.isUndefined(phtpb_data.elements[this.model.attributes.module_type].pht_themes_only) && phtpb_data.elements[this.model.attributes.module_type].pht_themes_only ) {
				this.elementClass += ' phtpb_drag_disabled--hard phtpb_disabled';
			} else if ( !_.isUndefined(phtpb_data.elements[this.model.attributes.module_type].is_disabled) && phtpb_data.elements[this.model.attributes.module_type].is_disabled ) {
				this.elementClass += ' phtpb_drag_disabled--hard phtpb_disabled';
			} else {
				this.elementClass += ' phtpb_colorblock';
			}
			
		},

		addEventsAndListeners : function() {
			_.extend( this.events, { 'click .phtpb_add-child' : 'addChild' } );
			this.listenTo( this.model, 'change:admin_label', this.renameElement );
		},

		addChild : function( event ) {

			event.preventDefault();
			var module_type = $( event.currentTarget ).attr( 'data-module_type' );
			var module_attributes = {
				phtpb_admin_type : 'module',
				phtpb_cid : this.collection.generateNewId(),
				module_type : module_type,
				admin_label : phtpb_data.elements[module_type].title,
				parent : this.model.attributes.phtpb_cid,
				view : this,
				phtpb_admin_mode : phtpb_data.elements[module_type].phtpb_admin_mode,
				child : phtpb_data.elements[module_type].child,
				from : 'create_settings',
			};
			this.collection.add( [ module_attributes ] );
		}

	} );
	
	/*--END------------ View - Module ------------------*/
	
	/*--------------------------------------------------*/
	/*------------------- MODALS: ----------------------*/
	/*--------------------------------------------------*/
	
	
	/*----------------- View - Modal -------------------*/		

	peHaaThemesPageBuilder.ModalView = window.wp.Backbone.View.extend( {

		events : {
			'click .phtpb_cancel-modal-action' : 'cancelModalAction',
			'click .phtpb_do-modal-action' : 'doModalAction',
			'click .phtpb_do-inner-modal-action' : 'doInnerModalAction',
		},

		className : 'phtpb_modal_settings_container',

		template : _.template( $('#phtpb_builder-modal-template').html() ),

		innerTemplateData : {},

		render : function() {
			
			var column_class = '';
			
			if ( 'column' === this.options.model.attributes.phtpb_admin_type ) {
				
				column_class = !_.isUndefined( this.options.nested ) && this.options.nested ? ' phtpb_modal__column-inner' : '';
				
			}
			this.$el.addClass( 'phtpb_modal__' + this.options.modal_window ).addClass( 'phtpb_modal__' + this.options.model.attributes.phtpb_admin_type + column_class ).html( this.template( _.extend(this.model.toJSON(), {modal_window: this.options.modal_window} ) ) );

			if ( !_.isUndefined( this.innerTemplate ) )	{
				this.container = this.$('.phtpb_modal-container');
				this.$el.append( this.innerTemplate( this.innerTemplateData ) );
			}		
			this.advancedRender();					
			this.addOverlay();
			return this;
		},
		
		resetFields : function() {
			this.$el.find('.phtpb_color-picker-hex').each( function() {
				$('label[for=' + $(this).attr('id') + ']').trigger('click');
			});
			if ( this.$( '#phtpb_content_new' ).length ) {

				if ( !_.isUndefined( window.switchEditors ) ) {
					window.switchEditors.go( 'phtpb_content_new', 'tinymce' );
				}
				if ( typeof window.tinyMCE !== 'undefined' ) {
					window.tinyMCE.execCommand( 'mceRemoveEditor', false, 'phtpb_content_new' );

					if ( typeof window.tinyMCE.get( 'phtpb_content_new' ) !== 'undefined' ) {
						window.tinyMCE.remove( '#phtpb_content_new' );
					}
				}
				
					//window.switchEditors.go( 'content','tinymce' );
					//window.wpActiveEditor = 'content';
				
			}
			
			
		},

		cancelModalAction : function( event ) {
			event.preventDefault();
			this.removeOverlay();			

			this.resetFields();
			
			if ( 'parent' === this.model.attributes.phtpb_admin_mode && this.options.from !== 'create_settings' ) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:reInitialized' );
			}	

			if ( this.options.from === 'create_settings'|| ( !_.isUndefined( this.options.fromAction ) && 'addChild' === this.options.fromAction ) ) {
				this.collection.removeViewAndModel( this.model );
			}

			this.remove();
		},

		doInnerModalAction : function() {},

		addOverlay : function() {

			if ( ! $( 'body' ).is('.phtpb_stop_scroll') ) {
				this.overlay = true;
				$( 'body' ).addClass( 'phtpb_stop_scroll' );
			}					
		},

		removeOverlay : function() {
			
			if ( !_.isUndefined( this.overlay ) && this.overlay ) {

				this.overlay = false;
				$( 'body' ).removeClass( 'phtpb_stop_scroll' );
			}
		},

		doModalAction : function() {},

		advancedRender : function() {}

	} );

	/*--END------------ View - Modal -------------------*/
	
	/*----------------- View - Settings ----------------*/
	
	peHaaThemesPageBuilder.ModalSettingsView = peHaaThemesPageBuilder.ModalView.extend( {			

		initialize : function() {
			_.extend( this.events, 
				{ 'click .phtpb_address-to-geocode' : 'addressToGeocode',
				'click .phtpb_upload-button' : 'openMediaFrame',
				'click .phtpb_icons-list__icon' : 'chooseIcon',
				'change :checkbox' : 'conditionalFields',
				'change select' : 'conditionalFields',
			} );
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
					phtpb_set_content( 'phtpb_content_new', content );
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
					setting_value = phtpb_get_content( 'phtpb_content_new' );
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
	
	} );


	peHaaThemesPageBuilder.ModalAllModulesView = peHaaThemesPageBuilder.ModalView.extend( {

		innerTemplateData : {},

		initialize : function() {

			this.innerTemplate = _.template( $('#phtpb_builder-all-modules-modal-template').html() );

		},

		doModalAction : function( event ) {

			var module_type = $( event.currentTarget ).attr( 'data-module_type' );
			event.preventDefault();

			this.remove();
			this.removeOverlay();

			this.collection.add( [ {
				phtpb_admin_type : 'module',
				phtpb_cid : this.collection.generateNewId(),
				module_type : module_type,
				admin_label : phtpb_data.elements[module_type].title,
				parent : this.attributes['data-parent_phtpb_cid'],
				view : this.options.view,
				phtpb_admin_mode : phtpb_data.elements[module_type].phtpb_admin_mode,
				child : phtpb_data.elements[module_type].child,
				add_submodule : phtpb_data.elements[module_type].add_submodule,
				from : 'create_settings',
			} ] );

			
		},

	} );

	peHaaThemesPageBuilder.ModalColumnsLayoutView = peHaaThemesPageBuilder.ModalView.extend( {

		initialize : function() {
			this.innerTemplate = _.template( $('#phtpb_builder-columns-modal-template').html() );
		},

		doModalAction : function( event ) {

			event.preventDefault();
			
			var module_id = this.collection.generateNewId(),
			$layout_el = $(event.target).is( 'li' ) ? $(event.target) : $(event.target).closest( 'li' ),
			layout = $layout_el.data('layout').split(','),
			parent_phtpb_cid = this.attributes['data-parent_phtpb_cid'];
			
			this.collection.add( [ {
				phtpb_admin_type : 'row',
				module_type : 'phtpb_row',
				phtpb_cid : module_id,
				parent : parent_phtpb_cid,
				view : this.options.view,
				columnlayout : layout,
				phtpb_admin_mode : phtpb_data.elements.phtpb_row.phtpb_admin_mode,
				admin_collapsed : false
			} ] );
			
			this.remove();
			this.removeOverlay();

			if ( event ) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			}
		},

	} );


	peHaaThemesPageBuilder.AppView = window.wp.Backbone.View.extend( {

		template : _.template( $('#phtpb_builder-app-template').html() ),

		initialize : function() {

			this.listenTo( this.collection, 'add', this.addModule );
			this.listenTo( peHaaThemesPageBuilder_Events, 'phtpb_module:cloned', this.onClone );
			this.listenTo( peHaaThemesPageBuilder_Events, 'phtpb_module:triggerUpdatePostContent', this.updatePostContent );
			this.listenTo( peHaaThemesPageBuilder_Events, 'phtpb_module:reInitialized', this.reInitialize );
			this.render();
			this.reInitialize( false, this.options.start_data );
		},

		render : function() {

			$('#phtpb_main_container').append( this.$el.html( this.template() ) );
			
			setTimeout( function() {
				$('.phtpb_preloader').addClass( 'phtpb_loaded' ).remove();
				$('#phtpb_main_container').addClass('phtpb_main_container-loaded');
			}, 600);
			
			this.$el.sortable( {
				cancel : '.add-row-layout, .phtpb_modal-bottom-container, .phtpb_row, #phtpb_layout_controls, .phtpb_drag_disabled, .phtpb_drag_disabled--hard',
				placeholder: "sortable-placeholder",
				//forcePlaceholderSize: true,
				tolerance: "pointer",
				//cursorAt: { left: 5 },
				update : function() {
					peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
				}
			} );
			return this;
		
		},

		reInitialize : function( withoutShortcodesUpdated, start_data ) {
		
			var content_source = contentSource,
				content,
				content_raw,
				contentIsEmpty,
				shortcode;

			if ( !_.isUndefined( start_data ) ) {
				content_source = start_data;
			}

			content = phtpb_get_content( content_source, true );

			contentIsEmpty = content === '';

			this.removeAllSections();
	
			if ( contentIsEmpty ) {
				
				content_raw = phtpb_get_content( 'content', true );
				if ( content_raw.indexOf( '[phtpb_section' ) === -1 ) {
					if ( '' !== content_raw ) {
					content = '[phtpb_row][phtpb_column layout="4_4"][phtpb_text phtpb_width="1"]' + content_raw + '[/phtpb_text][/phtpb_column][/phtpb_row]';
					
					} 
					content = '[phtpb_section]' + content + '[/phtpb_section]';
				
				} else {
					content = content_raw;
				}
				
			} else if ( content.indexOf( '[phtpb_section' ) === -1 ) {
				
				content = '[phtpb_section][phtpb_row][phtpb_column layout="4_4"][phtpb_text phtpb_width="1"]' + content + '[/phtpb_text][/phtpb_column][/phtpb_row][/phtpb_section]';

			} 
			this.createLayoutFromContent( content );
			
			if ( _.isUndefined(withoutShortcodesUpdated) || !withoutShortcodesUpdated ) {
				shortcode = this.updatePostContent();

				if ( shortcode !== content ) {

					var confirmation = alert( phtpb_data.confirmation );
					console.log(shortcode);
					console.log(content);
					this.removeAllSections();
					this.createLayoutFromContent( shortcode );
				}
			}
		},


		pageBuilderIsActive : function() {
			return this.$builder_toggle_button.hasClass( 'phtpb_builder_is_used' );
		},

		wp_regexp_not_global : _.memoize( function( tag ) {
			return new RegExp( '\\[(\\[?)(' + tag + ')(?![\\w-])([^\\]\\/]*(?:\\/(?!\\])[^\\]\\/]*)*?)(?:(\\/)\\]|\\](?:([^\\[]*(?:\\[(?!\\/\\2\\])[^\\[]*)*)(\\[\\/\\2\\]))?)(\\]?)' );
		}),

		createLayoutFromContent : function( content, parent_phtpb_cid ) {
			var this_el = this,
			/* /\[(\[?)(undefined)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*(?:\[(?!\/\2\])[^\[]*)*)(\[\/\2\]))?)(\]?)/g */
				reg_exp = window.wp.shortcode.regexp( peHaaThemesPageBuilder_shortcodes_regex ),

				inner_reg_exp = this.wp_regexp_not_global( peHaaThemesPageBuilder_shortcodes_regex ),
				matches = content.match( reg_exp );

			_.each( matches, function ( shortcode ) {
				var shortcode_element = shortcode.match( inner_reg_exp ),
					shortcode_name = shortcode_element[2],
					shortcode_attributes = shortcode_element[3] !== '' ? window.wp.shortcode.attrs( shortcode_element[3] ) : '',
					shortcode_content = shortcode_element[5],
					module_phtpb_cid = this_el.collection.generateNewId(),
					module_settings,
					prefixed_attributes = {},
					found_inner_shortcodes = !_.isUndefined( shortcode_content ) && shortcode_content !== '' && shortcode_content.match( reg_exp ),
					shortcodeAttributesString;

				shortcodeAttributesString = shortcode_element[3].replace(/(\"\S+\")([^(\s|\])])/, '$1');
				module_settings = {
					phtpb_admin_type : phtpb_data.elements[shortcode_name].phtpb_admin_type,
					phtpb_cid : module_phtpb_cid,
					created : 'manually',
					module_type : shortcode_name,
					admin_label : phtpb_data.elements[shortcode_name].title,
					shortcodeAttributesString :  shortcodeAttributesString,
					phtpb_admin_mode : phtpb_data.elements[shortcode_name].phtpb_admin_mode,
					child : phtpb_data.elements[shortcode_name].child,
				};
				
				if ( 'section' !== module_settings.phtpb_admin_type ) {
					module_settings.parent = parent_phtpb_cid;
				}

				if ( _.isObject( shortcode_attributes.named ) ) {
					for ( var key in shortcode_attributes.named ) {

						prefixed_attributes[key] = shortcode_attributes.named[key];
					}

					module_settings = _.extend( module_settings, prefixed_attributes );
				}

				if ( ! found_inner_shortcodes ) {
					module_settings.phtpb_content_new = shortcode_content;
				}
				
				this_el.collection.add( [ module_settings ] );

				if ( found_inner_shortcodes ) {

					this_el.createLayoutFromContent( shortcode_content, module_phtpb_cid );
				}
			} );
		},

		insertModuleToLayout : function( module, view ) {
			if ( module.get( 'phtpb_admin_mode' ) === 'advanced_twin' || module.get( 'phtpb_admin_mode' ) === 'advanced_child' ) {
				$('.phtpb_module-content[data-phtpb_cid="'+module.get('parent')+'"]').append( view.render().el );
			} else {
				if ( this.collection.getView( module.get( 'parent' ) ).$el.children( '.phtpb_js-container' ).children('.phtpb_insert-module').length ) {
					this.collection.getView( module.get( 'parent' ) ).$el.children( '.phtpb_js-container' ).children('.phtpb_insert-module').before( view.render().el );
				} else {
					this.collection.getView( module.get( 'parent' ) ).$el.children( '.phtpb_js-container' ).append( view.render().el );
				}
			}
		},

		addModule : function( module ) {

			var view,
			that = this,
				view_settings = {
					model : module,
					collection : this.collection,
					attributes : {
						'data-phtpb_cid' : module.get( 'phtpb_cid' ),
					}
				};

			switch ( module.get( 'phtpb_admin_type' ) ) {
				
				case 'section' :

			
					view = new peHaaThemesPageBuilder.SectionView( view_settings );
					this.collection.addView( module.get('phtpb_cid'), view );
					if ( ! _.isUndefined( module.get( 'view' ) ) ) {
						module.get( 'view' ).$el.after( view.render().el );
					}	else {
						this.$el.append( view.render().el );
					}
					this.maybeOpenSettings( module, view_settings );
				break;
				
				case 'row' :
				

					view = new peHaaThemesPageBuilder.RowLayoutView( view_settings );
					this.collection.addView( module.get('phtpb_cid'), view );						
					this.insertModuleToLayout( module, view );

					var columnlayout = view.model.attributes.columnlayout;
						_.each( columnlayout, function( element ) {
							var column_attributes = {
								phtpb_admin_type : 'column',
								module_type : 'phtpb_column',
								phtpb_cid : that.collection.generateNewId(),
								parent : view.model.get( 'phtpb_cid' ),
								layout : element,
								view : view,
								shortcodeAttributesString : 'layout="'+ element +'"',
								phtpb_admin_mode : 'simple'
							};
							that.collection.add( [ column_attributes ] );
						} ); 

				break;
				
				case 'column' :
				
					view_settings.className = 'phtpb_column phtpb_column-' + module.get( 'layout' );
					view = new peHaaThemesPageBuilder.ColumnView( view_settings );
					this.collection.addView( module.get('phtpb_cid'), view );
					this.insertModuleToLayout( module, view );

				break;
				
				case 'module' :

					view = new peHaaThemesPageBuilder.ModuleView( view_settings );
					this.insertModuleToLayout( module, view );
					this.collection.addView( module.get('phtpb_cid'), view );
					if ( 'manually' !== module.get( 'created' ) ) {
						if ( 'parent' === module.get( 'phtpb_admin_mode' ) && !_.isUndefined(phtpb_data.elements[module.attributes.module_type].kids) ) {
							_.each(phtpb_data.elements[module.attributes.module_type].kids, function( kid ) {
								var kid_attributes = {
										phtpb_admin_type : 'module',
										created : 'manually',
										module_type : kid,
										phtpb_cid : that.collection.generateNewId(),
										parent : view.model.get( 'phtpb_cid' ),
										view : view,
										admin_label : phtpb_data.elements[kid].title,
										phtpb_admin_mode : 'advanced_child',
									};
								that.collection.add( [ kid_attributes ] );
							});
						}
						this.maybeOpenSettings( module, view_settings );
					} 

				break;
			}
		},

		maybeOpenSettings : function( module, view_settings, condition ) {
			var modal_view;

			if ( _.isUndefined( condition ) ) {
				condition = 'manually' !== module.get( 'created' ) && phtpb_data.elements[module.attributes.module_type].create_with_settings;
			} 

			if ( condition ) {

				if ( !_.isUndefined( module.get( 'from') ) ) {
						view_settings.from = module.get( 'from' );
				}
				if ( !_.isUndefined( module.get( 'fromAction' ) ) ) {
						view_settings.fromAction = module.get( 'fromAction' );
				}
				view_settings.modal_window = 'settings';

				modal_view = new peHaaThemesPageBuilder.ModalSettingsView( view_settings );
				$('body').append( modal_view.render().el );
			
			} else if ( 'manually' !== module.get( 'created' ) ) {
				peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			}

		},

		moduleToModel : function( $module ) {
			var module_phtpb_cid = $module.data( 'phtpb_cid' );

			var model = this.collection.find( function( model ) {

				return model.get('phtpb_cid') === module_phtpb_cid;
			} );

			return model;
		},

		updatePostContent : function() {
					
			var content = this.generateModuleShortcode();

			phtpb_set_content( contentSource, content );

			phtpb_set_content( 'content', content );
			
			return content;

		},

	generateModuleShortcode	: function( $module, multiplier ) {

			var this_el = this, 
				attributes, 
				shortcode = '', 
				module, 
				inner = '',
				inner_multiplier = 1,
				multiplier_value,
				module_type,
				children_class = { 'section' : '.phtpb_row', 'row' : '.phtpb_column', 'column' : '.phtpb_first-level, .phtpb_row', 'module' : '.phtpb_advanced' },
				values = {
					'fullwidth' : 100,
					'4_4' : 1,
					'3_4' : 0.75,
					'2_3' : 0.66,
					'1_2' : 0.5,
					'1_3' : 0.33,
					'1_4' : 0.25
				};

			if ( _.isUndefined( $module ) ) {

				this.$el.find( '.phtpb_section' ).each( function() {
					shortcode += this_el.generateModuleShortcode( $(this), 1 );
				});

			} else {

				module = this_el.moduleToModel( $module );
				module_type = module.attributes.module_type;
				if ( module.attributes.module_type === 'phtpb_row' ) {
					if ( !_.isUndefined( module.attributes.wrapper ) && 'normal' !== module.attributes.wrapper  ) {
						inner_multiplier = 100;
					}
				}
				if ( module.attributes.module_type === 'phtpb_row_inner' ) {
					module_type = 'phtpb_row';
				}
				if ( module.attributes.module_type === 'phtpb_column_inner' ) {
					module_type = 'phtpb_column';
				}
				if ( module_type === 'phtpb_column' ) {
					inner_multiplier = inner_multiplier*values[module.attributes.layout];
				}
				if ( ( module_type === 'phtpb_row' || module_type === 'phtpb_column' ) && ( $module ).parents('.phtpb_column').length ) {
					inner = '_inner';
				}
				attributes = !_.isUndefined( module.attributes.shortcodeAttributesString ) && ''!== module.attributes.shortcodeAttributesString.trim() ? ' ' + module.attributes.shortcodeAttributesString.trim() : '';
				if ( module.attributes.phtpb_admin_type === 'module' ) {
					multiplier_value = Math.ceil(multiplier*10)/10;
					if ( attributes.search(/phtpb_width=([^\s]*)/) == -1 ) {
						attributes += ' phtpb_width="'+ multiplier_value +'"';
					} else {
						attributes = attributes.replace(/phtpb_width=([^\s]*)/, 'phtpb_width="'+ multiplier_value +'"');
					}	
				}
				if ( module.attributes.phtpb_admin_type === 'section' || module.attributes.phtpb_admin_type === 'row' ) {
					var value = $module.is('.js-slide-up');
					if ( attributes.search(/admin_collapsed=([^\s]*)/) == -1 ) {
						if ( value ) { attributes += ' admin_collapsed="'+ value +'"'; }
					} else {
						attributes = attributes.replace(/admin_collapsed=([^\s]*)/, 'admin_collapsed="'+ value +'"');
					}
				}
				shortcode += '[' + module_type + inner + attributes + ']';
				
				if ( !_.isUndefined( module.attributes.phtpb_content_new ) && module.attributes.phtpb_admin_type === 'module' && module.attributes.phtpb_admin_mode !== 'parent' ) {
					shortcode += module.attributes.phtpb_content_new;
				} else {

					$module.children('.phtpb_js-container').children( children_class[ module.attributes.phtpb_admin_type ] ).each( function(){

						shortcode += this_el.generateModuleShortcode( $(this), multiplier*inner_multiplier );
						
					});

				}
				shortcode += '[/' + module_type + inner +']';
			}

			return shortcode;
		},

		removeAllSections : function() {
			var that = this;
			
			this.$el.find( '.phtpb_section-content' ).each( function() {
				var $this_el = $(this),
					this_view = that.collection.getView( $this_el.data( 'phtpb_cid' ) );

				if ( ! _.isUndefined( this_view ) ) {						
					this_view.removeMe();
				} 
			} );
			that.collection.selfReset();

		},
				
		onClone : function() {
			var that = this;
			$('#phtpb_main_container').addClass('event-inactive').find('.phtpb_block').addClass('phtpb_drag_disabled');
				this.updatePostContent();
				var height = $('#phtpb .inside').height();
				$('#phtpb .inside').css('min-height',height+'px');
				setTimeout( function() {
					that.$el.find( '.phtpb_section' ).remove();
					that.reInitialize( false );
					$('#phtpb .inside').css('min-height',0);
					$('#phtpb_main_container').removeClass('event-inactive').find('.phtpb_block').removeClass('phtpb_drag_disabled');
				}, 500 );
		}

	} );
	
	/*--END------------ Collection - Modules -----------*/
		
	
	function phtpb_get_content( textarea_id, fix_shortcodes ) {
		
		var content;
		fix_shortcodes = typeof fix_shortcodes !== 'undefined' ? fix_shortcodes : false;
		
		content = typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden() ? window.tinyMCE.get( textarea_id ).getContent() : $( '#' + textarea_id ).val();

		if ( 'undefined' === typeof content ) {
			return '';
		}

		if ( fix_shortcodes && typeof window.tinyMCE !== 'undefined' ) {

			content = content.replace( /<p>\[/g, '[' );
			content = content.replace( /<br>\[/g, '[' );
			content = content.replace( /\]<\/p>/g, ']' );
		}

		return content.trim();
	}

	function phtpb_set_content( textarea_id, content ) {

		if ( typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden() ) {
			content = switchEditors.wpautop( content );
			
			window.tinyMCE.get( textarea_id ).setContent( content, { format : 'html'  } );
			setTimeout( function() {
				if ( window.tinyMCE.get( 'content' ) ) {
					window.tinyMCE.get( 'content' ).focus();
				}
				
			}, 50);
			
		} else {
			$( '#' + textarea_id ).val( content );
		}				
	}

	$( document ).ready( function() {
		
		if ( typeof qTranslateConfig !== 'undefined' ) {
			qTranslateConfig.qtx.removeContentHook( document.getElementById('phtpb_content_new') );
			qTranslateConfig.qtx.addLanguageSwitchAfterListener(
					function(){
						qTranslateConfig.qtx.removeContentHook( document.getElementById( 'phtpb_content_new' ) );
						peHaaThemesPageBuilder_App.remove();
						peHaaThemesPageBuilder_App.initialize();
					}
				);
		}
		var peHaaThemesPageBuilder_App,
			$builder_toggle_button = $( 'body' ).find( '#phtpb_toggle_builder-meta' ),
			isActivated = $builder_toggle_button.is('.phtpb_builder_is_used'), 
			currentState = isActivated,
			$current_state_mb = $("#phtpb_state-yes"),
			$page_builder_mb = $('#phtpb'),
			$main_editor_wrap = $('#phtpb_main_editor_wrap'),
			$hidden_editor = $( '#phtpb_hidden_editor' );
			
		peHaaThemesPageBuilder.initial_content = $hidden_editor.html();	
		$hidden_editor.remove();

		if ( phtpb_data.is_always_active ) {
			isActivated = true;
		}
	
		if ( true === isActivated ) {		

			peHaaThemesPageBuilder_App = new peHaaThemesPageBuilder.AppView( {
				model : peHaaThemesPageBuilder.Module,
				collection : new peHaaThemesPageBuilder.Modules(),
				start_data : contentSource
			} );
			
			$page_builder_mb.addClass('phtpb_visible');
			currentState = true;
			isActivated = true;
			
		} else {
			$page_builder_mb.addClass('phtpb_hidden');
		}

		$( document ).on( 'click', '.js-phtpb_toggle_builder', function( event ) {
			
			event.preventDefault();

			var start_data = $(this).data( 'content-source' ),
				showClass = 'content' === start_data ? 'phtpb_show_content_editor' : 'phtpb_show_second_editor';

			if ( !start_data ) {
				return false;
			}

			$(this).siblings('.phtpb_table--cell').remove();
			
			if ( true === currentState ) {

				currentState = false;
				$('.phtpb_use_default, .phtpb_use_pb').toggleClass('js-hidden');
				$(this).toggleClass('button-primary');
				$main_editor_wrap.addClass( 'phtpb_hide_pd ' + showClass );

			} else {

				if ( false === currentState && false === isActivated ) {

					peHaaThemesPageBuilder_App = new peHaaThemesPageBuilder.AppView( {
						model : peHaaThemesPageBuilder.Module,
						collection : new peHaaThemesPageBuilder.Modules(),
						start_data : start_data
					} );

					$(this).addClass( 'phtpb_builder_is_used' ).removeClass( 'button-primary' );
					$('.phtpb_activate_pb').addClass('js-hidden');
					$('.phtpb_use_default').removeClass('js-hidden');
					$main_editor_wrap.addClass('phtpb_hidden phtpb_activated').removeClass('phtpb_not_activated');
					
					$current_state_mb.prop( "checked", true );

					isActivated = true;
					currentState = true;
					
				} else if ( false === currentState && true === isActivated ) {
				
					peHaaThemesPageBuilder_App.remove();
					peHaaThemesPageBuilder_App.initialize();
					
					$('.phtpb_use_default, .phtpb_use_pb').toggleClass('js-hidden');
					$(this).toggleClass('button-primary');
					$main_editor_wrap.removeClass( 'phtpb_hide_pd ' + showClass );
					currentState = true;
									
				}
			} 

			$page_builder_mb.toggleClass('phtpb_hidden').toggleClass('phtpb_visible');
				
		});

	});

} )(jQuery);

function initialize() {}

function loadScript() {

  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = phtpb_data.gmaps_url;
  document.body.appendChild(script);

}

window.onload = loadScript;