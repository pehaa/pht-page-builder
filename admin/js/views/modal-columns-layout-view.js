var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';
	
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

	});

})( jQuery );