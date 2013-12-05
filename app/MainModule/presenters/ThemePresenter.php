<?php

namespace MainModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();
		if (!$this->subjectModel->isInSubject($this->getParameter('subjectId'),$this->user->getId())) {
			$this->flashMessage('Access denied','error');
			$this->redirect(':Main:Subject:showAll');
		}
	}
	public function actionShowMy($subjectId)
	{
		$this->template->subject = $this->subjectModel->get($subjectId);
		$this->template->themes = $this->themeModel->getMyThemes($subjectId);
		$this->template->taskPercentage = $this->themeModel->getThemePercentage($subjectId);
	}
}
