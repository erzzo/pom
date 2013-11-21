<?php
namespace MainModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{
	public function actionShowAll()
	{
		$this->template->subjects = $this->subjectModel->getSubjects();
		$this->template->userSubjects =  $this->subjectModel->getUserSubjects($this->getUser()->getId())->fetchPairs('id','subject_id');
	}

	public function actionShowMy()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}

	public function actionRequestEntry($subjectId)
	{

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