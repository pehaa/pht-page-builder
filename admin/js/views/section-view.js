var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.SectionView = peHaaThemesPageBuilder.ElementView.extend( {

		events: function(){
			return _.extend( {}, peHaaThemesPageBuilder.ElementView.prototype.events, {
				'click .phtpb_add-section' : 'addSection',
				'click .phtpb_js_handlediv' : 'toggleSection'
			});
		},

		sortableElement : '.phtpb_section-content',
		connectWith : '.phtpb_section-content, .phtpb_column-content',
		cancelDrag : 'phtpb_column-content, .phtpb_drag_disabled, .phtpb_drag_disabled--hard',

		addMoreClassesToEl : function() {
			
			this.elementClass += ' phtpb_border--none';
			this.elementClass += !_.isUndefined( this.model.attributes.admin_collapsed ) && "true" === this.model.attributes.admin_collapsed ? ' js-slide-up' : '';
		},

		addEventsAndListeners : function() {
			
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

	});

})( jQuery );