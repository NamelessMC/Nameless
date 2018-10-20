<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  License: MIT
 *
 *  Report class
 */
class Report {
	private $_db;
	
	// Construct Report class
	public function __construct(){
		$this->_db = DB::getInstance();
	}

	// Create a report
	// Params: $post - array containing fields
	public function create($post = array()){
		// Insert into database
		if(!$this->_db->insert('reports', $post)) {
			throw new Exception('There was a problem creating the report.');
		}
		
		$id = $this->_db->lastid();
		
		// Alert moderators
        // TODO: improve to use permissions
		$moderator_groups = $this->_db->get('groups', array('mod_cp', '=', 1))->results();
		
		if(count($moderator_groups)){
			foreach($moderator_groups as $group){
				$moderators = $this->_db->get('users', array('group_id', '=', $group->id))->results();
				
				if(count($moderators)){
					foreach($moderators as $moderator){
						try {
							// Get language
							Alert::create($moderator->id, 'report', array('path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'), array('path' => 'core', 'file' => 'moderator', 'term' => 'report_alert'), URL::build('/mod/reports/', 'report=' . $id));
						} catch(Exception $e){
							// Unable to alert moderator
							die($e->getMessage());
						}
					}
				}
			}
		}
		
	}
}