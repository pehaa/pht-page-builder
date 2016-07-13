var 
	peHaaThemesPageBuilder = peHaaThemesPageBuilder || {}, 
	peHaaThemesPageBuilder_Events = _.extend( {}, Backbone.Events );
	
( function($) {

	'use strict';

	peHaaThemesPageBuilder.phtpb_get_content = function( textarea_id, fix_shortcodes ) {
		
		var content;
		fix_shortcodes = typeof fix_shortcodes !== 'undefined' ? fix_shortcodes : false;
		
		content = typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden() ? window.tinyMCE.get( textarea_id ).getContent() : $( '#' + textarea_id ).val();

		if ( 'undefined' === typeof content ) {
			return '';
		}

		if ( fix_shortcodes && typeof window.tinyMCE !== 'undefined' ) {

			content = content.replace( /<p>\[/g, '[' );
			content = content.replace( /<br>\[/g, '[' );
			content = content.replace( /\]<\/p>/g, ']' );
		}

		return content.trim();
	};


	peHaaThemesPageBuilder.phtpb_set_content = function( textarea_id, content ) {

		if ( typeof window.tinyMCE !== 'undefined' && window.tinyMCE.get( textarea_id ) && ! window.tinyMCE.get( textarea_id ).isHidden() ) {
			content = switchEditors.wpautop( content );
			
			window.tinyMCE.get( textarea_id ).setContent( content, { format : 'html'  } );
			setTimeout( function() {
				if ( window.tinyMCE.get( 'content' ) ) {
					window.tinyMCE.get( 'content' ).focus();
				}
				
			}, 50);
			
		} else {
			$( '#' + textarea_id ).val( content );
		}				
	};

	peHaaThemesPageBuilder.gmapsAuthFailed = phtpb_data.gmaps_auth_failed;

	$( document ).ready( function() {
		
		if ( typeof qTranslateConfig !== 'undefined' ) {
			qTranslateConfig.qtx.removeContentHook( document.getElementById('phtpb_content_new') );
			qTranslateConfig.qtx.addLanguageSwitchAfterListener(
				function(){
					qTranslateConfig.qtx.removeContentHook( document.getElementById( 'phtpb_content_new' ) );
					peHaaThemesPageBuilder_App.remove();
					peHaaThemesPageBuilder_App.initialize();
				}
			);
		}
		var peHaaThemesPageBuilder_App,
			$builder_toggle_button = $( 'body' ).find( '#phtpb_toggle_builder-meta' ),
			isActivated = $builder_toggle_button.hasClass( 'phtpb_builder_is_used' ), 
			currentState = isActivated,
			$current_state_mb = $( '#phtpb_state-yes' ),
			$page_builder_mb = $( '#phtpb' ),
			$main_editor_wrap = $( '#phtpb_main_editor_wrap' ),
			$hidden_editor = $( '#phtpb_hidden_editor' );
			
		peHaaThemesPageBuilder.initial_content = $hidden_editor.html();	
		$hidden_editor.remove();

		if ( phtpb_data.is_always_active ) {
			isActivated = true;
		}
	
		if ( true === isActivated ) {

			peHaaThemesPageBuilder_App = new peHaaThemesPageBuilder.AppView( {
				model : peHaaThemesPageBuilder.Element,
				collection : new peHaaThemesPageBuilder.Elements(),
				start_data : phtpb_data.save_to
			});

			$page_builder_mb.addClass('phtpb_visible');
			currentState = true;
			isActivated = true;
			
		} else {
			$page_builder_mb.addClass('phtpb_hidden');
		}

		$( document ).on( 'click', '.js-phtpb_toggle_builder', function( event ) {
			
			event.preventDefault();

			var start_data = $(this).data( 'content-source' ),
				showClass = 'content' === start_data ? 'phtpb_show_content_editor' : 'phtpb_show_second_editor';

			if ( !start_data ) {
				return false;
			}

			$(this).siblings('.phtpb_table--cell').remove();
			
			if ( true === currentState ) {

				currentState = false;
				$('.phtpb_use_default, .phtpb_use_pb').toggleClass('js-hidden');
				$(this).toggleClass('button-primary');
				$main_editor_wrap.addClass( 'phtpb_hide_pd ' + showClass );

			} else {

				if ( false === currentState && false === isActivated ) {

					peHaaThemesPageBuilder_App = new peHaaThemesPageBuilder.AppView( {
						model : peHaaThemesPageBuilder.Element,
						collection : new peHaaThemesPageBuilder.Elements(),
						start_data : start_data
					} );

					$(this).addClass( 'phtpb_builder_is_used' ).removeClass( 'button-primary' );
					$('.phtpb_activate_pb').addClass('js-hidden');
					$('.phtpb_use_default').removeClass('js-hidden');
					$main_editor_wrap.addClass('phtpb_hidden phtpb_activated').removeClass('phtpb_not_activated');
					
					$current_state_mb.prop( "checked", true );

					isActivated = true;
					currentState = true;
					
				} else if ( false === currentState && true === isActivated ) {
				
					peHaaThemesPageBuilder_App.remove();
					peHaaThemesPageBuilder_App.initialize();
					
					$('.phtpb_use_default, .phtpb_use_pb').toggleClass('js-hidden');
					$(this).toggleClass('button-primary');
					$main_editor_wrap.removeClass( 'phtpb_hide_pd ' + showClass );
					currentState = true;
									
				}
			} 

			$page_builder_mb.toggleClass('phtpb_hidden').toggleClass('phtpb_visible');
				
		});

	});

} )(jQuery);

function initialize() {}

function gm_authFailure( error ) {

	peHaaThemesPageBuilder_Events.trigger( 'gmapsAuth:failed' );
	if ( phtpb_data.gmaps_key_missing ) {
		jQuery.post(
			ajaxurl,
			{
				action : phtpb_data.gmaps_auth_action,
				nonce : phtpb_data.gmaps_auth_nonce
			}
		);
	}
}

function loadScript() {

  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = phtpb_data.gmaps_url;
  document.body.appendChild(script);

}

window.onload = loadScript;