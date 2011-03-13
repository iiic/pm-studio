<?php

class MenuControl extends BaseControl
{

	private $menu;

	public function __construct($data) {
		$this->menu = $data;
	}

	public function render()
	{
		$this->template->items = $this->menu;
		$this->template->setFile(__DIR__.'/menu.latte');
		$this->template->render();
	}

}
