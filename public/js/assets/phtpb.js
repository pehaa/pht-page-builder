/**
 * Yaga WP Template
 * -----------
 *
 * author: PeHaa THEMES
 * email: info@pehaa.com
 *
 **/
jQuery(document).ready(function ($) {

	"use strict";

	var phtpb = {
		smallScreenQuery: "only screen and (max-width: 799px)", 
		palmDeviceScreen: Modernizr.mq("only screen and (max-device-width: 799px)"),		

		init: function () {

			var self = this;
			
			self.start();

			$(window).bind("resize", function () {
				
				self.resize();	
				
			});

		},

		resize : function() {

			var self = this;

			self.smallScreen  = Modernizr.mq(self.smallScreenQuery);
			self.setBackground('.js-pht-bg-ctnr');
		},
	
		start : function() {

			var self = this;
			self.smallScreen  = Modernizr.mq(self.smallScreenQuery);
			self.setBackground('.phtpb_section .js-pht-bg-ctnr');
			self.addSliders();
			self.openGallery();
			self.activateWaypoints();
			self.activateTabs();
			self.activateAccordion();
						
		},

		/************************************************************/

		/************************************************************/

		activateWaypoints : function () {

			if ( $.fn.waypoint ) {
				$( '.js-pht-waypoint' ).waypoint( {
					offset: '100%',
					handler: function() {
						$( this.element ).addClass( 'pht-animated' );
					}
				} );
			}

		},

		activateTabs : function () {
			$('.phtpb_tabs').tabs();
		},

		activateAccordion : function () {

			$('.phtpb_accordion').each( function() {
				var collapsible = $( this ).data('collapsible'),
					active = 'off' === $( this ).data('toggle-state') ? false : 0;
				$(this).accordion({
					header: ".js-pht-tab-header",
					collapsible: collapsible,
					active: active,
					heightStyle: "content",
					icons: { "header": "pht-ic-f1-arrow-expand", "activeHeader": "pht-ic-f1-arrow-condense" }
				});
			});

		},
		
		openGallery: function () {

			$('.js-pht-magnific_popup').magnificPopup( {
				type: 'image',
				gallery:{
					enabled:true
				}
			});
		},
		
		setBackground: function( selector ) {
			
			var self = this;

			$( selector ).each( function( index, elem ) {
				if ('loaded' === $( elem ).data( 'bgimage' ) ) { 
					return; 
				}
				if ( self.smallScreen && !$(elem).is('.js-force-palm') ) { 
					return; 
				}
				var $loader = $( elem ).find( '.js-pht-img-loader' ), 
					src = self.palmDeviceScreen ? 
					( $loader.data('imgurl-palm') ?
						$loader.data('imgurl-palm') :
						$loader.data('imgurl') ) :
					$loader.data('imgurl');

				if ( !src )  {
					return;
				}
				if ( !$loader.is( 'img' ) ) {
					$loader.html( '<img src="' + src + '">' );
				} 
				$( elem ).imagesLoaded( function() {} )
				.always( function( instance ) { $loader.remove(); } )
				.done( function( instance ){						
					$( elem ).css( 'background-image', 'url('+src+')').removeClass( 'js-initial-hidden' );
					$( elem ).data( 'bgimage', 'loaded' );
				} ); 
			} );
			
		},

		addSliders: function () {

			var self = this;

			$( '.phtpb_slicks' ).each( function( index, element ) {
				var autoplay = true === $( element ).data( 'auto' ),
					fade = true === $( element ).data( 'fade' );
				
				$( element ).slick( {
					prevArrow: '<button type="button" data-role="none" class="pht-gamma phtpb_slicks__btn phtpb_slicks__btn--prev pht-pointer " aria-label="previous"></button>',
					nextArrow: '<button type="button" data-role="none" class="pht-gamma phtpb_slicks__btn phtpb_slicks__btn--next pht-pointer" aria-label="next"></button>',
					autoplay: autoplay,
					dots: false,
					fade : fade
				});
			});

			$('.phtpb_flexslider').each( function( index, element )  {

				var $elem = $( element ); 

				$elem.imagesLoaded( function(){
					$elem.flexslider({
						namespace: "pht-flex-",
						animation: $elem.data('anim'),
						slideshow: $elem.data('slideshow'),
						controlNav: false,
						smoothHeight:false,
						useCSS: 'fade' === $elem.data('anim'),
						prevText: "",
						nextText: "",
						animationLoop: 'fade' === $elem.data('anim'),
						start: function( slider ) {
							
							setTimeout( function() {
								$( slider )
								.addClass( 'phtpb_flexslider--loaded' )
								.find( '.pht-flex-active-slide .phtpb_flexslider__caption')
								.addClass( 'phtpb_flexslider__caption--active' );
							},1000);

						},
						before: function( slider ) {
														
							$( slider )
								.find( '.pht-flex-active-slide .phtpb_flexslider__caption')
								.removeClass('phtpb_flexslider__caption--active');
						},
						after: function( slider ) {
							
							
							$( slider )
								.find( '.pht-flex-active-slide .phtpb_flexslider__caption')
								.addClass('phtpb_flexslider__caption--active');
						},
					});
				});
			});
		},

	};

	phtpb.init();

	$( window ).load(function() {

		function loadScript() {
			if ( !$('.phtpb_map-canvas').length ) return;
			var script = document.createElement('script');
			script.type = 'text/javascript';
			script.src = phtpb_data.gmaps_url;
			document.body.appendChild(script);
		}

		loadScript();

	});

	window.phtpb_initialize = function() {

		var map = {},
			myOptions = {},
			styleSettings = {};

		styleSettings.darkgreys = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}];

		styleSettings.ultralight = [{"featureType":"water","elementType":"geometry","stylers":[{"color":"#e9e9e9"},{"lightness":17}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffffff"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#ffffff"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#ffffff"},{"lightness":16}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#f5f5f5"},{"lightness":21}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#dedede"},{"lightness":21}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#ffffff"},{"lightness":16}]},{"elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#333333"},{"lightness":40}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#f2f2f2"},{"lightness":19}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#fefefe"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#fefefe"},{"lightness":17},{"weight":1.2}]}];

		styleSettings.desaturate = [{"stylers": [{ "saturation": -100 }]}];

		styleSettings.beigeblue = [{"featureType": "administrative","elementType": "geometry.fill","stylers": [{"hue": "#ff0000"}]},{"featureType": "administrative","elementType": "labels.text.fill","stylers": [{"color": "#444444"}]},{"featureType": "landscape","elementType": "all","stylers": [{"color": "#f2f2f2"}]},{"featureType": "poi","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "road","elementType": "all","stylers": [{"saturation": -100},{"lightness": 45}]},{"featureType": "road.highway","elementType": "all","stylers": [{"visibility": "simplified"}]},{"featureType": "road.arterial","elementType": "labels.icon","stylers": [{"visibility": "off"}]},{"featureType": "transit","elementType": "all","stylers": [{"visibility": "off"}]},{"featureType": "water","elementType": "all","stylers": [{"color": "#bbd0d8"},{"visibility": "on"}]}];

		$( '.phtpb_map-container' ).each( function( index, element )  {

			var $map = $( element ).find( '.phtpb_map-canvas' ), 
				$map_container = $( this ), 
				i = index;

			if ( $map.length ) {
				myOptions[i] = {
					zoom: $map.data( 'zoom' ),
					center: new google.maps.LatLng( $map.data( 'lat' ), $map.data( 'lng' ) ),
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					scrollwheel: false,
					draggable: !Modernizr.hiddenscroll,
				};
				if ( 'default' !== $map.data( 'styles' ) ) {
					myOptions[i].styles = styleSettings[ $map.data( 'styles' )];
				}
				map[i] = new google.maps.Map( $map[0], myOptions[i]);

				$map_container.find('.phtpb_marker').each( function( index, element ) {
				
					var markers = {},
						lat = $(element).data('lat'), 
						lng = $(element).data('lng'),
						marker_title = $(element).data('marker-title'),
						position = new google.maps.LatLng( parseFloat( lat ) , parseFloat( lng ) ),
						markerIcon = false,
						args = {
							map: map[i],
							position: position,
							title: marker_title,
						};

					if ( $(element).data( 'image_path' ) ) {
						markerIcon = new google.maps.MarkerImage( $(element).data( 'image_path' ), null, null, null, new google.maps.Size( $(element).data( 'image_width' ) , $(element).data( 'image_height' ) ) );
						if ( markerIcon ) {
							args.icon = markerIcon;
						}
					}

					markers[index] = new google.maps.Marker( args );
				});
			}

		});

	};

});