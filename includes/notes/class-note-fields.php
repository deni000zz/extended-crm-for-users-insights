<?php

if(!defined( 'ABSPATH' )) {
   exit;
}

/**
 * Adds additional note-related fields to the Users Insights users table.
 */
class ECUI_Note_Fields{
	
	protected $note_post_type = 'usin_note';
	
	public function __construct(){
		$this->init();
	}
	
	public function init(){
		add_filter('usin_fields', array($this , 'register_fields'));
		add_filter('usin_db_map', array($this, 'filter_db_map'));
		add_filter('usin_query_joins', array($this, 'filter_query_joins'));
		add_filter('usin_user_query_select', array($this, 'filter_query_select'));
	}
	
	public function register_fields($fields){
		
		if(!empty($fields) && is_array($fields)){

			$fields[]=array(
				'name' => __('Last Note Date', 'usin'),
				'id' => 'ecui_last_note_date',
				'order' => 'DESC',
				'show' => true,
				'fieldType' => 'ecui',
				'filter' => array(
					'type' => 'date'
				)
			);
		}

		return $fields;
	}
	
	
	public function filter_db_map($db_map){
		$db_map['ecui_last_note_date'] = array('db_ref'=>'ecui_last_note_date', 'db_table'=>'ecui_notes', 'nulls_last'=>true, 'cast'=>'DATETIME');
		return $db_map;
	}
	
	public function filter_query_joins($query_joins){
		global $wpdb;

		
		$query_joins .= "LEFT JOIN (SELECT $wpdb->postmeta.meta_value as user_id , MAX($wpdb->posts.post_date) as ecui_last_note_date FROM $wpdb->posts ".
			"INNER JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = '_usin_note_for'".
			"WHERE $wpdb->posts.post_type = '$this->note_post_type' GROUP BY user_id) ".
			"AS ecui_notes ON $wpdb->users.ID = ecui_notes.user_id";
		

		return $query_joins;
	}
	
	public function filter_query_select($query_select){
		$query_select.=', ecui_notes.ecui_last_note_date';
		return $query_select;
	}
}