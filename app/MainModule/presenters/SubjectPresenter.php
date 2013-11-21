<?php
namespace MainModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{
	public function actionShowAll($rocnikId = null)
	{
		$this->template->subjects =  $this->subjectModel->getSubjects($rocnikId);
		$this->template->userSubjects =  $usr = $this->subjectModel->getUserSubjects($this->getUser()->getId())->fetchPairs('id','subject_id');
	}

	public function actionShowMy()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}

	public function actionRequestEntry($subjectId)
	{

	}

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
		$this->flashMessage("Predmet bol pridaný");

		$this->redirect('showMy');
	}

	public function createComponentSelectGradesForm()
	{
		$grades = $this->subjectModel->getGrades()->fetchPairs('id','grade');
		$form = new Form;
		$form->addSelect('gradeId','Ročník',$grades)
			->setDefaultValue($this->presenter->getParameter('rocnikId'))
			->setRequired();
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processSelectGradesForm;
		return $form;
	}
	public function processSelectGradesForm(Form $form)
	{
		$values = $form->getValues();
		$this->redirect('this',$values->gradeId);
	}

	public function createComponentRequestEntryForm()
	{
		$form = new Form;
		$form->addPassword('password', 'Heslo do predmetu:')
			->setRequired("heslo je povinný atribút");
		$form->addSubmit('submit');

		$form->onSuccess[] = $this->processRequestEntryForm;
		return $form;
	}

	public function processRequestEntryForm(Form $form)
	{
		$values = $form->getValues();
		$subjectId = $this->presenter->params['subjectId'];

		$subject = $this->subjectModel->get($subjectId);

		if ($subject->password === $values['password']) {
			$this->subjectModel->addUserToSubject($subject->id);
			$this->flashMessage('Predmet pridaný');
			$this->redirect('showMy');
		} else {
			$this->flashMessage('Zadané heslo je nesprávne');
			$this->redirect('this');
		}
	}


}