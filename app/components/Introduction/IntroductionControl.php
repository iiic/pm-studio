<?php

class IntroductionControl extends BaseControl
{

	public function render()
	{
		/*
		$data = new DefaultPresenter();
		$data->renderDefault();
		$this->template->items = $data->template->items;
	 */
		$this->template->setFile(APP_DIR.'/templates/Default/default.latte');
		$this->template->render();
	}

}
