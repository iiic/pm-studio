<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Finder,
	Nette\String;

class MultimediaPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$data = array();
		foreach (Finder::findFiles('*')->in('images') as $file) {
			$data[] = (object) array(
				'name'=> $file->getFilename(),
				'size' => $file->getSize(),
				'time' => $file->getMTime(),
			);
		}
		$this->template->multimedia = (object) $data;
	}



	public function renderAdd()
	{
		// nic netřeba
	}



	public function renderDelete($name)
	{
		unlink('images/'.$name);
		unlink('images/full/'.$name);
		$this->flashMessage('Obrázek i miniatura byla úspěšně smazána', 'ok');
		$this->redirect('multimedia:');
	}



	protected function createComponentAddImage()
	{
		$form = new AppForm;
		//$form->getElementPrototype()->class('ajax');
		$form->addGroup('Formulář pro přidání obrázku');
		$form->addFile('photo', 'obrázek:')
			->addRule(Form::IMAGE, 'Povolené jsou pouze obrázky')
			->addRule(Form::MAX_FILE_SIZE, 'Fotografie nesmí být větší než 5MB', '5000000');
		$form->addSubmit('save', 'uložit');
		$form->onSubmit[] = callback($this, 'addImageSubmitted');
		return $form;
	}

	public function addImageSubmitted(AppForm $form)
	{
		$values = $form->values;
		if( ($values['photo']->isOk()) && ($values['photo']->isImage()) ) {
			$image = $values['photo']->toImage();
			$name = String::match($values['photo']->getName(), '~^(.+)\.[0-9a-z]+$~i');
			switch($values['photo']->getContentType()) {
				case('image/bmp'):
					$pripona = 'bmp';// @todo : zjistit jak se anglicky řekne přípona
				break;
				case('image/x-windows-bmp'):// nevím jestli tenhle případ může nastat... tyto mime sice existují ale nette možná vrací jen ty logické a né i tyhle zbytečné
					$pripona = 'bmp';
				break;
				case('image/gif'):
					$pripona = 'gif';
				break;
				case('image/x-icon'):
					$pripona = 'ico';
				break;
				case('image/gif'):
					$pripona = 'gif';
				break;
				case('image/png'):
					$pripona = 'png';
				break;
				case('image/tiff'):
					$pripona = 'tiff';
				break;
				case('image/x-tiff'):
					$pripona = 'tiff';
				break;
				default:
					$pripona = 'jpeg';
				break;
			}
			if(strlen($name[1]) > 240) {
				throw new Nette\Application\BadRequestException('Jméno souboru je příliš dlouhé');// @toto : vypsat to jako chybu validace formuláře
			}
			$uri = $name[1].'.'.$pripona;
			if(file_exists('images/'.$uri)) {// pokud uz tady tento soubor existuje
				$i = 0;
				do {// cyklus s podminkou na konci
					$i++;
				} while(file_exists($name[1].$i.'.'.$pripona));
				$uri = $name[1].$i.'.'.$pripona;
			}
			$image->save('images/full/'.$uri, 100);
			$image->resize(320, 180);
			$image->save('images/'.$uri, 80);
		} else {
			$this->flashMessage('Nahraná fotografie není úplná', 'error');
		}
		$this->redirect('multimedia:');
	}

}
