<?php

class RegisterControl extends BaseControl
{

	public function render()
	{
		$this->template->setFile(APP_DIR.'/templates/Register/default.latte');
		$this->template->render();
	}

	public function createComponentRegisterForm()
	{
		$data = new RegisterPresenter();
		$data->component = true;
		return $data->createComponentRegisterForm();
	}

}
