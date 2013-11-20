<?php

namespace MainModule;

class SubjectPresenter extends BasePresenter
{
	public function actionShowAll()
	{
		$this->template->subjects = $this->subjectModel->getSubjects();
	}

	public function actionShowMy()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}
}