<?php

namespace MainModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionShowMy($subjectId)
	{
		$this->template->subject = $this->subjectModel->get($subjectId);
		$this->template->themes = $this->themeModel->getMyThemes($subjectId);
		$this->template->taskPercentage = $taskPercentage = $this->themeModel->getThemePercentage($subjectId);

		$flotPercentage = array();
		foreach ($taskPercentage as $key => $perc) {
			$flotPercentage[$key][0] = array(
				"label" => "Splnene",
				"data" => $perc,
				"color" => "#00D50F"
			);
			$flotPercentage[$key][1] = array(
				"label" => "Nesplnene",
				"data" => 100-$perc,
				"color" => "#FA0000"
			);
		}

		$this->template->flotPercentage = json_encode($flotPercentage);
	}
}
