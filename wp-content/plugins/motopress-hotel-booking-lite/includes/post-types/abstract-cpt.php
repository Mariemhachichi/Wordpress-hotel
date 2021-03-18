<?php

namespace MPHB\PostTypes;

abstract class AbstractCPT {

	protected $postType;

	/**
	 *
	 * @var string
	 */
	protected $capability = 'edit_post';

	/**
	 *
	 * @var \MPHB\Admin\Groups\MetaBoxGroup[]
	 */
	protected $fieldGroups = array();

	public function __construct(){
		$this->addActions();
	}

	protected function addActions(){
		add_action( 'init', array( $this, 'register' ), 6 );
	}

	abstract public function register();

	public function getPostType(){
		return $this->postType;
	}

}
