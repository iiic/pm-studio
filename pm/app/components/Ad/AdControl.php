<?php

class AdControl extends BaseControl
{

	private $printMaterial;
	private $printColors;
	private $cardSize;
	private $cardColors;
	private $carSize;
	private $carColors;

	public function __construct() {
		$this->template->data = AdModel::fetch();
		$this->printMaterial = explode('
', $this->template->data->printMaterial); // odenterování se dělá takhle blbě XD
		$this->printColors = explode('
', $this->template->data->printColors);
		$this->cardSize = explode('
', $this->template->data->cardSize);
		$this->cardColors = explode('
', $this->template->data->cardColors);
		$this->carSize = explode('
', $this->template->data->carSize);
		$this->carColors = explode('
', $this->template->data->carColors);
	}

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
		$data->printMaterial = $this->printMaterial;
		$data->printColors = $this->printColors;
		$data->cardSize = $this->cardSize;
		$data->cardColors = $this->cardColors;
		$data->carSize = $this->carSize;
		$data->carColors = $this->carColors;
		return $data->createComponentAdForm();
	}

}
