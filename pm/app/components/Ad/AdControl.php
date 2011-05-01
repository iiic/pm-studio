<?php

class AdControl extends BaseControl
{

	public function render()
	{
		$data = new AdPresenter();
		$data->renderDefault();
		$this->template->data = $data->template->data;
		$this->template->setFile(APP_DIR.'/templates/Ad/default.latte');
		$this->template->render();
	}

	public function createComponentAdForm()
	{
		$data = new AdPresenter();
		return $data->createComponentAdForm();
	}

}
