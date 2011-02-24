<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class ContentPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->checkAuth();
	}



	protected function createComponentSelectSectionForm()
	{
		$menu = new MenuModel;
		$form = new AppForm;
		$form->addGroup('Výběr obsahové sekce pro editaci:');
		$form->addSelect('section', 'vyberte sekci', $menu->fetchPairs())
			->addRule(Form::FILLED, 'Musíte vybrat kterou sekci chcete upravovat.');
		$form->addSubmit('chose', 'vybrat');
		$form->onSubmit[] = callback($this, 'selectSectionFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}



	public function selectSectionFormSubmitted(AppForm $form)
	{
		if($form['chose']->isSubmittedBy()) {
			$values = $form->values;
			$this->redirect('content:', array('id' => $values['section']));
		}
		$this->redirect('content:');
	}



	protected function createComponentContentForm()
	{
		$form = new AppForm;
		$form->addGroup('Formulář pro úpravu nastavení webu');
		$form->addTextArea('content', 'Text')
			->addRule(Form::FILLED, 'Prosím zadejte titulek těchto stránek.');
		$form->addSubmit('save', 'uložit');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'contentFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}



	public function contentFormSubmitted(AppForm $form)
	{
		if($form['save']->isSubmittedBy()) {
			$values = $form->values;
			//úpravy values !
			$menu = new MenuModel;
			$menu->updateSingle($values);
			$this->flashMessage('Obsah byl úspěšně uložen.');
		}
		$this->redirect('content:');
	}

}
