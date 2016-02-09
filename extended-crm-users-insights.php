<?php
/**
 * Plugin Name: Extended CRM For Users Insights
 * Description: Extends the CRM functionality of Users Insights
 * Version: 1.1.0
 * Author: denizz
 * Text Domain: extended-crm-for-users-insights
 * License: GPLv2 or later
 * Requires at least: 4.4
 */

 if(!defined( 'ABSPATH' )) {
 	exit;
 }
 /**
  * Includes the main plugin initialization functionality.
  */
 class ECUI_Initializer {
	 
	 protected $requires_usin_version = '2.0.1';
	 protected $capability = 'list_users';
	 protected $usin_page_slug = 'users_insights';
	 
	 /**
	  * Registers the required hooks.
	  */
	 public function __construct() {
		 add_action('admin_init', array($this, 'init'));
		 add_action('plugins_loaded', array($this, 'load_textdomain'));
	 }
	 
	 /**
	  * Initializes the plugin.
	  */
	 public function init() {
		if(!is_admin()){
		 //this plugin runs in the admin only
			 return;
		}

		if(!$this->check_requirements()){
			 return;
		}

		global $usin;

		if(isset($usin->manager)){
			if(isset($usin->manager->capability)){
				$this->capability = $usin->manager->capability;
			}
			if(isset($usin->manager->slug)){
				$this->usin_page_slug = $usin->manager->slug;
			}
		}

		$this->include_files();
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets'));

		if ( ! defined( 'ECUI_PLUGIN_FILE' ) ) {
			 define( 'ECUI_PLUGIN_FILE', __FILE__);
		}
		if ( ! defined( 'ECUI_VERSION' ) ) {
			 define( 'ECUI_VERSION', '1.1.0');
		}

		$cfk = new ECUI_Custom_Fields_Keys();
		$gi = new ECUI_Group_Icons();
		$sn = new ECUI_Sticky_Notes($this->usin_page_slug, $this->capability);
		new ECUI_Note_Fields();
		 
	 }
	 
	 /**
	  * Checks whether Users Insights is activated and checks against the
	  * minimum version required.
	  * @return boolean true if Users Insights is activated and its version is
	  * equal or bigger than the required version and false otherwise.
	  */
	 protected function check_requirements(){
		 
		 if(!is_plugin_active('users-insights/users-insights.php')){
			 add_action( 'admin_notices', array($this,'show_plugin_inactive_notice'));
			 return false;
		 }
		 
		 if(defined('USIN_VERSION')){
			 if(!version_compare(USIN_VERSION, $this->requires_usin_version, '>=')){
				 add_action( 'admin_notices', array($this,'show_version_required_notice'));
				 return false;
			 }
		 }
		 
		 return true;
		 
	 }
	 
	 /**
	  * Checks whether the current page is the main Users Insights page.
	  * @return boolean true if it is the Users Insights page and false otherwise
	  */
	 protected function is_user_list_page(){
		 global $current_screen;

		 return strpos( $current_screen->base, $this->usin_page_slug ) !== false;
	 }
	 
	 /**
	  * Enqueues the required assets.
	  */
	 public function enqueue_assets(){
		 if($this->is_user_list_page()){
 			wp_enqueue_script('ecui_user_list_js', 
 				plugins_url('js/user-list.js', ECUI_PLUGIN_FILE), 
 				array('usin_user_list'), 
 				ECUI_VERSION);
 			
 			wp_enqueue_style( 'ecui_font_awesome', 
 					plugins_url('css/font-awesome.min.css', ECUI_PLUGIN_FILE ), array(), ECUI_VERSION );
			
			wp_enqueue_style( 'ecui_user_list_css', 
 					plugins_url('css/user-list.css', ECUI_PLUGIN_FILE ), array('usin_main_css', 'ecui_font_awesome'), ECUI_VERSION );
 		}

	 }
	 
	 /**
	  * Adds an admin notice when the Users Insights plugin is not active.
	  */
	 public function show_plugin_inactive_notice(){
		?>
	    <div class="notice update-nag">
	        <p><?php _e( 'Extended CRM For Users Insights requires the <a href="https://usersinsights.com/" target="_blank">Users Insights plugin</a> to be active.', 'extended-crm-for-users-insights' ); ?></p>
	    </div>
	    <?php
	 }
	 
	 /**
	  * Adds an admin notice when the currently installed version of Users Insights
	  * is smaller than the required verion.
	  */
	 public function show_version_required_notice(){
		 ?>
 	    <div class="notice update-nag">
 	        <p><?php printf(__( 'Extended CRM For Users Insights requires Users Insights version %s or later.', 'extended-crm-for-users-insights' ), 
				$this->requires_usin_version); ?></p>
 	    </div>
 	    <?php
	 }
	 
	 /**
	  * Includes the required PHP files.
	  */
	 protected function include_files() {
		 include_once( 'includes/custom-fields/class-custom-fields-keys.php' );
		 include_once( 'includes/group-icons/class-group-icons.php' );
		 include_once( 'includes/notes/class-sticky-notes.php' );
		 include_once( 'includes/notes/class-note-fields.php' );
	 }
	 
	 /**
	  * Loads the plugin text domain.
	  */
	 public function load_textdomain(){
		 load_plugin_textdomain('extended-crm-for-users-insights');
	 }
	 
 }
 
 
 new ECUI_Initializer();