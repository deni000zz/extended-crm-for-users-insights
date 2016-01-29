<?php

class ECUI_Sticky_Notes{
	
	protected $usin_page_slug;
	protected $user_capability;
	protected $ajax_nonce;
	protected $nonce_key = 'ecui_sticky_note';
	protected $note_post_type = 'usin_note';
	
	public function __construct($usin_page_slug, $user_capability) {
		$this->usin_page_slug = $usin_page_slug;
		$this->user_capability = $user_capability;
		$this->add_actions();
	}
	
	protected function add_actions() {
		$this->create_nonce();
		add_filter('usin_user_list_options', array($this, 'filter_options'));
		add_filter('usin_notes_list', array($this, 'set_sticky_notes'));
		add_action('wp_ajax_ecui_stick_note', array($this, 'stick_note'));
		add_action('wp_ajax_ecui_unstick_note', array($this, 'unstick_note'));
		add_filter('the_posts', array($this, 'order_notes_by_sticky'), 10, 2);
	}
	
	protected function is_user_list_page(){
		global $current_screen;

		return strpos( $current_screen->base, $this->usin_page_slug ) !== false;
	}
	
	public function filter_options($options){
		$key = 'note_actions';
		$template = plugins_url('views/notes/sticky-notes.html', ECUI_PLUGIN_FILE);
		
		if(isset($options['customTemplates'][$key])){
			$options['customTemplates'][$key][] = $template;
		}else{
			$options['customTemplates'][$key] = array($template);
		}
		
		//set the strings
		$options['strings']['stick'] = __('Stick', 'extended-crm-for-users-insights');
		$options['strings']['unstick'] = __('Unstick', 'extended-crm-for-users-insights');
		
		//set the nonce key
		$options['ecui_nonce'] = $this->ajax_nonce;
		
		return $options;
	}
	
	public function create_nonce(){
		$this->ajax_nonce = wp_create_nonce($this->nonce_key);
	}
	
	protected function validate_request(){
		if(!current_user_can($this->user_capability)){
			echo json_encode(array('error' => __('Invalid request', 'extended-crm-for-users-insights')));
			return false;
		}
		if(!wp_verify_nonce( $_GET['nonce'], $this->nonce_key )){
			echo json_encode(array('error' => __('Nonce did not verify', 'extended-crm-for-users-insights')));
			return false;
		}
		return true;
	}
	
	public function toggle_sticky_state($sticky){
		if(!$this->validate_request()){
			exit;
		}
		$success = false;
		$res = array();
		
		if(isset($_GET['note_id'])){
			$note_id = (int)$_GET['note_id'];
			$note_post = get_post($note_id);
			
			if($note_post && $note_post->post_type == $this->note_post_type){
				if($sticky){
					stick_post($note_id);
				}else{
					unstick_post($note_id);
				}
				
				$success = true;
				
				$note_obj = new USIN_Note($note_id);
				$user_id = $note_obj->get_note_user();
				$all_notes = USIN_Note::get_all($user_id);
				$res['notes'] = $all_notes;
			}
			
		}
		$res['success'] = $success;
		echo(json_encode($res));
		exit;
	}
	
	public function stick_note(){
		$this->toggle_sticky_state(true);
	}
	
	public function unstick_note(){
		$this->toggle_sticky_state(false);
	}
	
	
	public function order_notes_by_sticky($posts, $query){
		if($query->get('post_type') == $this->note_post_type){
			$stickies = array();
			foreach($posts as $i => $post) {
				if(is_sticky($post->ID)) {
					$stickies[] = $post;
					unset($posts[$i]);
				}
			}
			return array_merge($stickies, $posts);
		}
		return $posts;
	}
	
	public function set_sticky_notes($notes){
		foreach ($notes as &$note) {
			if(is_sticky($note->id)){
				$note->sticky = true;
				$note->state = 'sticky';
			}
		}
		return $notes;
	}
}