<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $projectModel;

	public function injectBase(Models\Subject $project)
	{
		$this->projectModel = $project;
	}
}