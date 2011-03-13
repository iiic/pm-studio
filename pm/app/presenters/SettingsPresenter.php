<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class SettingsPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->checkAuth();
		$form = $this['settingsForm'];
		if(!$form->isSubmitted()) {
			$settings = new SettingsModel;
			$row = $settings->fetch();
			if(!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$row['admins'] = explode('|', $row['admins']);// rozdělení do pole podle oddělovače
			$form->setDefaults($row);
		}
	}



	protected function createComponentSettingsForm()
	{
		$users = new UsersModel;
		$form = new AppForm;
		$form->getElementPrototype();
		$form->addGroup('Formulář pro úpravu nastavení webu');
		$form->addText('title', 'Titulek (nadpis)')
			->addRule(Form::FILLED, 'Prosím zadejte titulek těchto stránek.');
		$form->addText('description', 'Popis stránek')
			->addRule(Form::FILLED, 'Prosím zadejte popis stránek.');
		$form->addText('keywords', 'Klíčová slova')
			->addRule(Form::FILLED, 'Prosím vložte klíčová slova.')
			->setOption('description', Html::el('small')
				->setText('(velmi málá důležitost)')
				->setClass('description')
			);
		$form->addText('important_title', 'Nadpis důležité zprávy')
			->addRule(Form::FILLED, 'Nadpis důležité zprávy nemůže zůstat prázdný');
		$form->addText('important', 'Důležitá zpráva');
		$form->addSelect('important_efect', 'Efekt zobrazení důležité zprávy', array('bez efektu', 'psací stroj (js)'));
		$form->addSelect('style', 'Defaultní vzhled', array('black'));
		$form->addText('robots', 'Robots')
			->setOption('description', Html::el('small')
				->setText('(prázdná hodnota odpovídá zápisu \'all\', tedy \'index, follow\')')
				->setClass('description')
			);
		$form->addMultiSelect('admins', 'Administrátoři', $users->getUserList(), 10)
			->setOption('description', Html::el('small')
				->setText('(můžete vybrat více administrátorů)')
				->setClass('description')
			);
		$form->addSubmit('save', 'uložit');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'settingsFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}



	public function settingsFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			$values['admins'] = join('|', $values['admins']);
			$settings = new SettingsModel;
			$settings->update($values);
			$this->flashMessage('Úpravy nastavní byly uloženy.');
		}
		$this->redirect('settings:');
	}

}
