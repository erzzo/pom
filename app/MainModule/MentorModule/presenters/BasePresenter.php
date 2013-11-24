<?php
namespace MainModule\MentorModule;

class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		$user = $this->getUser();
		if (!$user->isLoggedIn() || !$user->isInRole('ucitel')) {
			$this->redirect(":Main:Subject:showAll");
		}
	}
}