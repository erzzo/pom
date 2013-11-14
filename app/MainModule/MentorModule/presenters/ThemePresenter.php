<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionDefault()
	{
		$this->template->themes = $this->themeModel->getAll();
	}

	public function actionAddEdit($id)
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
		$form->addText('name', 'Názov projektu')
			->setRequired('Povinný atribút');
		$form->addText('acronym', 'Akronym');
		$form->addText('description', 'Popis projektu');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditThemeForm;

		return $form;
	}

	public function processAddEditThemeForm(Form $form)
	{
		$values = $form->getValues();
		unset($values->acronym);
		$id = $this->presenter->getParameter('id');
		$values->created = new \Nette\Datetime;
		$values->project_id = 27;
		$this->themeModel->addEdit($values, $id);
		$this->flashMessage("Projekt bol pridaný");

		$this->redirect('default');
	}
}
