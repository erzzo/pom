<?php

use Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();

		if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
			//$router[] = new Route('index.php', 'Main:Sign:in', Route::ONE_WAY);

			$router[] = $baseRouter = new RouteList('Main');
			$baseRouter[] = new Route('<presenter>/<action>', 'Sign:in');

			$router[] = $mentorRouter = new RouteList('Mentor');
			$mentorRouter[] = new Route('admin/<presenter>/<action>[/<id>]', 'Main:Mentor:Default:default');

			$router[] = $studentRouter = new RouteList('Student');
			$studentRouter[] = new Route('student/<presenter>/<action>[/<id>]', 'Main:Student:Default:default');

		} else {
			$router = new SimpleRouter('Main:Sign:in');
		}

		return $router;
	}

}
