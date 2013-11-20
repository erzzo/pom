<?php
namespace MainModule\MentorModule;

class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn() || $this->getUser()->isInRole('student')) {
			$this->redirect("Subject:showAll");
		}
	}
}