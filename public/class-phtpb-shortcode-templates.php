<?php
/**
 * The shortcodes templates.
 *
 * @package   PeHaa_Themes_Page_Builder
 * @author    PeHaa THEMES <info@pehaa.com>
 * @license   GPL-2.0+
 * @link       http://github.com/pehaa/pht-page-builder
 * @copyright 2015 PeHaa Themes
 */

include 'class-phtpb-resize-image.php';
include 'class-phtpb-tiled-gallery.php';

/**
 * The shortcodes templates.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author PeHaa THEMES <info@pehaa.com>
 */
class PeHaa_Themes_Page_Builder_Shortcode_Template {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	protected $name;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize.
	 *
	 * @since     1.0.0
	 */
	public function __construct( $name ) {

		$this->name = $name;
		$this->content_width = PeHaa_Themes_Page_Builder_Public::$content_width;
	}

	public function getTemplate( $name, $atts, $content = null ) {

		$this->atts = $atts;
		$this->content = $content;

		$this->common_attributes();

		if ( method_exists( $this, $name ) ) {
			return apply_filters( $name, $this->$name(), $atts, $content );
		} else {
			return apply_filters( $name, do_shortcode( $content ), $atts, $content );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {


		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	protected function phtpb_section() {

		$output = '';

		$section_class = 'phtpb_section pht-parent';

		$img_to_be_loaded = $this->img_to_be_loaded( $this->phtpb_id, 'force' === $this->phtpb_type );
	
		if ( $img_to_be_loaded ) {
				
			$style_bg_size = '';
			if ( $this->r_w || $this->r_h ) {
				
				$bg_size = $this->r_w ? $this->r_w . 'px' : 'auto';
				$bg_size .= $this->r_h ? ' ' . $this->r_w . 'px' : '';
				
				$style_bg_size = "background-size: $bg_size;";
			}
			$opacity = isset( $this->atts['opacity'] ) && ''!== trim( $this->atts['opacity'] ) ? 'opacity:' . esc_attr( $this->atts['opacity'] ) . ';' : '';
			$style = $style_bg_size || $opacity ? "style='$style_bg_size $opacity'" : '';
			$palm_class = 'force' === $this->phtpb_type ? 'js-force-palm' : 'hidden--palm';
			$section_class .= 'force' === $this->phtpb_type ? ' phtpb_section--bg-force-palm' : ' phtpb_section--bg-hidden-palm';			

			$output .= "<div class='js-pht-bg-ctnr pht-bg-ctnr $palm_class js-initial-hidden' $style>";
			$output .= $img_to_be_loaded;
			$output .= '</div><!-- .pht-bg-ctnr -->';

		}
		
		if ( $this->bg_color && !$this->phtpb_id ) {

 			$this->content= preg_replace( '/\[phtpb_gallery([^\]]+)/i', '${0} herited_color="' . esc_attr( $this->bg_color ). '"', $this->content );
 			
 		}

		$output .= do_shortcode( $this->content );

		if ( ! $output ) {
			return;
		}
		
		$layout_option = isset( $this->atts['layout_option'] ) ? esc_attr( $this->atts['layout_option'] ) : 'none';
		$section_class .= ' phtpb_section--padding-' . $layout_option;
		$section_class .= 'inherit' === $this->phtpb_type ? ' pht-inherit-colors-palm' : '';

		return $this->container( $output, $section_class, '', 'section', array(), true, false );
	}

	protected function phtpb_row() {

		$layout_class = 'pht-layout';

		if ( !$this->gutter ) {
			$layout_class .= '--flush';
		} elseif ( 'yes' !== $this->gutter && 'default' !== $this->gutter ) {
			$layout_class .= '--' . $this->gutter;
		}

		$layout_class .= $this->equals ? ' pht-layout--equals ' : ' ';
		$layout_class .= $this->module_class;

		$wrapper = $this->select_attribute( 'wrapper' );

		$output = '';

		if ( !$this->bg_color && !$this->color ) {
			if ( 'none' === $wrapper ) {
				return sprintf( '<div class="%1$s"><!-- %2$s --></div>',
					esc_attr( $layout_class ),
					do_shortcode( $this->content )
				);
			} else {
				return sprintf( '<div %1$s class="%2$s pht-parent"><div class="%3$s"><!-- %4$s --></div></div>',
					$this->module_id,
					'normal' === $wrapper ? 'pht-wrapper' : 'pht-wrapper-none pht-wrapper-' . $wrapper,
					esc_attr( $layout_class ),
					do_shortcode( $this->content )
				);
			}
		}
	
		if ( $this->bg_color ) {
		
 			$this->content= preg_replace( '/\[phtpb_gallery([^\]]+)/i', '${0} herited_color="' . esc_attr( $this->bg_color ). '"', $this->content );
 			
 		}
		
		if ( 'none' === $wrapper ) {
			$output .= sprintf( '<div class="%1$s"><!-- %2$s --></div>',
				esc_attr( $layout_class ),
				do_shortcode( $this->content )
			);
			return $this->container( $output, 'pht-parent', '', 'div', array(), true, false  );
		}
		
		$output .= sprintf( '<div %1$s class="%2$s pht-parent"><div class="%3$s"><!-- %4$s --></div></div>',
			$this->module_id,
			'normal' === $wrapper ? 'pht-wrapper' : 'pht-wrapper-none pht-wrapper-' . $wrapper,
			esc_attr( $layout_class ),
			do_shortcode( $this->content )
		);
		return $this->container( $output, 'pht-parent', '', 'div', array(), true, false  );	
	}

	protected function phtpb_row_inner() {

		$layout_class = 'pht-layout';

		if ( !$this->gutter ) {
			$layout_class .= '--flush';
		} elseif ( 'yes' !== $this->gutter && 'default' !== $this->gutter ) {
			$layout_class .= '--' . $this->select_attribute( 'gutter' );
		}

		$layout_class .= $this->equals ? ' pht-layout--equals ' : ' ';
		$layout_class .= $this->module_class;

		return sprintf( '<div %1$s class="%2$s"><!-- %3$s --></div>',
			$this->module_id,
			esc_attr( $layout_class ),
			do_shortcode( $this->content )
		);
	
	}

	protected function phtpb_column() {

		$class = array(
			'4_4' => '',
			'1_2' => 'u-1-of-2-desk u-1-of-2-lap',
			'1_3' => 'u-1-of-3-desk u-1-of-3-lap',
			'1_4' => 'u-1-of-4-desk u-1-of-4-lap',
			'2_3' => 'u-2-of-3-desk u-2-of-3-lap',
			'3_4' => 'u-3-of-4-desk u-3-of-4-lap',
		);

		$margins = $this->is_checked( 'margins' );

		$layout_class = isset( $this->atts['layout'] ) && in_array( $this->atts['layout'], array_keys( $class ) ) ? $class[ $this->atts['layout'] ] : '';

		$matches = array();

		preg_match( '/phtpb_row_inner[^\]]+equals="yes"[^\"]+"/i', $this->content, $matches );
		if ( count( $matches ) ) {
			$layout_class .= ' pht-layout__item--wequals';
		}
		
		$colorbox_class = $this->module_class;
		$v_align = $this->select_attribute( 'valign' );
		$colorbox_class .= $v_align ? ' pht-box--valign-' . $v_align . ' ' : ' pht-box--valign-top ';
		$box_class = '';
		$animation = $this->select_attribute( 'animation' );
		$colorbox_class .= 'none' === $animation ? '' : apply_filters( 'phtpb_animation_class', "js-pht-waypoint pht-waypoint pht-$animation", $animation );

		$colorbox_style = $this->bg_color ? 'background:' . esc_attr( $this->bg_color ) .';' : '';
		$colorbox_style .= $this->color ? 'color:' . esc_attr( $this->color ) .';' : '';
		
		$padding = $this->select_attribute( 'padding' );

		if ( 'none' !== $padding ) {
			$box_class .= " pht-box pht-$padding";
		}

		$border_width = $this->select_attribute( 'border_width' );
		$border_style = $this->select_attribute( 'border_style' );

		if ( $this->bg_color || ( 'none' !== $border_style && $border_width ) ) {
			
			if ( $border_style && $border_width ) {
				$colorbox_class .= " pht-border--$border_style pht-border--$border_width";
				if ( $margins ) {
					$colorbox_class .= ' pht-border--margins';
				}
				$border_color = $this->is_checked( 'use_border_color' ) && isset( $this->atts['border_color'] ) ? esc_attr( $this->atts['border_color'] ) : false;
				if ( $border_color ) {
					if ( $this->color && $border_color !== $this->color || !$this->color && $border_color !== PeHaa_Themes_Page_Builder::$defaults['color'] ) {
						$colorbox_style .= 'border-color:' . esc_attr( $border_color ) .';';
					}
				}
			}

			$colorbox_class .= ' pht-rounded--' . $this->select_attribute( 'border_radius' );
			
		}		
		if ( '' === trim( $this->content ) ) {
			return sprintf( '--><div %1$s class="%2$s"></div><!--',
				$this->module_id,
				'pht-layout__item ' . $layout_class
			);
		}
		if ( $margins ) {
			return sprintf( '--><div %1$s class="%2$s"><div class="pht-colorbox %4$s" style="%5$s"><div class="%6$s">%3$s</div></div></div><!--',
				$this->module_id,
				'pht-layout__item ' . $layout_class,
				do_shortcode( $this->content ),
				$colorbox_class,
				$colorbox_style,
				$box_class
			);
		}

		
		return sprintf( '--><div %1$s class="%2$s"><div class="pht-colorbox %4$s" style="%5$s">%3$s</div></div><!--',
			$this->module_id,
			'pht-layout__item ' . $layout_class,
			do_shortcode( $this->content ),
			"$colorbox_class $box_class",
			$colorbox_style
		);
	
	}


	protected function phtpb_column_inner() {

		return $this->phtpb_column();

	}

	protected function phtpb_text() {
		
		return $this->container( do_shortcode( wpautop( $this->content ) ), 'phtpb_item phtpb_text cf' );
	
	}


	protected function phtpb_mixed_gallery() {

		$layout_option =  $this->select_attribute( 'layout_option' );
 
		$gutter = $this->select_attribute( 'gutter' );
		
		$gutter_class = '';
		
		if ( 'none' !== $gutter ) {
			$gutter_class = ' pht-mctnr--gut' . $gutter;
		}

		$this->content= preg_replace( '/\[phtpb_mixed_gallery_item([^\]]+)/i', '${0} layout_option="' . esc_attr( $layout_option ) . '" gutter="' . esc_attr( $gutter ) . '"', $this->content );

		$output = '<div class="js-showcase js-phtpb_showcase_ctnr cf ' . esc_attr( $gutter_class ) . '">';
		$output .= do_shortcode( $this->content );
		$output .= '</div>';

		return $this->container( $output, 'phtpb_item' );	

	}

	protected function phtpb_mixed_gallery_item() {

		$layout_option =  $this->select_attribute( 'layout_option' );

		$gutter = $this->select_attribute( 'gutter' );
		
		$column_count = (int) str_replace( 'c', '', $layout_option );

		$dimensions = self::dimensions( $layout_option );

		$article_layout_class = 'u-1-of-'  . $column_count . '-desk u-1-of-'  . $column_count . '-lap';

		if ( 1 !== $column_count ) {
			$article_layout_class .= ' u-1-of-2';
		} else {
			$article_layout_class .= ' u-1-of-1';
		}

		if ( 'none' !== $gutter ) {
			$article_layout_class .= ' pht-mctnr--gut' . $gutter .'__item';
		}

		$skip_array = apply_filters( 'phtpb_dont_resize_in_gallery', array( 'image/gif' ), $layout_option, $this->atts['module_id'] );		

		$output = '<figure class="pht-fig pht-fig--filter">';
		$output .= self::get_att_img(   $this->phtpb_id, array( $dimensions['width'], $dimensions['height'] ), false, array( 'class' => 'pht-img--fill', 'width' => $dimensions['width'] ), $skip_array );
		$link_type = $this->select_attribute( 'link_type' );

			if ( 'url' === $link_type && $this->link ) {
				$output .= 	'<div class="pht-fig__link--ctnr">';
				$output .= "<a href='$this->link' class='pht-fig__link pht-fig__link--main pht-fig__link--hoverdir pht-fig__link--main' $this->target></a>";
				$output .= '</div>';
				$output .= '</a>';
			} elseif ( 'lightbox' === $link_type ) {

				$output .= 	'<div class="pht-fig__link--ctnr">';
				$output .= sprintf( '<a class="pht-fig__link pht-fig__link--main mfp-image js-pht-magnific_popup pht-fig__link--hoverdir pht-fig__link--main pht-text-center a-a a-a--no-h" data-pht-mfp-title="%1$s" href="%2$s">',
						esc_attr( $this->atts['title'] ),
						esc_url( wp_get_attachment_url( $this->phtpb_id ) )
					); 
				$lightbox_icon_class = apply_filters( 'phtpb_lightbox_icon_class', 'pht-ic-f1-arrow-expand-alt' ); 
				$output .= '<i class="pht-fig__link__string ' . esc_attr( $lightbox_icon_class ) . ' pht-gamma"></i>';
				$output .= '</a>';
				$output .= '</div>';

				
			} elseif ( 'lightbox_video' === $link_type &&  $this->link ) {
				$output .= 	'<div class="pht-fig__link--ctnr">';
				$output .= sprintf( '<a class="pht-fig__link pht-fig__link--main mfp-iframe js-pht-magnific_popup pht-fig__link--hoverdir pht-fig__link--main pht-text-center a-a a-a--no-h" data-pht-mfp-title="%1$s" href="%2$s">',
					esc_attr( $this->atts['title'] ),
					esc_url( $this->link )
				); 
				$lightbox_video_icon_class = apply_filters( 'phtpb_lightbox_video_icon_class', 'pht-ic-f1-triangle-right-alt' ); 
				$output .= '<i class="pht-fig__link__string ' . esc_attr( $lightbox_video_icon_class ) . ' pht-gamma"></i>';
				$output .= '</a>';
				$output .= '</div>';

			}
		
		$output .= '</figure>';


		return $this->container( $output, 'pht-showcase__item pht-parent pht-hider js-pht-waypoint pht-waypoint pht-fadesat ' . esc_attr( $article_layout_class ) );	

	}

	protected function phtpb_gallery() {

		$layout_option =  $this->select_attribute( 'layout_option' );

		if ( 'default' === $layout_option ) {

			$tiled_gallery = new phtpb_Tiled_Gallery();
			$output = $tiled_gallery->rectangular_talavera( $this->atts['phtpb_ids'], $this->phtpb_width, false, $this->lightbox );
			if ( $output ) {
				$css = $this->herited_color ? 'color:' . $this->herited_color : '';
				$class = 'phtpb_item pht-gallery phtpb_gallery pht-gallery--' . $this->select_attribute( 'border_width' );
				return $this->container( $output, $class, $css );
			}
			return;
		}		

		$column_count = (int) str_replace( 'c', '', $layout_option );

		$dimensions = self::dimensions( $layout_option );

		$article_layout_class = 'u-1-of-'  . $column_count . '-desk u-1-of-'  . $column_count . '-lap';

		if ( 1 !== $column_count ) {
			$article_layout_class .= ' u-1-of-2';
		} else {
			$article_layout_class .= ' u-1-of-1';
		}

		$skip_array = apply_filters( 'phtpb_dont_resize_in_gallery', array( 'image/gif' ), $layout_option, $this->atts['module_id'] ); 
		$gutter = $this->select_attribute( 'border_width' );
		$gutter_class = '';
		
		if ( 'none' !== $gutter ) {
			$gutter_class = ' pht-mctnr--gut' . $gutter;
			$article_layout_class .= ' pht-mctnr--gut' . $gutter .'__item';
		}

		$lightbox_class = $this->lightbox ? 'pht-fig--withlightbox' : 'pht-fig--nolightbox';
		
		ob_start(); ?>

		<div class="js-showcase js-phtpb_showcase_ctnr pht-white cf <?php echo esc_attr( $gutter_class ); ?>">
			<?php 
			foreach( explode( ',', $this->atts['phtpb_ids'] ) as $id ) { ?>
				<div class="pht-showcase__item pht-parent pht-hider js-pht-waypoint pht-waypoint pht-fadesat <?php echo esc_attr( $article_layout_class ); ?>">
					<figure class="pht-fig pht-fig--filter <?php echo esc_attr( $lightbox_class ); ?>">
						<?php 
						echo self::get_att_img(  $id, array( $dimensions['width'], $dimensions['height'] ), false, array( 'class' => 'pht-img--fill', 'width' => $dimensions['width'] ), $skip_array );
						$image_as_post = get_post( $id );
						if ( trim( $image_as_post->post_excerpt ) ) { 
							echo '<figcaption class="pht-gallery__caption pht-transition">' . wptexturize( $image_as_post->post_excerpt ) . '</figcaption>';
						}
						if ( $this->lightbox ) { ?>
							<div class="pht-fig__link--ctnr">
								<?php printf( '<a class="pht-fig__link js-pht-magnific_popup pht-fig__link--hoverdir pht-fig__link--main pht-text-center a-a a-a--no-h" href="%1$s" data-pht-mfp-title="%2$s">', 
									esc_url( wp_get_attachment_url( $id ) ),
									trim( $image_as_post->post_excerpt ) ? wptexturize( $image_as_post->post_excerpt ) : ''
								); ?>
									<div class="pht-fig__titles">
										<?php $lightbox_icon_class = apply_filters( 'phtpb_lightbox_icon_class', 'pht-ic-f1-arrow-expand-alt' ); ?>
										<i class="pht-fig__link__string <?php echo esc_attr( $lightbox_icon_class ); ?> pht-gamma"></i>
									</div>
								</a>
							</div>
						<?php }	?>
					</figure>
				</div>

			<?php } ?>
		</div>


		<?php $output = ob_get_contents();

		ob_end_clean();

		return $this->container( $output, 'phtpb_item' );	
	}

	protected function phtpb_contact_form7() {
		
		if ( ! $this->phtpb_id ) {
			return;
		}

		$output = do_shortcode( '[contact-form-7 id=' . $this->phtpb_id .']' );
		
		if ( $output ) {
			$class = 'phtpb_item phtpb_contact_form7';
			return $this->container(  $output, $class );
		}
		return;
		
	}

	protected function phtpb_sidebar() {

		if ( !$this->phtpb_id_string ) {
			return;
		}

		ob_start();
		dynamic_sidebar( $this->phtpb_id_string );
		$output = ob_get_clean();
		if ( $output ) {
			return $this->container( $output, 'phtpb_item pht-sidebar cf' );
		}
		return;
	
	}


	protected function phtpb_tabs() {

		// Extract the tab titles for use in the tab widget.
		preg_match_all( '/phtpb_tab[^\]]+title="([^\"]+)"/i', $this->content, $matches, PREG_OFFSET_CAPTURE );

		$tab_titles = array();

		if ( isset( $matches[1] ) ) { $tab_titles = $matches[1]; }

		if ( count( $tab_titles ) ) {
			$class = 'phtpb_tabs phtpb_item';
			$output = '<div class="phtpb_tab-inner cf">';
			$output .= '<ul class="phtpb_tabs__nav pht-hider cf">';

			foreach ( $tab_titles as $tab ) {
				$output .= '<li class="phtpb_tabs__nav__item pht-hider"><a class="pht-actionfont pht-truncate" href="#phtpb_tab-'. sanitize_title( $tab[0] ) .'">' . $tab[0] . '</a></li>';
			}

			$output .= '</ul>';
			$output .= do_shortcode( $this->content );
			$output .= '</div><!-- .phtpb_tab-inner -->';

		} else {
			$class = '';
			$output = do_shortcode( $this->content );
		}
		if ( $output ) {
			return $this->container( $output, $class );
		}
		return;
	}

	protected function phtpb_tab() {

		return '<div id="phtpb_tab-'. sanitize_title( $this->title ) .'" class="phtpb_tab pht-box cf">'. do_shortcode( wpautop( $this->content ) ) .'</div>';

	}

	protected function phtpb_toggle() {

		$output = '<div class="phtpb_ac_tab">';
		$output .= '<h4 class="js-pht-tab-header pht-tab-header pht-transition pht-parent pht-pointer">' . $this->title . '</h4>';
		$output .= '<div class="phtpb_ac_tab_inner cf">' . do_shortcode( wpautop( $this->content ) ) .'</div>';
		$output .= '</div>';

		$class = 'phtpb_accordion phtpb_item phtpb_item--module';

		return $this->container( $output, $class, '', 'div', array( 'data-collapsible' => "true", 'data-toggle-state' => $this->phtpb_type ) );

	}

	protected function phtpb_accordion() {

		$data_collapsible = $this->is_checked( 'collapsible' ) ? 'true' : 'false';
		$data_active = !$this->is_checked( 'inactive' ) ? 'on' : 'off';
		$class = 'phtpb_accordion phtpb_item phtpb_item--module';
		
		return $this->container( do_shortcode( $this->content ), $class, '', 'div', array( 'data-collapsible' => $data_collapsible, 'data-toggle-state' => $data_active ) );

	}

	protected function phtpb_ac_tab() {

		$output = '<h4 class="js-pht-tab-header pht-tab-header pht-parent">' . $this->title . '</h4>';
		$output .= '<div class="phtpb_ac_tab_inner cf">' . do_shortcode( wpautop( $this->content ) ) .'</div>';
		return $this->container( $output, 'phtpb_ac_tab' );

	}

	protected function phtpb_cslider() {

		if ( !$this->content ) {
			return;
		}
		$args_array = array();
		if ( $this->is_checked( 'auto' ) ) {
			$args_array['data-auto'] = "true";
		}
		if ( 'fade' === $this->select_attribute( 'anim' ) ) {
			$args_array['data-fade'] = "true";
		}
		if ( 'arrows' === $this->select_attribute( 'nav' ) ) {
			$args_array['data-dots'] = "false";
		} else {
			$args_array['data-dots'] = "true";
			if ( 'dots' === $this->select_attribute( 'nav' ) ) {
				$args_array['data-arrows'] = "false";
			}
		}

		$id = isset( $this->atts['module_id'] ) && '' !== trim( $this->atts['module_id'] ) ? trim( $this->atts['module_id'] ) : NULL;

		$args_array = apply_filters( 'phtpb_cslider_data_attributes', $args_array, $id );
		
		return $this->container( do_shortcode( $this->content ), 'phtpb_slicks phtpb_slicks--c phtpb_item', '', 'div', $args_array  );

	}

	protected function phtpb_cslide() {

		if ( !$this->content ) {
			return;
		}

		return $this->container( do_shortcode( wpautop( $this->content ) ), 'phtpb_slick'  );
		
	}

	protected function phtpb_slider() {

		$output = '';

		if ( $this->content ) {

			$output .= '<ul class="phtpb_slides slides">';
			$output .= do_shortcode( $this->content );
			$output .= '</ul>';

			$data_attrs = array(
				'data-slideshow' => $this->is_checked( 'auto' ) ? 'true' : 'false',
				'data-anim' => $this->select_attribute( 'anim' )
			);

			$id = isset( $this->atts['module_id'] ) && '' !== trim( $this->atts['module_id'] ) ? trim( $this->atts['module_id'] ) : NULL;

			$data_attrs = apply_filters( 'phtpb_flexslider_data_attributes', $data_attrs, $id );

			$hoption = $this->select_attribute( 'hoption', 'full' );
			return $this->container( $output, "phtpb_flexslider phtpb_flexslider--$hoption phtpb_item", '', 'div', $data_attrs );
		
		}

		return;
	}

	protected function phtpb_slide() {

		$output = '';
		
		$img_to_be_loaded = $this->img_to_be_loaded( $this->phtpb_id );
	
		if ( $img_to_be_loaded ) {
			
			$opacity = isset( $this->atts['opacity'] ) && ''!== trim( $this->atts['opacity'] ) ? 'opacity:' . esc_attr( $this->atts['opacity'] ) . ';' : '';
			$style = $opacity ? "style='$opacity'" : '';
			$output .= "<div class='js-pht-bg-ctnr js-pht-bg-ctnr-img js-pht-bg-ctnr-h pht-bg-ctnr js-force-palm js-initial-hidden' $style>";
			$output .= $img_to_be_loaded;
			$output .= '</div>';
			
		}

		$output .= '<div class="phtpb_flexslider__caption pht-parent">';
		$output .= '<div class="phtpb_flexslider__caption__inner">';
		$output .= do_shortcode( wpautop( $this->content ) );
		$output .= '</div>';
		$output .= '</div>';

		return $this->container( $output, 'phtpb_flexslider__slide pht-parent', '', 'li' );		
	}

	protected function phtpb_image() {
	
		if ( !$this->phtpb_id ) {
			return;
		}

		if ( !wp_attachment_is_image( $this->phtpb_id ) ) {
			return;
		}

		if ( $this->is_checked( 'resize' ) || $this->d_w ) {

			if ( $this->r_h && !$this->r_w ) {
				$width = 0;
			} else {
				$width = $this->r_w ? $this->r_w : ( 24 + $this->content_width )*$this->phtpb_width - 24;
			}
			
			$height = $this->r_h ? $this->r_h : 0;
			
			if ( $this->d_w ) {
				$resize_width = $width ? $this->d_w : ( $height ? 0 : $this->d_w );
				$resize_height = $width ? round( $this->d_w/$width * $height ) : $height;
			} else {
				$resize_width = $width;
				$resize_height = $height;
			}

			$size = array( intval( $resize_width ), intval( $resize_height ) );
			
		} else {
			$size = 'full';
			if ( $this->d_w ) {
				$resize_width = $this->d_w;
			}
		}

		$rounded_class = $this->is_checked( 'rounded' ) ? ' pht-rounded' : '';

		$attr = array();
		$attr['class'] = $rounded_class;

		if ( !empty( $resize_width ) && $resize_width ) {
			$attr['width'] = intval( $resize_width );
		}
		if ( !empty( $resize_height ) && $resize_height ) {
			$attr['height'] = intval( $resize_height );
		}
				
		$output = '<span class="pht-fig__inner">';
		
		$output .= self::get_att_img( $this->phtpb_id, $size, false, $attr );

		if ( isset( $this->atts['link_type'] ) ) {
			if ( 'url' === $this->atts['link_type'] && $this->link ) {
				$output .= "<a href='$this->link' class='pht-fig__link pht-fig__link--main' $this->target></a>";
			} elseif ( 'lightbox' === $this->atts['link_type'] ) {
				$full_image = wp_get_attachment_image_src( $this->phtpb_id, apply_filters( 'phtpb_lightbox_image', 'full' ) );
				$lightbox_icon_class = apply_filters( 'phtpb_lightbox_icon_class', 'pht-ic-f1-arrow-expand-alt' );
				$output .= "<a href='$full_image[0]' class='pht-fig__link--hoverdir pht-fig__link js-pht-magnific_popup pht-fig__link--main $rounded_class'><i class='pht-fig__link__string $lightbox_icon_class'></i></a>";
			}
		}
		
		$output .= '</span>';
		$h_align_class = $this->h_align ? "phtpb_image--$this->h_align" : '';

		return $this->container( $output, "phtpb_item phtpb_item--module phtpb_image pht-fig $h_align_class", '', 'figure' );
		
	}

	protected function phtpb_img_text() {

		if ( !$this->phtpb_id  || !wp_attachment_is_image( $this->phtpb_id ) ) {
			return $this->container( do_shortcode( wpautop( $this->content ) ), 'phtpb_item phtpb_text cf' );
		}

		$width = $this->r_w ? $this->r_w : 0;
		$height = $this->r_h ? $this->r_h : 0;

		if ( $this->r_h && !$this->r_w ) {
			$width = 0;
		} else {
			$width = $this->r_w ? $this->r_w : 0;
		}
		
		$height = $this->r_h ? $this->r_h : 0;

		$skip_array = array();

		if ( $width || $height ) {
			
			$skip_array = apply_filters( 'phtpb_do_not_resize_in_img_text', array( 'image/gif' ), $this->atts['module_id'] );
			if ( $this->d_w ) {
				$resize_width = $width ? $this->d_w : 0;
				$resize_height = $width ? round( $this->d_w/$width * $height ) : $height;
			} else {
				$resize_width = $width;
				$resize_height = $height;
			}
			$size = array( intval( $resize_width ), intval( $resize_height ) );

		} else {
			$size = 'full';
		}

		$img_output = '';

		if ( $this->link ) {
			$img_output .= '<a href="' . esc_url( $this->link ) . '"' . $this->target . '>';
		}
		
		$attr = array( 
			'width' => intval( $this->d_w ) ? intval( $this->d_w ) : ( $width ? intval( $width) : 48 ), 
			'class' => 'pht_mb0' 
		);
		if ( $this->is_checked( 'rounded' ) ) {
			$attr['class'] .= ' pht-rounded';
		}
		$img_output = self::get_att_img( $this->phtpb_id, $size, false, $attr, $skip_array );
		
		if ( $this->link ) {
			$img_output .= "</a>";
		}

		$output_1 = '<div class="phtpb_img_text__img">' . $img_output . '</div>';
		$output_2 = '<div class="phtpb_img_text__text phtpb_text">' . do_shortcode( wpautop( $this->content ) ) . '</div>';

		if ( 'right' === $this->select_attribute( 'h_align' ) ) {
			$output = $output_2 . $output_1;
		} else {
			$output = $output_1 . $output_2;
		}
		return $this->container( $output, 'phtpb_item phtpb_img_text phtpb_img_text--' . $this->select_attribute( 'v_align' ) . ' phtpb_img_text--' . $this->select_attribute( 'h_align' ) );


	}

	protected function phtpb_inline_images() {
		$output = do_shortcode( $this->content );
		if ( $output ) {
			return $this->container(
				$output,
				'phtpb_item phtpb_inline_images phtpb_inline_images--' . $this->h_align . ' pht-text-' . $this->h_align,
				'',
				'div',
				array(),
				false
			);
		}
		return;
	}

	protected function phtpb_inline_image() {

		if ( !$this->phtpb_id ) {
			return;
		}

		if ( !wp_attachment_is_image( $this->phtpb_id ) ) {
			return;
		}

		if ( $this->is_checked( 'resize' ) ) {
			if ( $this->r_h && !$this->r_w ) {
				$width = 0;
			} else {
				$width = $this->r_w ? $this->r_w : ( 24 + $this->content_width )*$this->phtpb_width - 24;
			}
			$height = $this->r_h ? $this->r_h : 0;
			$skip_array = apply_filters( 'phtpb_do_not_resize_in_image', array( 'image/gif' ), $this->atts['module_id'] );
			if ( $this->d_w ) {
				$resize_width = $width ? $this->d_w : 0;
				$resize_height = $width ? round( $this->d_w/$width * $height ) : $height;
			} else {
				$resize_width = $width;
				$resize_height = $height;
			}
			$size = array( intval( $resize_width ), intval( $resize_height ) );
		} else {
			$size = 'full';
		}
		$attr = array(
			'class' => 'pht-mb0'
		);
		if ( $this->is_checked( 'rounded') ) {
			$attr['class'] .= ' pht-rounded' ;
		}
		if ( $this->title ) {
			$attr['title'] = esc_attr( $this->title );
		}
		if ( !empty( $resize_width ) && intval( $resize_width ) > 0 ) {
			$attr['width'] = intval( $resize_width );
		}
		$output = self::get_att_img( $this->phtpb_id, $size, false, $attr, $skip_array );
		
		if ( $this->link ) {
			$output .= "<a href='$this->link' class='pht-fig__link--main' $this->target></a>";
		}	
	
		return $this->container( $output, "phtpb_item phtpb_item--module phtpb_inline_image", '', 'span' );

	}

	protected function base_query( $post_type, $thumbnail ) {

		if ( !$post_type ) {
			return;
		}

		$query_args = array(
			'post_type' => $post_type,
			'ignore_sticky_posts' => 1
		);

		if ( $thumbnail ) {
			$query_args['meta_key'] = '_thumbnail_id';
		}

		if ( $this->count ) {
			$query_args['posts_per_page'] = $this->count;
		}
		return $query_args;
	}

	protected function phtpb_query( $post_type, $filter, $thumbnail = true, $return_posts = true ) {

		$query_args = $this->base_query( $post_type, $thumbnail );

		if ( empty( $query_args ) ) {
			return;
		}

		$phtpb_query = isset( $this->atts['phtpb_query'] ) ? htmlspecialchars_decode( $this->atts['phtpb_query'] ) : '';

		$phtpb_query = wp_parse_args( $phtpb_query );

		$phtpb_query = $this->remove_unallowed_query_args( $phtpb_query );

		$query_args_with_phtpb_query = wp_parse_args( $phtpb_query, $query_args );

		$query_args_with_phtpb_query = apply_filters( $filter, $query_args_with_phtpb_query, $this->atts['module_id'] );

		if ( $return_posts ) {
			return get_posts( $query_args_with_phtpb_query );
		}

		return $query_args_with_phtpb_query;

	}

	protected function phtpb_gallery_query() {

		return $this->phtpb_query( $this->phtpb_type, 'phtpb_gallery_portfolio_query' );

	}


	protected function phtpb_gallery_portfolio() {

		$posts = $this->phtpb_gallery_query();

		ob_start();		
	
		if ( count( $posts ) ) :
			$post_ids = array();
			foreach ( $posts as $gallery_post ) {
				$post_ids[] = $gallery_post->ID;
			}
			$tiled_gallery = new PHTPB_Tiled_Gallery();
			echo $tiled_gallery->rectangular_talavera_of_posts( $post_ids, $this->phtpb_width, false, $this->lightbox  );

		endif;		

		wp_reset_postdata();

		$posts = ob_get_contents();

		ob_end_clean();

		$output = $posts;
		
		$css = $this->herited_color ? 'color:' . $this->herited_color : '';
			
		return $this->container( $output, 'phtpb_item pht-gallery pht-gallery--' . $this->select_attribute( 'border_width' ), $css );

	}

	protected function phtpb_buttons() {

		$output = do_shortcode( $this->content );
		if ( $output ) {
			return $this->container(
				$output,
				'phtpb_item pht-btn__container pht-btn__container-' . $this->h_align . ' pht-text-' . $this->h_align,
				'',
				'div',
				array(),
				false
			);
		}
		return;
	}

	protected function phtpb_btn() {
		
		$span_content = $this->icon_string;
		if ( $this->title ) {
			$span_content .= sprintf( '<span class="pht-btn__text">%s</span>', 
			$this->title );
		}
		if ( !$span_content ) {
			return;
		}

		$link_class = $this->module_class;
		$link_class .= $this->skip ? ' u-1-of-1-desk u-1-of-1-lap' : '';
		$link_class .= ' pht-rounded--' . $this->select_attribute( 'border_radius' );

		$style = $this->color ? "style='color:$this->color;'" : '';
		$output = "<a href='$this->link' class='pht-btn pht-btn__pb $link_class' $this->target $style> $span_content </a>";		

		return $output;
	}

	protected function phtpb_icons() {

		$output = do_shortcode( $this->content );
		
		if ( $output ) {

			return $this->container(
				$output,
				'phtpb_item pht-btn__container pht-btn__container-' . $this->h_align . ' pht-text-' . $this->h_align,
				'',
				'div',
				array(),
				false
			);
		}
		return;
		
	}


	protected function phtpb_icon() {
		
		$class = "pht-icon pht-icon--$this->phtpb_type ";
		$style = '';
		if ( 'pill' === $this->border_radius ) {
			$class .= "pht-btn--pill ";
		} elseif ( in_array( $this->border_radius, array( '2', '3', '5', '10' ) ) ) {
			$class .= "pht-rounded--$this->border_radius ";
		}
		$class .= $this->skip ? ' ' : 'pht-icon--border ';

		$class .= $this->module_class ? ' ' . $this->module_class : '';

		if ( $this->color || $this->bg_color ) {
			if ( $this->color ) {
				$style = "color:$this->color;";
			}
			if ( $this->bg_color ) {
				$style .= "background-color:$this->bg_color;";
			}
			if ( $style ) {
				$style = "style={$style}";
			}	
		}

		if ( $this->link ) {
			$output = "<a href='$this->link' class='$class' $this->target $style>$this->icon_string</a>";
		} else {
			$output = "<span class='$class pht-nohover' $style>$this->icon_string</span>";
		}

		return $output;
	}

	protected function phtpb_divider() {

		$position = $this->select_attribute( 'position' );

		$hide = 'hidden--' . $this->select_attribute( 'hide' );

		return $this->container(
			'',
			"phtpb_item pht-divider pht-divider_$this->phtpb_type pht-divider_$position $hide",
			$this->height ? "height:$this->height;" : ''
		);

	}

	protected function phtpb_countdown() {

		$time = strtotime( $this->atts['date'] );

		if ( !$time ) {
			return;
		}

		$display = $this->select_attribute( 'display' );
		$size = $this->select_attribute( 'size' );

		$output = '';

		if ( $this->title ) {
			$output = '<h2 class="phtpb_countdown__title">' . esc_html( $this->title ) .'</h2>'; 
		}
		
		$output .= "<div class='phtpb_timer js-phtpb_timer js-phtpb_timer--$display phtpb_timer--$this->h_align pht-$size phtpb_timer--$size' data-counter-date='$time'>";
		$display_units = $this->select_attribute( 'display_units' );
	
		foreach ( array( 'days', 'hours', 'mins', 'secs' ) as  $unit ) {
			$output .= $this->countdown_item( $unit, $display_units );
		}
		$output .= '</div>';
		
		return $this->container( $output, "phtpb_item phtpb_countdown pht-text-$this->h_align", '', 'div', array( 'data-display' => $this->select_attribute( 'display' ) ) );

	}

	protected function countdown_item( $item, $display_units ) {

		if ( !in_array( $item, array( 'days', 'hours', 'mins', 'secs' ) ) ) {
			return;
		}
		

		if ( 'secs' === $item && 'dhms' !== $display_units ) {
			return;
		}

		if ( 'mins' === $item && 'dh' === $display_units ) {
			return;
		}

		$output = '<div class="phtpb_timer__module">';
		$output .=  "<span class='phtpb_timer__module__value phtpb_$item js-phtpb_$item'></span>";
		$label = $item . '_label';
		$output .=  '<span class="phtpb_timer__module__unit">' . $this->atts[ $label ] . '</span>';
		$output .= '</div>';

		return $output;

	}

	protected function phtpb_timetable() {

		global $wp_locale;

		$ts = current_time( 'timestamp' );
		$date = gmdate( 'Y/m/d H:m', $ts);
		
		$check_start = $check_end = true;

		if ( isset( $this->atts['start'] ) && $this->atts['start'] ) {
			
			$check_start = $this->atts['start'] <= $date;
			
		}

		if ( isset( $this->atts['end'] ) && $this->atts['end'] ) {
			
			$check_end = $this->atts['end'] >= $date;
			
		}

		if ( !( $check_start && $check_end ) ) {
			return;
		}

		$output = '';

		if ( $this->title ) {
			$output .= '<h3 class="phtpb_timetable_title">' . esc_html( $this->title ) .'</h3>'; 
		}

		$output .= '<div class="phtpb_timetable__table">';
		
		$days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
		
		for ( $i = 0; $i <= 6; $i++ ) {

			$class = 'phtpb_timetable__day phtpb_timetable__day--' . $days[ $i ];

			$day_number = ( $i + 1 ) % 7;

			if ( $day_number === (int) gmdate( 'w', $ts ) ) {
				$class .= ' phtpb_timetable__day--today';
			}

			$wd = $wp_locale->get_weekday( $day_number );

			$class .= preg_match( "/\d/", $this->atts[$days[ $i ] . '_hours'] ) ? '' : ' phtpb_timetable__day--closed';

			$output .= sprintf( '<div class="%s"><span class="phtpb_timetable__day__label">%s</span> <span class="phtpb_timetable__day__value">%s</span></div>',
				esc_attr( $class ),
				$this->is_checked( 'abbrev_day' ) ? $wp_locale->get_weekday_abbrev( $wd ) : $wd,
				$this->atts[$days[ $i ] . '_hours'] ? esc_html( $this->atts[$days[ $i ] . '_hours'] ) : ''
			);
			 
		}
		$output .= '</div>';

		return $this->container( $output, 'phtpb_item phtpb_timetable' );

	}
	


	protected function phtpb_events_calendar() {

		if ( !class_exists( 'PeHaa_Themes_Events_Calendar' ) ) {
			return;
		}

		$calendar = PeHaa_Themes_Events_Calendar::get_instance();
		
		$output = $calendar->pht_get_calendar();
		
		return $this->container( $output, 'phtpb_events_calendar', '', 'div' );
	}


	protected function phtpb_google_map() {

		if ( ! $this->lat ) {
			$this->lat = 0;
		}
		if ( ! $this->lng ) {
			$this->lng = 0;
		}
		
		$style = '';
		if ( $this->height && '400px' !== $this->height ) {
			$style = "style='height:$this->height'";
		}
		$zoom = isset( $this->atts['zoom'] ) ? ( int ) $this->atts['zoom'] : 8;
		$styles = $this->select_attribute( 'styles' );
		$alwaysdrag = $this->is_checked( 'alwaysdrag' ) ? 'data-alwaysdrag = "true"' : '';
		$output = "<div class='phtpb_map-canvas pht-map-canvas' data-lat='$this->lat' data-lng='$this->lng' data-zoom='$zoom' data-styles='$styles' $style $alwaysdrag></div>";
		$output .= do_shortcode( $this->content );
		
		return $this->container( $output, 'phtpb_item phtpb_map-container' );
	}

	protected function phtpb_marker() {

		if ( ! $this->lat || ! $this->lng ) return;
		$data_image = '';
		if ( $this->phtpb_id  && wp_attachment_is_image( $this->phtpb_id ) ) {
			$display_image = wp_get_attachment_image_src( $this->phtpb_id, 'full' );
			if ( $display_image ) {
				$image_path = $display_image[0];
				$data_image = 'data-image_path="' . esc_url( $image_path ) . '"';
				$image_width = round( 0.5*$display_image[1]);
				$data_image .= " data-image_width='$image_width'";
				$image_height = round( 0.5*$display_image[2]);
				$data_image .= " data-image_height='$image_height'";
			}
			
			$data_image_path = 'data-image="' . esc_url( $image_path ) . '"';
			
		}		

		return "<div class='phtpb_marker' data-lat='$this->lat' data-lng='$this->lng' data-marker-title='$this->title' $data_image></div>";
	}

	protected function phtpb_img_carousel() {

		$srcs_array = explode( ',', $this->atts['phtpb_ids'] );

		if ( empty( $srcs_array ) ) {

			return;
		
		}
		
		$args_array = array();

		$woption = $this->select_attribute( 'woption' );

		$hoption = $this->select_attribute( 'hoption' );

		if ( $this->is_checked( 'auto' ) ) {
			$args_array['data-auto'] = "true";
		}
		if ( 'fade' === $this->select_attribute( 'anim' ) ) {
			$args_array['data-fade'] = "true";
		}
		if ( 'variable' !== $woption ) {
			$args_array['data-variablewidth'] = 'fixed';
			$args_array['data-slidestoscroll'] = $this->select_attribute( 'scroll' );
		} else {
			$args_array['data-variablewidth'] = 'variable';
		}
		if ( 'arrows' === $this->select_attribute( 'nav' ) ) {
			$args_array['data-dots'] = "false";
		} else {
			$args_array['data-dots'] = "true";
			if ( 'dots' === $this->select_attribute( 'nav' ) ) {
				$args_array['data-arrows'] = "false";
			}
		}
		$args_array['data-slidestoshow'] = $this->select_attribute( 'count' );		
		
		$id = isset( $this->atts['module_id'] ) && '' !== trim( $this->atts['module_id'] ) ? trim( $this->atts['module_id'] ) : NULL;

		$this->lazy_load = apply_filters( 'phtpb_img_carousel_lazyload', true, $id );

		if ( $this->lazy_load ) {
			$args_array['data-lazyload'] = 'ondemand';
		}

		$args_array = apply_filters( 'phtpb_img_carousel_data_attributes', $args_array, $id );	

		$content = '';

		$ratio = '3_2' === $woption ? 1.5 : ( '4_3' === $woption ? 1.334 : 0 );
		

		foreach ( $srcs_array as $image_id ) {
			$content .= $this-> phtpb_img_carousel_slide( $image_id, $ratio );
		}	

		$woption_class = 'variable' === $woption ? 'variable' : 'fixed';
		
		return $this->container( $content, "phtpb_slicks phtpb_slicks--img phtpb_slicks--img-$hoption phtpb_slicks--img-$woption_class phtpb_slicks--img-$woption phtpb_item", '', 'div', $args_array  );

	}

	protected function phtpb_img_carousel_slide( $image_id,  $ratio ) {

		if ( !$image_id ) {
			return;
		}

		if ( !wp_attachment_is_image( $image_id ) ) {
			return;
		}
		$width_index =  'width';
		$url_index = 'url';
		$method_name = $this->lazy_load ? 'get_att_img_lazy' : 'get_att_img';
		if (  $ratio ) {
			$display_image = self::$method_name( $image_id, array( round( $this->select_attribute( 'hoption' ) * $ratio ),  $this->select_attribute( 'hoption' ) ), false, array( 'height' => $this->select_attribute( 'hoption' ) ) );			
		} else if ( 'flexible' === $this->select_attribute( 'woption' ) ) {
			$display_image = self::$method_name( $image_id, array( $this->select_attribute( 'woption1' ), $this->select_attribute( 'hoption1' ) ) );
		} else {
			$display_image = self::$method_name( $image_id, array( 0, $this->select_attribute( 'hoption' ) ) );
		}
		
		$width = $this->select_attribute( 'woption1' ) ? $this->select_attribute( 'woption1' ) : $this->select_attribute( 'hoption' ) * $ratio;
		
		$output = '<div class="slick-slide">';
		$output .= '<figure class="pht-fig pht-white">';
		$output .= $display_image;
		if ( $this->lightbox ) {
			$full_image = wp_get_attachment_image_src( $image_id, apply_filters( 'phtpb_lightbox_image', 'full' ) );
			$output .= '<a href="' . $full_image[0] .'" class="pht-fig__link--hoverdir pht-fig__link pht-fig__link--main a-a a-a--no-h"><i class="pht-fig__link__string pht-ic-f1-arrow-expand-alt pht-gamma"></i></a>';
		}
		$output .= '</figure>';
		$output .= '</div>';

		return $output;
	
	}

	protected function phtpb_tlist() {

		$output = do_shortcode( $this->content );

		if ( $output ) {
			return $this->container( $output, 'phtpb_item phtpb_tlist', '', 'div' );
		}
		return;
	}

	protected function phtpb_tlist_item() {
		$output = '<div class="phtpb_tlist__line">';
		$output .= '<h5 class="phtpb_tlist__titles pht-mb0">' . $this->title . '</h5>';
		$subtitle = isset( $this->atts['item_subtitle'] ) && '' !== trim( $this->atts['item_subtitle'] ) ? esc_attr( trim( $this->atts['item_subtitle'] ) ) : false;
		if ( $subtitle ) {
			$output .= '<div class="phtpb_tlist__line phtpb_tlist__decription">' . $subtitle . '</div>';
		}
		$output .= '</div>';
		$output .= sprintf ( '<span class="phtpb_tlist__attribute strong">%s</span>',
			isset( $this->atts['item_attribute'] ) && '' !== trim( $this->atts['item_attribute'] ) ? esc_attr( trim( $this->atts['item_attribute'] ) ) : '' );		
		
		return $this->container( $output, 'phtpb_tlist__item', '', 'div' );
	
	}

	protected function phtpb_showcase_query( $post_type ) {
		return $this->phtpb_query( $post_type, 'phtpb_showcase_query' );
	}


	protected function phtpb_showcase() {

		if ( !( $this->phtpb_type ) ) {
			return;
		}

		$query = explode( '::', $this->phtpb_type );


		if ( 2 === count( $query ) ) {
			$post_type = $query[0];
			$taxonomy = '__' === $query[1] ? '' : $query[1];
		}	

		if ( empty( $post_type ) && is_int( (int) $this->phtpb_type ) ) {
			
			//legacy phtpb_type
			
			$showcases_array = PeHaa_Themes_Page_Builder::$showcases_array;

			if ( ! isset( $showcases_array[ $this->phtpb_type ]['item'] ) ) {
				return;
			}

			$post_type = $showcases_array[ $this->phtpb_type ]['item'];

			if ( ! isset( $showcases_array[ $this->phtpb_type ]['taxonomy'] ) ) {
				$taxonomy = '';
			} else {
				$taxonomy = $showcases_array[ $this->phtpb_type ]['taxonomy'];
			}

		}

		if ( empty( $post_type ) ) {
			return;
		}
		
		
		$taxonomy_array = $taxonomy ? array( $taxonomy ) : array();
		$taxonomy_array = apply_filters( 'phtpb_taxonomy_filter', $taxonomy_array, $this->atts['module_id'] );

		$showcase_posts = $this->phtpb_showcase_query( $post_type );

		ob_start();	
	
		if ( count ( $showcase_posts ) ) :

			if ( count( $taxonomy_array ) ) {

				$args = array(
					'order' => apply_filters( 'phtpb_taxonomy_filter_order', 'ASC', $taxonomy_array, $this->atts['module_id'] ),
					'orderby' => apply_filters( 'phtpb_taxonomy_filter_orderby', 'slug', $taxonomy_array, $this->atts['module_id'] )
				);
				
				$args = apply_filters( 'phtpb_taxonomy_filter_args', $args, $taxonomy_array, $this->atts['module_id'] );

				$terms = get_terms( $taxonomy_array, $args );

				$p_classes = apply_filters( 'phtpb_showcase_filter_container_classes', 'pht-showcase__filters pht-actionfont pht-zeta' );

				$filter_custom_classes = apply_filters( 'phtpb_showcase_filter_custom_classes', 'pht-showcase__filter' );

				printf( '<p class="%s">' , esc_attr( $p_classes ) );

				printf( '<a href="#" class="js-pht-showcase-filter pht-showcase__filter--active %s" data-filter="*">%s</a>',
					esc_attr( $filter_custom_classes ),
					isset( $this->atts['all_label'] ) && '' !== trim( $this->atts['all_label'] ) ? esc_html( trim( $this->atts['all_label'] ) ) : esc_html__( 'All', 'phtpb' ) 
				);
				foreach ( $terms as $term ) {

					printf( '<a href="#" class="js-pht-showcase-filter %s" data-filter=".%s">%s</a>',
						esc_attr( $filter_custom_classes ),
						esc_attr( $term->slug ),
						esc_html( $term->name )
					);
				} 
				echo '</p>';
			
			}		

			$layout_option =  $this->select_attribute( 'layout_option' );

			$column_count = (int) str_replace( 'c', '', $layout_option );

			$dimensions = self::dimensions( $layout_option );

			$article_layout_class = 'u-1-of-'  . $column_count . '-desk u-1-of-'  . $column_count . '-lap';

			if ( 1 !== $column_count ) {
				$article_layout_class .= ' u-1-of-2';
			} else {
				$article_layout_class .= ' u-1-of-1';
			}

			$gutter = $this->select_attribute( 'gutter' );
			$gutter_class = '';

			if ( 'none' !== $gutter ) {
				$gutter_class = ' pht-mctnr--gut' . $gutter;
				$article_layout_class .= ' pht-mctnr--gut' . $gutter .'__item';
			}

			$skip_array = apply_filters( 'phtpb_dont_resize_in_filtrable_portfolio', array(), $layout_option, $this->atts['module_id'] );
			?>
			
			<div class="js-showcase js-phtpb_showcase_ctnr pht-white cf <?php echo esc_attr( $gutter_class ); ?>">

				<?php 

				global $post; 

				foreach ( (array) $showcase_posts as $query_post ) : $post = $query_post; setup_postdata( $post );

					$terms_class = '';
					$terms = '';					 

					if ( count( $taxonomy_array ) ) {
						foreach ( $taxonomy_array as $tax ) {
							$terms_array = get_the_terms( get_the_ID(), $tax  );
							if ( $terms_array ) {
								$count = count( $terms_array );
								$i = 0;
								foreach ( $terms_array as $term ) {
									$terms_class .= $term->slug . ' ';
									$terms .= $term->name;
									$i++;
									if ( $i < $count ) {
										$terms .= ', ';
									}


								}
							}
						}

					} ?>

					<article class="pht-showcase__item pht-parent pht-hider js-pht-waypoint pht-waypoint <?php echo esc_attr( $terms_class . ' ' . $article_layout_class ); ?>">

						<?php $this->entry_image( get_the_ID(), get_post_thumbnail_id( get_the_ID() ), $dimensions['width'], $dimensions['height'], $skip_array, $this->lightbox, $terms );
						?>


					</article>

				<?php endforeach; ?>

			</div><!-- .js-phtpb_showcase_ctnr -->

		<?php endif;

		wp_reset_postdata();

		$output = ob_get_contents();

		ob_end_clean();

		return $this->container( $output, 'phtpb_item phtpb_item--showcase' );

	}

	protected function phtpb_posts_query() {

		return $this->phtpb_query( 'post', 'phtpb_posts_portfolio_query', false );

	}

	protected function phtpb_posts() {
		
		$posts = $this->phtpb_posts_query();

		ob_start();		
	
		if ( count( $posts ) ) :		

			$layout_option =  $this->select_attribute( 'layout_option' );			

			$dimensions = self::dimensions( $layout_option );

			$article_layout_class = self::layout_classes( $layout_option);

			$skip_array = apply_filters( 'phtpb_dont_resize_in_posts_grid', array(), $layout_option, $this->atts['module_id'] ); 

			$gutter_class = 'pht-mctnr--gut24 pht-mctnr--gut-deskwide-' . $this->select_attribute( 'gutter' ); ?>
			
			<div class="js-showcase js-phtpb_showcase_ctnr <?php echo esc_attr( $gutter_class ); ?> cf">

				<?php 

				global $post; 

				foreach ( (array) $posts as $query_post ) : $post = $query_post; setup_postdata( $post ); ?>

					<article class="pht-showcase__item pht-parent pht-hider js-pht-waypoint pht-waypoint pht-mctnr--gut24__item <?php echo esc_attr( $article_layout_class ); ?>">

						<?php $this->post_entry( $skip_array ); ?>

					</article>

				<?php endforeach; ?>

			</div>

		<?php endif;

		wp_reset_postdata();

		$output = ob_get_contents();

		ob_end_clean();

		return $this->container( $output, 'phtpb_item phtpb_item--posts-grid' );

	}

	protected function entry_image( $post_id, $attachment_id, $width, $height, $skip_array, $lightbox, $terms = '' ) {

		if ( post_password_required() ) {
			return;
		}
		
		printf( '<figure class="pht-fig ">' );

		echo self::get_att_img( $attachment_id, array( $width, $height), false, array( 'class' => 'pht-img--fill', 'width' => $width, 'height' => $height ), $skip_array );
		
		echo '<div class="pht-fig__link--ctnr">';	
		printf( '<a class="pht-fig__link pht-fig__link--hoverdir pht-fig__link--main pht-text-center a-a a-a--no-h" href="%1$s">',
			esc_url( get_permalink( $post_id ) )
		);
		printf( '<%1$s class="%2$s">',
			esc_attr( apply_filters( 'phtpb_showcase_title_tag', 'h4' ) ),
			esc_attr( apply_filters( 'phtpb_showcase_title_class', 'pht-fig__titles pht-epsilon pht-mb0' ) )
		);
		the_title();
		
		printf( '</%s>',
			esc_attr( apply_filters( 'phtpb_showcase_title_tag', 'h4' ) )
		);
		echo '<span class="pht-fig__subtitles pht-zeta">';
		echo esc_html( $terms );
		echo '</span>';
		echo '</a>';

		if ( $lightbox ) {
			$lightbox_icon_class = apply_filters( 'phtpb_lightbox_icon_class', 'pht-ic-f1-arrow-expand-alt' );
			$full_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			printf( '<a href="%s" class="pht-fig__link js-pht-magnific_popup pht-fig__link--secondary a-a a-a--no-h %s"></a>',
				esc_url( $full_image[0] ),
				esc_attr( $lightbox_icon_class ) );
		}
		echo '</div>';		
		echo '</figure>';
	}

	protected function post_entry( $skip_array = array() ) {

		$attachment_id = get_post_thumbnail_id( get_the_ID() );

		if ( $attachment_id && !post_password_required() ) {

			printf( '<figure class="pht-fig pht-mb">' );

			$dimensions = self::dimensions(  $this->select_attribute( 'layout_option' ) );

			echo self::get_att_img( $attachment_id,  array( $dimensions['width'], $dimensions['height'] ), false, array( 'class' => 'pht-img--fill', 'width' => $dimensions['width'], 'height' => $dimensions['height'] ), $skip_array );
			
			echo '<div class="pht-fig__link--ctnr">';	
			printf( '<a class="pht-fig__link pht-fig__link--hoverdir pht-fig__link--main pht-text-center a-a a-a--no-h" href="%1$s">',
				esc_url( get_permalink() )
			);
			echo '</a>';
			echo '</div>';		
			echo '</figure>';
		}
		echo '<div class="pht-parent">';
		echo '<a class="phtpb-entry-date--a pht-micro" href="' . esc_url( get_permalink() ) . '">';
		echo '<time class="phtpb-entry-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
		echo '</a>';
		printf( '<a class="" href="%1$s">',
			esc_url( get_permalink( get_the_ID() ) )
		);
		printf( '<%1$s class="%2$s">',
			esc_attr( apply_filters( 'phtpb_posts_grid_title_tag', 'h3' ) ),
			esc_attr( apply_filters( 'phtpb_posts_grid_title_class', 'pht-epsilon' ) )
		);
		the_title();
		printf( '</%s>',
			esc_attr( apply_filters( 'phtpb_posts_grid_title_tag', 'h3' ) )
		);
		echo '</a>';
		echo '<div class="entry-summary pht-zeta pht-entry-summary pht-mt cf">';
		the_excerpt();
		echo '</div>';
		echo '</div>';

	}

	protected function image_src( $id, $size = 'thumbnail', $filter = 'phtpb_insert_thumbnail' ) {
		
		if ( !$id ) {
			return;
		}

		if ( !wp_attachment_is_image( $id ) ) {
			return;
		}

		$img = wp_get_attachment_image_src( $id, apply_filters( $filter, $size ) );
		if ( $img ) {
			return $img[0];
		}
		return;

	}

	protected function image_src_palm( $id ) {
		
		if ( !$id ) {
			return;
		}

		if ( !wp_attachment_is_image( $id ) ) {
			return;
		}

		$resizer = PHTPB_Resize_Image::get_instance();
		$img = $resizer->phtpb_resize_image( $id, apply_filters( 'pht_palm_background_width', 800 ) );
		if ( $img ) {
			return $img['url'];
		}
		return;

	}

	public function custom_classes( $value, $atts ) {
		return $value . $this->module_class;
	}

	protected function container( $inner, $classes = '', $css = '', $itemtag = 'div', $args_array = array(), $colors_apply = true, $width_class = true  ) {

		$inner = apply_filters( $this->name . '_inner', $inner, $this->atts, $this->content );

		$classes .= $this->module_class;
		if ( $this->is_checked( 'margin_b' ) ) {
			$classes .= ' pht-mb';
		}
		if ( $width_class ) {
			if ( $this->phtpb_width > 1 ) {
			$classes .= ' phtpb_width--plus';
			}
			if ( $this->phtpb_width >=1 ) {
				$classes .= ' phtpb_width--1plus';
			}
			if ( $this->phtpb_width >=0.75 ) {
				$classes .= ' phtpb_width--075plus';
			}
			if ( $this->phtpb_width < 0.25 ) {
				$classes .= ' phtpb_width--025less';
			}
			$classes .= ' phtpb_width--value' . round( 10*$this->phtpb_width );
		}
		
		$args = '';
		$color_styles = $this->container_colors( $colors_apply );
		$style = $css || $color_styles ? "style=$color_styles" . esc_attr( $css ) : '';

		if ( count( $args_array ) ) {
			foreach ( $args_array as $args_key => $args_value ) {

				$args .= esc_attr( $args_key ) . '="' . esc_attr( $args_value ) . '" ';
			}
		}

		if ( $this->module_id || $classes || $style || $args ) {
			if ( trim( $classes ) ) {
				$attr_classes = 'class="'. trim( $classes ) .'"';
			} else {
				$attr_classes = '';
			}
			return "<{$itemtag} $this->module_id $attr_classes $style $args>$inner</{$itemtag}>";
		}
		return $inner;

	}

	public static function dimensions( $layout_option ) {

		switch ( $layout_option ) {
			case '2c':
				return array( 'width'=> 570, 'height' => 384 );
			break;
			case '3c' :
				return array( 'width' => 388, 'height' => 264 );
			break;
			case '4c' :
				return array( 'width' => 288, 'height' => 192 );
			break;
			case '5c' :
				return array( 'width' => 388, 'height' => 264 );
			break;
			case '6c' :
				return  array( 'width' => 388, 'height' => 264 );
			break;
			case '2'  :
				return array( 'width'=> 570, 'height' => 0 );
			break;
			case '3' :
				return array( 'width' => 388, 'height' => 0 );
			break;
			case '4' :
				return array( 'width' => 288, 'height' => 0 );
			break;
			case '5' :
				return array( 'width' => 388, 'height' => 0 );
			break;
			case '6' :
				return array( 'width' => 388, 'height' => 0 );
			break;
			default :
				return array( 'width' => 1140, 'height' => 0 );
			break;
		}
	}

	public static function layout_classes( $layout_option ) {

		$column_count = (int) str_replace( 'c', '', $layout_option );

		$article_layout_class = 'u-1-of-1-small u-1-of-'  . $column_count . '-desk u-1-of-'  . $column_count . '-lap';

		if ( 1 !== $column_count ) {
			$article_layout_class .= ' u-1-of-2';
		} else {
			$article_layout_class = ' u-1-of-1';
		}

		return $article_layout_class;
	}

	protected function container_colors( $apply = true ) {

		if ( ! $apply ) {
			return;
		}

		if ( $this->bg_color || $this->color ) {
			$colors_css = $this->bg_color ? 'background-color:' . $this->bg_color .';' : '';
			$colors_css .= $this->color ? 'color:' . $this->color .';' : '';
			return $colors_css;
		}
		return;

	}

 	protected function hex2rgb( $colour ) {
        if ( $colour[0] == '#' ) {
                $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
                return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
        return array( 'red' => $r, 'green' => $g, 'blue' => $b );
	}

	public static function image_alt( $id ) {

		if ( !$id ) {
			return;
		}

		if ( !wp_attachment_is_image( $id ) ) {
			return;
		}

		$alt = trim( strip_tags( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) ); 
		if ( !$alt ) {
			$attachment = get_post( $id );
			$alt = trim( strip_tags( $attachment->post_excerpt ) );
		}
		if ( !$alt ) {
			$alt = trim( strip_tags( $attachment->post_title ) );
		}

		return $alt;

	}

	public static function get_att_img( $attachment_id, $size, $icon = false, $attr = '', $skip_mime_types = array() ) {

		if ( !is_array( $size ) ) {
			
			$resized = wp_get_attachment_image_src( $attachment_id, $size );

			if ( !$resized ) {
				return;
			}
			$img = array();
			$img['1x']['url'] = $resized[0];
			$img['1x']['width'] = $resized[1];
			$img['1x']['height'] = $resized[2];
		} else {
			list( $width, $height ) = $size;
			$resizer = PHTPB_Resize_Image::get_instance();
			$img = $resizer->resize_image_srcset( $attachment_id, intval( $width ),  intval( $height ), $skip_mime_types );
		}
		
		$html = '';

		if ( isset( $img['1x']['url'] ) && isset( $img['1x']['width'] ) && isset( $img['1x']['height'] ) ) {
			$default_attr = array(
				'src'	=> $img['1x']['url'],
				'class'	=> '',
				'alt'	=> self::image_alt( $attachment_id )
			);
			if (  isset( $img['2x']['url'] ) && isset( $img['2x']['url'] ) && $img['2x']['url'] ) {
				$default_attr['srcset'] = $img['1x']['url'] .' 1x, ' . $img['2x']['url'] . ' 2x';
			}
			$attr = wp_parse_args( $attr, $default_attr );
			$attr = array_map( 'esc_attr', $attr );
			$html = '<img ';
			foreach ( $attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}
			$html .= ' />';
		}

		return $html;
	}

	public static function get_att_img_lazy( $attachment_id, $size, $icon = false, $attr = '', $skip_mime_types = array() ) {

		if ( !is_array( $size ) ) {
			
			$resized = wp_get_attachment_image_src( $attachment_id, $size );

			if ( !$resized ) {
				return;
			}
			$img = array();
			$img['1x']['url'] = $resized[0];
			$img['1x']['width'] = $resized[1];
			$img['1x']['height'] = $resized[2];
		} else {
			list( $width, $height ) = $size;
			$resizer = PHTPB_Resize_Image::get_instance();
			$img = $resizer->resize_image_srcset( $attachment_id, intval( $width ),  intval( $height ), $skip_mime_types );
		}
		
		$html = '';

		if ( isset( $img['1x']['url'] ) && isset( $img['1x']['width'] ) && isset( $img['1x']['height'] ) ) {
			$default_attr = array(
				'src'	=> '',
				'data-lazy' => $img['1x']['url'],
				'class'	=> '',
				'alt'	=> self::image_alt( $attachment_id ),
				'width' => $img['1x']['width'],
				'height' => $img['1x']['height']
			);
			if (  isset( $img['2x']['url'] ) && isset( $img['2x']['url'] ) && $img['2x']['url'] ) {
				$default_attr['srcset'] = $img['1x']['url'] .' 1x, ' . $img['2x']['url'] . ' 2x';
			}
			$attr = wp_parse_args( $attr, $default_attr );
			$attr = array_map( 'esc_attr', $attr );
			$html = '<img ';
			foreach ( $attr as $name => $value ) {
				$html .= " $name=" . '"' . $value . '"';
			}
			$html .= ' />';
		}

		return $html;
	}

	protected function is_checked( $attr ) {
		return isset( $this->atts[ $attr ] ) && 'yes' === $this->atts[ $attr ];
	}

	protected function select_attribute( $attr, $default = false ) {


		if ( !isset( PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['type'] ) || 'select' !== PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['type'] ) {
			if ( isset( $this->atts[ $attr ] ) ) {
				return $this->atts[ $attr ];
			}
			return $default;
		}
		
		if ( !isset( PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['options'] ) ) {
			return $default;
		}
		$options = array_keys( PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['options'] );

		$default = isset( PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['default'] ) ? PeHaa_Themes_Page_Builder::$phtpb_config_data[$this->name]['fields'][ $attr ]['default'] : $default;

		if ( !isset( $this->atts[ $attr ] ) || !in_array( $this->atts[ $attr ], $options ) ) {
			$this->atts[ $attr ] = $default;
		}
		return $this->atts[ $attr ];
		
	}

	protected function img_to_be_loaded( $id, $force_on_palm = true ) {

		if ( !$id ) {
			return;
		}

		if ( !wp_attachment_is_image( $id ) ) {
			return;
		}

		$img = $this->image_src( $id, 'full', 'phtpb_insert_section_bg_size' );

		if ( PeHaa_Themes_Page_Builder_Public::$is_mobile ) {

			return sprintf( '<div class="js-image-container js-pht-img-loader" data-imgurl="%1$s" %2$s style="display:none;"></div>',
				esc_url( $img ), 
				$force_on_palm ? 'data-imgurl-palm="' . esc_url( $this->image_src_palm( $id ) ) . '"' : '' 
				);

		} else {
			return sprintf( '<img class="js-image-container js-pht-img-loader" src="%1$s" data-imgurl="%2$s" alt="%3$s" style="display:none;">',
				esc_url( $img ), 
				esc_url( $img ),
				self::image_alt(  $this->phtpb_id ) 
				);
		}

	}

	protected function common_attributes() {

		$this->phtpb_width = isset( $this->atts['phtpb_width'] ) && 'NaN' !== trim( $this->atts['phtpb_width'] ) ? $this->atts['phtpb_width'] : 1;

		$this->gutter = isset( $this->atts['gutter'] ) && $this->atts['gutter'] && 'flush' !== $this->atts['gutter'] ? $this->atts['gutter'] : false;

		$this->equals = $this->is_checked( 'equals' );

		$this->phtpb_id = isset( $this->atts['phtpb_id'] ) ? (int) $this->atts['phtpb_id'] : false;

		$this->phtpb_id_string = isset( $this->atts['phtpb_id'] ) ? esc_attr( $this->atts['phtpb_id'] ) : false;

		$this->phtpb_type = isset( $this->atts['phtpb_type'] ) ? esc_attr( $this->atts['phtpb_type'] ) : '' ;

		$this->module_id = isset( $this->atts['module_id'] ) && '' !== trim( $this->atts['module_id'] ) ? 'id="' . sanitize_title( $this->atts['module_id'] ) . '"' : '';

		$this->module_class = isset( $this->atts['module_class'] ) && trim( $this->atts['module_class'] ) ? ' ' . esc_attr( $this->atts['module_class'] ) : '';

		$this->bg_color = $this->is_checked( 'use_bg_color' ) && isset( $this->atts['bg_color'] ) ? esc_attr( $this->atts['bg_color'] ) : false;

		$this->bg_opacity = isset( $this->atts['bg_opacity'] ) && '1' !==  $this->atts['bg_opacity'] ? esc_attr( $this->atts['bg_opacity'] ) : false; 

		if ( $this->bg_color && $this->bg_opacity ) {

			$rgb = $this->hex2rgb( $this->bg_color );

			$red = $rgb['red'];

			$green = $rgb['green'];

			$blue = $rgb['blue'];

			$this->bg_color = "rgba($red,$green,$blue,$this->bg_opacity)";

		}

		$this->color = $this->is_checked( 'use_color' ) && isset( $this->atts['color'] ) ? esc_attr( $this->atts['color'] ) : false;

		$this->title = isset( $this->atts['title'] ) && '' !== trim( $this->atts['title'] ) ? esc_attr( trim( $this->atts['title'] ) ) : false;

		$this->link = isset( $this->atts['link'] ) && '' !== trim( $this->atts['link'] ) ? esc_url( $this->atts['link'] ) : false;

		$target = $this->select_attribute( 'target' );

		$this->target = $target ? "target='$target'" : '';

		$this->lightbox = $this->is_checked( 'lightbox' );

		$this->h_align = $this->select_attribute( 'h_align' );

		$this->border_radius = isset( $this->atts['border_radius'] ) ? esc_attr( $this->atts['border_radius'] ) : '';

		$this->icon = isset( $this->atts['icon'] ) && '' !== trim( $this->atts['icon'] ) ? esc_attr( trim( $this->atts['icon'] ) ) : '';

		$this->icon_string = $this->icon ? '<i class="pht-btn__icon ' .  $this->icon . '"></i> ' : '';

		$this->height = isset( $this->atts['height'] ) && '' !== trim( $this->atts['height'] ) && '0' !== trim( $this->atts['height'] ) ? str_replace( 'px', '', esc_attr( $this->atts['height'] ) ).'px' : false;

		$this->lat = isset( $this->atts['lat'] ) && '' !== trim( $this->atts['lat'] ) ? esc_attr( $this->atts['lat'] ) : ( isset( $this->atts['marker_lat'] ) && '' !== trim( $this->atts['marker_lat'] ) ? esc_attr( $this->atts['marker_lat'] ) : '' );

		$this->lng = isset( $this->atts['lng'] ) && '' !== trim( $this->atts['lng'] ) ? esc_attr( $this->atts['lng'] ) : ( isset( $this->atts['marker_lng'] ) && '' !== trim( $this->atts['marker_lng'] ) ? esc_attr( $this->atts['marker_lng'] ) : '' );


		$this->r_w = isset( $this->atts['r_w'] ) && '' !== trim( $this->atts['r_w'] ) && '0' !== trim( $this->atts['r_w'] ) ? (int) str_replace( 'px', '', esc_attr( $this->atts['r_w'] ) ) : false;
		$this->r_h = isset( $this->atts['r_h'] ) && '' !== trim( $this->atts['r_h'] ) && '0' !== trim( $this->atts['r_h'] ) ? (int) str_replace( 'px', '', esc_attr( $this->atts['r_h'] ) ) : false;
		$this->d_w = isset( $this->atts['d_w'] ) && '' !== trim( $this->atts['d_w'] ) && '0' !== trim( $this->atts['d_w'] ) ? (int) str_replace( 'px', '', esc_attr( $this->atts['d_w'] ) ) : false;

		$this->count = isset( $this->atts['count'] ) ? (int) $this->atts['count'] : '';

		$this->skip = $this->is_checked( 'skip' );

		$this->herited_color = isset( $this->atts['herited_color'] ) && trim( $this->atts['herited_color'] ) ? esc_attr( $this->atts['herited_color'] ) : '';		
		
	}

	protected function remove_unallowed_query_args( $query ) {

		if ( is_array( $query ) ) {
			unset( $query['author_in'] );
			unset( $query['author__not_in'] );
			unset( $query['category__and'] );
			unset( $query['category__in'] );
			unset( $query['category__not_in'] );
			unset( $query['tag__and'] );
			unset( $query['tag__in'] );
			unset( $query['tag__not_in'] );
			unset( $query['tag_slug__and'] );
			unset( $query['tag_slug__in'] );
			unset( $query['tax_query'] );
			unset( $query['post_parent__in'] );
			unset( $query['post_parent__not_in'] );
			unset( $query['post__in'] );
			unset( $query['post__not_in'] );
			unset( $query['post_name__in'] );
			unset( $query['date_query'] );
			unset( $query['meta_query'] );
		}
		return $query;
	}

}