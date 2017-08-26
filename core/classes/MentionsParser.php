<?php
/**
 * Mentions parser
 * By: fetch404
 * Date: 7/1/2015
 * License: MIT
 *
 * Modified by Samerton for NamelessMC
 * https://github.com/NamelessMC/Nameless/
 * NamelessMC version 2.0.0-pr2
 */
class MentionsParser {
	/*
	 *  Private variable
	 */
	private $_db;
	 
    /**
     * Create a new instance of MentionsParser.
     *
     */
    public function __construct()
    {
		// Initialise database connection
		$this->_db = DB::getInstance();
    }

    /**
     * Parse the given HTML to include @username tags.
     *
     */
    public function parse($author_id, $value = '', $topic_id = null, $post_id = null, $user_tag = 'User tag', $user_tag_info = 'You have been tagged in a post by {x}.'){
        if(preg_match_all("/\@([A-Za-z0-9\-_!\.\s]+)/", $value, $matches)){
			// Get username of author
			$author_name = $this->_db->get('users', array('id', '=', $author_id))->results();
			if(count($author_name)){
			    $author_id = $author_name[0]->id;
			    $author_name = Output::getClean($author_name[0]->username);
            }
			else $author_name = 'Anonymous';
			
            $matches = $matches[1];
			
            foreach($matches as $possible_username){
                $user = null;
				
                while((strlen($possible_username) > 0) && !$user){
					$user = $this->_db->get('users', array('username', '=', $possible_username));
					$user = $user->first();
                    if($user){
                        $value = preg_replace("/".preg_quote("@{$possible_username}", "/")."/", "<a href=\"" . URL::build('/profile/' . rtrim($possible_username, ' ')) . "\">@{$possible_username}</a>", $value);

                        // Check if user is blocked by OP
                        if(isset($author_id)){
                            $user_blocked = $this->_db->get('blocked_users', array('user_id', '=', $user->id));
                            if($user_blocked->count()){
                                $user_blocked = $user_blocked->results();

                                foreach($user_blocked as $item){
                                    if($item->user_blocked_id == $author_id){
                                        break 2;
                                    }
                                }
                            }
                        }

                        Alert::create($user->id, 'tag', $user_tag, str_replace('{x}', '<a href="' . URL::build('/profile/' . $author_name) . '">' . $author_name . '</a>', $user_tag_info),  URL::build('/forum/topic/' . $topic_id, 'pid=' . $post_id));

						break;
                    }

                    // chop last word off of it
                    $new_possible_username = preg_replace("/([^A-Za-z0-9]{1}|[A-Za-z0-9]+)$/", "", $possible_username);
                    if($new_possible_username !== $possible_username) $possible_username = $new_possible_username;
                    else break;
                }
            }
        }
        return $value;
    }
}