<?php

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();

		if (!$this->user->isLoggedIn()) {
			$this->redirect('Sign:in', array(
				'backlink' => $this->storeRequest()
			));
		}
	}

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
