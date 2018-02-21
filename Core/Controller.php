<?php
namespace Core;

abstract class Controller{

	protected $route_params = [];

	public function __construct($params){
		$this->route_params = $params;
	}

	public function __call($name,$args = []){

		$method = $name."Action";

		if(method_exists($this, $method)){
			if($this->before()!=false){
				call_user_func_array([$this,$method], $args);
				$this->after();
			}
		}
		else{
			throw new \Exception("Method: $method not found in Class:".get_class($this));
		}

	}
	protected function before(){
		// Code to run before every function
		return true;
	}
	protected function after(){
		// Code to run after every function
	}

	public function redirect($url){
		header('Location: http://'.$_SERVER['HTTP_HOST'].$url,true,303);
		exit;
	}
}

?>