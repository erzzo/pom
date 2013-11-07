<?php

namespace MainModule\StudentModule;

class DefaultPresenter extends BasePresenter
{

	/**
	 * @autowire
	 * @var \Models\Project
	 */
	protected $projectModel;

	public function renderDefault()
	{
		//dump($this->projectModel->getAll());
	}
}