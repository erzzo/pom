<?php

namespace MainModule;

use Nette\Application\UI\Form;
use Nette\DateTime;
use Nette\Application\Responses\FileResponse,
    Nette\Utils\Strings;


class TaskPresenter extends BasePresenter
{
	public function startup()
	{
		parent::startup();
		if ($this->action === 'taskDetail') {
			$task = $this->taskModel->get($this->getParameter('taskId'));
			if ($task) {
				if (!$this->user->isAllowed('admin') && !$this->themeModel->isInTheme($task->theme_id,$this->user->getId())) {
					$this->flashMessage('Access denied','error');
					$this->redirect(':Main:Subject:showAll');
				}
			} else {
				$this->flashMessage('Access denied','error');
				$this->redirect(':Main:Subject:showAll');
			}
		} else {
			if (!$this->user->isAllowed('admin') && !$this->themeModel->isInTheme($this->presenter->getParameter('themeId'),$this->user->getId())) {
				$this->flashMessage('Access denied','error');
				$this->redirect(':Main:Subject:showAll');
			}
		}
	}

	public function actionDefault($themeId)
	{
		$this->template->theme = $this->themeModel->get($themeId);
		$this->template->tasks = $this->taskModel->getTasks($themeId);
		$this->template->files = $this->fileModel->getFiles($themeId);
		$this->template->hours_worked = $this->taskModel->getHoursWorked($themeId);

		$paginator = $this['paginator']->getPaginator();
		$paginator->itemCount = count($this->themeModel->getAllComments($themeId));
		$this->template->comments = $this->themeModel->getComments($themeId,NULL,$paginator->offset, $paginator->itemsPerPage);

		$this->template->taskPercentage = $taskPercentage = $this->themeModel->getThemePercentage($themeId);

		$flotPercentage[] = array(
			"label" => "Splnene",
			"data" => $taskPercentage
		);

		$flotPercentage[] = array(
			"label" => "Nesplnene",
			"data" => 100-$taskPercentage
		);

		$this->template->flotPercentage = json_encode($flotPercentage);
	}

	public function actionTaskDetail($taskId)
	{

		$this->template->task = $task = $this->taskModel->get($taskId);
		$this->template->files = $this->fileModel->getFiles($task->theme_id,$taskId);

		$paginator = $this['paginator']->getPaginator();
		$paginator->itemCount = count($this->themeModel->getAllComments(NULL,$taskId));
		$this->template->comments = $this->themeModel->getComments(NULL,$taskId,$paginator->offset, $paginator->itemsPerPage);
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

	public function handleDeleteFile($fileId)
	{
		$document = $this->fileModel->get($fileId);
		if ($document->name) {
			unlink($this->context->params['wwwDir'] . '/../storage/files/'.$document->url);
		}
		$document->delete();
		$this->flashMessage('Súbor zmazaný.', 'success');
		$this->redirect('this');
	}

	public function handleMarkDone($taskId)
	{
		$this->taskModel->markDone($taskId);

		if (!$this->presenter->isAjax()) {
			$this->presenter->redirect('this');
		}
		$this->invalidateControl();
	}

	public function handleDownloadFile($fileId) {
		$fileDat= $this->fileModel->get($fileId);

		$file = $this->context->params['wwwDir'].'/../storage/files/'. $fileDat->url;
		$fileName = $fileDat->name.'.'.$fileDat->extension;
		$httpResponse = $this->context->getService('httpResponse');
		$httpResponse->setHeader('Pragma', "public");
		$httpResponse->setHeader('Expires', 0);
		$httpResponse->setHeader('Cache-Control', "must-revalidate, post-check=0, pre-check=0");
		$httpResponse->setHeader('Content-Transfer-Encoding', "binary");
		$httpResponse->setHeader('Content-Description', "File Transfer");
		if( $httpResponse->setHeader('Content-Length', filesize($file)) &&
			$this->sendResponse(new FileResponse($file,$fileName ,'application/octet-stream,application/force-download, application/download'))
		){$this->flashMessage('Súbor stiahnutý.', 'success');
		}else{
			$this->flashMessage('Problém pri sťahovaní súboru.', 'error');
		}
		if (!$this->presenter->isAjax()) {

			$this->presenter->redirect('this');
		}

		$this->invalidateControl();
	}

	public function createComponentEditHoursWorkedForm()
	{
		$form = new Form;
		$form->addText('hours_worked', 'Počet odrobených hodín')
			->setType('Number');
		$form->onSuccess[] = $this->processEditHoursWorkedForm;
		$form->addSubmit('submit');
		return $form;
	}

	public function processEditHoursWorkedForm(Form $form)
	{
		$values = $form->getValues();
		$id = $this->presenter->getParameter('taskId');
		$this->flashMessage("Počet hodín upravený");

		$this->taskModel->addEdit($values, $id);

		$this->redirect('this');
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
		$form->addText('hours_worked', 'Počet odrobených hodín')
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
			$values['created'] = new \Nette\DateTime();
			$this->flashMessage("Úloha bola pridaná");
		} else {
			$this->flashMessage("Úloha bola upravená");
		}

		$this->taskModel->addEdit($values, $id);

		$this->redirect('default', array('themeId' => $themeId));
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

	public function handleSendToEvaluation($themeId)
	{
		$theme = $this->themeModel->get($themeId);
		$theme->update(array('submitted' => new \Nette\DateTime()));

		$this->flashMessage('Zadanie bolo úspešne odovzdané');
		$this->redirect('this');
	}

	public function processAddEditFileForm(Form $form)
	{
		$values = $form->getValues();
		$taskId = $this->presenter->getParameter('taskId');
		$themeId = $this->presenter->getParameter('themeId');
		//Max file count, default 1, for task.
		$maxFileCount = 1;

		if ($taskId) {
			$task = $this->taskModel->get($taskId);
			$theme = $task->theme;
			$values['theme_id'] = $theme->id;
			$values['task_id'] = $task->id;
			$fileCount = $this->fileModel->getAll()->where('task_id', $taskId)->count();
		} else {
			$values['created'] = new \Nette\DateTime();
			$theme = $this->themeModel->get($themeId);
			$values['theme_id'] = $theme->id;
			$maxFileCount = $theme->project->max_files_count;
			$fileCount = $this->fileModel->getAll()->where('theme_id', $themeId)->count();
		}

		if ($maxFileCount > $fileCount) {
			if($values['file']->isOk()){
				$values['extension'] = pathinfo($values['file']->getName(), PATHINFO_EXTENSION);
				$values['file_name'] = Strings::webalize($values['name'].new DateTime()).'.'.$values['extension']; //TODO: datum na Y-m-d
				$urlSubject = Strings::webalize('subject'. $theme->project->subject->name, NULL, FALSE);
				$urlProject = Strings::webalize('project'.$theme->project->name, NULL, FALSE);
				$urlTheme = Strings::webalize('theme'.$theme->name, NULL, FALSE);
				$URL = $urlSubject.'/'.$urlProject.'/'.$urlTheme;
				if (!file_exists($this->context->params['wwwDir'] . '/../storage/files/'.$URL)) {
					mkdir($this->context->params['wwwDir'] . '/../storage/files/'.$URL, 0777, true);
				}
				$values['url'] = $URL.'/'.$values['file_name'];
				$values['file']->move($this->context->params['wwwDir'] . '/../storage/files/'.$values['url']);

				//DB ukladanie
				unset($values['file']);
				unset($values['file_name']);

				$values['created'] = new \Nette\DateTime;
				$this->fileModel->addEdit($values);
				$this->flashMessage("Súbor nahratý");
			}else {
				$this->flashMessage("CHYBA súboru");
			}
		} else {
			$this->flashMessage("Prekročený maximálny počet súborov");
		}

		unset($values['name']);

		$this->presenter->redirect('this');
	}

	public function createComponentAddEditCommentForm()
	{
		$form = new Form;
		$form->addTextArea('text', 'Text')
			->addRule(Form::FILLED,'Zadajte text.');
		$form->addSubmit('submit', 'Odošli');
		$form->onSuccess[] = $this->processAddEditCommentForm;
		return $form;
	}

	public function processAddEditCommentForm(Form $form)
	{
		$values = $form->getValues();
		$id = $this->presenter->getParameter('commentId');
		$values['created'] = new DateTime();
		$values['user_id'] = $this->user->id;
		$paginator = $this['paginator']->getPaginator();

		if ($this->presenter->getParameter('themeId')) {
			$values['theme_id'] = $this->presenter->getParameter('themeId');
			$this->themeModel->addEditComment($values, $id);
			$paginator->itemCount = count($this->themeModel->getAllComments($values['theme_id']));
			$this->template->comments = $this->themeModel->getComments($values['theme_id'],NULL,$paginator->offset, $paginator->itemsPerPage);
		} else {
			$values['task_id'] = $this->presenter->getParameter('taskId');
			$task = $this->taskModel->get($values['task_id']);
			$values['theme_id'] = $task->theme_id;
			$this->themeModel->addEditComment($values, $id);
			$paginator->itemCount = count($this->themeModel->getAllComments(NULL, $values['task_id']));
			$this->template->comments = $this->themeModel->getComments(NULL, $values['task_id'],$paginator->offset, $paginator->itemsPerPage);
		}
		$this['addEditCommentForm']->setValues([], TRUE);

		$this->invalidateControl('comments');
	}

}
