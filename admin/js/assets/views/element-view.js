var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

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
			
			this.$el
				.children( '.phtpb_js-module-title' )
				.find( '.phtpb_js-module-title__string')
				.html( this.model.get( 'admin_label' ) );
		},
		
		render : function() {

			if ( _.isUndefined( this.template ) ) {
				this.template =  _.template( $( this.collection.generateTemplateName( this.model.get( 'phtpb_admin_type' ), false ) ).html() );

			}

			this.$el
				.addClass( this.elementClass )
				.html( this.template( this.model.toJSON() ) );
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
					
					$("#phtpb").removeClass( "phtpb_disable-section-module phtpb_disable-row-row-row phtpb_disable-row-row");

					if ( window.hasToUpdateContent ) {

						that.collection.setNewParentID( ui.item.data( 'phtpb_cid' ), window.newParentId );
						if ( ! $(that.sortableElement).parent().is( '.phtpb_option-advanced-module-settings' ) ) {
							peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerRefreshLayout' );
							
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
						
						if ( view_settings.model.attributes.module_type  === "phtpb_row" && $( this.el.parentElement ).hasClass( 'phtpb_column-content' ) ) {
							view_settings.model.attributes.module_type  = "phtpb_row_inner";
						}
						
						modal_view = new peHaaThemesPageBuilder.ModalSettingsView( view_settings );		
					break;
				}
			}
			
			$('body').append( modal_view.render().el );
		},
						
	});

})( jQuery );