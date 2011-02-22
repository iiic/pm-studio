<?php

class ContactControl extends BaseControl
{

	public function render()
	{
		$data = new ContactPresenter();
		$data->renderDefault();
		$this->template->data = $data->template->data;
		$this->template->setFile(APP_DIR.'/templates/Contact/default.latte');
		$this->template->render();
	}

	public function createComponentMailForm()
	{
		$data = new ContactPresenter();
		return $data->createComponentMailForm();
	}

}
