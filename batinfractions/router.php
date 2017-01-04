<?php
class Router {
	private $urlData;
	private $controller;
	private $action;
	
	public function __construct($urlData) {
		$this->urlData = $urlData;
		if(isset($urlData['admin'])){
			echo "isset";
		}
		if (isset ( $urlData ['p'] )) {
			$this->controller = $urlData ['p'];
		} else {
			$this->controller = "home";
		}
		if (isset ( $urlData ['action'] )) {
			$this->action = $urlData ['action'];
		} else {
			$this->action = "index";
		}
	}
	
	public function getController() {
		if (class_exists ( $this->controller )) {
			$parents = class_parents ( $this->controller );
			if (in_array ( "BaseController", $parents )) {
				if (method_exists ( $this->controller, $this->action )) {
					$controllerObj = new $this->controller ( $this->action, $this->urlData );
					$reflectionObj = new ReflectionObject($controllerObj);
					if($reflectionObj->getMethod($this->action)->isProtected() || $reflectionObj->getMethod($this->action)->isPublic()){
						return $controllerObj;
					}
				}
			}
		}
		// Use the homepage as default
		$this->controller = "home";
		$this->action = "index";
		return new $this->controller ( $this->action, $this->urlData );
	}
	
	public function getControllerName() {
		return $this->controller;
	}
}