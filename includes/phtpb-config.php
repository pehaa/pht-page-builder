<?php

/**
 * Set the configuration data.
 *
 * @link       http://github.com/pehaa/pht-page-builder
 * @since      1.0.0
 *
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/includes
 */

/**
 * Set the configuration data.
 *
 *
 * @since      1.0.0
 * @package    PeHaa_Themes_Page_Builder
 * @subpackage PeHaa_Themes_Page_Builder/includes
 * @author     PeHaa THEMES <info@pehaa.com>
 */

$default_bg_color = isset( self::$defaults['bg_color'] ) ? self::$defaults['bg_color'] : '#fff';
$default_color = isset( self::$defaults['color'] ) ? self::$defaults['color'] : '#303030';
$margin_b_item = array(
	'title' => esc_html__( 'Bottom margin', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => 'yes',
);
$lightbox_item = array(
	'title' => esc_html__( 'Open in Lightbox', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
	'description' => esc_html__( 'Check here if you want your image to open in a lightbox.', $this->plugin_name )
);

$target_item = array(
	'title' => esc_html__( 'Link target', $this->plugin_name ),
	'type' => 'select',
	'options' => array(
		'_self' => esc_html__( 'Same window', $this->plugin_name ),
		'_blank' => esc_html__( 'New window', $this->plugin_name ),
	),
	'default' => '_self',
	'description' => esc_html__( 'Choose whether or not your link opens in a new window.', $this->plugin_name ),
);

$use_bg_color_item = array(
	'title' => esc_html__( 'Use custom background color', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
);
$bg_color_item = array(
	'title' => esc_html__( 'Background Color', $this->plugin_name ),
	'type' => 'color',
	'default' => $default_bg_color,
);
$use_color_item = array(
	'title' => esc_html__( 'Use custom text color', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
);
$color_item = array(
	'title' => esc_html__( 'Text Color', $this->plugin_name ),
	'type' => 'color',
	'default' => $default_color,
);

$phtpb_module_class = array(
	'title' => esc_html__( 'Class', $this->plugin_name ),
	'type' => 'text',
	'description' => esc_html__( 'Insert CSS classes separated by single spaces.', $this->plugin_name ),
	'default' => '',
);
$phtpb_module_id = array(
	'title' => esc_html__( 'ID', $this->plugin_name ),
	'type' => 'text',
	'description' => esc_html__( 'Insert a unique ID fot this module.', $this->plugin_name ),
	'default' => '',
);

$phtpb_config_data['phtpb_section'] = array(
	'label' => 'phtpb_section',
	'title' => esc_html__( 'Section', $this->plugin_name ),
	'phtpb_admin_type' => 'section',
	'fields' => array(
		'admin_collapsed' => array(
			'type' => 'hidden',
			'default' => false
		),
		'use_bg_color' => $use_bg_color_item,
		'bg_color' => $bg_color_item,
		'use_color' => $use_color_item,
		'color' => $color_item,
		'layout_option' => array(
			'title' => esc_html__( 'Top and bottom padding', $this->plugin_name ),
			'type' => 'select',
			'default' => 'none',
			'options' => array(
				'none' => esc_html__( 'None', $this->plugin_name ),
				'normal' => esc_html__( 'Normal, 48px', $this->plugin_name ),
				'big' => esc_html__( 'Big, 96px', $this->plugin_name ),
				'huge' => esc_html__( 'Huge, 160px', $this->plugin_name ),
				'giant' => esc_html__( 'Giant, 264px', $this->plugin_name ),
			),
		),
		'background_image' => array(
			'title' => esc_html__( 'Background Image', $this->plugin_name ),
			'type' => 'image',
			'description' => esc_html__( 'Upload the background image.', $this->plugin_name ),
			'default' => '',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
			'default' => '',
		),
		'opacity' => array(
			'title' => esc_html__( 'Image Opacity', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'1' => '100%',
				'0.95' => '95%',
				'0.9' => '90%',
				'0.85' => '85%',
				'0.80' => '80%',
				'0.75' => '75%',
				'0.7' => '70%',
				'0.6' => '60%',
				'0.5' => '50%',
				'0.4' => '40%',
				'0.3' => '30%',
				'0.25' => '25%',
				'0.2' => '20%',
				'0.15' => '15%',
				'0.1' => '10%',
				'0.05' => '5%',
			),
			'default' => 1,
		),
		'r_w' => array(
			'title' => esc_html__( 'Background Width', $this->plugin_name ),
			'description' => esc_html__( 'Resize width, auto is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => esc_html__( 'Auto (cover)', $this->plugin_name ),
		),
		'r_h' => array(
			'title' => esc_html__( 'Background Height', $this->plugin_name ),
			'description' => esc_html__( 'Resize height, auto is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => esc_html__( 'Auto (cover)', $this->plugin_name ),
		),
		'phtpb_type' => array(
			'title' => esc_html__( 'Small screens', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'colors' => esc_html__( 'Keep the custom background and text colors', $this->plugin_name ),
				'inherit' => esc_html__( 'Use the default colors', $this->plugin_name ),
				'force' => esc_html__(  'Force the background image to be displayed', $this->plugin_name ),
			),
			'description' => esc_html__( 'Define how this section is displayed on small screens (<800px).', $this->plugin_name ),
			'default' => 'colors',
		),
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => false
);
$phtpb_config_data['phtpb_section']['fields'] = apply_filters( 'phtpb_config_fields_phtpb_section', $phtpb_config_data['phtpb_section']['fields'] );

$column_settings = array(
	'use_bg_color' => $use_bg_color_item,
	'bg_color' => $bg_color_item,
	'bg_opacity' => array(
		'title' => esc_html__( 'Background Opacity', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'1' => '100%',
			'0.95' => '95%',
			'0.9' => '90%',
			'0.85' => '85%',
			'0.80' => '80%',
			'0.75' => '75%',
			'0.7' => '70%',
			'0.6' => '60%',
			'0.5' => '50%',
			'0.4' => '40%',
			'0.3' => '30%',
			'0.25' => '25%',
			'0.2' => '20%',
			'0.15' => '15%',
			'0.1' => '10%',
			'0.05' => '5%',
		),
		'default' => 1,
	),
	'use_color' => $use_color_item,
	'color' => $color_item,
	'border_radius' => array(
		'title' => esc_html__( 'Rounded corners', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => esc_html__( 'No rounded corners', $this->plugin_name ),
			'2' => esc_html__( '2px - very subtle', $this->plugin_name ),
			'3' => esc_html__( '3px - subtle', $this->plugin_name ),
			'5' => esc_html__( '5px', $this->plugin_name ),
			'10' => esc_html__( '10px', $this->plugin_name ),
		),
		'default' => 'none',
	),
	'border_style' => array(
		'title' => esc_html__( 'Border style', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => esc_html__( 'No border', $this->plugin_name ),
			'solid' => esc_html__( 'Solid', $this->plugin_name ),
			'dashed' => esc_html__( 'Dashed', $this->plugin_name ),
			'dotted' => esc_html__( 'Dotted', $this->plugin_name ),
			'double' => esc_html__( 'Double', $this->plugin_name ),
		),
		'default' => 'none',
	),
	'border_width' => array(
		'title' => esc_html__( 'Border width', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'1' => '1px',
			'2' => '2px',
			'3' => '3px',
			'4' => '4px',
			'5' => '5px',
			'8' => '8px',
			'16' => '16px',
			'24' => '24px',
		),
		'default' => '1',
	),
	'margins' => array(
		'title' => esc_html__( 'Margins between border and content',  $this->plugin_name ),
		'type' => 'checkbox',
		'default' => ''
	),
	'use_border_color' => array(
		'title' => esc_html__( 'Use custom border color', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => '',
	),
	'border_color' => array(
		'title' => esc_html__( 'Border Color', $this->plugin_name ),
		'type' => 'color',
		'default' => $default_color,
	),
	'padding' => array(
		'title' => esc_html__( 'Padding', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => esc_html__( 'None', $this->plugin_name ),
			'box--tiny' => esc_html__( 'Third (8px)', $this->plugin_name ),
			'box--small' => esc_html__( 'Half (12px)', $this->plugin_name ),
			'box' => esc_html__( 'Normal (24px)', $this->plugin_name ),
			'box--large' => esc_html__( 'Large (32px)', $this->plugin_name ),
			'box--huge' => esc_html__( 'Huge (48px)', $this->plugin_name ),
		),
		'default' => 'none',
		'description' => esc_html__( 'Padding arround the column content.', $this->plugin_name ),
	),
	'layout' => array(
		'type' => 'hidden_2_skip'
	),
	'animation' => array(
		'title' => esc_html__( 'Fade-in Animation', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => esc_html__( 'None', $this->plugin_name ),
			'l2r' => esc_html__( 'Left to Right', $this->plugin_name ),
			'r2l' => esc_html__( 'Right to Left', $this->plugin_name ),
			'b2t' => esc_html__( 'Bottom to Top', $this->plugin_name ),
			't2b' => esc_html__( 'Top to Bottom', $this->plugin_name ),
			'fade' => esc_html__( 'Fade-in', $this->plugin_name ),
			'scaleinx' => esc_html__( 'Scale-in Horizontally', $this->plugin_name ),
			'scaleiny' => esc_html__( 'Scale-in Vertically', $this->plugin_name ),
			'scalein' => esc_html__( 'Scale-in', $this->plugin_name ),
		),
		'default' => 'none',
		'description' => esc_html__( 'A subtle fade-in effect. Please use them with moderation.', $this->plugin_name )
	),
	'valign' => array(
		'title' => esc_html__( 'Vertical Align', $this->plugin_name ),
		'type' => 'select',
		'default' => 'top',
		'options' => array(
			'top' => esc_html__( 'Top', $this->plugin_name ),
			'center' => esc_html__( 'Center', $this->plugin_name ),
			'bottom' => esc_html__( 'Bottom', $this->plugin_name ),
		),
		'description' => esc_html__( 'If "Equal Columns" are checked in row settings you can choose the vertical alignment for the column. This feature only works in modern browsers and is enabled for screens at least 1024px wide. ', $this->plugin_name ),
	),
);

$row_settings = array(
	'admin_collapsed' => array(
		'type' => 'hidden',
		'default' => false
	),
	'gutter' => array(
		'title' => esc_html__( 'Gutters', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => 'yes',
		'description' => esc_html__( 'If checked, columns are separated by vertical whitespaces.', $this->plugin_name ),
	),
	'equals' => array(
		'title' => esc_html__( 'Equal columns', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => '',
		'description' => esc_html__( 'If checked, columns will have equal heights - use it if you apply a background color or borders to the columns. Equals heights have to be checked if you want change the vertical align of your column. Note that this feature only works in modern browsers and is enabled for screens at least 1024px wide.', $this->plugin_name ),
	),
	'wrapper' => array(
		'title' => esc_html__( 'Wrapper', $this->plugin_name ),
		'type' => 'select',
		'default' => 'normal',
		'options' => array(
			'normal' => esc_html__( '1140px', $this->plugin_name ),
			'none' => esc_html__( 'Full width', $this->plugin_name ),
			'none-12' => esc_html__( 'Full width with 12px margins', $this->plugin_name ),
			'none-24' => esc_html__( 'Full width with 24px margins', $this->plugin_name ),
			'none-48' => esc_html__( 'Full width with 48px margins', $this->plugin_name ),
			'none-96' => esc_html__( 'Full width with 96px margins', $this->plugin_name ),
		),
		'description' => esc_html__( 'You can switch between boxed and stretched layout of each row. The margin values correspond to the left and right margins.', $this->plugin_name ),
	),
);

$phtpb_config_data['phtpb_row'] = array(
	'label' => 'phtpb_row',
	'title' => esc_html__( 'Row', $this->plugin_name ),
	'phtpb_admin_type' => 'row',
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => false,
	'fields' => $row_settings
);
$phtpb_config_data['phtpb_row_inner'] = array(
	'label' => 'phtpb_row_inner',
	'title' => esc_html__( 'Row', $this->plugin_name ),
	'phtpb_admin_type' => 'row',
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => false,
	'fields' => $row_settings
);
$phtpb_config_data['phtpb_column'] = array(
	'label' => 'phtpb_column',
	'title' => esc_html__( 'Column', $this->plugin_name ),
	'phtpb_admin_type' => 'column',
	'phtpb_admin_mode' => 'simple',
	'fields' => $column_settings
);
$phtpb_config_data['phtpb_column_inner'] = array(
	'label' => 'phtpb_column_inner',
	'title' => esc_html__( 'Column', $this->plugin_name ),
	'phtpb_admin_type' => 'column',
	'phtpb_admin_mode' => 'simple',
	'fields' => $column_settings
);


// *************************** MODULES *************************** //

$phtpb_config_data['phtpb_text'] = array(
	'label' =>  'phtpb_text',
	'title' => esc_html__( 'Text', $this->plugin_name ),
	'icon' => 'fa fa-file-text-o',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is the main content', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_image'] = array(
	'label' =>  'phtpb_image',
	'title' => esc_html__( 'Image', $this->plugin_name ),
	'icon' => 'fa fa-image',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'src' => array(
			'title' => esc_html__( 'Image Source', $this->plugin_name ),
			'type' => 'image',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
		),
		'resize' => array(
			'title' => esc_html__( 'Resize', $this->plugin_name ),
			'description' => esc_html__( 'Resize to fit the column size (recommended).', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => 'yes'
		),
		'r_w' => array(
			'title' => esc_html__( 'Resize Width', $this->plugin_name ),
			'description' => esc_html__( 'Resize width, auto (or leave empty) is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => esc_html__( 'Auto', $this->plugin_name ),
		),
		'r_h' => array(
			'title' => esc_html__( 'Resize Height', $this->plugin_name ),
			'description' => esc_html__( 'Resize height, auto (or leave empty) is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => esc_html__( 'Auto', $this->plugin_name ),
		),
		'd_w' => array(
			'title' => esc_html__( 'Display Width', $this->plugin_name ),
			'description' => esc_html__( 'You can change here the width that will be use to display the image. If left empty (recommended) the default value will be applied.', $this->plugin_name ),
			'type' => 'text',
			'default' => esc_html__( '', $this->plugin_name ),
		),
		'link_type' => array(
			'title' => esc_html__( 'Link Type', $this->plugin_name ),
			'type' => 'select',
			'default' => 'none',
			'options' => array(
				'none' => esc_html__( 'None', $this->plugin_name ),
				'lightbox' => esc_html__( 'Lightbox', $this->plugin_name ),
				'url' => esc_html__( 'URL', $this->plugin_name ),
			),
			'description' => esc_html__( '', $this->plugin_name )
		),
		'link' => array(
			'title' => esc_html__( 'Link URL', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( '(Optional) If you would like your image to be a link, input your destination URL here.', $this->plugin_name )
		),
		'target' => $target_item,
		'h_align' => array(
			'title' => esc_html__( 'Image alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => esc_html__( 'Center', $this->plugin_name ),
				'left' => esc_html__( 'Left', $this->plugin_name ),
				'right' => esc_html__( 'Right', $this->plugin_name ),
			),
			'default' => 'center',
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_gallery'] = array(
	'label' =>  'phtpb_gallery',
	'title' => esc_html__( 'Gallery', $this->plugin_name ),
	'icon' => 'fa fa-trello',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_ids' => array(
			'title' => esc_html__( 'Image Source', $this->plugin_name ),
			'type' => 'gallery',
		),
		'border_width' => array(
			'title' => esc_html__( 'Images separation', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'6' => '6px',
				'0' => esc_html__( 'No separation', $this->plugin_name ),
				'16' => '16px',
				'24' => '24px',
			),
			'default' => '6',
		),
		'lightbox' => $lightbox_item,
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_buttons'] = array(
	'label' => 'phtpb_buttons',
	'title' => esc_html__( 'Buttons block', $this->plugin_name ),
	'icon' => 'fa fa-square-o',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_btn',
	'fields' => array(
		'h_align' => array(
			'title' => esc_html__( 'Buttons alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => esc_html__( 'Center', $this->plugin_name ),
				'left' => esc_html__( 'Left', $this->plugin_name ),
				'right' => esc_html__( 'Right', $this->plugin_name ),
			),
			'default' => 'center',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Button', $this->plugin_name ),
);

$phtpb_config_data['phtpb_btn'] = array(
	'label' => 'phtpb_btn',
	'title' => esc_html__( 'Button', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Button Text', $this->plugin_name ),
			'type' => 'text',
		),
		'icon' => array(
			'title' => esc_html__( 'Icon', $this->plugin_name ),
			'type' => 'icons',
		),
		'link' => array(
			'title' => esc_html__( 'URL', $this->plugin_name ),
			'type' => 'text',
			'description' => esc_html__( 'Input your destination URL here.', $this->plugin_name ),
			'default' => '',
		),
		'target' => $target_item,
		'use_color' => $use_color_item,
		'color' => $color_item,
		'border_radius' => array(
			'title' => esc_html__( 'Rounded corners', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'none' => esc_html__( 'No rounded corners', $this->plugin_name ),
				'2' => esc_html__( '2px - very subtle', $this->plugin_name ),
				'3' => esc_html__( '3px - subtle', $this->plugin_name ),
				'5' => esc_html__( '5px', $this->plugin_name ),
				'10' => esc_html__( '10px', $this->plugin_name ),
			),
			'default' => 'none',
		),
		'skip' => array(
			'title' => esc_html__( 'Full-width button', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Button', $this->plugin_name ),
);


$phtpb_config_data['phtpb_icons'] = array(
	'label' => 'phtpb_icons',
	'title' => esc_html__( 'Icons block', $this->plugin_name ),
	'icon' => 'fa fa-circle-o',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_icon',
	'fields' => array(
		'h_align' => array(
			'title' => esc_html__( 'Icons alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => esc_html__( 'Center', $this->plugin_name ),
				'left' => esc_html__( 'Left', $this->plugin_name ),
				'right' => esc_html__( 'Right', $this->plugin_name ),
			),
			'default' => 'center',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Icon', $this->plugin_name ),
);

$phtpb_config_data['phtpb_icon'] = array(
	'label' => 'phtpb_icon',
	'title' => esc_html__( 'Icon', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'icon' => array(
			'title' => esc_html__( 'Icon', $this->plugin_name ),
			'type' => 'icons',
		),
		'phtpb_type' => array(
			'title' => esc_html__( 'Icon size', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'normal' => esc_html__( 'Normal', $this->plugin_name ),
				'tiny' => esc_html__( 'Tiny', $this->plugin_name ),
				'large' => esc_html__( 'Large', $this->plugin_name ),
				'huge' => esc_html__( 'Huge', $this->plugin_name ),
			),
			'default' => 'normal'
		),
		'link' => array(
			'title' => esc_html__( 'Link URL', $this->plugin_name ),
			'type' => 'text',
			'description' => esc_html__( '(Optional) If you want your icon to be a link, input your destination URL here.', $this->plugin_name ),
			'default' => '',
		),
		'target' => $target_item,
		'use_bg_color' => $use_bg_color_item,
		'bg_color' => $bg_color_item,
		'use_color' =>  array(
			'title' => esc_html__( 'Use custom color', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
		),
		'color' => $color_item = array(
			'title' => esc_html__( 'Color', $this->plugin_name ),
			'type' => 'color',
			'default' => $default_color,
		),
		'skip' => array(
			'title' => esc_html__( 'No border', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => ''
		),
		'border_radius' => array(
			'title' => esc_html__( 'Rounded corners', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'pill' => esc_html__( 'Circle', $this->plugin_name ),
				'2' => esc_html__( '2px - very subtle', $this->plugin_name ),
				'3' => esc_html__( '3px - subtle', $this->plugin_name ),
				'5' => esc_html__( '5px', $this->plugin_name ),
				'10' => esc_html__( '10px', $this->plugin_name ),
				'none' => esc_html__( 'None', $this->plugin_name ),
			),
			'default' => 'pill',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Icon', $this->plugin_name ),
);



$phtpb_config_data['phtpb_tabs'] = array(
	'label' => 'phtpb_tabs',
	'title' => esc_html__( 'Tabs', $this->plugin_name ),
	'icon' => 'fa fa-list-alt',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_tab',
	'fields' => array(
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Tab', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tab'] = array(
	'label' => 'phtpb_tab',
	'title' => esc_html__( 'Tab', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Tab Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Tab', $this->plugin_name ),
);

$phtpb_config_data['phtpb_cslider'] = array(
	'label' => 'phtpb_cslider',
	'title' => esc_html__( 'Content Slider', $this->plugin_name ),
	'icon' => 'fa fa-exchange',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_cslide',
	'fields' => array(
		'margin_b' => $margin_b_item,
		'auto' => array(
			'title' => esc_html__( 'Auto slideshow', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => esc_html__( 'Animate slider automatically.', $this->plugin_name )
		),
		'anim' => array(
			'title' => esc_html__( 'Animation type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'fade' => esc_html__( 'Fade', $this->plugin_name ),
				'slide' => esc_html__( 'Slide', $this->plugin_name ),
			),
			'default' => 'fade',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Slide', $this->plugin_name ),
);
$phtpb_config_data['phtpb_cslide'] = array(
	'label' => 'phtpb_cslide',
	'title' => esc_html__( 'Content Slide', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Slide', $this->plugin_name ),
);

$phtpb_config_data['phtpb_slider'] = array(
	'label' => 'phtpb_slider',
	'title' => esc_html__( 'Slider', $this->plugin_name ),
	'icon' => 'fa fa-exchange',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_slide',
	'fields' => array(
		'hoption' => array(
			'title' => esc_html__( 'Slider Height', $this->plugin_name ),
			'type' => 'select',
			'default' => 'full',
			'options' => array(
				'full' => esc_html__( 'Full window height', $this->plugin_name ),
				'480' => '480px',
				'600' => '600px',
			),
			'description' => esc_html__( 'Animate slider automatically.', $this->plugin_name )
		),
		'auto' => array(
			'title' => esc_html__( 'Auto slideshow', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => esc_html__( 'Animate slider automatically.', $this->plugin_name )
		),
		'anim' => array(
			'title' => esc_html__( 'Animation type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'fade' => esc_html__( 'Fade', $this->plugin_name ),
				'slide' => esc_html__( 'Slide', $this->plugin_name ),
			),
			'default' => 'fade',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Slide', $this->plugin_name ),
);

$phtpb_config_data['phtpb_slide'] = array(
	'label' => 'phtpb_slide',
	'title' => esc_html__( 'Slide', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
	'background_image' => array(
			'title' => esc_html__( 'Background Image', $this->plugin_name ),
			'type' => 'image',
			'description' => esc_html__( 'Upload the background image.', $this->plugin_name ),
			'default' => '',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
			'default' => '',
		),
		'opacity' => array(
			'title' => esc_html__( 'Image Opacity', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'1' => '100%',
				'0.95' => '95%',
				'0.9' => '90%',
				'0.85' => '85%',
				'0.80' => '80%',
				'0.75' => '75%',
				'0.7' => '70%',
				'0.6' => '60%',
				'0.5' => '50%',
				'0.4' => '40%',
				'0.3' => '30%',
				'0.25' => '25%',
				'0.2' => '20%',
				'0.15' => '15%',
				'0.1' => '10%',
				'0.05' => '5%',
			),
			'default' => 1,
		),
		'use_bg_color' => $use_bg_color_item,
		'bg_color' => $bg_color_item,
		'use_color' => $use_color_item,
		'color' => $color_item,
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Slide', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tlist'] = array(
	'label' => 'phtpb_tlist',
	'title' => esc_html__( 'Tabbled List', $this->plugin_name ),
	'icon' => 'fa fa-list-ul',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_tlist_item',
	'fields' => array(
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Item', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tlist_item'] = array(
	'label' => 'phtpb_tlist_item',
	'title' => esc_html__( 'Tabbled List Item', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'List item title', $this->plugin_name ),
			'type' => 'text',
		),
		'item_subtitle' => array(
			'title' => esc_html__( 'List item description', $this->plugin_name ),
			'type' => 'text',
		),
		'item_attribute' => array(
			'title' => esc_html__( 'List item attribute (e.g. price)', $this->plugin_name ),
			'type' => 'text',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Item', $this->plugin_name ),
);

$phtpb_config_data['phtpb_accordion'] = array(
	'label' => 'phtpb_accordion',
	'title' => esc_html__( 'Accordion', $this->plugin_name ),
	'icon' => 'fa fa-sort',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_ac_tab',
	'fields' => array(
		'collapsible' => array(
			'title' => esc_html__( 'Collapsible', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => esc_html__( 'Allows collapsing the active tab (all tabs can be closed).', $this->plugin_name )
		),
		'inactive' => array(
			'title' => esc_html__( 'Panels closed', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => esc_html__( 'All panels are closed on page load. Make sure that "Collapsible" is checked as well.', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => esc_html__( 'Add Tab', $this->plugin_name ),
);

$phtpb_config_data['phtpb_ac_tab'] = array(
	'label' => 'phtpb_ac_tab',
	'title' => esc_html__( 'Tab', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Tab Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is the accordion content', $this->plugin_name ),
		),
	),
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_toggle'] = array(
	'label' => 'phtpb_toggle',
	'title' => esc_html__( 'Toggle', $this->plugin_name ),
	'icon' => 'fa fa-sort',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'simple',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Toggle Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_type' => array(
			'title' => esc_html__( 'Initial state', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'on' => esc_html__( 'Opened', $this->plugin_name ),
				'off' => esc_html__( 'Closed', $this->plugin_name ),
			),
			'default' => 'on',
			'description' => esc_html__( 'Choose if you want your toggle section to be opened or closed by default.', $this->plugin_name ),
		),
		'phtpb_content_new' => array(
			'title' => esc_html__( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => esc_html__( 'This is the toggle content', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_google_map'] = array(
	'label' => 'phtpb_google_map',
	'title' => esc_html__( 'Google Map', $this->plugin_name ),
	'icon' => 'fa  fa-map-marker',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_marker',
	'fields' => array(
		'map_address_center' => array(
			'title' => esc_html__( 'Map Center', $this->plugin_name ),
			'type' => 'find_address',
			'default' => '',
		),
		'lat' => array(
			'type' => 'hidden',
			//'default' => 0
		),
		'lng' => array(
			'type' => 'hidden',
			//'default' => 0
		),
		'zoom' => array(
			'type' => 'hidden',
			'default' => '8'
		),
		'styles' => array(
			'title' => esc_html__( 'Map styling', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'default' => esc_html__( 'Default', $this->plugin_name ),
				'desaturate' => esc_html__( 'Grayscale', $this->plugin_name ),
				'ultralight' => esc_html__( 'Very Light', $this->plugin_name ),
				'darkgreys' => esc_html__( 'Dark Shades of Grey', $this->plugin_name ),
				'beigeblue' => esc_html__( 'Light Beige and Blue', $this->plugin_name ),
			),
			'default' => 'default', 
			'description' => esc_html__( 'Choose colors variant for your map. Note that the admin area displays the default colors, you need to preview the page to see your changes.', $this->plugin_name ),
		),
		'height' => array(
			'title' => esc_html__( 'Height', $this->plugin_name ),
			'type' => 'text',
			'description' => esc_html__( 'Set the height of map (in pixels)', $this->plugin_name ),
			'default' => '400'
		),
		'margin_b' => $margin_b_item,
	),
	'add_submodule' => esc_html__( 'Add Marker', $this->plugin_name ),
	'create_with_settings' => true
);
$phtpb_config_data['phtpb_marker'] = array(
	'label' => 'phtpb_marker',
	'title' => esc_html__( 'Map Marker', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'map_address_marker' => array(
			'title' => esc_html__( 'Add Marker', $this->plugin_name ),
			'type' => 'find_address',
		),
		'title' => array(
			'title' => esc_html__( 'Marker Title', $this->plugin_name ),
			'type' => 'text',
		),
		'src' => array(
			'title' => esc_html__( 'Image Source', $this->plugin_name ),
			'type' => 'image',
			'description' => esc_html__( 'You can add a custom icon for this marker. Make sure to upload it double sized as it will be scaled down for crispy retina displays. (Eg. if you want your marker icon to be 24px x 48px upload a 48px x 96px image). Note that the admin area displays the default markers, you need to preview the page to see your changes.', $this->plugin_name ),
		),
		'phtpb_id' => array(
			'type' => 'image_id',
		),
		'marker_lat' => array(
			'type' => 'hidden',
		),
		'marker_lng' => array(
			'type' => 'hidden',
		),
	),
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_divider'] = array(
	'label' =>  'phtpb_divider',
	'title' => esc_html__( 'Divider', $this->plugin_name ),
	'icon' => 'fa fa-arrows-v',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'height' => array(
			'title' => esc_html__( 'Height', $this->plugin_name ),
			'type' => 'text',
			'description' => esc_html__( 'Set the height of your divider (in pixels).', $this->plugin_name ),
			'default' => '48'
		),
		'phtpb_type' => array(
			'title' => esc_html__( 'Divider Type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'none' => esc_html__( 'None', $this->plugin_name ),
				'single1' => esc_html__( 'Solid line (1px)', $this->plugin_name ),
				'single2' => esc_html__( 'Solid line (2px)', $this->plugin_name ),
				'double' => esc_html__( 'Double line', $this->plugin_name ),
				'dashed' => esc_html__( 'Dashed line', $this->plugin_name ),
			),
			'default' => 'none',
		),
		'position' => array(
			'title' => esc_html__( 'Position', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'middle' => esc_html__( 'Middle', $this->plugin_name ),
				'top' => esc_html__( 'Top', $this->plugin_name ),
				'bottom' => esc_html__( 'Bottom', $this->plugin_name ),
			),
			'default' => 'middle',
		),
		'hide' => array(
			'title' => esc_html__( 'Screen options.', $this->plugin_name ),
			'type' => 'select',
			'default' => 'never',
			'options' => array(
				'never' => esc_html__( 'Always display', $this->plugin_name ),
				'palm' => esc_html__( 'Only on screens 800px and more', $this->plugin_name ),
			),
			'description' => esc_html__( 'Note that on small screens the divider height will be reduced to 48px maximum.', $this->plugin_name )
		),
		'use_color' => $use_color_item,
		'color' => $color_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_posts'] = array(
	'label' =>  'phtpb_posts',
	'title' => esc_html__( 'Posts Grid', $this->plugin_name ),
	'icon' => 'fa fa-th-large',
	'phtpb_admin_type' => 'module',
	'fields' => array(),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true,
	'pht_themes_only' => true,
);


$options_array = PeHaa_Themes_Page_Builder::$phtpb_available_post_types;
unset( $options_array['page'] );

$phtpb_config_data['phtpb_gallery_portfolio'] = array(
	'label' =>  'phtpb_gallery_portfolio',
	'title' => esc_html__( 'Gallery Portfolio', $this->plugin_name ),
	'icon' => 'fa fa-trello',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_type' => array(
			'title' => esc_html__( 'Post type', $this->plugin_name ),
			'type' => 'select',
			'options' => $options_array,
			'description' => esc_html__( 'Choose the query.', $this->plugin_name ),
		),
		'count' => array(
			'title' => esc_html__( 'Posts count', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'Number of posts. Set -1 to display all posts.', $this->plugin_name ),
		),
		'phtpb_query' => array(
			'title' => esc_html__( 'Custom query', $this->plugin_name ),
			'type' => 'text',
			'description' => esc_html__( 'You can modify the query here, e.g. orderby=title&order=ASC', $this->plugin_name ),
		),
		'border_width' => array(
			'title' => esc_html__( 'Images separation', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'6' => esc_html__( '6px - default', $this->plugin_name ),
				'0' => esc_html__( 'No separation', $this->plugin_name ),
				'16' => esc_html__( '16px', $this->plugin_name ),
				'24' => esc_html__( '24px - large separation', $this->plugin_name ),
			),
			'default' => '6',
		),
		'lightbox' => $lightbox_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_showcase'] = array(
	'label' =>  'phtpb_showcase',
	'title' => esc_html__( 'Filtered Portfolio', $this->plugin_name ),
	'icon' => 'fa fa-th-large',
	'phtpb_admin_type' => 'module',
	'fields' => array(),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true,
	'pht_themes_only' => true,
);

$sidebars = array();
global $wp_registered_sidebars;
foreach ( $wp_registered_sidebars as $id => $options ) {
	$sidebars[ $id ] = $options['name'];
}

$phtpb_config_data['phtpb_sidebar'] = array(
	'label' =>  'phtpb_sidebar',
	'title' => esc_html__( 'Sidebar', $this->plugin_name ),
	'icon' => 'fa fa-tags',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_id' => array(
			'title' => esc_html__( 'Choose sidebar', $this->plugin_name ),
			'type' => 'select',
			'options' => apply_filters( 'phtpb_available_sidebars', $sidebars )
		),
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_wp_calendar'] = array(
		'label' =>  'phtpb_wp_calendar',
		'title' => esc_html__( 'WP -  Calendar', $this->plugin_name ),
		'icon' => 'fa fa-wordpress',
		'widget' => 'WP_Widget_Calendar',
		'phtpb_admin_type' => 'module',
		'fields' => array(
			'title' => array(
				'title' => esc_html__( 'Title', $this->plugin_name ),
				'type' => 'text',
				'default' => '',
				'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
			),
			'margin_b' => $margin_b_item,
		),
		'phtpb_admin_mode' => 'simple',
		'create_with_settings' => true,
	);


$phtpb_config_data['phtpb_wp_pages'] = array(
	'label' =>  'phtpb_wp_pages',
	'title' => esc_html__( 'WP -  Pages', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Pages',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'sortby' => array(
			'title' => esc_html__( 'Sort by', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'menu_order' => esc_html__( 'Page order', $this->plugin_name ),
				'post_title' => esc_html__( 'Page title', $this->plugin_name ),
				'ID' => esc_html__( 'Page ID', $this->plugin_name ),
			),
			'default' => 'menu_order',
		),
		'exclude' => array(
			'title' => esc_html__( 'Exclude', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'Page IDs, separated by commas.', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_wp_search'] = array(
	'label' =>  'phtpb_wp_search',
	'title' => esc_html__( 'WP -  Search', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Search',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_meta'] = array(
	'label' =>  'phtpb_wp_meta',
	'title' => esc_html__( 'WP -  Meta', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Meta',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_recent_comments'] = array(
	'label' =>  'phtpb_wp_recent_comments',
	'title' => esc_html__( 'WP -  Recent Comments', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Recent_Comments',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'number' => array(
			'title' => esc_html__( 'Number of comments to show', $this->plugin_name ),
			'type' => 'text',
			'default' => 5,
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_recent_posts'] = array(
	'label' =>  'phtpb_wp_recent_posts',
	'title' => esc_html__( 'WP -  Recent Posts', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Recent_Posts',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'number' => array(
			'title' => esc_html__( 'Number of posts to show', $this->plugin_name ),
			'type' => 'text',
			'default' => 5,
		),
		'show_date' => array(
			'title' => esc_html__( 'Display post date?', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$tag_taxonomies = array();
foreach ( get_taxonomies() as $taxonomy ) {
	$tax = get_taxonomy( $taxonomy );
	if ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) {
		continue;
	}
	$tag_taxonomies[esc_attr( $taxonomy )] = $tax->labels->name;
}

$phtpb_config_data['phtpb_wp_tagcloud'] = array(
	'label' =>  'phtpb_wp_tagcloud',
	'title' => esc_html__( 'WP -  Tags', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Tag_Cloud',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'taxonomy' => array(
			'title' => esc_html__( 'Taxonomy', $this->plugin_name ),
			'type' => 'select',
			'options' => $tag_taxonomies,
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$custom_menus = array();
$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
if ( is_array( $menus ) ) {
	foreach ( $menus as $single_menu ) {
		$custom_menus[ $single_menu->term_id ] = $single_menu->name;
	}
}

$phtpb_config_data['phtpb_wp_custom_menu'] = array(
	'label' =>  'phtpb_wp_custom_menu',
	'title' => esc_html__( 'WP -  Custom Menu', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Nav_Menu_Widget',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'nav_menu' => array(
			'title' => esc_html__( 'Select menu', $this->plugin_name ),
			'type' => 'select',
			'options' => $custom_menus,
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_archives'] = array(
	'label' =>  'phtpb_wp_archives',
	'title' => esc_html__( 'WP -  Archives', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Archives',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'dropdown' => array(
			'title' => esc_html__( 'Display as dropdown', $this->plugin_name ),
			'type' => 'checkbox',
		),
		'count' => array(
			'title' => esc_html__( 'Show post counts', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_categories'] = array(
	'label' =>  'phtpb_wp_categories',
	'title' => esc_html__( 'WP -  Categories', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Categories',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => esc_html__( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => esc_html__( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'dropdown' => array(
			'title' => esc_html__( 'Display as dropdown', $this->plugin_name ),
			'type' => 'checkbox',
		),
		'count' => array(
			'title' => esc_html__( 'Show post counts', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'hierarchical' => array(
			'title' => esc_html__( 'Show hierarchy', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$contact_forms = array();
if ( class_exists( 'WPCF7_ContactForm' ) ) {
	global $wpdb;
	$cf7 = $wpdb->get_results(
		"
	  SELECT ID, post_title
	  FROM $wpdb->posts
	  WHERE post_type = 'wpcf7_contact_form'
	  "
	);
	$contact_forms = array();
	if ( $cf7 ) {
		foreach ( $cf7 as $cform ) {
			$contact_forms[ $cform->ID ] = $cform->post_title;
		}
	} else {
		$contact_forms[0] = esc_html__( 'No contact forms found', $this->plugin_name );
	}
} // if contact form7 plugin active
$phtpb_config_data['phtpb_contact_form7'] = array(
	'label' =>  'phtpb_contact_form7',
	'title' => esc_html__( 'Contact Form', $this->plugin_name ),
	'icon' => 'fa fa-envelope',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_id' => array(
			'title' => 'Contact form',
			'type' => 'select',
			'options' => $contact_forms,
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true,
	'is_disabled' => ! class_exists( 'WPCF7_ContactForm' )
);

$phtpb_config_data = apply_filters( 'phtpb_config_data', $phtpb_config_data );

$phtpb_config_data_for_js = array();

foreach ( $phtpb_config_data as $key => $phtpb_config_data_element ) {
	
	foreach ( $phtpb_config_data_element as $field_key => $field_value ) {

		if ( in_array( $field_key, array( 'fields', 'icon' ) ) ) {
			continue;
		}
		$phtpb_config_data_for_js[ $key ][ $field_key ] = $field_value;
	}

	if ( isset( $phtpb_config_data_element['fields'] ) ) {
		if ( ! in_array( $key, array( 'phtpb_row', 'phtpb_row_inner', 'phtpb_column', 'phtpb_column_inner' ) ) ) {
			$phtpb_config_data[ $key ]['fields']['admin_label'] = array(
				'title' => esc_html__( 'Page Builder Label', $this->plugin_name ),
				'type' => 'text',
				'default' => $phtpb_config_data[ $key ]['title'],
				'description' => esc_html__( 'Add a custom label for your module.', $this->plugin_name ),
			);
		}
		if ( ! in_array( $key, array( 'phtpb_google_map', 'phtpb_marker', 'phtpb_tab' ) ) ) {
			$phtpb_config_data[ $key ]['fields']['module_class'] = $phtpb_module_class;
			$phtpb_config_data[ $key ]['fields']['module_id'] = $phtpb_module_id;
		}

	}
}