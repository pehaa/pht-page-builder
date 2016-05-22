var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

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

	});


})( jQuery );