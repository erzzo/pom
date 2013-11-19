<?php

namespace MainModule\StudentModule;

use Nette\Application\UI\Form;

class SubjectPresenter extends BasePresenter
{
	public function actionDefault($gradeId = NULL)
	{
		if ($gradeId) {
			$this->template->subjects = $this->subjectModel->getSubjects($gradeId);
		} else {
			$this->template->subjects = $this->subjectModel->getSubjects();
			//$this->template->grades = $this->subjectModel->getGrades();
		}
	}

	public function actionMySubjects()
	{
		$this->template->userSubjects = $this->subjectModel->getUserSubjects($this->getUser()->getId());
	}

}
