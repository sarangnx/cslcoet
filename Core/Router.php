<?php
namespace Core;
class Router{

/* 
 * Routing Table
 */

protected $routes = [];
/*
 * Parameters Controller,Action etc..
 */
protected $params = [];
/*
 * adding routes to Routing Table
 * route  - (string) Route URL
 * params - (array)  Controller,Action etc..
 */
public function add($route,$params = []){

	$route = preg_replace('/\//', '\\/', $route);
	$route = preg_replace('/\{([a-z]+)\}/', '(?<\1>[a-z-]+)', $route);
	$route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?<\1>\2)', $route);
	$route = "/^".$route."\/?$/i";
    $this->routes[$route] = $params ;
}

/*
 * Get Current Routing Table as Array
 */
public function getRoutes(){
	return $this->routes;
}


/*
 * Matching incoming URL with 
 * Routing Table
 */
public function match($url){

	foreach($this->routes as $route=>$params){
		if(preg_match($route,$url,$matches)){		
			
			foreach ($matches as $key => $match) {
				if(is_string($key)){
				   	$params[$key] = $match;
				}
			}
			$this->params = $params;
			return true;
		}
	}
	return false;
}

/*
 * Return protected params
 */
public function getParams(){
	return $this->params;
}


/*
 * Dispatch
 */

public function dispatch($url){

	$url = $this->removeQueryString($url);

	if( $this->match($url) ){
		$controller = $this->params['controller'];
		$controller = $this->convertToStudlyCaps($controller);
		$controller = $this->getNamespace().$controller;
		if(class_exists($controller)){
			$controller_object = new $controller($this->params);
			
			$action = $this->params['action'];
			$action = $this->convertToCamelCase($action);

			if(is_callable([$controller_object,$action])){
				$controller_object->$action();
			} 
			else{
				throw new \Exception("Method {$action} in Class {$controller} Not Found",404);
			}
		}
		else{
			throw new \Exception("Class {$controller} does not exist",404);
		}
	}
	else{
		throw new \Exception("URL {$url} Not Found on server",404);
	}
}

/*
 * StudlyCapsFunction
 */
 protected function convertToStudlyCaps($str){
 	return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
 }
/*
 * camelCaseFunction
 */
 protected function convertToCamelCase($str){
 	return lcfirst($this->convertToStudlyCaps($str));
 }
/*
 * Removing Query String Variables
 * ..../page?var=1&name=2
 */
 protected function removeQueryString($url){
 	if($url!=''){
 		$parts=explode('&',$url,2);
 		if(strpos($parts[0],'=') === false){
 			$url=$parts[0];
 		}
 		else{
 			$url='';
 		}
 	}
 	return $url;
 }
 protected function getNamespace(){
 	$namespace = "App\Controllers\\";
 	if(array_key_exists('namespace',$this->params)){
 		$namespace .= $this->params['namespace'] . "\\";
 	}
 	return $namespace;
 }
}
?>
