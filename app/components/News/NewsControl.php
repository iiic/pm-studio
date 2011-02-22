<?php

class NewsControl extends BaseControl
{

	public function render()
	{
		$data = new NewsPresenter();
		$data->renderDefault();
		$this->template->items = $data->template->items;
		$this->template->setFile(APP_DIR.'/templates/News/default.latte');
		$this->template->render();
	}

}
