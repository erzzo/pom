<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{

	public function actionAddEdit($id)
	{
		if ($id) {
			$subject = $this->subjectModel->get($id);
			if (!$subject) {
				//throw Nette\
			}
			$this['addEditSubjectForm']->setDefaults($subject);
		}
	}

	public function createComponentAddEditSubjectForm()
	{
		$schoolYear = $this->subjectModel->getSchoolYears()->fetchPairs('id', 'year');
		$terms = $this->subjectModel->getTerms()->fetchPairs('id', 'name');
		$grades = $this->subjectModel->getGrades()->fetchPairs('id', 'grade');

		$form = new Form;
		$form->addSelect('school_year_id', 'Skolský rok', $schoolYear)
			->setRequired('Povinný atribút');
		$form->addSelect('term_id', 'Semester', $terms)
			->setRequired('Povinný atribút');
		$form->addSelect('grade_id', 'Ročník', $grades)
			->setRequired('Povinný atribút');
		$form->addText('name', 'Názov predmetu')
			->setRequired('Povinný atribút');
		$form->addText('password', 'Heslo na vstup');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditSubjectForm;

		return $form;
	}

	public function processAddEditSubjectForm(Form $form)
	{
		$values = $form->getValues();
		$values->created = new \Nette\Datetime;
		$id = $this->presenter->getParameter('id');

		$this->subjectModel->addEdit($values, $id);

		if ($id) {
			$this->flashMessage("Predmet bol upravený.");
		}else {
			$this->flashMessage("Predmet bol pridaný.");
		}

		$this->redirect(':Main:Subject:showMy');
	}
}