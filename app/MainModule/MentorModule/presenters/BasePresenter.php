<?php
namespace MainModule\MentorModule;

class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		if (!$this->user->isAllowed($this->name, $this->action))	{
			$this->redirect(':Main:Subject:showAll');
		}
	}
}