<?php

namespace MainModule;

class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		$user = $this->getUser();
		if (!$user->isLoggedIn() && !$this->presenter->isLinkCurrent(":Main:Sign:in")) {
			$this->redirect(":Main:Sign:in");
		}
	}
}