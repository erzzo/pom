<?php

namespace MainModule;
use VisualPaginator;
class BasePresenter extends \BasePresenter
{
	public function startup()
	{
		parent::startup();
		$user = $this->getUser();
		if (!$user->isLoggedIn() && !$this->presenter->isLinkCurrent(":Main:Sign:in")) {
			$this->redirect(":Main:Sign:in");
		}
	}

	protected function createComponentPaginator()
	{
		$visualPaginator = new VisualPaginator();
		$visualPaginator->paginator->itemsPerPage = 2;
		$visualPaginator->onShowPage[] = callback($this, 'triggerShowPage');
		return $visualPaginator;
	}

	public function triggerShowPage()
	{
		if ($this->presenter->isAjax()) {
			$this->invalidateControl('comments');
		} else {
			$this->presenter->redirect('this');
		}
	}
}