<?php
namespace Core;

class View{

	public static function render($view, $args = []){
        extract($args, EXTR_SKIP);
        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory
        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

	public static function renderTemplate($template,$args = []){
		static $twig = null;

		if ($twig == null){
			$loader = new \Twig_Loader_Filesystem("../App/Views");
			$twig = new \Twig_Environment($loader);
			$twig->addGlobal("is_logged_in",\App\Auth::isLoggedIn());
		}

		echo $twig->render($template,$args);
	}
}


?>