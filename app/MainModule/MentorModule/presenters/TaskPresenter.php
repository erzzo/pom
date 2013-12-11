<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;
use Nette\DateTime;

class TaskPresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();
		$theme = $this->themeModel->get($this->getParameter('themeId'));
		if ($theme) {
			if (!$this->subjectModel->isInSubject($theme->project->subject_id,$this->user->getId())) {
				$this->flashMessage('Access denied','error');
				$this->redirect(':Main:Subject:showAll');
			}
		} else {
			$this->flashMessage('Access denied','error');
			$this->redirect(':Main:Subject:showAll');
		}
	}

	public function actionAddEditEvaluation($themeId, $evaluationId = NULL)
	{
		$this->template->theme = $theme = $this->themeModel->get($themeId);
		if ($theme->project->evaluation_from > new DateTime()) {
			$this->flashMessage("Nieje ešte možné pridávať hodnotenie");
			$this->redirect(':Main:Task:default', ['themeId' => $themeId]);
		}
		if ($evaluationId) {
			$evalutaion = $this->themeModel->getEvaluation($evaluationId);
			if (!$evalutaion) {
				//throw Nette\
			}
			$this['addEditEvaluationForm']->setDefaults($evalutaion);
		}
	}

	public function createComponentAddEditEvaluationForm()
	{
		$theme = $this->themeModel->get($this->presenter->getParameter('themeId'));
		$form = new Form;
		$form->addText('points', 'Počet bodov')
			->setType('numner')
			->setRequired('Povinný atribút')
			->addRule($form::EQUAL, "Maximálny počet bodov je %d",$theme->project->max_points);
		$form->addTextArea('description', 'Popis');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditEvaluationForm;

		return $form;
	}

	public function processAddEditEvaluationForm(Form $form)
	{
		$values = $form->getValues();
		$values->user_id = $this->user->getId();
		$id = $this->presenter->getParameter('evaluationId');
		$themeId = $this->presenter->getParameter('themeId');
		$theme = $this->themeModel->get($themeId);
		$eval = $this->themeModel->addEditEvaluation($values, $id);

		if (!$id) {
			$this->flashMessage("Hodnotenie bolo pridané");
			$theme->update(array("evaluation_id" => $eval->id));
		} else {
			$this->flashMessage("Hodnotenie bolo upravené");
		}
		$this->redirect(':Main:Task:default', ['themeId' => $themeId]);
	}
}
