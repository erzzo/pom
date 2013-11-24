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
		$form->addText('username', 'Prihl. meno:')
			->setRequired('Zadajte svoje užívatelské meno.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadajte heslo.');

		$form->addCheckbox('remember', 'Zapamatať si prihlásenie');

		$form->addSubmit('send', 'Prihlásiť');

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
			$this->redirect("Subject:showAll");
		} catch (\Nette\Security\AuthenticationException $e) {
			$form->addError($e->getMessage());
		}
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

}
