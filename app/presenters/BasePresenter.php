<?php

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $projectModel;
	protected $subjectModel;
	protected $themeModel;
	protected $taskModel;
	protected $fileModel;


	public function injectBase(Models\Project $project, Models\Subject $subject, Models\Theme $theme, Models\Task $task, Models\File $file)
	{
		$this->projectModel = $project;
		$this->subjectModel = $subject;
		$this->themeModel = $theme;
		$this->taskModel = $task;
		$this->fileModel = $file;
	}
}