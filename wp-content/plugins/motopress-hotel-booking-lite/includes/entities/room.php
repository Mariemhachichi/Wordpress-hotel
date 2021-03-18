<?php

namespace MPHB\Entities;

class Room {

	private $id;

	/**
	 *
	 * @param int|\WP_POST $id
	 */
	public function __construct( $post ){
		if ( is_a( $post, '\WP_Post' ) ) {
			$this->id = $post->ID;
		} else {
			$this->id = absint( $post );
		}
	}

	/**
	 *
	 * @return int
	 */
	public function getRoomTypeId(){
		return absint( get_post_meta( $this->id, 'mphb_room_type_id', true ) );
	}

	/**
	 *
	 * @return string
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 *
	 * @return string
	 */
	public function getTitle(){
		return get_the_title( $this->id );
	}

	/**
	 * Retrieve link for room post.
	 *
	 * @return string|false
	 */
	public function getLink(){
		return get_permalink( $this->id );
	}

	/**
	 * @return string[] [%syncId% => %calendarUrl%]
	 */
	public function getSyncUrls()
    {
		return MPHB()->getSyncUrlsRepository()->getUrls($this->id);
	}

	public function setSyncUrls($urls)
    {
		MPHB()->getSyncUrlsRepository()->updateUrls($this->id, $urls);
	}

}
