<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Mail,
	Nette\Web\Html;

class AdPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->data = AdModel::fetch();
	}

	public function renderEdit()
	{
		$this->checkAuth();
		$form = $this['contactForm'];
		if (!$form->isSubmitted()) {
			$contact = new ContactModel;
			$row = $contact->fetch();
			if (!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$form->setDefaults($row);
		}
	}



	protected function createComponentAdForm()
	{
		$form = new AppForm;

		$form->addCheckbox('print', 'velkoplošný tisk')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('print-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('print-box'));
		$form->addSelect('printMaterial', 'tisk na:', array('plakátový papíř | 20 Kč / m2', 'samolepicí fólie | 40 Kč / m2', 'billboardový papír | 20 Kč / m2', 'papír pro Citylighty | 25 Kč / m2'));
		$form->addSelect('printColors', 'barevnost:', array('černobýlý | 20 Kč / m2', 'monochromatický | 40 Kč / m2', '4 barvy | 60 Kč / m2', 'plnobarevné | 90 Kč / m2'));
		$form->addText('printArea', 'plocha:');
		// možná ještě výlep (ve vlastní režii / odborný od nás)
		$form->addText('printCount', 'počet kusů:');
		$form->setCurrentGroup(null);

		$form->addCheckbox('card', 'vizitky')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('card-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('card-box'));
		$form->addSelect('cardSize', 'rozměr:', array('90x50 mm (standardní) | 0.5 Kč', '85x55 mm (Euro formát) | 0.4 Kč', '75x40 mm (běžné v UK) | 0.4 Kč', '80x45 mm (širokoúhlé) | 0.5 Kč'));
		$form->addSelect('cardColors', 'barevnost:', array('černobýlý | 0.2 Kč', 'monochromatický | 0.4 Kč', '4 barvy | 0.5 Kč', 'plnobarevné | 0.75 Kč'));
		$form->addText('cardCount', 'počet kusů:');
		$form->setCurrentGroup(null);

		$form->addCheckbox('car', 'originální samolepky na auta')
			->addCondition(Form::EQUAL, TRUE) // conditional rule: if is checkbox checked...
				->toggle('car-box'); // toggle div #sendBox
		$form->addGroup()
			->setOption('container', Html::el('div')->id('car-box'));
		$form->addSelect('carSize', 'rozměr:', array('90x50 mm (standardní) | 0.5 Kč', '85x55 mm (Euro formát) | 0.4 Kč', '75x40 mm (běžné v UK) | 0.4 Kč', '80x45 mm (širokoúhlé) | 0.5 Kč'));
		$form->addSelect('carColors', 'barevnost:', array('černobýlý | 0.2 Kč', 'monochromatický | 0.4 Kč', '4 barvy | 0.5 Kč', 'plnobarevné | 0.75 Kč'));
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
			$contact = new ContactModel;
			$contact->update($values);
			$this->flashMessage('sekce kontakt byla úspěšně upravena.');
			$this->redirect('contact:');// @todo : přesměrovat na předchozí stránku
		} else {
			$this->redirect('contact:');
		}
	}

}
