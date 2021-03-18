<?php

namespace MPHB\PostTypes;

use \MPHB\Admin\EditCPTPages;
use \MPHB\Admin\ManageCPTPages;

abstract class EditableCPT extends AbstractCPT {

	/**
	 *
	 * @var EditCPTPages\EditCPTPage
	 */
	protected $editPage;

	/**
	 *
	 * @var ManageCPTPages\ManageCPTPage
	 */
	protected $managePage;

	protected function addActions(){
		parent::addActions();
		add_action( 'init', array( $this, 'initEditPage' ) );
		add_action( 'init', array( $this, 'initManagePage' ) );
	}

	public function initEditPage(){
		$this->editPage = $this->createEditPage();
	}

	public function initManagePage(){
		$this->managePage = $this->createManagePage();
	}

	/**
	 *
	 * @return \MPHB\Admin\EditCPTPages\EditCPTPage
	 */
	protected function createEditPage(){
		return new EditCPTPages\EditCPTPage( $this->postType, $this->getFieldGroups() );
	}

	/**
	 *
	 * @return \MPHB\Admin\ManageCPTPages\ManageCPTPage
	 */
	protected function createManagePage(){
		return new ManageCPTPages\ManageCPTPage( $this->postType );
	}

	/**
	 *
	 * @return EditCPTPages\EditCPTPage
	 */
	public function getEditPage(){
		return $this->editPage;
	}

	/**
	 *
	 * @return ManageCPTPages\ManageCPTPage
	 */
	public function getManagePage(){
		return $this->managePage;
	}

	public function getMenuSlug(){
		return 'edit.php?post_type=' . $this->getPostType();
	}

	public function registerMetaBoxes(){
		do_action( "mphb_register_{$this->postType}_metaboxes" );
	}

	abstract public function getFieldGroups();
}
