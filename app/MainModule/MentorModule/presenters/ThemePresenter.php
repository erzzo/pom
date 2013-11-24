<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ThemePresenter extends BasePresenter
{
	public function actionDefault($projectId)
	{
		//pridat tu aj theme id a zjedodusit model funkcie...
		$this->template->project = $this->projectModel->get($projectId);
		$this->template->themes = $this->themeModel->getThemes($projectId);
		$themeStudents = $this->themeModel->getThemeStudents($projectId);

		$themeStudent = array();
		foreach ($themeStudents as $row) {
			$themeStudent[$row->theme_id][$row->user_id] = $row->user->firstname . ' ' . $row->user->lastname;
		}

		$this->template->themeStudent = $themeStudent;
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
		$students = $this->userModel->getFreeStudentsForTheme($_GET['projectId'], $_GET['q']);

		$result = array();
		foreach ($students as $key => $student) {
			$result[] = array(
				'id' => $key,
				'name' => $student
			);
		}

		echo json_encode($result);
		$this->terminate();
	}

	public function createComponentAssignStudentForm()
	{
		$form = new Form;
		$form->addText('students');
		$form->addHidden('theme_id');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAssignStudentForm;

		return $form;
	}

	public function processAssignStudentForm(Form $form)
	{
		$values = $form->getValues();
		$studentIds = explode(',', $values['students']);

		$this->themeModel->addRemoveUsersToTheme($values['theme_id'], $studentIds);

		$this->flashMessage("Studenti boli priradený k téme.");
		$this->redirect('this');
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
