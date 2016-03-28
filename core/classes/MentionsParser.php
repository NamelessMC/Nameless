<?php
class MentionsParser
{

    /**
     * Mentions parser
     * By: fetch404
     * Date: 7/1/2015
     * License: MIT
	 *
	 * Modified by Samerton for NamelessMC
     */

	 
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
    public function parse($value = '', $topic_id = null, $post_id = null, $user_language = array('tag' => 'User Tag'))
    {
        if (preg_match_all("/\@([A-Za-z0-9\-_!\.\s]+)/", $value, $matches))
        {
            $matches = $matches[1];
            foreach($matches as $possible_username)
            {
                $user = null;
                while((strlen($possible_username) > 0) && !$user)
                {
					$user = $this->_db->get('users', array('username', '=', $possible_username));
					$user = $user->first();
                    if ($user)
                    {
                        $value = preg_replace("/".preg_quote("@{$possible_username}", "/")."/", "<a href=\"/profile/{$possible_username}\">@{$possible_username}</a>", $value);
                        
						// Create alert for user
						$this->_db->insert('alerts', array(
							'user_id' => $user->id,
							'type' => 'tag',
							'url' => '/forum/view_topic/?tid=' . $topic_id . '&amp;pid=' . $post_id,
							'content' => 'You have been tagged in a post. Click <a href="/forum/view_topic/?tid=' . $topic_id . '&amp;pid=' . $post_id . '">here</a> to view.',
							'created' => date('U')
						));
						
						break;
                    }

                    // chop last word off of it
                    $new_possible_username = preg_replace("/([^A-Za-z0-9]{1}|[A-Za-z0-9]+)$/", "", $possible_username);
                    if ($new_possible_username !== $possible_username)
                    {
                        $possible_username = $new_possible_username;
                    }
                    else
                    {
                        break;
                    }
                }
            }
        }

        return $value;
    }
}