<?php
namespace App;
/*
 * Authentication code orgainzed in a single class
 * For call from all controllers
 */
class Auth{

	/*
	 *  Login
	 */

	public static function login($user){
		session_regenerate_id(true);
		$_SESSION['username'] = $user->username;
		$_SESSION['usergroup'] = $user->usergroup;
	}

	/*
	 *   Logout
	 */
	public static function logout(){
		// Unset all of the session variables.
		$_SESSION = [];

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
    		$params = session_get_cookie_params();
    		setcookie(session_name(), '', time() - 42000,
        		$params["path"], $params["domain"],
        		$params["secure"], $params["httponly"]
    		);

			// Finally, destroy the session.
			session_destroy();
		}
	}

	/*
	 *   Check if Logged in or not
	 */
	public static function isLoggedIn(){
		return isset($_SESSION['username']);
	}
	/*
	 *  Return to Reffered Page after Loggin In
	 */
	public static function rememberPage(){
		$_SESSION['return'] = $_SERVER['REQUEST_URI'];
	}

	public static function getRememberedPage(){
		return $_SESSION['return'] ?? '/admin/';
	}
}

?>
