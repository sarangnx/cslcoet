<?php

/* ------------------------
 * File : Front Controller
 * ------------------------
 * All Site Traffic is redirected through
 * this file using .htaccess file.
 */

/*
 * AutoLoader
 */
spl_autoload_register(function($class){
	$root=dirname(__DIR__);
	$file=$root."/".str_replace('\\', '/', $class).".php";
	if(is_readable($file)){
		require($file);
	}

});

/*
 * Error and Exception Handler
 * Defined in Core/Error.php
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');
session_start();
/*
 * Template Engine
 */
 require_once( dirname(__DIR__) . "/vendor/Twig/lib/Twig/Autoloader.php" );
 Twig_Autoloader::register();
 
$router= new \Core\Router();
$router->add('admin',['controller' => 'home','action'=>'index','namespace' => 'Admin']);
$router->add('admin/{controller}/page/{num:\d+}',['action' => 'page','namespace' => 'Admin']);
$router->add('admin/{controller}/{action}/{var:[\w\s]+}',['namespace' => 'Admin']);
$router->add('admin/{controller}/{action}',['namespace' => 'Admin']);

$router->add('admin/{controller}',['action' => 'index','namespace' => 'Admin']);
$router->add('',['controller' => 'Home' , 'action' => 'index']);
$router->add('{controller}/page/{num:\d+}',['action' => 'page']);
$router->add('{controller}',['action' => 'index']);
$router->add('{controller}/{action}/{var:[\w\s]+}');
$router->add('{controller}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);
?>
