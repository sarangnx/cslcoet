<?php
namespace App\Controllers;
/*
 * Contact page
 */
use \Core\View;
class Contact extends \Core\Controller{

	protected function before(){
		return true;
	}

	/**
	 * Show /contact page
	 */
	public function contactAction(){
		View::renderTemplate('Page/contact.html',
			['title' => 'Contact' ,
			 'banner' => '/images/fdbanner/contact.png'
			]

		);
	}
	public function indexAction(){
		self::contactAction();
	}

}

?>