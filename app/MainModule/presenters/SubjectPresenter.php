<?php
namespace MainModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{
	public function actionShowAll($rocnikId = null)
	{
		$this->template->subjects =  $this->subjectModel->getSubjects($rocnikId);
		$this->template->userSubjects =  $this->subjectModel->getUserSubjects($this->getUser()->getId())->fetchPairs('id','subject_id');
	}

	public function actionShowMy()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}

	public function actionRequestEntry($subjectId)
	{

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
		} else {
			$this->flashMessage('Zadané heslo je nesprávne');
		}
	}


}