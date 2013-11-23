<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionDefault($projectId)
	{
		$this->template->project = $this->projectModel->get($projectId);
		$this->template->themes = $this->themeModel->getThemes($projectId);

		$this->template->maxSolvers = 3; //vytiahnut z DB
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

	public function handleSearchStudent()
	{

	}

	public function createComponentAssignStudentForm()
	{
		$form = new Form;
		$form->addText('students')
			->getControlPrototype()
			->class('student-search');
		$form->addHidden('theme_id');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAssignStudentForm;

		return $form;
	}

	public function processAssignStudentForm(Form $form)
	{
		$values = $form->getValues();
	}


	public function createComponentAddEditThemeForm()
	{
		$form = new Form;
		$form->addText('name', 'Názov temy')
			->setRequired('Povinný atribút');
		$form->addText('acronym', 'Akronym');
		$form->addTextArea('description', 'Popis temy');
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
