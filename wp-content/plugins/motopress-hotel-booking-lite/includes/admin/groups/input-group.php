<?php

namespace MPHB\Admin\Groups;

use \MPHB\Admin\Fields;

abstract class InputGroup {

	/**
	 *
	 * @var \MPHB\Admin\Fields\InputField[]
	 */
	protected $fields = array();
	protected $name;
	protected $label;

	public function __construct( $name, $label ){
		$this->name	 = $name;
		$this->label = $label;
	}

	/**
	 *
	 * @param \MPHB\Admin\Fields\InputField $field
	 */
	public function addField( Fields\InputField $field ){
		$this->fields[] = $field;
	}

	/**
	 *
	 * @param \MPHB\Admin\Fields\InputField[] $fields
	 */
	public function addFields( $fields ){
		foreach ( $fields as $field ) {
			$this->addField( $field );
		}
	}

	/**
	 *
	 * @param \MPHB\Admin\Fields\InputField $field
	 * @param int $index
	 */
	public function insertField( Fields\InputField $field, $index ){
		$this->fields[$index] = $field;
	}

	/**
	 *
	 * @param int|string $key Field index or name.
	 *
	 * @return boolean true if removed, false - otherwise
	 */
	public function removeField( $key ){
		$index = is_numeric( $key ) ? intval( $key ) : $this->getIndexByName( $key );

		if ( isset( $this->fields[$index] ) ) {
			unset( $fields[$index] );
			return true;
		}

		return false;
	}

	/**
	 *
	 * @return \MPHB\Admin\Fields\InputField[]
	 */
	public function getFields(){
		return $this->fields;
	}

	/**
	 *
	 * @param string $name
	 *
	 * @return \MPHB\Admin\Fields\InputField|null Searched field or null if
	 * nothing found.
	 */
	public function getFieldByName( $name ){
		$index = $this->getIndexByName( $name );
		return ( $index != -1 ) ? $this->fields[$index] : null;
	}

	/**
	 *
	 * @param string $name
	 *
	 * @return int Field index or -1 if nothing found.
	 */
	public function getIndexByName( $name ){
		for ( $i = 0, $count = count( $this->fields ); $i < $count; $i++ ) {
			if ( $name == $this->fields[$i]->getName() ) {
				return $i;
			}
		}

		return -1;
	}

	public function getName(){
		return $this->name;
	}

	public function getLabel(){
		return $this->label;
	}

	public function setName( $name ){
		$this->name = $name;
	}

	abstract public function render();

	abstract public function save();

	public function getAttsFromRequest( $request = null, $allowReadonly = true ){

		if ( is_null( $request ) ) {
			$request = $_REQUEST;
		}

		$atts = array();

		foreach ( $this->fields as $field ) {
			if ( !$allowReadonly && $field->isReadonly() ) {
				continue;
			}
			$fieldName = $field->getName();

			if ( isset( $request[$fieldName] ) ) {

				$value = $request[$fieldName];

				$atts[$fieldName] = $field->sanitize( $value );
			}
		}

		return $atts;
	}

}
