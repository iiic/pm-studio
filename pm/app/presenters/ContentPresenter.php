<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class ContentPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->checkAuth();
		$form = $this['contentForm'];
		if(!$form->isSubmitted()) {
			//$menu = new MenuModel;
			//$row = $menu->fetch();
			//if(!$row) {
			//	throw new Nette\Application\BadRequestException('Nepodařilo se načíst data');
			//}
			//$form->setDefaults($row);
		}
	}



	protected function createComponentSelectSectionForm()
	{
		$menu = new MenuModel;
		$form = new AppForm;
		$form->addGroup('Výběr obsahové sekce pro editaci:');
		$form->addSelect('p11', '11. položka', $menu->fetchPairs())
			->addRule(Form::FILLED, 'Musíte vybrat kterou sekci chcete upravovat.');
		$form->addSubmit('save', 'uložit');
		$form->addSubmit('cancel', 'zrušit')->setValidationScope(NULL);
		$form->onSubmit[] = callback($this, 'contentFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
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
