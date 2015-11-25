<?php
/**
 * WP Image Editor in use for resizing
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 */

/**
 * WP Image Editor in use for resizing.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/public
 * @author PeHaa THEMES <info@pehaa.com>
 */
class PHTPB_Resize_Image {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;


	/**
	 * Initialize
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$arg = array(

			'methods' => array(
				'get_size',
				'get_suffix',
				'resize',
				'save'
			),
		);

		$this->img_editor_test = wp_image_editor_supports( $arg );

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

	public function resize_image( $attach_id = NULL, $img_url = NULL, $width, $height = 0, $crop = true ) {

		$image_src = array( $img_url, 0, 0 );

		if ( $attach_id ) {

			$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
			$img_url = $image_src[0];
			$file_path = get_attached_file( $attach_id );

		// this is not an attachment, let's use the image url
		} elseif ( $img_url ) {

			$file_path = parse_url( $img_url );
			$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
		}

		// Look for Multisite Path
		if ( file_exists( $file_path ) === false ) {

			global $blog_id;
			$file_path = parse_url( $img_url );
			if ( file_exists( $file_path['path'] ) === false ) return;
			if ( preg_match( "/files/", $file_path['path'] ) ) {
				$path = explode( '/', $file_path['path'] );
				foreach ( $path as $k=>$v ) {
					if ( $v == 'files' ) {
						$path[ $k-1 ] = '/wp-content/blogs.dir/'.$blog_id;
					}
				}
				$path = implode( '/', $path );
			}
			$file_path = $_SERVER['DOCUMENT_ROOT'].$path;
		}
		//end Look for Multisite

        if ( !file_exists( $file_path ) ) {
        	return;
        }

		$file_info = pathinfo( $file_path );
		$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
		$extension = '.'. $file_info['extension'];

		if ( !$image_src[1] || !$image_src[2] ) {
			if ( $this->img_editor_test ) {
				$f_image = wp_get_image_editor( $file_path );
				if ( ! is_wp_error( $f_image ) ) {
					$original_size = $f_image->get_size();
					$image_src[1] = $original_size['width'];
					$image_src[2] = $original_size['height'];
				}
			}
		}

		if ( !$height || !$crop ) {

			$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
			$width = $proportional_size[0];
			$height = $proportional_size[1];
		}

		// check if the resized version already exists
		$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;

		if ( file_exists( $cropped_img_path ) ) {

			$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
			return array (
				'url' => $cropped_img_url,
				'width' => $width,
				'height' => $height
			);

		}

		if ( $this->img_editor_test ) {

			if ( !isset( $f_image ) ) {
				$f_image = wp_get_image_editor( $file_path );
			} 

			if ( ! is_wp_error( $f_image ) ) {

				$file_info = pathinfo( $file_path );

				if ( ! file_exists( $no_ext_path.$extension ) ) {
					return;
				}
				
				// check if the file width is larger than the target width
				if ( $image_src[1] >= $width ) {

					$f_image->resize( $width, $height, true );

					if ( $f_image !== FALSE ) {

						$new_size = $f_image->get_size();
						$f_suffix=$f_image->get_suffix();
						$f_filename = $f_image->generate_filename( $f_suffix, $image_src[0], NULL );
						$f_image->save( $f_filename );

						if ( file_exists( $f_filename ) ) {

							$f_filename_url = str_replace( basename( $image_src[0] ), basename( $f_filename ), $image_src[0] );

							return array (
								'url' => $f_filename_url,
								'width' => $new_size['width'],
								'height' => $new_size['height'],
							);

						}

					}

				}

			}

		}

		return array (
			'url' => $img_url,
			'width' => isset( $image_src ) ? $image_src[1] : '',
			'height' => isset( $image_src ) ? $image_src[2] : '',
		);


		return;

	}
}