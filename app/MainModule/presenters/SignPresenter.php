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
		$form->addText('username', 'Meno:')
			->setRequired('Zadajte svoje užívatelské meno.')
                        ->setAttribute('placeholder', 'Meno');;

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadajte heslo.')->setAttribute('placeholder', 'Heslo');

		$form->addCheckbox('remember', 'Neodhlasovať');

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
		$this->flashMessage('Boli ste odhlásení.','success');
		$this->redirect('in');
	}

}
