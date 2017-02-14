/**
 *PeHaa THEMES Page Builder
 * -----------
 *
 * author: PeHaa THEMES
 * email: info@pehaa.com
 *
 **/

var phtpb = phtpb || {};

jQuery( document ).ready( function ($) {

	"use strict";

	phtpb = {

		smallScreenQuery: "only screen and (max-width: 799px)", 
		palmDeviceScreen: Modernizr.mq("only screen and (max-device-width: 799px)"),
		cachedScriptGMapsPromise : '',	

		init: function () {

			var self = this;
			
			self.start();

			$( window ).bind("resize", function () {
				
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
			self.smallScreen  = Modernizr.mq( self.smallScreenQuery );
			self.setBackground( '.phtpb_section .js-pht-bg-ctnr, .js-pht-bg-ctnr--row' );
			self.addSliders();
			self.openGallery();
			self.activateWaypoints();
			self.activateTabs();
			self.activateAccordion();
			self.doCountDown();
			self.doIsotope();

			$.cachedGetScript = function() {

				if ( !self.cachedScriptGMapsPromise ) {
					self.cachedScriptGMapsPromise = $.Deferred(function( defer ) {
						$.getScript( phtpb_data.gmaps_url ).then( defer.resolve, defer.reject );
					}).promise();
				}
				return self.cachedScriptGMapsPromise;
			};

			$.cachedGetScript().done(function(){
				window.phtpb_initialize();
			});
						
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

			$('.phtpb_slicks--img').each(function() { // the containers for all your galleries
				$(this).magnificPopup({
					delegate: '.slick-slide:not(.slick-cloned) a', // the selector for gallery item
					type: 'image',
					gallery: {
						enabled:true
					},
					removalDelay: 500, //delay removal by X to allow out-animation
					callbacks: {
						beforeOpen: function() {
							this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
							this.st.mainClass = 'mfp-zoom-in';
						},
					},
					closeOnContentClick: true,
					midClick: true
				});
			});

			$( '.js-pht-lightboxgallery, .phtpb_image, .js-showcase, .pht-gallery' ).each( function() {
				if ( $( this ).parents( '.js-pht-lightboxgallery' ).length ) {
					return;
				}
				$( this ).magnificPopup( {
					delegate: '.js-pht-magnific_popup',
					type: 'image',
					gallery: {
						enabled:true
					},
					removalDelay: 500, //delay removal by X to allow out-animation
					callbacks: {
						beforeOpen: function() {
							this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
							this.st.mainClass = 'mfp-zoom-in';
						},
					},
					closeOnContentClick: true,
					midClick: true
				});
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
				}); 
			});			
		},

		addSliders: function () {

			var self = this;

			$( '.phtpb_slicks--img' ).each( function( index, element ) {

				var autoplay = true === $( element ).data( 'auto' ),
					dots = true === $( element ).data( 'dots' ),
					prevArrow = false === $( element ).data( 'arrows' ) ? false : '<div data-role="none" class="phtpb_slicks--img__btn phtpb_slicks__btn phtpb_slicks__btn--prev pht-pointer" aria-label="previous"></div>',
					nextArrow = false === $( element ).data( 'arrows' ) ? false : '<div data-role="none" class="phtpb_slicks--img__btn phtpb_slicks__btn phtpb_slicks__btn--next pht-pointer" aria-label="next"></div>';
				
				$( element ).slick( {
					autoplay: autoplay,
					dots: dots,
					prevArrow: prevArrow,
					nextArrow: nextArrow,
					infinite: true,
					autoplaySpeed : $( element ).data( 'autoplayspeed' ) ? $( element ).data( 'autoplayspeed' ) : 3000,
					speed : $( element ).data( 'speed' ) ? $( element ).data( 'speed' ) : 300,
					slidesToShow:  $( element ).data( 'slidestoshow' ),
					slidesToScroll:  $( element ).data( 'slidestoscroll' ) ? $( element ).data( 'slidestoscroll' ) : 1,
					centerMode: 'variable' === $( element ).data( 'variablewidth' ),
					variableWidth: 'fixed' !== $( element ).data( 'variablewidth' ),
					centerPadding : 0,
					lazyLoad: 'ondemand',
					responsive: [
						{
							breakpoint: 500,
							settings: {
								slidesToShow: 1,
								slidesToScroll:1,
								variableWidth: false,
								centerMode: false,
								adaptiveHeight: false,
							}
						}
					]
				});
			});

			$( '.phtpb_slicks--c' ).each( function( index, element ) {
				var autoplay = true === $( element ).data( 'auto' ),
					fade = true === $( element ).data( 'fade' ),
					dots = true === $( element ).data( 'dots' ),
					prevArrow = false === $( element ).data( 'arrows' ) ? false : '<button type="button" data-role="none" class="phtpb_slicks--c__btn pht-gamma phtpb_slicks__btn phtpb_slicks__btn--prev pht-pointer " aria-label="previous"></button>',
					nextArrow = false === $( element ).data( 'arrows' ) ? false : '<button type="button" data-role="none" class="phtpb_slicks--c__btn pht-gamma phtpb_slicks__btn phtpb_slicks__btn--next pht-pointer" aria-label="next"></button>';
				
				$( element ).slick( {
					prevArrow: prevArrow,
					nextArrow: nextArrow,
					autoplay: autoplay,
					dots: dots,
					fade : fade,
					autoplaySpeed : $( element ).data( 'autoplayspeed' ) ? $( element ).data( 'autoplayspeed' ) : 3000,
					speed : $( element ).data( 'speed' ) ? $( element ).data( 'speed' ) : 500
				}).slideDown(600);
			});

			$('.phtpb_flexslider').each( function( index, element )  {

				var $elem = $( element ); 

				$elem.imagesLoaded( function(){
					$elem.flexslider({
						namespace: "pht-flex-",
						animation: $elem.data('anim'),
						slideshow: $elem.data('slideshow'),
						slideshowSpeed: $elem.data('slideshowspeed') ? $elem.data('slideshowspeed') : 7000,
						animationSpeed: $elem.data('animationspeed') ? $elem.data('animationspeed') : 600,
						initDelay: $elem.data('initdelay') ? $elem.data('initdelay') : 0,
						randomize : $elem.data('randomize') ? true : false,
						fadeFirstSlide : typeof $elem.data('fadefirstslide') != 'undefined' && !$elem.data('fadefirstslide') ? false : true,
						controlNav: false,
						directionNav : typeof $elem.data('directionnav') != 'undefined' && !$elem.data('directionnav') ? false : true,
						smoothHeight:false,
						useCSS: 'fade' === $elem.data('anim'),
						prevText: "",
						nextText: "",
						animationLoop: 'fade' === $elem.data('anim') || $elem.data('slideshow'),
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

		doCountDown: function () {

			var self = this;

			var count = function ( display ) {
			
				setTimeout( function () {
					
					requestAnimationFrame( function(){ count( display ); } );
					
					var now = new Date();

					$( '.js-phtpb_timer--' + display ).each( function( index, element ) {
						var secondsDiff = Math.floor( ( $(element).data('counter-date')*1000 - now.getTime() ) / 1000),
						time = [],
						items = [ 'days', 'hours', 'mins', 'secs' ],
						quit_condition = 'since' === display ? ( secondsDiff > 0 ) : ( secondsDiff < 0 ),
						$container = $(element).parent();

						if ( quit_condition ) {
							$( element ).removeClass( 'js-phtpb_timer--' + display );
							$container.removeClass( 'js-phtpb_countdown-activated' );
							return;
						}

						time.secs = secondsDiff;
						time.mins = Math.floor( time.secs / 60 );
						time.hours = Math.floor( time.mins / 60 );
						time.days = Math.floor( time.hours / 24 );
						time.hours %= 24;
						time.mins %= 60;
						time.secs %= 60;

						items.forEach( function( el ) {
							
							var selector = '.js-phtpb_' + el;
							$(element).find( selector ).html( Math.abs( time[ el ] ) );

						});
						if ( !$container.is( '.js-phtpb_countdown-activated' ) ) {
							$container.addClass( 'js-phtpb_countdown-activated' );
						}
					});
				}, 1000);
			};

			count( 'since' );
			count( 'until' );
		},

		doIsotope: function() {
			
			var $showcase = $( '.js-phtpb_showcase_ctnr').isotope( {
				itemSelector: 'article'
			});
			
			$showcase.isotope( 'bindResize' );
			$showcase.imagesLoaded( function() {
				
				$showcase.isotope( 'layout' );
				
			});
			$showcase.isotope( 'on', 'layoutComplete', function() {
					
				if ( $.fn.waypoint ) {
					setTimeout( function() {
						Waypoint.refreshAll();
					}, 500 );						
				}
			});

			$( document ).on( 'click', '.js-pht-showcase-filter',  function ( event ) {

				event.stopPropagation();
				if ( !$( this ).hasClass( "pht-showcase__filter--active" ) ) {
					
					var selector = $(this).attr('data-filter');
					
					$( this ).parent().next()
						.find( '.pht-fadesat' ).removeClass( 'pht-fadesat' )
						.end()
						.isotope( {
							filter: selector
						});
					$( this )
						.parent()
						.find( ".pht-showcase__filter--active" )
						.removeClass( "pht-showcase__filter--active" );
					
					$( this ).addClass( "pht-showcase__filter--active" );
				}
				return false;
			});
		}
	};

	phtpb.init();

	// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
	// http://my.opera.com/emoller/blog/2011/12/20/requestanimationframe-for-smart-er-animating 
	// requestAnimationFrame polyfill by Erik Möller
	// fixes from Paul Irish and Tino Zijdel
	(function () {
		var lastTime = 0;
		var vendors = ['ms', 'moz', 'webkit', 'o'];
		for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
			window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
			window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame'] || window[vendors[x] + 'CancelRequestAnimationFrame'];
		}

		if (!window.requestAnimationFrame) {
			window.requestAnimationFrame = function (callback, element) {
				var currTime = new Date().getTime();
				var timeToCall = Math.max(0, 16 - (currTime - lastTime));
				var id = window.setTimeout(function () {
					callback(currTime + timeToCall);
				},
			timeToCall);
			lastTime = currTime + timeToCall;
			return id;
			};
		}

		if (!window.cancelAnimationFrame) {
			window.cancelAnimationFrame = function (id) {
				clearTimeout(id);
			};
		}
	}());

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
					draggable: $map.data('alwaysdrag') && true === $map.data('alwaysdrag') ? true : !Modernizr.hiddenscroll && !self.palmDeviceScreen,

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