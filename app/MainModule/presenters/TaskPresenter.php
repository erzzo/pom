<?php

namespace MainModule;

use Nette\Application\UI\Form;
use Nette\DateTime;
use Nette\Application\Responses\FileResponse,
    Nette\Utils\Strings;


class TaskPresenter extends BasePresenter
{

	public function actionDefault($themeId)
	{
		$this->template->theme = $this->themeModel->get($themeId);
		$this->template->tasks = $this->taskModel->getTasks($themeId);
	}

	public function actionTaskDetail($taskId)
	{
		$this->template->task = $this->taskModel->get($taskId);
		$this->template->files = $this->fileModel->getFiles($taskId);
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

	public function createComponentAddEditFileForm()
	{
		$form = new Form;
		$form->addText('name', 'Názov')
			->addRule(Form::FILLED,'Zadajte názov súboru.');
		$form->addTextArea('description','Popis');
		$form->addUpload('file', 'Dokument')
			->addRule(Form::FILLED,'Musi byt vyplnený.')
			->addRule(Form::MAX_FILE_SIZE, 'Príliš veľký súbor.', 10 * 1024 * 1024);
			//->addRule(Form::MIME_TYPE,'Bad file format.',$parent->context->parameters['mimeTypes']);
		$form->addSubmit('submit', 'Ulož');
		$form->onSuccess[] = $this->processAddEditFileForm;
		return $form;
	}

	public function processAddEditFileForm(Form $form)
	{
		$values = $form->getValues();
		$taskId = $this->presenter->getParameter('taskId');
		$task = $this->taskModel->get($taskId);
		$fileCount = $this->fileModel->getAll()->where('task_id', $taskId)->count();
		if ($task && $task->max_files_count > $fileCount) {
			if($values['file']->isOk()){
				//$values['extension'] = pathinfo($values['file']->getName(), PATHINFO_EXTENSION); //TODO: treba do DB dat extension
				//$values['file_name'] = $values['name']->getName().new DateTime().'.'.$values['extension'];

				$values['url'] = $URL = Strings::webalize('subject'. $task->theme->project->subject->name .'/project'.$task->theme->project->name .'/theme'.$task->theme->name.'/task'. $task->name, NULL, FALSE);
				if (!file_exists($this->context->params['wwwDir'] . '/../storage/files/'.$URL)) {
					//mkdir($this->context->params['wwwDir'] . '/../storage/files/subject'.$URL, 0755, true);
				}
				//$values['name']->move($this->context->params['wwwDir'] . '/../storage/files/'.$URL);
				//DB ukladanie
				unset($values['file']);
				$values['theme_id'] = $task->theme_id;
				$values['task_id'] = $task->id;
				$values['created'] = new \Nette\DateTime;
				$this->fileModel->addEdit($values);
				$this->flashMessage("Súbor nahratý");
			}else {
				$this->flashMessage("CHYBA súboru");
			}
		}else {
			$this->flashMessage("Prekročený maximálny počet súborov.");
		}

		unset($values['name']);

		$this->presenter->redirect('Task:taskDetail',$taskId);
	}
}
