<?php

namespace MainModule\MentorModule;

use Nette\Application\UI\Form;

class TaskPresenter extends BasePresenter
{
	private $themeId;

	public function actionDefault($themeId)
	{
		$this->template->theme = $this->themeModel->get($themeId);
		$this->template->tasks = $this->taskModel->getTasks($themeId);
	}

	public function actionTaskDetail($taskId)
	{
		$this->template->task = $this->taskModel->get($taskId);
	}

	public function actionAddEdit($themeId, $id)
	{
		if ($id) {
			$task = $this->taskModel->get($id);
			if (!$task) {
				//throw Nette\
			}
			$this['addEditTaskForm']->setDefaults($task);
		}
	}

	public function createComponentAddEditTaskForm()
	{
		$users = $this->themeModel->getThemeUsers($this->presenter->params['themeId']);

		$form = new Form;
		$form->addText('name', 'Názov úlohy')
			->setRequired('Povinný atribút');
		$form->addSelect('user_id','Riešitelia', $users)
			->setRequired('Povinný atribút');
		$form->addText('max_files_count', 'Maximálny počet nahratých súborov')
			->setType('Number');
		$form->addTextArea('description', 'Popis úlohy');
		$form->addSubmit('submit');
		$form->onSuccess[] = $this->processAddEditTaskForm;

		return $form;
	}

	public function processAddEditTaskForm(Form $form)
	{
		$values = $form->getValues();
		$id = $this->presenter->getParameter('id');
		$themeId = $this->presenter->getParameter('themeId');

		if (!$id) {
			$values->theme_id = $themeId;
			$this->flashMessage("Úloha bola pridaná");
		} else {
			$this->flashMessage("Úloha bola upravená");
		}

		$this->taskModel->addEdit($values, $id);

		$this->redirect('default', ['themeId' => $themeId]);
	}
}
