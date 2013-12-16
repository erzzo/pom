<?php

namespace MainModule;

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
		$this->template->taskPercentage = $taskPercentage = $this->themeModel->getThemesPercentage($subjectId);

		$flotPercentage = array();
		foreach ($taskPercentage as $key => $perc) {
			$flotPercentage[$key][0] = array(
				"label" => "Splnene",
				"data" => $perc,
				"color" => "#acda3e"
			);
			$flotPercentage[$key][1] = array(
				"label" => "Nesplnene",
				"data" => 100-$perc,
				"color" => "#CCCCCC"
			);
		}

		$this->template->flotPercentage = json_encode($flotPercentage);
	}
}
