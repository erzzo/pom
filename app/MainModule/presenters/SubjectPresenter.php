<?php
namespace MainModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{

	public function actionShowAll($rocnikId = null)
	{
		$this->template->subjects =  $this->subjectModel->getSubjects($rocnikId);
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId())->fetchPairs('id','subject_id');
	}

	public function actionShowMy()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}

	public function actionRequestEntry($subjectId)
	{

	}

	public function createComponentFilterSubjectsForm()
	{
		$grades = $this->subjectModel->getGrades()->fetchPairs('id','grade');
		$form = new Form;
		$form->addSelect('gradeId','Ročník', array(0 => 'Všetky') + $grades)
			->setRequired();
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processFilterSubjectsForm;

		return $form;
	}
	public function processFilterSubjectsForm(Form $form)
	{
		$values = $form->getValues();
		if ($this->isAjax()) {
			if ($this->presenter->isLinkCurrent('showAll')) {
				$this->template->subjects =  $this->subjectModel->getSubjects($values['gradeId']);
			} else {
				$this->template->userSubjects = $this->subjectModel->getUserSubjects()->where('subject.grade_id', $values['gradeId']);
			}
			$this->invalidateControl('subjects');
		} else {
			$this->redirect('this',$values->gradeId);
		}
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
			$this->flashMessage('Predmet pridaný','alert-success');
			$this->redirect('showMy');
		} else {
			$this->flashMessage('Zadané heslo je nesprávne','alert-danger');
			$this->redirect('this');
		}
	}


}