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
	'title' => __( 'Bottom margin', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => 'yes',
);
$lightbox_item = array(
	'title' => __( 'Open in Lightbox', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
	'description' => __( 'Check here if you want your image to open in a lightbox.', $this->plugin_name )
);

$target_item = array(
	'title' => __( 'Link target', $this->plugin_name ),
	'type' => 'select',
	'options' => array(
		'_self' => __( 'Same window', $this->plugin_name ),
		'_blank' => __( 'New window', $this->plugin_name ),
	),
	'default' => '_self',
	'description' => __( 'Choose whether or not your link opens in a new window.', $this->plugin_name ),
);

$use_bg_color_item = array(
	'title' => __( 'Use custom background color', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
);
$bg_color_item = array(
	'title' => __( 'Background Color', $this->plugin_name ),
	'type' => 'color',
	'default' => $default_bg_color,
);
$use_color_item = array(
	'title' => __( 'Use custom text color', $this->plugin_name ),
	'type' => 'checkbox',
	'default' => '',
);
$color_item = array(
	'title' => __( 'Text Color', $this->plugin_name ),
	'type' => 'color',
	'default' => $default_color,
);

$phtpb_module_class = array(
	'title' => __( 'Class', $this->plugin_name ),
	'type' => 'text',
	'description' => __( 'Insert CSS classes separated by single spaces.', $this->plugin_name ),
	'default' => '',
);
$phtpb_module_id = array(
	'title' => __( 'ID', $this->plugin_name ),
	'type' => 'text',
	'description' => __( 'Insert a unique ID fot this module.', $this->plugin_name ),
	'default' => '',
);

$phtpb_config_data['phtpb_section'] = array(
	'label' => 'phtpb_section',
	'title' => __( 'Section', $this->plugin_name ),
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
			'title' => __( 'Top and bottom padding', $this->plugin_name ),
			'type' => 'select',
			'default' => 'none',
			'options' => array(
				'none' => __( 'None', $this->plugin_name ),
				'normal' => __( 'Normal, 48px', $this->plugin_name ),
				'big' => __( 'Big, 96px', $this->plugin_name ),
				'huge' => __( 'Huge, 160px', $this->plugin_name ),
				'giant' => __( 'Giant, 264px', $this->plugin_name ),
			),
		),
		'background_image' => array(
			'title' => __( 'Background Image', $this->plugin_name ),
			'type' => 'image',
			'description' => __( 'Upload the background image.', $this->plugin_name ),
			'default' => '',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
			'default' => '',
		),
		'opacity' => array(
			'title' => __( 'Image Opacity', $this->plugin_name ),
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
			'title' => __( 'Background Width', $this->plugin_name ),
			'description' => __( 'Resize width, auto is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => __( 'Auto (cover)', $this->plugin_name ),
		),
		'r_h' => array(
			'title' => __( 'Background Height', $this->plugin_name ),
			'description' => __( 'Resize height, auto is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => __( 'Auto (cover)', $this->plugin_name ),
		),
		'phtpb_type' => array(
			'title' => __( 'Small screens', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'colors' => __( 'Keep the custom background and text colors', $this->plugin_name ),
				'inherit' => __( 'Use the default colors', $this->plugin_name ),
				'force' => __(  'Force the background image to be displayed', $this->plugin_name ),
			),
			'description' => __( 'Define how this section is displayed on small screens (<800px).', $this->plugin_name ),
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
		'title' => __( 'Background Opacity', $this->plugin_name ),
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
		'title' => __( 'Rounded corners', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => __( 'No rounded corners', $this->plugin_name ),
			'2' => __( '2px - very subtle', $this->plugin_name ),
			'3' => __( '3px - subtle', $this->plugin_name ),
			'5' => __( '5px', $this->plugin_name ),
			'10' => __( '10px', $this->plugin_name ),
		),
		'default' => 'none',
	),
	'border_style' => array(
		'title' => __( 'Border style', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => __( 'No border', $this->plugin_name ),
			'solid' => __( 'Solid', $this->plugin_name ),
			'dashed' => __( 'Dashed', $this->plugin_name ),
			'dotted' => __( 'Dotted', $this->plugin_name ),
			'double' => __( 'Double', $this->plugin_name ),
		),
		'default' => 'none',
	),
	'border_width' => array(
		'title' => __( 'Border width', $this->plugin_name ),
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
		'title' => __( 'Margins between border and content',  $this->plugin_name ),
		'type' => 'checkbox',
		'default' => ''
	),
	'use_border_color' => array(
		'title' => __( 'Use custom border color', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => '',
	),
	'border_color' => array(
		'title' => __( 'Border Color', $this->plugin_name ),
		'type' => 'color',
		'default' => $default_color,
	),
	'padding' => array(
		'title' => __( 'Padding', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => __( 'None', $this->plugin_name ),
			'box--tiny' => __( 'Third (8px)', $this->plugin_name ),
			'box--small' => __( 'Half (12px)', $this->plugin_name ),
			'box' => __( 'Normal (24px)', $this->plugin_name ),
			'box--large' => __( 'Large (32px)', $this->plugin_name ),
			'box--huge' => __( 'Huge (48px)', $this->plugin_name ),
		),
		'default' => 'none',
		'description' => __( 'Padding arround the column content.', $this->plugin_name ),
	),
	'layout' => array(
		'type' => 'hidden_2_skip'
	),
	'animation' => array(
		'title' => __( 'Fade-in Animation', $this->plugin_name ),
		'type' => 'select',
		'options' => array(
			'none' => __( 'None', $this->plugin_name ),
			'l2r' => __( 'Left to Right', $this->plugin_name ),
			'r2l' => __( 'Right to Left', $this->plugin_name ),
			'b2t' => __( 'Bottom to Top', $this->plugin_name ),
			't2b' => __( 'Top to Bottom', $this->plugin_name ),
			'fade' => __( 'Fade-in', $this->plugin_name ),
			'scaleinx' => __( 'Scale-in Horizontally', $this->plugin_name ),
			'scaleiny' => __( 'Scale-in Vertically', $this->plugin_name ),
			'scalein' => __( 'Scale-in', $this->plugin_name ),
		),
		'default' => 'none',
		'description' => __( 'A subtle fade-in effect. Please use them with moderation.', $this->plugin_name )
	),
	'valign' => array(
		'title' => __( 'Vertical Align', $this->plugin_name ),
		'type' => 'select',
		'default' => 'top',
		'options' => array(
			'top' => __( 'Top', $this->plugin_name ),
			'center' => __( 'Center', $this->plugin_name ),
			'bottom' => __( 'Bottom', $this->plugin_name ),
		),
		'description' => __( 'If <i>Equal Columns</i> are checked in row settings you can choose the vertical alignment for the column. This feature only works in modern browsers and is enabled for screens at least 1280px wide. ', $this->plugin_name ),
	),
);

$row_settings = array(
	'admin_collapsed' => array(
		'type' => 'hidden',
		'default' => false
	),
	'gutter' => array(
		'title' => __( 'Gutters', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => 'yes',
		'description' => __( 'If checked, columns are separated by vertical whitespaces.', $this->plugin_name ),
	),
	'equals' => array(
		'title' => __( 'Equal columns', $this->plugin_name ),
		'type' => 'checkbox',
		'default' => '',
		'description' => __( 'If checked, columns will have equal heights - use it if you apply a background color or borders to the columns. Equals heights have to be checked if you want change the vertical align of your column. Note that this feature only works in modern browsers and is enabled for screens at least 1280px wide.', $this->plugin_name ),
	),
	'wrapper' => array(
		'title' => __( 'Wrapper', $this->plugin_name ),
		'type' => 'select',
		'default' => 'normal',
		'options' => array(
			'normal' => __( '1140px', $this->plugin_name ),
			'none' => __( 'Full width', $this->plugin_name ),
			'none-12' => __( 'Full width with 12px margins', $this->plugin_name ),
			'none-24' => __( 'Full width with 24px margins', $this->plugin_name ),
			'none-48' => __( 'Full width with 48px margins', $this->plugin_name ),
			'none-96' => __( 'Full width with 96px margins', $this->plugin_name ),
		),
		'description' => __( 'You can switch between boxed and stretched layout of each row. The margin values correspond to the left and right margins.', $this->plugin_name ),
	),
);

$phtpb_config_data['phtpb_row'] = array(
	'label' => 'phtpb_row',
	'title' => __( 'Row', $this->plugin_name ),
	'phtpb_admin_type' => 'row',
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => false,
	'fields' => $row_settings
);
$phtpb_config_data['phtpb_row_inner'] = array(
	'label' => 'phtpb_row_inner',
	'title' => __( 'Row', $this->plugin_name ),
	'phtpb_admin_type' => 'row',
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => false,
	'fields' => $row_settings
);
$phtpb_config_data['phtpb_column'] = array(
	'label' => 'phtpb_column',
	'title' => __( 'Column', $this->plugin_name ),
	'phtpb_admin_type' => 'column',
	'phtpb_admin_mode' => 'simple',
	'fields' => $column_settings
);
$phtpb_config_data['phtpb_column_inner'] = array(
	'label' => 'phtpb_column_inner',
	'title' => __( 'Column', $this->plugin_name ),
	'phtpb_admin_type' => 'column',
	'phtpb_admin_mode' => 'simple',
	'fields' => $column_settings
);


// *************************** MODULES *************************** //

$phtpb_config_data['phtpb_text'] = array(
	'label' =>  'phtpb_text',
	'title' => __( 'Text', $this->plugin_name ),
	'icon' => 'fa fa-file-text-o',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_content_new' => array(
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is the main content', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_image'] = array(
	'label' =>  'phtpb_image',
	'title' => __( 'Image', $this->plugin_name ),
	'icon' => 'fa fa-image',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'src' => array(
			'title' => __( 'Image Source', $this->plugin_name ),
			'type' => 'image',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
		),
		'resize' => array(
			'title' => __( 'Resize', $this->plugin_name ),
			'description' => __( 'Resize to fit the column size (recommended).', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => 'yes'
		),
		'r_w' => array(
			'title' => __( 'Resize Width', $this->plugin_name ),
			'description' => __( 'Resize width, auto (or leave empty) is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => __( 'Auto', $this->plugin_name ),
		),
		'r_h' => array(
			'title' => __( 'Resize Height', $this->plugin_name ),
			'description' => __( 'Resize height, auto (or leave empty) is recommended.', $this->plugin_name ),
			'type' => 'text',
			'default' => __( 'Auto', $this->plugin_name ),
		),
		'd_w' => array(
			'title' => __( 'Display Width', $this->plugin_name ),
			'description' => __( 'You can change here the width that will be use to display the image. If left empty (recommended) the default value will be applied.', $this->plugin_name ),
			'type' => 'text',
			'default' => __( '', $this->plugin_name ),
		),
		'link_type' => array(
			'title' => __( 'Link Type', $this->plugin_name ),
			'type' => 'select',
			'default' => 'none',
			'options' => array(
				'none' => __( 'None', $this->plugin_name ),
				'lightbox' => __( 'Lightbox', $this->plugin_name ),
				'url' => __( 'URL', $this->plugin_name ),
			),
			'description' => __( '', $this->plugin_name )
		),
		'link' => array(
			'title' => __( 'Link URL', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( '(Optional) If you would like your image to be a link, input your destination URL here.', $this->plugin_name )
		),
		'target' => $target_item,
		'h_align' => array(
			'title' => __( 'Image alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => __( 'Center', $this->plugin_name ),
				'left' => __( 'Left', $this->plugin_name ),
				'right' => __( 'Right', $this->plugin_name ),
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
	'title' => __( 'Gallery', $this->plugin_name ),
	'icon' => 'fa fa-trello',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_ids' => array(
			'title' => __( 'Image Source', $this->plugin_name ),
			'type' => 'gallery',
		),
		'border_width' => array(
			'title' => __( 'Images separation', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'6' => '6px',
				'0' => __( 'No separation', $this->plugin_name ),
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
	'title' => __( 'Buttons block', $this->plugin_name ),
	'icon' => 'fa fa-square-o',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_btn',
	'fields' => array(
		'h_align' => array(
			'title' => __( 'Buttons alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => __( 'Center', $this->plugin_name ),
				'left' => __( 'Left', $this->plugin_name ),
				'right' => __( 'Right', $this->plugin_name ),
			),
			'default' => 'center',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Button', $this->plugin_name ),
);

$phtpb_config_data['phtpb_btn'] = array(
	'label' => 'phtpb_btn',
	'title' => __( 'Button', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => __( 'Button Text', $this->plugin_name ),
			'type' => 'text',
		),
		'icon' => array(
			'title' => __( 'Icon', $this->plugin_name ),
			'type' => 'icons',
		),
		'link' => array(
			'title' => __( 'URL', $this->plugin_name ),
			'type' => 'text',
			'description' => __( 'Input your destination URL here.', $this->plugin_name ),
			'default' => '',
		),
		'target' => $target_item,
		'use_color' => $use_color_item,
		'color' => $color_item,
		'border_radius' => array(
			'title' => __( 'Rounded corners', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'none' => __( 'No rounded corners', $this->plugin_name ),
				'2' => __( '2px - very subtle', $this->plugin_name ),
				'3' => __( '3px - subtle', $this->plugin_name ),
				'5' => __( '5px', $this->plugin_name ),
				'10' => __( '10px', $this->plugin_name ),
			),
			'default' => 'none',
		),
		'skip' => array(
			'title' => __( 'Full-width button', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Button', $this->plugin_name ),
);


$phtpb_config_data['phtpb_icons'] = array(
	'label' => 'phtpb_icons',
	'title' => __( 'Icons block', $this->plugin_name ),
	'icon' => 'fa fa-circle-o',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_icon',
	'fields' => array(
		'h_align' => array(
			'title' => __( 'Icons alignment', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'center' => __( 'Center', $this->plugin_name ),
				'left' => __( 'Left', $this->plugin_name ),
				'right' => __( 'Right', $this->plugin_name ),
			),
			'default' => 'center',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Icon', $this->plugin_name ),
);

$phtpb_config_data['phtpb_icon'] = array(
	'label' => 'phtpb_icon',
	'title' => __( 'Icon', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'icon' => array(
			'title' => __( 'Icon', $this->plugin_name ),
			'type' => 'icons',
		),
		'phtpb_type' => array(
			'title' => __( 'Icon size', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'normal' => __( 'Normal', $this->plugin_name ),
				'tiny' => __( 'Tiny', $this->plugin_name ),
				'large' => __( 'Large', $this->plugin_name ),
				'huge' => __( 'Huge', $this->plugin_name ),
			),
			'default' => 'normal'
		),
		'link' => array(
			'title' => __( 'Link URL', $this->plugin_name ),
			'type' => 'text',
			'description' => __( '(Optional) If you want your icon to be a link, input your destination URL here.', $this->plugin_name ),
			'default' => '',
		),
		'target' => $target_item,
		'use_bg_color' => $use_bg_color_item,
		'bg_color' => $bg_color_item,
		'use_color' =>  array(
			'title' => __( 'Use custom color', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
		),
		'color' => $color_item = array(
			'title' => __( 'Color', $this->plugin_name ),
			'type' => 'color',
			'default' => $default_color,
		),
		'skip' => array(
			'title' => __( 'No border', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => ''
		),
		'border_radius' => array(
			'title' => __( 'Rounded corners', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'pill' => __( 'Circle', $this->plugin_name ),
				'2' => __( '2px - very subtle', $this->plugin_name ),
				'3' => __( '3px - subtle', $this->plugin_name ),
				'5' => __( '5px', $this->plugin_name ),
				'10' => __( '10px', $this->plugin_name ),
				'none' => __( 'None', $this->plugin_name ),
			),
			'default' => 'pill',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Icon', $this->plugin_name ),
);



$phtpb_config_data['phtpb_tabs'] = array(
	'label' => 'phtpb_tabs',
	'title' => __( 'Tabs', $this->plugin_name ),
	'icon' => 'fa fa-list-alt',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_tab',
	'fields' => array(
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Tab', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tab'] = array(
	'label' => 'phtpb_tab',
	'title' => __( 'Tab', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => __( 'Tab Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_content_new' => array(
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Tab', $this->plugin_name ),
);

$phtpb_config_data['phtpb_cslider'] = array(
	'label' => 'phtpb_cslider',
	'title' => __( 'Content Slider', $this->plugin_name ),
	'icon' => 'fa fa-exchange',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_cslide',
	'fields' => array(
		'margin_b' => $margin_b_item,
		'auto' => array(
			'title' => __( 'Auto slideshow', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => __( 'Animate slider automatically.', $this->plugin_name )
		),
		'anim' => array(
			'title' => __( 'Animation type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'fade' => __( 'Fade', $this->plugin_name ),
				'slide' => __( 'Slide', $this->plugin_name ),
			),
			'default' => 'fade',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Slide', $this->plugin_name ),
);
$phtpb_config_data['phtpb_cslide'] = array(
	'label' => 'phtpb_cslide',
	'title' => __( 'Content Slide', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'phtpb_content_new' => array(
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Slide', $this->plugin_name ),
);

$phtpb_config_data['phtpb_slider'] = array(
	'label' => 'phtpb_slider',
	'title' => __( 'Slider', $this->plugin_name ),
	'icon' => 'fa fa-exchange',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_slide',
	'fields' => array(
		'hoption' => array(
			'title' => __( 'Slider Height', $this->plugin_name ),
			'type' => 'select',
			'default' => 'full',
			'options' => array(
				'full' => __( 'Full window height', $this->plugin_name ),
				'480' => '480px',
				'600' => '600px',
			),
			'description' => __( 'Animate slider automatically.', $this->plugin_name )
		),
		'auto' => array(
			'title' => __( 'Auto slideshow', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => __( 'Animate slider automatically.', $this->plugin_name )
		),
		'anim' => array(
			'title' => __( 'Animation type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'fade' => __( 'Fade', $this->plugin_name ),
				'slide' => __( 'Slide', $this->plugin_name ),
			),
			'default' => 'fade',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Slide', $this->plugin_name ),
);

$phtpb_config_data['phtpb_slide'] = array(
	'label' => 'phtpb_slide',
	'title' => __( 'Slide', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
	'background_image' => array(
			'title' => __( 'Background Image', $this->plugin_name ),
			'type' => 'image',
			'description' => __( 'Upload the background image.', $this->plugin_name ),
			'default' => '',
		),
		'phtpb_id' => array(
			'type' => 'image_id',
			'default' => '',
		),
		'opacity' => array(
			'title' => __( 'Image Opacity', $this->plugin_name ),
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
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is your tab content.', $this->plugin_name ),
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Slide', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tlist'] = array(
	'label' => 'phtpb_tlist',
	'title' => __( 'Tabbled List', $this->plugin_name ),
	'icon' => 'fa fa-list-ul',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_tlist_item',
	'fields' => array(
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Item', $this->plugin_name ),
);
$phtpb_config_data['phtpb_tlist_item'] = array(
	'label' => 'phtpb_tlist_item',
	'title' => __( 'Tabbled List Item', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => __( 'List item title', $this->plugin_name ),
			'type' => 'text',
		),
		'item_subtitle' => array(
			'title' => __( 'List item description', $this->plugin_name ),
			'type' => 'text',
		),
		'item_attribute' => array(
			'title' => __( 'List item attribute (e.g. price)', $this->plugin_name ),
			'type' => 'text',
		),
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Item', $this->plugin_name ),
);

$phtpb_config_data['phtpb_accordion'] = array(
	'label' => 'phtpb_accordion',
	'title' => __( 'Accordion', $this->plugin_name ),
	'icon' => 'fa fa-sort',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_ac_tab',
	'fields' => array(
		'collapsible' => array(
			'title' => __( 'Collapsible', $this->plugin_name ),
			'type' => 'checkbox',
			'default' => '',
			'description' => __( 'Allows collapsing the active tab (all tabs can be closed).', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true,
	'add_submodule' => __( 'Add Tab', $this->plugin_name ),
);

$phtpb_config_data['phtpb_ac_tab'] = array(
	'label' => 'phtpb_ac_tab',
	'title' => __( 'Tab', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'title' => array(
			'title' => __( 'Tab Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_content_new' => array(
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is the accordion content', $this->plugin_name ),
		),
	),
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_toggle'] = array(
	'label' => 'phtpb_toggle',
	'title' => __( 'Toggle', $this->plugin_name ),
	'icon' => 'fa fa-sort',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'simple',
	'fields' => array(
		'title' => array(
			'title' => __( 'Toggle Title', $this->plugin_name ),
			'type' => 'text',
		),
		'phtpb_type' => array(
			'title' => __( 'Initial state', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'on' => __( 'Opened', $this->plugin_name ),
				'off' => __( 'Closed', $this->plugin_name ),
			),
			'default' => 'on',
			'description' => __( 'Choose if you want your toggle section to be opened or closed by default.', $this->plugin_name ),
		),
		'phtpb_content_new' => array(
			'title' => __( 'Content', $this->plugin_name ),
			'type' => 'wysiwyg',
			'description' => __( 'This is the toggle content', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_google_map'] = array(
	'label' => 'phtpb_google_map',
	'title' => __( 'Google Map', $this->plugin_name ),
	'icon' => 'fa  fa-map-marker',
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'parent',
	'child' => 'phtpb_marker',
	'fields' => array(
		'map_address_center' => array(
			'title' => __( 'Map Center', $this->plugin_name ),
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
			'title' => __( 'Map styling', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'default' => __( 'Default', $this->plugin_name ),
				'desaturate' => __( 'Grayscale', $this->plugin_name ),
				'ultralight' => __( 'Very Light', $this->plugin_name ),
				'darkgreys' => __( 'Dark Shades of Grey', $this->plugin_name ),
				'beigeblue' => __( 'Light Beige and Blue', $this->plugin_name ),
			),
			'default' => 'default', 
			'description' => __( 'Choose colors variant for your map. Note that the admin area displays the default colors, you need to preview the page to see your changes.', $this->plugin_name ),
		),
		'height' => array(
			'title' => __( 'Height', $this->plugin_name ),
			'type' => 'text',
			'description' => __( 'Set the height of map (in pixels)', $this->plugin_name ),
			'default' => '400'
		),
		'margin_b' => $margin_b_item,
	),
	'add_submodule' => __( 'Add Marker', $this->plugin_name ),
	'create_with_settings' => true
);
$phtpb_config_data['phtpb_marker'] = array(
	'label' => 'phtpb_marker',
	'title' => __( 'Map Marker', $this->plugin_name ),
	'phtpb_admin_type' => 'module',
	'phtpb_admin_mode' => 'advanced_twin',
	'fields' => array(
		'map_address_marker' => array(
			'title' => __( 'Add Marker', $this->plugin_name ),
			'type' => 'find_address',
		),
		'title' => array(
			'title' => __( 'Marker Title', $this->plugin_name ),
			'type' => 'text',
		),
		'src' => array(
			'title' => __( 'Image Source', $this->plugin_name ),
			'type' => 'image',
			'description' => __( 'You can add a custom icon for this marker. Make sure to upload it double sized as it will be scaled down for crispy retina displays. (Eg. if you want your marker icon to be 24px x 48px upload a 48px x 96px image). Note that the admin area displays the default markers, you need to preview the page to see your changes.', $this->plugin_name ),
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
	'title' => __( 'Divider', $this->plugin_name ),
	'icon' => 'fa fa-arrows-v',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'height' => array(
			'title' => __( 'Height', $this->plugin_name ),
			'type' => 'text',
			'description' => __( 'Set the height of your divider (in pixels).', $this->plugin_name ),
			'default' => '48'
		),
		'phtpb_type' => array(
			'title' => __( 'Divider Type', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'none' => __( 'None', $this->plugin_name ),
				'single1' => __( 'Solid line (1px)', $this->plugin_name ),
				'single2' => __( 'Solid line (2px)', $this->plugin_name ),
				'double' => __( 'Double line', $this->plugin_name ),
				'dashed' => __( 'Dashed line', $this->plugin_name ),
			),
			'default' => 'none',
		),
		'position' => array(
			'title' => __( 'Position', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'middle' => __( 'Middle', $this->plugin_name ),
				'top' => __( 'Top', $this->plugin_name ),
				'bottom' => __( 'Bottom', $this->plugin_name ),
			),
			'default' => 'middle',
		),
		'hide' => array(
			'title' => __( 'Screen options.', $this->plugin_name ),
			'type' => 'select',
			'default' => 'never',
			'options' => array(
				'never' => __( 'Always display', $this->plugin_name ),
				'palm' => __( 'Only on screens 800px and more', $this->plugin_name ),
			),
			'description' => __( 'Note that on small screens the divider height will be reduced to 48px maximum.', $this->plugin_name )
		),
		'use_color' => $use_color_item,
		'color' => $color_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$options_array = array();
$posts_post_types = apply_filters( 'phtpb_available_post_types', array() );
$options_array = apply_filters( 'phtpb_posts_available_post_types', array_merge( array( 'post' => 'Posts' ), $posts_post_types ) );

$phtpb_config_data['phtpb_posts'] = array(
	'label' =>  'phtpb_posts',
	'title' => __( 'Posts Grid', $this->plugin_name ),
	'icon' => 'fa fa-th-large',
	'phtpb_admin_type' => 'module',
	'fields' => array(),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true,
	'pht_themes_only' => true,
);

$options_array = array();
$gallery_post_types = apply_filters( 'phtpb_available_post_types', array() );
$options_array = apply_filters( 'phtpb_gallery_available_post_types', array_merge( array( 'post' => 'Posts' ), $gallery_post_types ) );

$phtpb_config_data['phtpb_gallery_portfolio'] = array(
	'label' =>  'phtpb_gallery_portfolio',
	'title' => __( 'Gallery Portfolio', $this->plugin_name ),
	'icon' => 'fa fa-trello',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_type' => array(
			'title' => __( 'Post type', $this->plugin_name ),
			'type' => 'select',
			'options' => $options_array,
			'description' => __( 'Choose the query.', $this->plugin_name ),
		),
		'count' => array(
			'title' => __( 'Posts count', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'Number of posts. Set -1 to display all posts.', $this->plugin_name ),
		),
		'phtpb_query' => array(
			'title' => __( 'Custom query', $this->plugin_name ),
			'type' => 'text',
			'description' => __( 'You can modify the query here, e.g. orderby=title&order=ASC', $this->plugin_name ),
		),
		'border_width' => array(
			'title' => __( 'Images separation', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'6' => __( '6px - default', $this->plugin_name ),
				'0' => __( 'No separation', $this->plugin_name ),
				'16' => __( '16px', $this->plugin_name ),
				'24' => __( '24px - large separation', $this->plugin_name ),
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
	'title' => __( 'Filtered Portfolio', $this->plugin_name ),
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
	'title' => __( 'Sidebar', $this->plugin_name ),
	'icon' => 'fa fa-tags',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'phtpb_id' => array(
			'title' => __( 'Choose sidebar', $this->plugin_name ),
			'type' => 'select',
			'options' => apply_filters( 'phtpb_available_sidebars', $sidebars )
		),
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_wp_calendar'] = array(
		'label' =>  'phtpb_wp_calendar',
		'title' => __( 'WP -  Calendar', $this->plugin_name ),
		'icon' => 'fa fa-wordpress',
		'widget' => 'WP_Widget_Calendar',
		'phtpb_admin_type' => 'module',
		'fields' => array(
			'title' => array(
				'title' => __( 'Title', $this->plugin_name ),
				'type' => 'text',
				'default' => '',
				'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
			),
			'margin_b' => $margin_b_item,
		),
		'phtpb_admin_mode' => 'simple',
		'create_with_settings' => true,
	);


$phtpb_config_data['phtpb_wp_pages'] = array(
	'label' =>  'phtpb_wp_pages',
	'title' => __( 'WP -  Pages', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Pages',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'sortby' => array(
			'title' => __( 'Sort by', $this->plugin_name ),
			'type' => 'select',
			'options' => array(
				'menu_order' => __( 'Page order', $this->plugin_name ),
				'post_title' => __( 'Page title', $this->plugin_name ),
				'ID' => __( 'Page ID', $this->plugin_name ),
			),
			'default' => 'menu_order',
		),
		'exclude' => array(
			'title' => __( 'Exclude', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'Page IDs, separated by commas.', $this->plugin_name ),
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);


$phtpb_config_data['phtpb_wp_search'] = array(
	'label' =>  'phtpb_wp_search',
	'title' => __( 'WP -  Search', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Search',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_meta'] = array(
	'label' =>  'phtpb_wp_meta',
	'title' => __( 'WP -  Meta', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Meta',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_recent_comments'] = array(
	'label' =>  'phtpb_wp_recent_comments',
	'title' => __( 'WP -  Recent Comments', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Recent_Comments',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'number' => array(
			'title' => __( 'Number of comments to show', $this->plugin_name ),
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
	'title' => __( 'WP -  Recent Posts', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Recent_Posts',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'number' => array(
			'title' => __( 'Number of posts to show', $this->plugin_name ),
			'type' => 'text',
			'default' => 5,
		),
		'show_date' => array(
			'title' => __( 'Display post date?', $this->plugin_name  ),
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
	'title' => __( 'WP -  Tags', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Tag_Cloud',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'taxonomy' => array(
			'title' => __( 'Taxonomy', $this->plugin_name ),
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
	'title' => __( 'WP -  Custom Menu', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Nav_Menu_Widget',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'nav_menu' => array(
			'title' => __( 'Select menu', $this->plugin_name ),
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
	'title' => __( 'WP -  Archives', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Archives',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'dropdown' => array(
			'title' => __( 'Display as dropdown', $this->plugin_name ),
			'type' => 'checkbox',
		),
		'count' => array(
			'title' => __( 'Show post counts', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'margin_b' => $margin_b_item,
	),
	'phtpb_admin_mode' => 'simple',
	'create_with_settings' => true
);

$phtpb_config_data['phtpb_wp_categories'] = array(
	'label' =>  'phtpb_wp_categories',
	'title' => __( 'WP -  Categories', $this->plugin_name ),
	'icon' => 'fa fa-wordpress',
	'widget' => 'WP_Widget_Categories',
	'phtpb_admin_type' => 'module',
	'fields' => array(
		'title' => array(
			'title' => __( 'Title', $this->plugin_name ),
			'type' => 'text',
			'default' => '',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', $this->plugin_name )
		),
		'dropdown' => array(
			'title' => __( 'Display as dropdown', $this->plugin_name ),
			'type' => 'checkbox',
		),
		'count' => array(
			'title' => __( 'Show post counts', $this->plugin_name  ),
			'type' => 'checkbox',
		),
		'hierarchical' => array(
			'title' => __( 'Show hierarchy', $this->plugin_name  ),
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
		$contact_forms[0] = __( 'No contact forms found', $this->plugin_name );
	}
} // if contact form7 plugin active
$phtpb_config_data['phtpb_contact_form7'] = array(
	'label' =>  'phtpb_contact_form7',
	'title' => __( 'Contact Form', $this->plugin_name ),
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
				'title' => __( 'Page Builder Label', $this->plugin_name ),
				'type' => 'text',
				'default' => $phtpb_config_data[ $key ]['title'],
				'description' => __( 'Add a custom label for your module.', $this->plugin_name ),
			);
		}
		if ( ! in_array( $key, array( 'phtpb_google_map', 'phtpb_marker', 'phtpb_tab' ) ) ) {
			$phtpb_config_data[ $key ]['fields']['module_class'] = $phtpb_module_class;
			$phtpb_config_data[ $key ]['fields']['module_id'] = $phtpb_module_id;
		}

	}
}