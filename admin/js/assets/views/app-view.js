var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

		var 
		peHaaThemesPageBuilder_shortcodes = _.keys( phtpb_data.elements ),
		peHaaThemesPageBuilder_shortcodes_regex = peHaaThemesPageBuilder_shortcodes.join('|');

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
				tolerance: "pointer",
				update : function() {
					peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
				}
			});

			return this;
		
		},

		reInitialize : function( withoutShortcodesUpdated, start_data ) {
		
			var content_source = phtpb_data.save_to,
				content,
				content_raw,
				contentIsEmpty,
				shortcode;

			if ( !_.isUndefined( start_data ) ) {
				content_source = start_data;
			}

			content = peHaaThemesPageBuilder.phtpb_get_content( content_source, true );

			contentIsEmpty = content === '';

			this.removeAllSections();
	
			if ( contentIsEmpty ) {
				
				content_raw = peHaaThemesPageBuilder.phtpb_get_content( 'content', true );
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

			peHaaThemesPageBuilder.phtpb_set_content( phtpb_data.save_to, content );

			peHaaThemesPageBuilder.phtpb_set_content( 'content', content );
			
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

	});


})( jQuery );