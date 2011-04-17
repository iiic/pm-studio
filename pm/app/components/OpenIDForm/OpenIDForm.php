<?php

/**
 * OpenID Authentication Form
 *
 * Copyright (c) 2010 Stanislav Kocanda (http://www.stanleyk.net), Miroslav Tynovsky
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 *
 */

namespace OpenIDForm;

use Nette,
	Nette\Debug,
	Nette\Application\AppForm;

/**
 * OpenID Authentication Form control
 *
 * @author     Stanislav Kocanda, Miroslav Tynovsky
 */
class OpenIDForm extends Nette\Application\Control
{
	/** form field name */
	const OID_FIELD = 'openid_identifier';

	/** name of the signal processing OP redirection */
	const PROCESS_SIGNAL = 'process';

	/** name of the form component */
	const FORM_COMPONENT = 'identifierForm';

	/** @var array of function($identity, $attributes);
	 *  These functions are called when the user authentication succeeds */
	public $onSignin;

    /** @var string
     * Message shown when user cancels signing in */
    public $cancelMsg;

    /** @var string
     * Message shown when signing in goes wrong */
    public $errorMsg;

    /** @var string
     * Label of the form field */
    public $formLabel;

    /** @var string
     * Message shown if the form field is submitted empty */
    public $formEmptyMsg;

	/** @var LightOpenID */
	protected $openid;

	/** @var Nette\ITranslator */
	private $translator;


	private $formCaption;

	/**
	 * Component constructor.
	 * @param  Nette\IComponentContainer
	 * @param  string
	 */
	public function __construct(
		Nette\IComponentContainer $parent = NULL, $name = NULL
	) {
			parent::__construct( $parent, $name );

			$this->openid    = new \LightOpenID;
			$this->errorMsg  = 'Zrušil jste autentizaci!';
			$this->cancelMsg = 'Autentizace selhala!';
			$this->formLabel = 'Vaše OpenID';
			$this->formEmptyMsg = 'Prosím zadejte své OpenID!';
			$this->formCaption = 'Formulář pro přihlášení pomocí OpenID';
	}

	/**
	 * Sets required fields in the AX format.
	 * Accepts either string or an array of strings
	 * @param  string|array 
	 * @return OpenIDForm provides a fluent interface
	 */
	public function setRequired( $required ) {
		if ( is_array( $required ) ) {
			$this->openid->required = array_merge(
					 $this->openid->required,
					 $required
			);
		}
		else {
			$this->openid->required[] = $required;
		}
		return $this;
	}

	/**
	 * Sets optional fields in the AX format.
	 * Accepts either string or an array of strings
	 * @param  string|array 
	 * @return OpenIDForm provides a fluent interface
	 */
	public function setOptional( $optional ) {
		if ( is_array( $optional ) ) {
			$this->openid->optional = array_merge(
					 $this->openid->optional,
					 $optional
			);
		}
		else {
			$this->openid->optional[] = $optional;
		}
		return $this;
	}

	/**
	 * Set translate adapter 
	 * @param Nette\Itranslator
	 * @return OpenIDForm provides a fluent interface
	 */
	public function setTranslator( Nette\ITranslator $translator = NULL ) { 
		$this->translator = $translator;
		return $this;
	}

	/**
	 * Returns translate adapter.
	 * @return Nette\ITranslator|NULL
	 */
	final public function getTranslator()
	{
		return $this->translator;
	}

	/**
	 * Component rendering
	 */
	public function render() {
		$template = $this->template;
		$template->setFile(__DIR__ . '/default.phtml');
		$template->render();
	}

	/**
	 * Handle the process! signal.
	 * This signal occurs after a successful redirection back from the OP
	 */
	public function handleProcess() {
        if ( ! $this->openid->mode ) {
            return;
        }

		if ( $this->openid->mode == 'cancel') {
			$this[ self::FORM_COMPONENT ]->addError( $this->cancelMsg );
            return;
		}

		$this->openid->returnUrl = $this->returnUrl( self::PROCESS_SIGNAL );
        if ( ! $this->openid->validate() ) {
            $this[ self::FORM_COMPONENT ]->addError( $this->errorMsg );
            return;
        }

        $this->onSignin(
            $this->openid->identity,
            $this->openid->getAttributes()
        );
	}

	/**
	 * Create the form itself
	 */
	public function createComponentIdentifierForm() {
		$form = new AppForm;
		$form->getElementPrototype()->class('shift-left');
		$form->addGroup($this->formCaption);
		if ( $this->translator ) {
			$form->setTranslator( $this->translator );
		}
		$form->addText( self::OID_FIELD, $this->formLabel)
			->addRule( Nette\Forms\Form::FILLED, $this->formEmptyMsg );
		$form->addSubmit( 'login', 'Přihlásit' );
		$form->onSubmit[] = array( $this, 'processIdentifier' );
		return $form;
	}

	/**
	 * The form onSubmit function
	 * @param AppForm
	 */
	public function processIdentifier( AppForm $form ) {
		$values = $form->values;
		$this->openid->identity = $values[ self::OID_FIELD ];
		$this->openid->returnUrl = $this->returnUrl( self::PROCESS_SIGNAL );
		try {	
			header( 'Location: ' . $this->openid->authUrl() );
			exit();
		}
		catch ( \ErrorException $e ) {
			$form->addError( $e->getMessage() );
		}
	}

	/**
	 * Compose the return URL for the OpenId request.
	 * Includes the processing signal call.
	 * @param string
	 */
	protected function returnUrl( $signal ) {
		return $this->openid->trustRoot .
			$this->getParent()->link( $this->getName() . ':' . $signal . '!' );
	}
}
