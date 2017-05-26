<?php 
// CoLWI v0.9.3
// Login PHP class
// Copyright (c) 2015-2016 SimonOrJ

// void __construct ( array &Configuration )
//
// bool check ( void )
//   returns true on success, false on failure, or null on locked account.
// string getUsername ( void )
//   returns string of the username.
// bool permission ( int minPerm )
//   true on allowed access and false on no access.
// bool login ( string username, @string password[, @boolean remember = false] )
//   same return as check().
// bool logout ( void )
//   returns true at all times.
// Constants:
// Login::PERM_SETUP
// Login::PERM_PURGE
// Login::PERM_LOOKUP

// Original Login Script by richcheting from http://www.ricocheting.com/code/php/simple-login

// TODO: Make a more secure login sessions.


class Login {
    const PERM_SETUP = 0,
          PERM_PURGE = 1,
          PERM_LOOKUP = 2;
    
    private $prefix = "CoLogin_",
            $user = "",
            $pass = "",
            $c;
    
    public function __construct(&$config) {
        $this->c = &$config;
        $this->cookie_duration = $this->c['login']['duration']*86400;
        
        // Start a session.
        session_start();
        
        // If cookies exist
        if (isset($_COOKIE[$this->prefix . 'user'])) {
            // make session equal to cookie
            $_SESSION[$this->prefix . 'user'] = $_COOKIE[$this->prefix . 'user'];
            $_SESSION[$this->prefix . 'pass'] = $_COOKIE[$this->prefix . 'pass'];
        }
        
        // set username and password
        if (!empty($_SESSION[$this->prefix . 'user'])) $this->user = $_SESSION[$this->prefix . 'user'];
        if (!empty($_SESSION[$this->prefix . 'pass'])) $this->pass = $_SESSION[$this->prefix . 'pass'];
    }
    
    // Checks cookie login status.
    public function check() {
        // If user does not exist (empty) or the password does not match the user
        if (empty($this->user) || empty($this->pass)) {
            return false;
        }
        
        // If credentials doesn't match
        if (empty($this->c['user'][$this->user]) || md5($this->prefix . $this->c['user'][$this->user]['pass']) !== $this->pass) {
            $this->logout();
            return false;
        }
        
        // If account is locked
        if ($this->c['user'][$this->user]['lock'] !== false) {
            return null;
        }
        
        // Login
        return true;
    }
    
    // Gets the logged in username.
    public function getUsername() {
        return $this->user;
    }
    
    // Gets the logged in username.
    public function permission($minPerm) {
        // If logged in and the user's permission is good enough
        if (
            $this->check() === true
            && $minPerm >= $this->c['user'][$this->user]['perm']
        )   return true;
        
        // True if login is not required and if permission is good enough
        return (
            !$this->c['login']['required']
            && $minPerm >= $this->c['login']['baseperm']
        );
    }

    // Checks login details
    public function login($user, $pass, $remember = false) {
        $this->user = $user;
        $this->pass = md5($this->prefix . $pass);
        
        // If login is unsuccessful
        if (($ret = $this->check()) === false)
            return false;
        
        // If remember is set
        if ($remember) {
            setcookie($this->prefix . "user", $this->user, time() + ($this->cookie_duration * 86400));
            setcookie($this->prefix . "pass", $this->pass, time() + ($this->cookie_duration * 86400));
        }

        $_SESSION[$this->prefix . 'user'] = $this->user;
        $_SESSION[$this->prefix . 'pass'] = $this->pass;
        return $ret;
    }
    
    // Logs the user out
    public function logout() {
        if (!empty($_COOKIE[$this->prefix . 'user'])) setcookie($this->prefix . "user", NULL, -1, "/");
        if (!empty($_COOKIE[$this->prefix . 'pass'])) setcookie($this->prefix . "pass", NULL, -1, "/");
        session_destroy();
        $this->user = null;
        $this->pass = null;
        return true;
    }
}
?>