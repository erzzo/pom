<?php

namespace MainModule\StudentModule;


class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		$user = $this->getUser();
		if (!$user->isLoggedIn() || !$user->isInRole('ucitel')) {
			$user->logout();
			$this->redirect(":Main:Sign:in");
		}
	}
}