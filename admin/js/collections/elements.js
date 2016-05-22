var peHaaThemesPageBuilder = peHaaThemesPageBuilder || {};

( function($) {

	'use strict';

	peHaaThemesPageBuilder.Elements = Backbone.Collection.extend( {

		model : peHaaThemesPageBuilder.Element,			
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
				if ( !_.isUndefined( view ) && view.model.attributes.parent === parent_id ) {
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

				if ( !_.isUndefined( view ) && view.model.attributes.phtpb_admin_type === module_name ) {
					num++;
				}		
			});
			return num;
		},
		selfReset : function() {
						
			this.moduleNumber = 0;			
			this.views = [];
			this.models = [];

			return this;
		}	
	});

})();