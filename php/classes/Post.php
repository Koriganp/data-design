<?php
/**
 * Post entity for reddit
 *
 * This is the Post entity that stores posts of Profiles.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 7.1
 **/
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;


class Post implements \JsonSerializable {
	/**
	 * id for this Post; this is the primary key
	 */
	private $postId;
	/**
	 *id for the Profile who owns this Post; this is a foreign key
	 */
	private $postProfileId;
	/**
	 *this is the content of the post
	 */
	private $postContent;
	/**
	 * this is the date post was created
	 */
	private $postDate;

	/**
	 * accessor method for post id
	 *
	 * @return Uuid value of post id
	 */
	public function getPostId(): Uuid {
		return $this->postId;
	}

	/**
	 * mutator method for post id
	 *
	 * @param Uuid $newPostId new value of post id
	 * @throws UnexpectedValueException if $newPostId is not a UUID
	 */

	public function setPostId($newPostId) : void {
		//verify the post id is valid

	}
}


