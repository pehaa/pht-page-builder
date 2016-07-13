var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.ModalAllModulesView = peHaaThemesPageBuilder.ModalView.extend( {

		innerTemplateData : {},

		initialize : function( options ) {

			this.options = options;
			
			this.listenTo( peHaaThemesPageBuilder_Events, 'gmapsAuth:failed', this.gmapsAuthFailed );
			this.innerTemplate = _.template( $('#phtpb_builder-all-modules-modal-template').html() );
			this.innerTemplateData = { phtpb_modules_data: { gmapsAuthFailed : peHaaThemesPageBuilder.gmapsAuthFailed } };
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
			}]);
	
		},

		gmapsAuthFailed : function() {
			$( '.phtpb_gmaps_enabled' ).removeClass( 'phtpb_gmaps_enabled' ).addClass( 'phtpb_gmaps_disabled' );
		}

	});

})( jQuery );