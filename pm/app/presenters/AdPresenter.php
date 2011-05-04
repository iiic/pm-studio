<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\Html;

class AdPresenter extends BasePresenter
{

	public $printMaterial;
	public $printColors;
	public $cardSize;
	public $cardColors;
	public $carSize;
	public $carColors;

	public function renderDefault()
	{
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

	public function renderEdit()
	{
		$this->checkAuth();
		$form = $this['adEditForm'];
		if (!$form->isSubmitted()) {
			$ad = new AdModel;
			$row = $ad->fetch();
			if (!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$form->setDefaults($row);
		}
	}



	public function createComponentAdForm()
	{
		$form = new AppForm;

		$form->addCheckbox('print', 'velkoplošný tisk')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('print-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('print-box'));
		$form->addSelect('printMaterial', 'tisk na:', $this->printMaterial);
		$form->addSelect('printColors', 'barevnost:', $this->printColors);
		$form->addText('printArea', 'plocha:');
		// možná ještě výlep (ve vlastní režii / odborný od nás)
		$form->addText('printCount', 'počet kusů:');
		$form->setCurrentGroup(null);

		$form->addCheckbox('card', 'vizitky')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('card-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('card-box'));
		$form->addSelect('cardSize', 'rozměr:', $this->cardSize);
		$form->addSelect('cardColors', 'barevnost:', $this->cardColors);
		$form->addText('cardCount', 'počet kusů:');
		$form->setCurrentGroup(null);

		$form->addCheckbox('car', 'originální samolepky na auta')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('car-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('car-box'));
		$form->addSelect('carSize', 'rozměr:', $this->carSize);
		$form->addSelect('carColors', 'barevnost:', $this->carColors);
		$form->addText('carCount', 'počet kusů:');
		$form->setCurrentGroup(null);

		$form->addSubmit('save', 'spočítat');
		$form->addButton('null','původní hodnoty')->setAttribute('type', 'reset');
		$form->onSubmit[] = callback($this, 'adFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}

	public function adFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			unset($values['null']);
			// nějaké rozumné vyhodnocení
		} else {
			$this->redirect('ad:');
		}
	}



	protected function createComponentAdEditForm()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro úpravu sekce reklama');
		$form->addText('title', 'Nadpis')
			->addRule(Form::FILLED, 'Prosím zadejte nadpis.');
		$form->addTextArea('content', 'Obsah')
			->addRule(Form::FILLED, 'Prosím vložte obsah.')
			->setOption('description', Html::el('a')
				->setText('texy! syntax')
				->href('http://www.texy.info/cs/syntax')
				->title('kliknutím otevřete novou stránku s informacemi o syntaxi')
				->onclick('return !window.open(this.href)')
			);
		$form->addTextArea('printMaterial', 'select \'tisk na\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addTextArea('printColors', 'select \'barvy tisku\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addTextArea('cardSize', 'select \'rozměr vizitek\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addTextArea('cardColors', 'select \'barvy vizitek\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addTextArea('carSize', 'select \'rozměr samolepek\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addTextArea('carColors', 'select \'barvy samolepek\'')
			->addRule(Form::FILLED, 'zadejte možnosti tohoto selektu');
		$form->addText('vatRatio', 'koeficient DPH')
			->addRule(Form::FILLED, 'Prosím zadejte platný koeficient pro výpočet DPH.');
		$form->addSubmit('preview', 'náhled')->setAttribute('class', 'default');
		$form->addSubmit('save', 'uložit');
		$form->addButton('null','původní hodnoty')->setAttribute('type', 'reset');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'adEditFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}

	public function adEditFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			unset($values['null']);
			$ad = new AdModel;
			$ad->update($values);
			$this->flashMessage('sekce reklama byla úspěšně upravena.');
			$this->redirect('ad:');// @todo : přesměrovat na předchozí stránku
		} elseif($form['preview']->isSubmittedBy()) {
			$this->template->content = $form->values['content'];
			$this->invalidateControl('content');
			if(!$this->isAjax()) {
				$this->redirect('this');
			}
		} else {
			$this->redirect('ad:');
		}
	}

}
