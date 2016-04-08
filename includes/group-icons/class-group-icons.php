<?php

if(!defined( 'ABSPATH' )) {
   exit;
}

class ECUI_Group_Icons {
	
	protected $user_page_slug = 'users_insights';
	protected static $taxonomy;
	protected static $icon_meta_key = 'ecui_icon';
	
	public function __construct() {
		self::$taxonomy = USIN_Groups::$slug;
		$this->add_actions();
	}
	
	protected function add_actions() {
		
		if(function_exists('add_term_meta')){
			//add the hooks to add an icon option for the group taxonomy
		
			add_filter( 'usin_user_list_options', array($this, 'filter_options'));
			add_filter( 'usin_group_colors', array($this, 'filter_group_colors'));
			
			add_action( self::$taxonomy.'_add_form_fields', array($this, 'add_icon_field'));
			add_action( self::$taxonomy.'_edit_form_fields', array($this, 'add_edit_icon_field'), 10, 2 );
			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_assets'));
			add_action( 'created_'.self::$taxonomy, array($this, 'save_icon_meta'), 10, 2);
			add_action( 'edited_'.self::$taxonomy, array($this, 'update_icon_meta'), 10, 2);
			add_filter( 'manage_edit-'.self::$taxonomy.'_columns', array($this, 'add_group_icon_column'));
			add_filter( 'manage_'.self::$taxonomy.'_custom_column',  array($this, 'add_group_icon_column_content'), 10, 3 );
		}
	}
	
	protected function is_group_taxonomy_page(){
		global $pagenow;
		return !empty($_GET['taxonomy']) && ($pagenow == 'edit-tags.php' || $pagenow =='term.php') && $_GET['taxonomy'] == self::$taxonomy;
	}
	
	public function enqueue_assets(){
		if($this->is_group_taxonomy_page()){
			//enqueue JavaScript files
			
			wp_enqueue_script('ecui_icon_select', 
				plugins_url('js/icon-select.js', ECUI_PLUGIN_FILE), 
				array('jquery'), 
				ECUI_VERSION);
				
			wp_enqueue_style( 'ecui_font_awesome', 
					plugins_url('css/font-awesome.min.css', ECUI_PLUGIN_FILE ), array(), ECUI_VERSION );
			
			wp_enqueue_style( 'ecui_icon_select_css', 
					plugins_url('css/icon-select.css', ECUI_PLUGIN_FILE ), array(), ECUI_VERSION );
		}
		

	}

	public function filter_options($options){
		
		if(!empty($options['groups'])){
			foreach ($options['groups'] as &$group ) {
				$icon = get_term_meta( $group['key'], self::$icon_meta_key, true );
			    if( !empty( $icon ) && $icon != 'none' ){
					$group['icon'] = $icon;
				}
			}
		}
			
		return $options;
	}
	
	/**
	 * Adds a icon select field to the Add User Group form.
	 * @param string $taxonomy the taxonomy
	 */
	public function add_icon_field($taxonomy){
		?><div class="form-field term-group">
			<label for="icont-group"><?php _e('Group Icon', 'extended-crm-for-users-insights'); ?></label>
			<input type="hidden" name="ecui-group-icon" class="ecui-icon-select" />
		</div><?php
	}
	
	/**
	 * Adds a icon select field to the Edit User Group form.
	 * @param object $term     the term object that is being edited
	 * @param string $taxonomy the taxonomy
	 */
	public function add_edit_icon_field($term, $taxonomy){
		$saved_icon = get_term_meta( $term->term_id, self::$icon_meta_key, true );
		?><tr class="form-field term-group-wrap">
	        <th scope="row">
				<label for="icont-group"><?php _e('Group Icon', 'extended-crm-for-users-insights'); ?></label>
			</th>
	        <td>
				<input type="hidden" name="ecui-group-icon" class="ecui-icon-select" value="<?php echo $saved_icon; ?>" />
			</td>
		</tr><?php
	}
	
	/**
	 * Saves the icon meta when a new user group is created.
	 * @param  int $term_id the ID of the term that is created
	 * @param  int $tt_id   the term_taxonomy ID
	 */
	public function save_icon_meta($term_id, $tt_id){
		if(isset($_POST['ecui-group-icon']) && '' !== $_POST['ecui-group-icon'] ){
			add_term_meta( $term_id, self::$icon_meta_key, $_POST['ecui-group-icon'], true );
		}
	}
	
	/**
	 * Updates the icon meta when a user group is updated.
	 * @param  int $term_id the ID of the term that is created
	 * @param  int $tt_id   the term_taxonomy ID
	 */
	public function update_icon_meta($term_id, $tt_id){
		if(isset($_POST['ecui-group-icon']) && '' !== $_POST['ecui-group-icon'] ){
			update_term_meta( $term_id, self::$icon_meta_key, $_POST['ecui-group-icon'] );
		}
	}
	
	/**
	 * Adds a Group Icon column to the User Group table.
	 * @param array $columns the existing table columns
	 */
	public function add_group_icon_column( $columns ){
		$new_columns = array_slice($columns, 0, 2, true) +
		    array('ecui_icon' =>  __( 'Group Icon', 'extended-crm-for-users-insights' )) +
		    array_slice($columns, 2, count($columns) - 1, true) ;
		
	    return $new_columns;
	}
	
	/**
	 * Adds the icon box to the Group Icon column that indicates the icon of
	 * the group.
	 * @param string $content     the default column content
	 * @param string $column_name the ID of the column
	 * @param int $term_id     the ID of the term/group in the table
	 */
	public function add_group_icon_column_content($content, $column_name, $term_id){
		if( $column_name !== 'ecui_icon' ){
	        return $content;
	    }
		
		$term_id = absint( $term_id );
	    $icon = get_term_meta( $term_id, self::$icon_meta_key, true );

	    if( !empty( $icon ) && $icon != 'none' ){
	        $content .= '<i class="ecui-icon-box fa '.esc_attr($icon).'"></i>';
	    }

	    return $content;
	}
	
	public function filter_group_colors($colors){
		$custom_colors = array('1d508d','297cbb','16c98d','ffc83f','bf538d', 'D54E21');
		if(is_array($colors)){
			$colors = array_merge($colors, $custom_colors);
		}
		return $colors;
	}
	
	
}