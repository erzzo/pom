<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class ProjectPresenter extends BasePresenter
{
	public function actionDefault($subjectId)
	{
		$this->template->subject = $this->subjectModel->get($subjectId);
		$this->template->projects = $this->projectModel->getProjects($subjectId);
	}

	public function actionAddEdit($subjectId, $id)
	{
		if ($id) {
			$project = $this->projectModel->get($id);
			if (!$project) {
				//throw Nette\
			}
			$this['addEditProjectForm']->setDefaults($project);
		}
	}

	public function createComponentAddEditProjectForm()
	{
		$form = new Form;
		$form->addText('name', 'Názov projektu')
			->setRequired('Povinný atribút');
		$form->addTextArea('description', 'Popis projektu');
		$form->addText('max_solver_count', 'Počet riešiteľov')
			->setType('Number');
			//->setRequired('Povinný atribút')
			//->addRule(FORM::RANGE,'Číslo musí byť pozitívne číslo.',array(0,NULL));
		$form->addText('max_points', 'Maximálny počet bodov')
			->setType('Number');
			//->setRequired('Povinný atribút');
		$form->addText('solution_from', 'Riešenie od');
			//->setRequired('Povinný atribút');
		$form->addText('max_files_count', 'Maximálny počet súborov')
			->setType('Number');
		$form->addText('solution_to', 'Riešenie do');
			//->setRequired('Povinný atribút');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditProjectForm;

		return $form;
	}

	public function processAddEditProjectForm(Form $form)
	{
		$values = $form->getValues();
		$id = $this->presenter->getParameter('id');
		$subjectId = $this->presenter->getParameter('subjectId');

		if (!$id) {
			$values->subject_id = $subjectId;
			$this->flashMessage("Projekt bol pridaný");
		} else {
			$this->flashMessage("Projekt bol upravený");
		}

		$this->projectModel->addEdit($values, $id);

		$this->redirect('default', ['subjectId' => $subjectId]);
	}
}
