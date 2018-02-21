<?php
namespace Core;
use \Core\View;
class Error{

	/**
	 * Function to convert Errors to 
	 * Exceptions
	 */

	public static function errorHandler($level,$message,$file,$line){

		if(error_reporting()!=0){
			throw new \ErrorException($message,0,$level,$file,$line);
		}

	}

	/**
	 * Exception Handler
	 */

	public static function exceptionHandler($exception){
		$code = $exception->getCode();
		if($code!=404){
			$code=500;
		}
		http_response_code($code);

		if(\App\Config::SHOW_ERROR){
			echo "<h1>Fatal Error</h1>";
			echo "<p>Uncaught Exception: ".get_Class($exception)." </p>";
			echo "<p>Message: ".$exception->getMessage()."</p>";
			echo "<p>Stack Trace:<pre>".$exception->getTraceAsString()."</pre></p>";
			echo "<p>Thrown in ".$exception->getFile()." on line ".$exception->getLine()."</p>";
		}
		else{
			$log = dirname(__DIR__)."/logs/".date('Y-m-d-h-m-s').".txt";
			ini_set("log_errors", TRUE);
			ini_set('error_log',$log);
			$message = "Uncaught Exception : '".get_Class($exception)."' ";
			$message.= "With Message : '".$exception->getMessage()."' ";
			$message.= "\nStack Trace: ".$exception->getTraceAsString();
			$message.= "\nThrown in ".$exception->getFile()." on line ".$exception->getLine();
			error_log($message);
			View::renderTemplate("$code.html");
		}


	}


}

?>