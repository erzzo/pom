<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ProjectPresenter extends BasePresenter
{
	public function actionDefault()
	{
		$this->template->projects = $this->projectModel->getAll();
	}

	public function actionAdd()
	{

	}

	public function createComponentAddEditProjectForm()
	{
		$form = new Form;
		$form->addHidden("subject_id",3);
		$form->addText('name', 'Názov predmetu');
		$form->addText('acronym', 'Akronym');
		$form->addText('description', 'Popis projektu');
		$form->addText('max_solver_count', 'Počet riešiteľov')
		->setType('Number');
		$form->addText('max_points', 'Maximálny počet bodov');
		$form->addText('solution_from', 'Riešenie od');
		$form->addText('solution_to', 'Riešenie do');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditProjectForm;

		return $form;
	}

	public function processAddEditProjectForm(Form $form)
	{
		$values = $form->getValues();
		$values->created = new \Nette\Datetime;
		$this->projectModel->addEdit($values);
		$this->flashMessage("Projekt bol pridaný");

		$this->redirect('default');
	}
}
