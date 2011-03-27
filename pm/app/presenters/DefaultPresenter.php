<?php

use Nette\Application\AppForm,
	Nette\Forms\Form;

class DefaultPresenter extends BasePresenter
{

	public function createComponentNews()
	{
		$data = new NewsControl();
		return $data;
	}

	public function createComponentContact()
	{
		$data = new ContactControl();
		return $data;
	}

	public function createComponentRegister()
	{
		$data = new RegisterControl();
		return $data;
	}

	public function renderDefault()
	{
		//nic
	}

}
