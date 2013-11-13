<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{
	public function actionDefault()
	{
		$this->template->subjects = $this->subjectModel->getSubjects();
	}

	public function actionAdd()
	{

	}

	public function createComponentAddEditSubjectForm()
	{
		$schoolYear = $this->subjectModel->getSchoolYears()->fetchPairs('id', 'year');

		$form = new Form;
		$form->addSelect('school_year_id', 'Skolský rok', $schoolYear);
		$form->addText('name', 'Názov predmetu');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditSubjectForm;

		return $form;
	}

	public function processAddEditSubjectForm(Form $form)
	{
		$values = $form->getValues();
		$values->created = new \Nette\Datetime;
		$this->subjectModel->addEdit($values);
		$this->flashMessage("Predmet bol pridaný");

		$this->redirect('default');
	}
}
