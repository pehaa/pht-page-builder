var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.ModuleView = peHaaThemesPageBuilder.ElementView.extend( {

		events: function(){
			return _.extend({},peHaaThemesPageBuilder.ElementView.prototype.events,{
				'click .phtpb_add-child' : 'addChild'
			});
		},

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
			//_.extend( this.events, { 'click .phtpb_add-child' : 'addChild' } );
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

	});

})( jQuery );