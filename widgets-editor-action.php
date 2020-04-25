<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**************************************************************************************
 *
 *EDITOR WIDGET ACTIONS
 *
 **************************************************************************************/
// See -> https://code.elementor.com/php-hooks/


add_action('elementor/element/common/_section_style/after_section_end', function ($element){


		$defaultValueName = 'tabs';
		$defaultValueProperty = [
			'tab_title:',
			'tab_content:'
		];

		htjfe_add_control_to_editor($element, $defaultValueName, $defaultValueProperty);

}, 1, 1);


/**************************************************************************************
	You can change this to be more fine in which widget will have this plugin activated.
	Here are some examples :
**************************************************************************************

add_action('elementor/element/toggle/section_toggle/after_section_end', function ($element)
{
	$defaultValueName = 'tabs';
	$defaultValueProperty = [
		'tab_title:',
		'tab_content:'
	];

	htjfe_add_control_to_editor($element, $defaultValueName, $defaultValueProperty);
}, 1, 1);

add_action( 'elementor/element/eae-timeline/timeline/after_section_end', function( $element ) {

	$defaultValueName = 'timeline_items';
	$defaultValueProperty = [
		'item_date:',
		'item_title_text:',
		'item_content:'
	];
	htjfe_add_control_to_editor($element, $defaultValueName, $defaultValueProperty);

}, 1, 1 );

add_action('elementor/element/heading/section_title/after_section_end', function ($element)
{
	$defaultValueName = 'timeline_items';
	$defaultValueProperty = [];
    htjfe_add_control_to_editor($element, $defaultValueName, $defaultValueProperty);

}, 1, 1);

*/


/**************************************************************************************
 *
 *EDITOR WIDGET FUNCTIONS
 *
 **************************************************************************************/
if (!function_exists('htjfe_add_control_to_editor')) {
	function htjfe_add_control_to_editor($element, $defaultValueName='', $defaultValueProperty=[])
	{
	    $debug_trace = false;

		$element->start_controls_section(
			'section_toggle_htjfe',
			[
				'label' => __( 'Happy Json Integration', 'htjfe' ),
			]
		);

		  $element->add_control(
			'ws_url',
			[
			  'label' => __( 'Set JSON URL', 'htjfe' ),
			  'type' => \Elementor\Controls_Manager::TEXT,
			  'placeholder' => __( 'Enter your url', 'htjfe' ),
			  'default' => __( '', 'htjfe' ),
			  'separator' => 'before',
			]
		  );

		  $element->add_control(
			'json_content',
			[
			  'label' => __( 'Set JSON Content', 'htjfe' ),
			  'type' => \Elementor\Controls_Manager::TEXTAREA,
			  'placeholder' => __( 'Enter your json content', 'htjfe' ),
			  'default' => __( '', 'htjfe' ),
			  'label_block' => true,
			]
		  );

			do_action( 'htjfe_action_editor_acf', $element );


		  $element->add_control(
			'phygital_widget_settings',
			[
			  'label' => __( 'Widget Settings Items', 'htjfe' ),
			  'type' => \Elementor\Controls_Manager::REPEATER,
			  'fields' => [
				[
				  'name' => 'widget_settings_name',
				  'label' => __( 'Widget Settings Name', 'htjfe' ),
				  'type' => \Elementor\Controls_Manager::TEXT,
				  'placeholder' => __( 'tabs', 'htjfe' ),
				  'default' => __( $defaultValueName, 'htjfe' ),
				  'label_block' => true,
				],
				[
				  'name' => 'widget_json_paths',
				  'label' => __( 'JSON paths', 'htjfe' ),
				  'type' => \Elementor\Controls_Manager::TEXT,
				  'placeholder' => __( 'root.element', 'htjfe' ),
				  'default' => __( '', 'htjfe' ),
				  'label_block' => true,
				],
				[
				  'name' => 'widget_json_array_todo',
				  'label' => __( 'JSON array instructions.<br/>For example tab_title:name and tab_content:email separated by EOL.', 'htjfe' ),
				  'type' => \Elementor\Controls_Manager::TEXTAREA,
				  'default' => __( implode(PHP_EOL, $defaultValueProperty), 'htjfe' ),
				  'label_block' => true,
				],
			  ],
			  'title_field' => '{{{ widget_settings_name }}}',
			]

		);

		  $element->add_control(
			'is_debug_js',
			[
			  'label' => __( 'Debug mode', 'htjfe' ),
			  'type' => \Elementor\Controls_Manager::SWITCHER,
			  'label_on' => __( 'Yes', 'htjfe' ),
			  'label_off' => __( 'No', 'htjfe' ),
			  'return_value' => 'yes',
			  'default' => 'no',
			]
		  );

		$element->end_controls_section();

	}
}



// The action callback function.
if (!function_exists('htjfe_add_editor_acf_free')) {
	function htjfe_add_editor_acf_free($element) {


		 $element->add_control(
			 'free_important_note',
			 [
				 'label' => __( 'Upgrade Pro !', 'htjfe' ),
				 'type' => \Elementor\Controls_Manager::RAW_HTML,
				 'raw' => __( 'And extend possibilities by using JSON data from ACF, Session or a custom backend functions.', 'elementor' ),
				 'separator' => 'before',
			 ]
		 );


	 $element->add_control(
		'free_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);


}

	add_action( 'htjfe_action_editor_acf', 'htjfe_add_editor_acf_free', 10 );

	if (function_exists('htjfe_add_editor_acf_pro')) remove_action( 'htjfe_action_editor_acf', 'htjfe_add_editor_acf_free', 10 );
}
