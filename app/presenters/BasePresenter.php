<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $projectModel;
	protected $subjectModel;

	public function injectBase(Models\Project $project, Models\Subject $subject)
	{
		$this->projectModel = $project;
		$this->subjectModel = $subject;
	}
}