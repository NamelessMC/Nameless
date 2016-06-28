<?php 
function getSelfURL(){
  if($_SERVER['SERVER_ADDR'] !== "127.0.0.1"){
        if($_SERVER['SERVER_PORT'] == 80){
			$url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
        } else {
            $url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
        }
		
		if(substr($url, -1) !== '/') $url .= '/';
		
		return $url;
		
    } else {
        return false;
    }
}