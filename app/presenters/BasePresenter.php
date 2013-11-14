<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $projectModel;
	protected $subjectModel;
	protected $themeModel;

	public function injectBase(Models\Project $project, Models\Subject $subject, Models\Theme $theme)
	{
		$this->projectModel = $project;
		$this->subjectModel = $subject;
		$this->themeModel = $theme;
	}
}