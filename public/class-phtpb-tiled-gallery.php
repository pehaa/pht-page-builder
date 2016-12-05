<?php

/**
 * The jetpack-like tiled gallery. Reuses some functionalities from https://wordpress.org/plugins/jetpack/
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 */

/**
 * The jetpack-like tiled gallery. Reuses some functionalities from https://wordpress.org/plugins/jetpack/
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author PeHaa THEMES <info@pehaa.com>
 */ 

class PHTPB_Constrained_Array_Rounding {
	
	public static function get_rounded_constrained_array( $bound_array, $sum = false ) {
		
		$keys        = array_keys( $bound_array );
		$bound_array = array_values( $bound_array );

		$bound_array_int = self::get_int_floor_array( $bound_array );
		
		$lower_sum = array_sum( wp_list_pluck( $bound_array_int, 'floor' ) );
		if ( ! $sum || ( $sum < $lower_sum ) ) {
			$sum = array_sum( $bound_array );
		}
		$diff_sum = $sum - $lower_sum;
		
		self::adjust_constrained_array( $bound_array_int, $diff_sum );

		$bound_array_fin = wp_list_pluck( $bound_array_int, 'floor' );
		return array_combine( $keys, $bound_array_fin );
	}

	private static function get_int_floor_array( $bound_array ) {
		$bound_array_int_floor = array();
		foreach ( $bound_array as $i => $value ){
			$bound_array_int_floor[ $i ] = array(
				'floor'    => (int) floor( $value ),
				'fraction' => $value - floor( $value ),
				'index'    => $i,
			);
		}

		return $bound_array_int_floor;
	}

	private static function adjust_constrained_array( &$bound_array_int, $adjustment ) {
		usort( $bound_array_int, array( 'self', 'cmp_desc_fraction' ) );

		$start = 0;
		$end = $adjustment - 1;
		$length = count( $bound_array_int );

		for ( $i = $start; $i <= $end; $i++ ) {
			$bound_array_int[ $i % $length ]['floor']++;
		}

		usort( $bound_array_int, array( 'self', 'cmp_asc_index' ) );
	}

	private static function cmp_desc_fraction( $a, $b ) {
		if ( $a['fraction'] == $b['fraction'] )
			return 0;
		return $a['fraction'] > $b['fraction'] ? -1 : 1;
	}

	private static function cmp_asc_index( $a, $b ) {
		if ( $a['index'] == $b['index'] )
			return 0;
		return $a['index'] < $b['index'] ? -1 : 1;
	}
}

class PHTPB_Tiled_Gallery {

	public function __construct() {
		
		$this->resizer = PHTPB_Resize_Image::get_instance();

	}

	public function get_attachments() {
		extract( $this->atts );

		if ( !empty( $include ) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( !empty( $exclude ) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		} else {
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		}
		return $attachments;
	}

	public function get_attachment_link( $attachment_id, $orig_file ) {
		if ( isset( $this->atts['link'] ) && $this->atts['link'] == 'file' )
			return $orig_file;
		else
			return get_attachment_link( $attachment_id );
	}

	public function pht_talavera( $srcs, $width = 1 ) {

		if ( is_array( $srcs ) ) {
			$srcs_array = $srcs;
		} else {
			$srcs_array = explode( ',', $srcs );
		}

		$attachments = array();
		if ( is_array( $srcs_array ) ) {
			
			foreach ( $srcs_array as $image_attachment_id ) {
				$attachments[] = get_post( $image_attachment_id );
			}
		}
		$grouper = new PHTPB_Tiled_Gallery_Grouper( $attachments, $width );

		PHTPB_Tiled_Gallery_Shape::reset_last_shape();

		return $grouper;

	}

	public function rectangular_talavera_of_posts( $post_ids, $width = 1, $container = true, $fancybox = true  ) {
		$srcs = array();
		foreach( $post_ids as $post_id ) {
			if ( has_post_thumbnail( $post_id ) )
				$srcs[] = get_post_thumbnail_id( $post_id ); 
		}
		return $this->rectangular_talavera( $srcs, $width, $container, $fancybox, $post_ids );
	}

	public function rectangular_talavera( $srcs, $width = 1, $container = true, $fancybox = true, $post_ids = array() ) {

		$linked_to_posts = count( $post_ids );
		$grouper = $this->pht_talavera( $srcs, $width );
		$skip_resize_array = apply_filters( 'phtpb_do_not_resize_in_gallery', array( 'image/gif' ) );

		$output = '';
		$i = 0;
		if ( $container ) {
			$output = '<div class="pht-gallery">';
		}		
			$row_count = 0;
		foreach ( $grouper->grouped_images as $row ) {
			$row_count++;
			$row_count_class = ( 1 < $row_count ) ? 'pht-gallery__row--not1st' : '';
			$output .= '<div class="pht-gallery__row" style="' . esc_attr( 'width:100%; padding-bottom: ' . ( $row->height)/($row->width)*100 . '%;' ) . '"><div class="pht-gallery__group-container">';
			$group_count = 0;
			foreach( $row->groups as $group ) {
				$group_count++;
				$count = count( $group->images );
				$output .= '<div class="pht-gallery__group images-' . esc_attr( $count ) . '" style="' . esc_attr( 'width: ' . ($group->width)/($row->width)*100 . '%; padding-bottom: ' . ( $group->height)/($group->width)*100 . '%;' ) . '">';
				$item_count = 0;
				foreach ( $group->images as $image ) {
					$item_count++;
					$item_count_class = ( 1 < $item_count || 1 < $row_count ) ? 'pht-gallery__item--not1stt' : '';
					$link_count_class = ( 1 < $item_count || 1 < $row_count ) ? ' pht-gallery__link--not1stt' : '';
					$item_count_class .= ( 1 < $group_count ) ? ' pht-gallery__item--not1stl' : '';
					$link_count_class .= ( 1 < $group_count ) ? ' pht-gallery__link--not1stl' : '';
					$size = 'large';
					if ( $image->width < 150 ) {
						$size = 'tiny';
					}
					if ( $image->width < 250 ) {
						$size = 'small';
					}
					

					$image_title = $image->post_title;
					$orig_file = wp_get_attachment_url( $image->ID );
					$link = $this->get_attachment_link( $image->ID, $orig_file );

					$img_src = $this->resizer->resize_image_srcset( $image->ID , $image->width, $image->height, $skip_resize_array );

					if ( isset( $img_src['2x'] ) && isset( $img_src['2x']['url'] ) && $img_src['2x']['url'] ) {
						$image_html = sprintf( '<img %1$s src="%2$s" srcset="%2$s 1x, %3$s 2x" alt="%4$s" title="%5$s"/>',
							image_hwstring( $image->width, $image->height ),
							esc_url( $img_src['1x']['url'] ),
							esc_url( $img_src['2x']['url'] ),
							esc_attr( PeHaa_Themes_Page_Builder_Shortcode_Template::image_alt( $image->ID ) ),
							esc_attr( $image_title )
						);
					} else {
						$image_html =  sprintf( '<img %1$s src="%2$s" class="pht-img--fill" alt="%3$s" title="%4$s"/>',
							image_hwstring( $image->width, $image->height ),
							esc_url( $img_src['1x']['url'] ),
							esc_attr( PeHaa_Themes_Page_Builder_Shortcode_Template::image_alt( $image->ID ) ),
							esc_attr( $image_title )
						);
					}

					$original_image = wp_get_attachment_image_src( $image->ID, 'full' );
					$original_image_url = $original_image[0];

					$figure_class = 'pht-gallery__item pht-gallery__item-' . esc_attr( $size ) . ' ' . $item_count_class;
					$output .= "<figure class='pht-fig $figure_class js-pht-waypoint pht-waypoint pht-fadesat'>";

					$output .= $image_html;
					$link_class = $link_count_class . ' pht-fig__link--main pht-fig__link--hoverdir';
					if ( $linked_to_posts ) {
						$output .= "<a href='" . esc_url( get_permalink( $post_ids[ $i ] ) ) . "' class='pht-fig__link pht-fig__link--main pht-fig__link--main $link_class'></a>";
						$output .= '<figcaption class="pht-gallery__caption pht-transition">' . esc_html( get_the_title( $post_ids[ $i ] ) ) . '</figcaption>';
						$link_class = 'pht-fig__link--secondary';
						$i++;
					} else {
						if ( trim( $image->post_excerpt ) )
						$output .= '<figcaption class="pht-gallery__caption pht-transition">' . wptexturize( $image->post_excerpt ) . '</figcaption>';

					}
					if ( $fancybox ) { 	

						$output .= "<a href='$orig_file' class='pht-fig__link js-pht-magnific_popup $link_class $link_count_class a-a a-a--no-h'><i class='pht-fig__link__string pht-ic-f1-arrow-expand-alt'></i></a>";
					}
					$output .= '</figure>';
				}
				$output .= '</div>' . "\n";
			}
			$output .= '</div></div>' . "\n";
		}
		if ( $container ) $output .= '</div><!-- .gallery -->' . "\n";
		return $output;
	}


	public static function get_content_width( $width = 1 ) {
		
		$tiled_gallery_content_width = max( 480, PeHaa_Themes_Page_Builder_Public::$content_width * $width );

		if ( ! $tiled_gallery_content_width )
			$tiled_gallery_content_width = PeHaa_Themes_Page_Builder_Public::$content_width;

		return $tiled_gallery_content_width;
	}
	
}

class PHTPB_Tiled_Gallery_Shape {
	static $shapes_used = array();

	public function __construct( $images ) {
		$this->images = $images;
		$this->images_left = count( $images );
	}

	public function sum_ratios( $number_of_images = 3 ) {
		return array_sum( array_slice( wp_list_pluck( $this->images, 'ratio' ), 0, $number_of_images ) );
	}

	public function next_images_are_symmetric() {
		return $this->images_left > 2 && $this->images[0]->ratio == $this->images[2]->ratio;
	}

	public function is_not_as_previous( $n = 1 ) {
		return ! in_array( get_class( $this ), array_slice( self::$shapes_used, -$n ) );		
	}

	public function is_wide_theme() {
		global $content_width;
		return $content_width > 1000;
	}

	public static function set_last_shape( $last_shape ) {
		self::$shapes_used[] = $last_shape;
	}

	public static function reset_last_shape() {
		self::$shapes_used = array();
	}
}

class PHTPB_Tiled_Gallery_Three extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1 );

	public function is_possible() {
		$ratio = $this->sum_ratios( 3 );
		return $this->images_left > 2 && $this->is_not_as_previous() &&
			( ( $ratio < 2.5 ) || ( $ratio < 5 && $this->next_images_are_symmetric() ) || $this->is_wide_theme() );
	}
}

class PHTPB_Tiled_Gallery_Four extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1, 1 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->sum_ratios( 4 ) < 3.5 &&
			( $this->images_left == 4 || ( $this->images_left != 8 && $this->images_left > 5 ) );
	}
}

class PHTPB_Tiled_Gallery_Five extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 1, 1, 1, 1 );

	public function is_possible() {
		return $this->is_wide_theme() && $this->is_not_as_previous() && $this->sum_ratios( 5 ) < 5 &&
			( $this->images_left == 5 || ( $this->images_left != 10 && $this->images_left > 6 ) );
	}
}

class PHTPB_Tiled_Gallery_Two_One extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 2, 1 );

	public function is_possible() {
		return $this->is_not_as_previous( 3 ) && $this->images_left >= 3 &&
			$this->images[2]->ratio < 1.6 && $this->images[0]->ratio >=0.9 && $this->images[1]->ratio >= 0.9;
	}
}

class PHTPB_Tiled_Gallery_One_Two extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 2 );

	public function is_possible() {
		return $this->is_not_as_previous( 3 ) && $this->images_left >= 3 &&
			$this->images[0]->ratio < 1.6 && $this->images[1]->ratio >=0.9 && $this->images[2]->ratio >= 0.9;
	}
}

class PHTPB_Tiled_Gallery_One_Three extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 3 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->images_left >= 4 &&
			$this->images[0]->ratio < 0.8 && $this->images[1]->ratio >=0.9 && $this->images[2]->ratio >= 0.9 && $this->images[3]->ratio >= 0.9;
	}
}

class PHTPB_Tiled_Gallery_Symmetric_Row extends PHTPB_Tiled_Gallery_Shape {
	public $shape = array( 1, 2, 1 );

	public function is_possible() {
		return $this->is_not_as_previous() && $this->images_left >= 4 && $this->images_left != 5 &&
			$this->images[0]->ratio < 0.8 && $this->images[0]->ratio == $this->images[3]->ratio;
	}
}

class PHTPB_Tiled_Gallery_Grouper {
	public $margin = 0;
	public function __construct( $attachments, $width ) {
		$content_width = PHTPB_Tiled_Gallery::get_content_width( $width );
		$this->last_shape = '';
		$this->images = $this->get_images_with_sizes( $attachments );
		$this->grouped_images = $this->get_grouped_images();
		$this->apply_content_width( $content_width ); 
	}

	public function get_current_row_size() {
		$images_left = count( $this->images );
		if ( $images_left < 3 )
			return array_fill( 0, $images_left, 1 );

		foreach ( array( 'One_Three', 'One_Two', 'Five', 'Four', 'Three', 'Two_One', 'Symmetric_Row' ) as $shape_name ) {
			$class_name = "PHTPB_Tiled_Gallery_$shape_name";
			$shape = new $class_name( $this->images );
			if ( $shape->is_possible() ) {
				PHTPB_Tiled_Gallery_Shape::set_last_shape( $class_name );
				return $shape->shape;
			}
		}

		PHTPB_Tiled_Gallery_Shape::set_last_shape( 'Two' );
		return array( 1, 1 );
	}

	public function get_images_with_sizes( $attachments ) {
		$images_with_sizes = array();

		foreach ( $attachments as $image ) {
			if ( !isset( $image->ID ) ) continue;
			$meta  = wp_get_attachment_metadata( $image->ID );
			if ( !$meta ) continue;
			$image->width_orig = ( $meta['width'] > 0 )? $meta['width'] : 1;
			$image->height_orig = ( $meta['height'] > 0 )? $meta['height'] : 1;
			$image->ratio = $image->width_orig / $image->height_orig;
			$image->ratio = $image->ratio? $image->ratio : 1;
			$images_with_sizes[] = $image;
		}
		return $images_with_sizes;
	}

	public function read_row() {
		$vector = $this->get_current_row_size();

		$row = array();
		foreach ( $vector as $group_size ) {
			$row[] = new PHTPB_Tiled_Gallery_Group( array_splice( $this->images, 0, $group_size ) );
		}

		return $row;
	}

	public function get_grouped_images() {
		$grouped_images = array();

		while( !empty( $this->images ) ) {
			$grouped_images[] = new PHTPB_Tiled_Gallery_Row( $this->read_row() );
		}

		return $grouped_images;
	}

	public function apply_content_width( $width ) {
		foreach ( $this->grouped_images as $row ) {
			$row->width = $width;
			$row->raw_height = 1 / $row->ratio * ( $width - $this->margin * ( count( $row->groups ) - $row->weighted_ratio ) );
			$row->height = round( $row->raw_height );

			$this->calculate_group_sizes( $row );
		}
	}

	public function calculate_group_sizes( $row ) {
		
		$group_widths_array = array();
		foreach ( $row->groups as $group ) {
			$group->height = $row->height;
			$group->raw_width = ( $row->raw_height - $this->margin * count( $group->images ) ) * $group->ratio + $this->margin;
			$group_widths_array[] = $group->raw_width;
		}
		$rounded_group_widths_array = PHTPB_Constrained_Array_Rounding::get_rounded_constrained_array( $group_widths_array, $row->width );

		foreach ( $row->groups as $group ) {
			$group->width = array_shift( $rounded_group_widths_array );
			$this->calculate_image_sizes( $group );
		}
	}

	public function calculate_image_sizes( $group ) {
		
		$image_heights_array = array();
		foreach ( $group->images as $image ) {
			$image->width = $group->width - $this->margin;
			$image->raw_height = ( $group->raw_width - $this->margin ) / $image->ratio;
			$image_heights_array[] = $image->raw_height;
		}

		$image_height_sum = $group->height - count( $image_heights_array ) * $this->margin;
		$rounded_image_heights_array = PHTPB_Constrained_Array_Rounding::get_rounded_constrained_array( $image_heights_array, $image_height_sum );

		foreach ( $group->images as $image ) {
			$image->height = array_shift( $rounded_image_heights_array );
		}
	}
}

class PHTPB_Tiled_Gallery_Row {
	public function __construct( $groups ) {
		$this->groups = $groups;
		$this->ratio = $this->get_ratio();
		$this->weighted_ratio = $this->get_weighted_ratio();
	}

	public function get_ratio() {
		$ratio = 0;
		foreach ( $this->groups as $group ) {
			$ratio += $group->ratio;
		}
		return $ratio > 0? $ratio : 1;
	}

	public function get_weighted_ratio() {
		$weighted_ratio = 0;
		foreach ( $this->groups as $group ) {
			$weighted_ratio += $group->ratio * count( $group->images );
		}
		return $weighted_ratio > 0 ? $weighted_ratio : 1;
	}
}

class PHTPB_Tiled_Gallery_Group {
	public function __construct( $images ) {
		$this->images = $images;
		$this->ratio = $this->get_ratio();
	}

	public function get_ratio() {
		$ratio = 0;
		foreach ( $this->images as $image ) {
			if ( $image->ratio )
				$ratio += 1/$image->ratio;
		}
		if ( !$ratio )
			return 1;

		return 1/$ratio;
	}
}