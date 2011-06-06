<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Mail;

class ContactPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->data = ContactModel::fetch();
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



	protected function createComponentContactForm()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro úpravu sekce kontakt');
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
		$form->addText('email', 'Email příjemce vzkazů')
			->addRule(Form::FILLED, 'Zapoměl jste vyplnit váš email.')
			->addRule(Form::EMAIL, 'Zadejte platnou emailovou adresu na kterou budou uživatelé psát.');
		$form->addSubmit('preview', 'náhled')->setAttribute('class', 'default');
		$form->addSubmit('save', 'uložit');
		$form->addButton('null','původní hodnoty')->setAttribute('type', 'reset');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'contactFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}

	public function contactFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			unset($values['null']);
			$contact = new ContactModel;
			$contact->update($values);
			$this->flashMessage('sekce kontakt byla úspěšně upravena.');
			$this->redirect('contact:');// @todo : přesměrovat na předchozí stránku
		} elseif($form['preview']->isSubmittedBy()) {
			$this->template->content = $form->values['content'];
			$this->invalidateControl('content');
			if(!$this->isAjax()) {
				$this->redirect('this');
			}
		} else {
			$this->redirect('contact:');
		}
	}

	public function createComponentMailForm()
	{
		$form = new AppForm;
		$form->addGroup('Formulář pro rychlé zaslání emailu');
		$form->addText('shipper', 'Jméno odesílatele')
			->addRule(Form::FILLED, 'Zadejte prosím vaše jméno.');
		$form->addText('email', 'Email odesílatele')
			//->setEmptyValue('@')// otravné
			->addRule(Form::FILLED, 'Zadejte prosím vaši emailovou adresu.')
			->addRule(Form::EMAIL, 'Zadaný email není platný.');
		$form->addTextArea('content', 'Vzkaz')
			->addRule(Form::FILLED, 'Prosím vložte obsah.');
		$form->addSubmit('send', 'odeslat e-mail');
		$form->addButton('null','smazat obsah')->setAttribute('type', 'reset');
		$form->onSubmit[] = callback($this, 'mailFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}

	public function mailFormSubmitted(AppForm $form)
	{
		if($form['send']->isSubmittedBy()) {
			$values = $form->values;
			unset($values['null']);
			$mail = new Mail;
			$mail->setFrom('Franta <franta@example.com>');
			$mail->addTo('petr@example.com');
			$mail->setSubject('Potvrzení objednávky');
			$mail->setHTMLBody('<b>Mail odeslaný ze stránek</b> text mailu zde');
			$mail->send();
			$this->redirect('contact:');
		}
	}

}
