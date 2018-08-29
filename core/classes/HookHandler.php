<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr4
 *
 *  Hook handler class
 */

class HookHandler {
    private static $_events = array();

    // Register an event name
    // Params:  $event - name of event to add
    //          $description - human readable description
	//          $params - array of available parameters and their descriptions
    public static function registerEvent($event, $description, $params = array()){
        if(!isset(self::$_events[$event]))
            self::$_events[$event] = array();

        self::$_events[$event]['description'] = $description;
        self::$_events[$event]['params'] = $params;

        return true;
    }

    // Register an event hook
    // Params:  $event - event name to hook into
    //          $hook - function name to execute, eg Class::method
    public static function registerHook($event, $hook){
        if(!isset(self::$_events[$event]))
            self::$_events[$event] = array();

        self::$_events[$event]['hooks'][] = $hook;

        return true;
    }

    // Execute an event
    // Params:  $event - event name to call
    public static function executeEvent($event, $param = null){
        if(!isset(self::$_events[$event]) || !count(self::$_events[$event]['hooks']))
            return false;

        if(!is_array($param)){
        	$param = array();
        }

        if(!isset($param['event']))
        	$param['event'] = $event;

        foreach(self::$_events[$event]['hooks'] as $hook){
            call_user_func($hook, $param);
        }

        return true;
    }

    // Get a list of hooks
    public static function getHooks(){
        $ret = array();
        foreach(self::$_events as $key => $item)
            $ret[$key] = $item['description'];

        return $ret;
    }

	// Get a certain hook
	public static function getHook($hook){
    	if(isset(self::$_events[$hook]))
    		return self::$_events[$hook];
    	else
    		return null;
	}

	// Get parameters
	public static function getParameters($event){
    	if(isset(self::$_events[$event]['parameters'])){
    		return self::$_events[$event]['parameters'];
	    } else {
    		return null;
	    }
	}

}