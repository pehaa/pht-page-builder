var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.ColumnView = peHaaThemesPageBuilder.ElementView.extend( { 

		sortableElement : '.phtpb_column-content',
		connectWith : '.phtpb_column-content, .phtpb_section-content',
		
		addMoreClassesToEl : function() {
			if ( -1 !== this.model.attributes.module_type.indexOf( '_inner' ) ) {
				this.elementClass += ' ' + this.model.attributes.module_type;
			}
		},

	});

})( jQuery );