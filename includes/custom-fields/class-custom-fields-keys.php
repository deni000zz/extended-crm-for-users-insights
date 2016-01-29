<?php

if(!defined( 'ABSPATH' )) {
   exit;
}

class ECUI_Custom_Fields_Keys {
	
	protected $page_slug = 'usin_custom_fields';
	
	public function __construct() {
		$this->add_actions();
	}
	
	protected function add_actions() {
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets') );
		add_filter( 'usin_cf_options', array($this, 'filter_options'));
	}
	
	protected function is_custom_fields_page(){
		global $current_screen;
		return strpos( $current_screen->base, $this->page_slug ) !== false;
	}
	
	public function enqueue_assets(){
		if($this->is_custom_fields_page()){
			//enqueue JavaScript files
			
			wp_enqueue_script('ecui_custom_fields', 
				plugins_url('js/custom-fields.js', ECUI_PLUGIN_FILE), 
				array('usin_custom_fields', 'jquery'), 
				ECUI_VERSION);
		}

	}
	
	public function filter_options($options){
		
		$options['customTemplates'] = $this->add_keys_template($options['customTemplates']);
		
		$options['strings']['selectKey'] = __('OR Select existing meta key', 'extended-crm-for-users-insights');
		
		$options['ecuiKeyOptions'] = $this->get_user_meta_keys();
			
		return $options;
	}
	
	public function add_keys_template($templates){
		
		$key = 'after_key_field';
		$template = plugins_url('views/custom-fields/keys-select.html', ECUI_PLUGIN_FILE);
		
		if(isset($templates['after_key_field'])){
			$templates['after_key_field'][] = $template;
		}else{
			$templates['after_key_field'] = array($template);
		}
		
		return $templates;
	}
	
	protected function get_user_meta_keys(){
		global $wpdb;
		$meta_keys = $wpdb->get_col( "SELECT DISTINCT meta_key FROM $wpdb->usermeta ORDER BY meta_key ASC" );
		$meta_keys = array_filter($meta_keys, array($this, 'is_not_private_field'));
		$meta_keys = array_filter($meta_keys, array($this, 'is_not_core_field'));
		return $meta_keys;
	}
	
	public function is_not_private_field($field){
		return strpos($field, '_') !== 0;
	}
	
	public function is_not_core_field($field){
		if(method_exists('USIN_Custom_Fields_Options', 'is_key_wp_core_key')){
			return !USIN_Custom_Fields_Options::is_key_wp_core_key($field);
		}
	}
	
	
}