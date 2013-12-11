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
	protected $fileModel;
	protected $authorizator;

	public function injectBase(Security\Acl $authorizator, Models\User $user, Models\Project $project, Models\Subject $subject, Models\Theme $theme, Models\Task $task, Models\File $file)
	{
		$this->authorizator = $authorizator;
		$this->userModel = $user;
		$this->projectModel = $project;
		$this->subjectModel = $subject;
		$this->themeModel = $theme;
		$this->taskModel = $task;
		$this->fileModel = $file;
	}
}