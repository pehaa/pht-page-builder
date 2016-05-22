var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.RowLayoutView = peHaaThemesPageBuilder.ElementView.extend( {

		events: function(){
			return _.extend({},peHaaThemesPageBuilder.ElementView.prototype.events,{
				'click .phtpb_js_handlediv' : 'toggleRow'
			});
		},
		
		addMoreClassesToEl : function() {
			if ( -1 !== this.model.attributes.module_type.indexOf( '_inner' ) ) {
				this.elementClass += ' ' + this.model.attributes.module_type;
			}
			this.elementClass += ' phtpb_border--none';
			this.elementClass += !_.isUndefined( this.model.attributes.admin_collapsed ) && "true" === this.model.attributes.admin_collapsed ? ' js-slide-up' : '';
		},

		addEventsAndListeners : function() {
			//_.extend( this.events, { 'click .phtpb_js_handlediv' : 'toggleRow' } );
			this.listenTo( this.model, 'change:admin_label', this.renameElement );
		},

		toggleRow : function( event ) {
			event.preventDefault();
			event.stopPropagation();
			this.$el.toggleClass('js-slide-up');
			peHaaThemesPageBuilder_Events.trigger( 'phtpb_module:triggerUpdatePostContent' );
			this.model.attributes.admin_collapsed = this.$el.is('.js-slide-up');
		}
		
	});
})( jQuery );