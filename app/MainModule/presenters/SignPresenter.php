<?php

namespace MainModule;

use \Nette\Application\UI;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{

	protected function createComponentSignInForm()
	{
		$form = new UI\Form;
		$form->addText('username', 'Username:')
			->setRequired('Please enter your username.');

		$form->addPassword('password', 'Password:')
			->setRequired('Please enter your password.');

		$form->addCheckbox('remember', 'Keep me signed in');

		$form->addSubmit('send', 'Sign in');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}


	public function signInFormSucceeded($form)
	{
		$values = $form->getValues();

		if ($values->remember) {
			$this->getUser()->setExpiration('14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);

			if ($this->getUser()->isInRole('ucitel')) {
				$this->redirect("Mentor:Default:");
			} else {
				$this->redirect("Student:Default:");
			}
		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

	public function actionIn()
	{
		if ($this->getUser()->isLoggedIn()) {
			if ($this->getUser()->isInRole('ucitel')) {
				$this->redirect("Mentor:Default:");
			} else {
				$this->redirect("Student:Default:");
			}
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

}
