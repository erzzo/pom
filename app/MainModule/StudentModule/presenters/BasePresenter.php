<?php

namespace MainModule\StudentModule;


class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect(":Main:Sign:in");
		}
	}
}