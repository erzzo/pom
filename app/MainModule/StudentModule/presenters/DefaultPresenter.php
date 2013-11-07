<?php

namespace MainModule\StudentModule;

class DefaultPresenter extends BasePresenter
{
	public function renderDefault()
	{
		dump($this->context->projectModel->getAll());
	}
}