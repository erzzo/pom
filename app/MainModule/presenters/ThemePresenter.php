<?php

namespace MainModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionShowMy($subjectId)
	{
		$this->template->subject = $this->subjectModel->get($subjectId);
		$this->template->themes = $this->themeModel->getMyThemes($subjectId);
	}
}
