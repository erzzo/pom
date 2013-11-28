<?php
namespace MainModule\MentorModule;

class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		$user = $this->getUser();
		if (!$user->isInRole('ucitel')) {
			$user->logout();
			$this->redirect(":Main:Sign:in");
		}
	}
}