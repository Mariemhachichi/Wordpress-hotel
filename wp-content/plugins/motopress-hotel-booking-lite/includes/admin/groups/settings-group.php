<?php

namespace MPHB\Admin\Groups;

use \MPHB\Admin\Fields;

class SettingsGroup extends InputGroup {

	protected $name;
	protected $page;
	protected $description;

	/**
	 * @note that name of group must
	 *
	 * @param string $name
	 * @param string $label Optional.
	 * @param string $page
	 * @param string $description Optional.
	 */
	public function __construct( $name, $label = '', $page, $description = '' ){
		parent::__construct( $name, $label );
		$this->description	 = $description;
		$this->page			 = $page;
	}

	/**
	 *
	 * @param \MPHB\Admin\Fields\InputField $field
	 */
	public function addField( Fields\InputField $field ){
		// @todo temporary solution. move this code
		switch ( $field->getName() ) {
			case 'mphb_template_mode':
				$value	 = MPHB()->settings()->main()->getTemplateMode();
				break;
			case 'mphb_email_base_color':
				$value	 = MPHB()->settings()->emails()->getBaseColor();
				break;
			case 'mphb_email_bg_color':
				$value	 = MPHB()->settings()->emails()->getBGColor();
				break;
			case 'mphb_email_body_bg_color':
				$value	 = MPHB()->settings()->emails()->getBodyBGColor();
				break;
			case 'mphb_email_body_text_color':
				$value	 = MPHB()->settings()->emails()->getBodyTextColor();
				break;
			default:
				$value	 = get_option( $field->getName(), $field->getDefault() );
				break;
		}

		$field->setValue( $value );
		parent::addField( $field );
	}

	public function register(){
		add_settings_section( $this->getName(), $this->getLabel(), array( $this, 'render' ), $this->getPage() );
		foreach ( $this->fields as $field ) {
			register_setting( $this->getName(), $field->getName() );
			add_settings_field( $field->getName(), $field->getLabel(), array( $field, 'output' ), $this->getPage(), $this->getName() );
		}
	}

	public function getPage(){
		return $this->page;
	}

	public function render(){
		if ( !empty( $this->description ) ) {
			echo '<p>' . $this->description . '</p>';
		}
	}

	public function save(){
		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[$field->getName()] ) ) {
				$value	 = $_POST[$field->getName()];
				$value	 = wp_unslash( $value );
				$value	 = $field->sanitize( $value );
				update_option( $field->getName(), $value );
				if ( $field->isTranslatable() ) {
					MPHB()->translation()->registerWPMLString( $field->getName(), $value );
				}
			}
		}
	}

}
