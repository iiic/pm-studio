<?php

use Nette\Application\AppForm,
	Nette\Forms\Form,
	Nette\Finder,
	Nette\String;

class MultimediaPresenter extends BasePresenter
{

	private $miniatures = array(
		'null' => 'nevytvářet miniaturu',
		'16:9' => array(
			'320x180' => 'vytvořit 320 × 180',
			'304x171' => 'vytvořit 304 × 171',
			'288x162' => 'vytvořit 288 × 162',
			'272x153' => 'vytvořit 272 × 153',
			'256x144' => 'vytvořit 256 × 144',
			'240x135' => 'vytvořit 240 × 135',
			'224x126' => 'vytvořit 224 × 126',
			'208x117' => 'vytvořit 208 × 117',
		),
		'4:3' => array(
			'320x240' => 'vytvořit 320 × 240',
			'300x225' => 'vytvořit 300 × 225',
			'280x210' => 'vytvořit 280 × 210',
			'240x180' => 'vytvořit 240 × 180',
			'200x150' => 'vytvořit 200 × 150',
			'160x120' => 'vytvořit 160 × 120',
			'120x90' => 'vytvořit 120 × 90',
		),
	);

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
		if(file_exists('images/'.$name)) {
			unlink('images/'.$name);
		}
		if(file_exists('images/mini/'.$name)) {
			unlink('images/mini/'.$name);
		}
		$this->flashMessage('Obrázek byl odstraněn');
		$this->redirect('multimedia:');
	}



	protected function createComponentAddImage()
	{
		$form = new AppForm;
		$form->getElementPrototype()->class('shift-left');
		$form->addGroup('Formulář pro přidání obrázku');
		$form->addFile('photo', 'obrázek')
			->addRule(Form::IMAGE, 'Povolené jsou pouze obrázky')
			->addRule(Form::MAX_FILE_SIZE, 'Fotografie nesmí být větší než 5MB', '5000000');
		$form->addSelect('miniature', 'miniatura', $this->miniatures)
			->setDefaultValue('null')
			->addRule(Form::FILLED, 'Zvolte prosím druh miniatury.');
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
					$extension = 'bmp';
				break;
				case('image/x-windows-bmp'):// nevím jestli tenhle případ může nastat... tyto mime sice existují ale nette možná vrací jen ty logické a né i tyhle zbytečné
					$extension = 'bmp';
				break;
				case('image/gif'):
					$extension = 'gif';
				break;
				case('image/x-icon'):
					$extension = 'ico';
				break;
				case('image/gif'):
					$extension = 'gif';
				break;
				case('image/png'):
					$extension = 'png';
				break;
				case('image/tiff'):
					$extension = 'tiff';
				break;
				case('image/x-tiff'):
					$extension = 'tiff';
				break;
				default:
					$extension = 'jpeg';
				break;
			}
			if(strlen($name[1]) > 240) {
				throw new Nette\Application\BadRequestException('Jméno souboru je příliš dlouhé');// @toto : vypsat to jako chybu validace formuláře
				// return chyba
			}
			$uri = $name[1].'.'.$extension;
			if(file_exists('images/'.$uri)) {// pokud uz tady tento soubor existuje
				$i = 0;
				do {// cyklus s podminkou na konci
					$i++;
				} while(file_exists($name[1].$i.'.'.$extension));
				$uri = $name[1].$i.'.'.$extension;
			}
			$image->save('images/'.$uri, 100);
			if($values['miniature'] != 'null') {
				$dimensions = explode('x', $values['miniature']);
				$image->resize($dimensions[0], $dimensions[1]);
				$image->save('images/mini/'.$uri, 80);
			}
		} else {
			$this->flashMessage('Nahraná fotografie není úplná', 'error');
		}
		$this->redirect('multimedia:');
	}

}
