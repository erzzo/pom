<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionDefault($projectId)
	{
		$this->template->themes = $this->themeModel->getThemes($projectId);
	}

	public function actionAddEdit($projectId, $id)
	{
		if ($id) {
			$theme = $this->themeModel->get($id);
			if (!$theme) {
				//throw Nette\
			}
			$this['addEditThemeForm']->setDefaults($theme);
		}
	}

	public function createComponentAddEditThemeForm()
	{
		$form = new Form;
		$form->addText('name', 'Názov temy')
			->setRequired('Povinný atribút');
		$form->addText('acronym', 'Akronym');
		$form->addText('description', 'Popis temy');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditThemeForm;

		return $form;
	}

	public function processAddEditThemeForm(Form $form)
	{
		$values = $form->getValues();
		unset($values->acronym);
		$id = $this->presenter->getParameter('id');
		$projectId = $this->presenter->getParameter('projectId');

		if (!$id) {
			$values->project_id = $projectId;
			$this->flashMessage("Téma bola pridaná");
		} else {
			$this->flashMessage("Téma bola upravená");
		}

		$this->themeModel->addEdit($values, $id);

		$this->redirect('default', ['projectId' => $projectId]);
	}
}
