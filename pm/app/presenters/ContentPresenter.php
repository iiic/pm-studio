<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Web\HTML;

class ContentPresenter extends BasePresenter
{

	public function renderDefault()
	{
		//$this->checkAuth();
	}



	public function renderView($id = null)
	{
		if($id == null) {
			$this->redirect('content:');
		} else {
			foreach($this->template->siteContent as $content) {
				if( ($content->p_type == 0) && ($content->hash == $id) ) {
					$this->template->title = $content->title;
					$this->template->content = $content->content;
				}
			}
		}
	}



	public function renderEdit($id)
	{
		$this->checkAuth();
		$form = $this['contentForm'];
		$this->template->id = $id;
		if(!$form->isSubmitted()) {
			$content = new MenuModel;
			$row = $content->fetchRow($id);
			if ($row) {
				$form->setDefaults($row);
			}
		}
	}



	protected function createComponentSelectSectionForm()
	{
		$menu = new MenuModel;
		$form = new AppForm;
		$form->getElementPrototype()->class('shift-left');
		$form->addGroup('Výběr obsahové sekce pro editaci');
		$form->addSelect('section', 'vyberte sekci', $menu->fetchPairs())
			->setDefaultValue($this->getParam('id'))
			->addRule(Form::FILLED, 'Musíte vybrat kterou sekci chcete upravovat.');
		$form->addSubmit('chose', 'vybrat');
		$form->onSubmit[] = callback($this, 'selectSectionFormSubmitted');
		$form->addProtection('Prosím odešlete formulář znovu (vypršel čas sezení).');
		return $form;
	}



	public function selectSectionFormSubmitted(AppForm $form)
	{
		if($form['chose']->isSubmittedBy()) {// teď je tady ta podmínka asi zbytečná
			$values = $form->values;
			$this->redirect('content:edit', array('id' => $values['section']));
		}
		$this->redirect('content:');
	}



	protected function createComponentContentForm()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro úpravu obsahu obsahových sekcí webu');
		$form->addText('title', 'Nadpis')
			->addRule(Form::FILLED, 'Musíte zadat nějaký nadpis.');
		$form->addText('hash', 'Adresa')
			->addRule(Form::FILLED, 'Musíte zadat určenovací část URI adresy.');
		$form->addTextArea('content', 'Obsah')
			->addRule(Form::FILLED, 'Musíte zadat nějaký obsah.');
		$form->addSubmit('preview', 'náhled');
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
			$menu = new MenuModel;
			$menu->updateSingle($values, $this->getParam('id'));
			$this->flashMessage('Obsah byl úspěšně uložen.');
			$this->redirect('content:');
		} elseif($form['preview']->isSubmittedBy()) {
			$this->template->content = $form->values['content'];
			$this->invalidateControl('content');
			if(!$this->isAjax()) {
				$this->redirect('this');
			}
		} else {
			$this->redirect('content:');
		}
	}

}
