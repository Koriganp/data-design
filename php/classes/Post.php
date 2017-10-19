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
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this Post; this is the primary key
	 * @var Uuid $postId
	 */
	private $postId;
	/**
	 * id for the Profile who owns this Post; this is a foreign key
	 * @var Uuid $postProfileId
	 */
	private $postProfileId;
	/**
	 * this is the content of the post
	 * @var string $postContent
	 */
	private $postContent;
	/**
	 * this is the date post was created
	 * @var \DateTime $postDate
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
		 //convert and store the post
		$this->postId = $uuid;
	}

	/**
	 * accessor method for post profile id
	 *
	 * @return Uuid value of post profile id
	 */

	public function getPostProfileId() {
		return $this->postProfileId;
	}

	/**
	 * mutator method for post profile id
	 *
	 * @param Uuid $newPostProfileId new value of post profile id
	 * @throws \UnexpectedValueException if $newPostProfileId is not a UUID
	 */
	public function setPostProfileId($newPostProfileId) : void {
		try {
			$uuid = self::validateUuid($newPostProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert nad store the post
		$this->postProfileId = $uuid;
	}

	/**
	 * accessor method for post content
	 *
	 * @return string value of post content
	 */
	public function getPostContent() {
		return $this->postContent;
	}

	/**
	 * mutator method for post content
	 *
	 * @param string $newPostContent new value of post content
	 * @throws \InvalidArgumentException if $newPostContent is not a string or insecure
	 * @throws \RangeException if $newPostContent is > 65535 characters
	 *@throws \TypeError if $newPostContent is not a string
	 */
	public function setPostContent($newPostContent) : void {
		//verify the post content is secure
		$newPostContent = trim($newPostContent);
		$newPostContent = filter_var($newPostContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostContent) === true) {
			throw(new \InvalidArgumentException("post content is empty or insecure"));
		}

		//verify the post content will fit in the database
		if(strlen($newPostContent) > 65535) {
			throw(new \RangeException("post content too large"));
		}

		//store the post content
		$this->postContent = $newPostContent;
	}

	/**
	 * @return \DateTime value of post date
	 */
	public function getPostDate() : \DateTime {
		return($this->postDate);
	}

	/**
	 * mutator method for post date
	 *
	 * @param \DateTime|string|null $newPostDate post date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newPostDate is not a valid object or string
	 * @throws \RangeException if $newPostDate is a date that does not exist
	 **/
	public function setPostDate($newPostDate = null) : void {
		// base case: if the date is null, use the current date and time
		if($newPostDate === null) {
			$this->postDate = new \DateTime();
			return;
		}

		// store the like date using the ValidateDate trait
		try {
			$newPostDate = self::validateDateTime($newPostDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->postDate = $newPostDate;
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


