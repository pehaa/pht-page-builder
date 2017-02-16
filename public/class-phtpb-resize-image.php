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

		$this->upload    = wp_upload_dir();

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

	private function get_pathinfo( $path ) {
		return pathinfo( $path );
	}

	private function get_attachment_url() {

		
		$this->pathinfo = $this->get_pathinfo( $this->path );
		return $this->upload['baseurl'] . str_replace( $this->upload['basedir'], '', $this->pathinfo['dirname'] );

	}

	private function original_image() {
	
		return array(
			'url' => "{$this->url}/{$this->pathinfo['basename']}",
			'width' => isset( $this->meta['width'] ) ? $this->meta['width'] : 0,
			'height' => isset( $this->meta['height'] ) ? $this->meta['height'] : 0
		);
	}

	private function skip_resizing( $skip_mime_types ) {

		if ( !empty( $skip_mime_types ) ) {
			$filetype = wp_check_filetype( $this->url );
			if ( in_array( $filetype, $skip_mime_types ) ) {
				return true;
			}
		}
		return false;
	}

	private function check_meta( $width, $height ) {

		if ( !isset( $this->meta['width'] ) || !isset( $this->meta['height'] ) || !is_array( $this->meta['sizes'] ) ) {
			return false;
		}

		if ( $this->meta['width'] >= $width && $this->meta['height'] >= $height ) {
			foreach ( $this->meta['sizes'] as $key => $size ) {
				if ( $size['width'] == $width && $size['height'] == $height || isset( $size['keep_ratio']) && $size['keep_ratio'] && ( $size['width'] == $width && $height == 0 || $size['height'] == $height && $width == 0 ) ) {
					return array( 
						'url' => "{$this->url}/{$size['file']}",
						'width' => $size['width'],
						'height' => $size['height']
					);
				}
			}
		}

		return false;

	}

	public function do_resizing( $attachment_id, $width, $height ) {

		if ( !isset( $this->meta['width'] ) || !isset( $this->meta['height'] ) ) {
			return false;
		}

		if ( $this->meta['width'] >= $width && $this->meta['height'] >= $height ) {

			$resized = image_make_intermediate_size( $this->path, $width, $height, true );

			if ( $resized && ! is_wp_error( $resized ) ) {
				// Let metadata know about our new size.
				
				$this->update_attachment_metadata( $attachment_id, $resized, $width, $height );
				$this->update_meta = true;

				return array( 
					'url' => "{$this->url}/{$resized['file']}",
					'width' => $resized['width'],
					'height' => $resized['height']
				);
			}

		}
		return false;
	}

	private function update_attachment_metadata( $attachment_id, $resized, $width, $height ) {
		
		$key = sprintf( 'phtpb-resized-%dx%d', $width, $height );
		
		if ( 0 === (int) $width || 0 === (int) $height ) {
				
			$resized['keep_ratio'] = true;

		}

		$this->meta['sizes'][ $key ] = $resized;

		$this->update_meta = true;
		
	}

	private function find_size_array( $attachment_id, $width, $height ) {
		
		$img = $this->check_meta( $width, $height );
		
		if ( !$img || !file_exists( str_replace( $this->upload['baseurl'], $this->upload['basedir'], $img['url'] ) ) ) {

			$img = $this->do_resizing( $attachment_id, $width, $height );

		}

		return $img;

	}

	public function resize_image( $attachment_id, $url = '', $width, $height = 0, $crop = true, $skip_mime_types = array() ) {
		return $this->phtpb_resize_image( $attachment_id, $width, $height, $skip_mime_types );
	}

	public function phtpb_resize_image( $attachment_id, $width, $height = 0, $skip_mime_types = array() ) {

		$this->img = false;
		$this->update_meta = false;
		$width = (int) $width;
		$height = (int) $height;
		
		$this->path = get_attached_file( $attachment_id );
		if ( !$this->path ) {
			return false;
		}
		$this->url = $this->get_attachment_url();

		if ( $this->url ) {
			$this->meta = wp_get_attachment_metadata( $attachment_id );
			
			if ( !$this->skip_resizing( $skip_mime_types ) ) {

				$this->img = $this->find_size_array( $attachment_id, $width, $height );
				if ( $this->update_meta ) {
					wp_update_attachment_metadata( $attachment_id, $this->meta );
				}
			}
			
			if ( !$this->img ) {
				$this->img = $this->original_image();
			}

			return $this->img;	
		}

		return false;
	}

	public function resize_image_srcset( $attachment_id, $width, $height = 0, $skip_mime_types = array() ) {

		$this->img = false;
		$this->update_meta = false;

		$width = (int) $width;
		$height = (int) $height;

		$this->path = get_attached_file( $attachment_id );
		if ( !$this->path ) {
			return false;
		}
		$this->url = $this->get_attachment_url();
		
		if ( $this->url ) {
			$this->meta = wp_get_attachment_metadata( $attachment_id );

			if ( !$this->skip_resizing( $skip_mime_types ) ) {

				$this->img['1x'] = $this->find_size_array( $attachment_id, $width, $height );
				$img2x =  $this->find_size_array( $attachment_id, 2*$width, 2*$height );
				if ( $img2x ) {
					$this->img['2x'] = $img2x;
				}
			}
			if ( $this->update_meta ) {
				wp_update_attachment_metadata( $attachment_id, $this->meta );
			}
			if ( !$this->img || !$this->img['1x'] ) {
				$this->img = array( 
					'1x' => $this->original_image() 
				);
			}
			return $this->img;	

		}
		
		return false;		

	}
}