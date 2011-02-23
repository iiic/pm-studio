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



	public function renderDefault()
	{
		$this->template->data = IntroductionModel::fetch();
	}

	public function renderEdit()
	{
		$this->checkAuth();
		$form = $this['introductionForm'];
		if (!$form->isSubmitted()) {
			$introduction = new IntroductionModel;
			$row = $introduction->fetch();
			if (!$row) {
				throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			}
			$form->setDefaults($row);
		}
	}



	protected function createComponentIntroductionForm()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro úpravu úvodní stránky');
		$form->addText('title', 'Nadpis:')
			->addRule(Form::FILLED, 'Prosím zadejte nadpis.');
		$form->addTextArea('content', 'Obsah:')
			->addRule(Form::FILLED, 'Prosím vložte obsah.');
		$form->addSubmit('preview', 'náhled')->setAttribute('class', 'default');
		$form->addSubmit('save', 'uložit');
		$form->addButton('null','původní hodnoty')->setAttribute('type', 'reset');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'introductionFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}

	public function introductionFormSubmitted(AppForm $form)
	{
		if( ($form['save']->isSubmittedBy()) || ($form['preview']->isSubmittedBy()) ) {
			$values = $form->values;
			unset($values['null']);
			$introduction = new IntroductionModel;
			$introduction->update($values);
			$this->flashMessage('Úvodní sekce byla úspěšně upravena.');
			if($form['preview']->isSubmittedBy()) {
				$this->template->content = $introduction->getContent();
				$this->invalidateControl('content');
			}
			if((!$this->isAjax()) || ($form['save']->isSubmittedBy())) {
				// @todo : přesměrovat na předchozí stránku
				$this->redirect('default:');
			}
		} else {
			$this->redirect('default:');
		}
	}

}
