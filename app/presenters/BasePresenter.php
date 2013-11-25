<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $userModel;
	protected $projectModel;
	protected $subjectModel;
	protected $themeModel;
	protected $taskModel;

	public function injectBase(Models\User $user, Models\Project $project, Models\Subject $subject, Models\Theme $theme, Models\Task $task)
	{
		$this->userModel = $user;
		$this->projectModel = $project;
		$this->subjectModel = $subject;
		$this->themeModel = $theme;;
		$this->taskModel = $task;
	}
}