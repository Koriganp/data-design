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

require_once(dirname(__DIR__, 2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;


class Post implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
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
	 * constructor for this Post
	 *
	 * @param Uuid $newPostId id of this Post or null if a new Post
	 * @param Uuid $newPostProfileId id of the Profile that wrote this Post
	 * @param string $newPostContent string containing actual post data
	 * @param \DateTime|string|null $newPostDate date and time post was made or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	public function __construct($newPostId, $newPostProfileId, string $newPostContent, $newPostDate = null) {
		try {
			$this->setPostId($newPostId);
			$this->setPostProfileId($newPostProfileId);
			$this->setPostContent($newPostContent);
			$this->setPostDate($newPostDate);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

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
	 * @throws \UnexpectedValueException if $newPostId is not a UUID
	 */

	public function setPostId($newPostId) : void {
		try {
			$uuid = self::validateUuid($newPostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
				$exceptionType = get_class($exception);
				throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		 //convert nad store the post
		$this->postId = $uuid;
	}





	public function jsonSerialize() {
		$fields = get_object_vars($this);

		$fields["postId"] = $this->postId;
		$fields["postProfileId"] = $this->postProfileId;
		$fields["postContent"] = $this->postContent;

		//format the date so that the front end can consume it
		$fields["postDate"] = round(floatval($this->postDate->format("U.u")) * 1000);
		return($fields);
	}
}


