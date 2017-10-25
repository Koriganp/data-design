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
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;


class Post implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this Post; this is the primary key
	 * @var Uuid $postId
	 **/
	private $postId;
	/**
	 * id for the Profile who owns this Post; this is a foreign key
	 * @var Uuid $postProfileId
	 **/
	private $postProfileId;
	/**
	 * this is the content of the post
	 * @var string $postContent
	 **/
	private $postContent;
	/**
	 * this is the date post was created
	 * @var \DateTime $postDate
	 **/
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
	 **/
	public function getPostId(): Uuid {
		return $this->postId;
	}

	/**
	 * mutator method for post id
	 *
	 * @param Uuid $newPostId new value of post id
	 * @throws \UnexpectedValueException if $newPostId is not a UUID
	 **/
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
	 **/
	public function getPostProfileId() {
		return $this->postProfileId;
	}

	/**
	 * mutator method for post profile id
	 *
	 * @param Uuid $newPostProfileId new value of post profile id
	 * @throws \UnexpectedValueException if $newPostProfileId is not a UUID
	 **/
	public function setPostProfileId($newPostProfileId) : void {
		try {
			$uuid = self::validateUuid($newPostProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the post id
		$this->postProfileId = $uuid;
	}

	/**
	 * accessor method for post content
	 *
	 * @return string value of post content
	 **/
	public function getPostContent() {
		return $this->postContent;
	}

	/**
	 * mutator method for post content
	 *
	 * @param string $newPostContent new value of post content
	 * @throws \InvalidArgumentException if $newPostContent is not a string or insecure
	 * @throws \RangeException if $newPostContent is > 3000 characters
	 *@throws \TypeError if $newPostContent is not a string
	 **/
	public function setPostContent($newPostContent) : void {
		//verify the post content is secure
		$newPostContent = trim($newPostContent);
		$newPostContent = filter_var($newPostContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newPostContent) === true) {
			throw(new \InvalidArgumentException("post content is empty or insecure"));
		}
		//verify the post content will fit in the database
		if(strlen($newPostContent) > 3000) {
			throw(new \RangeException("post content too large"));
		}
		//store the post content
		$this->postContent = $newPostContent;
	}

	/**
	 * accessor method for post date
	 *
	 * @return \DateTime value of post date
	 **/
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
		// store the post date using the ValidateDate trait
		try {
			$newPostDate = self::validateDateTime($newPostDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->postDate = $newPostDate;
	}

	/**
	 * inserts this Post into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		// create query template
		$query = "INSERT INTO post(postId, postProfileId, postContent, postDate) VALUES(:postId, :postProfileId, :postContent, :postDate)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$formattedDate = $this->postDate->format("Y-m-d H:i:s.u");
		$parameters = ["postId" => $this->postId->getBytes(), "postProfileId" => $this->postProfileId->getBytes(), "postContent" => $this->postContent, "postDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Post from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		// create query template
		$query = "DELETE FROM post WHERE postId = :postId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["postId" => $this->postId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Post in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		// create query template
		$query = "UPDATE post SET postProfileId = :postProfileId, postContent = :postContent, postDate = :postDate WHERE postId = :postId";
		$statement = $pdo->prepare($query);
		$formattedDate = $this->postDate->format("Y-m-d H:i:s.u");
		$parameters = ["postId" => $this->postId->getBytes(),"postProfileId" => $this->postProfileId->getBytes(), "postContent" => $this->postContent, "postDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * gets the Post by postId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postId post id to search for
	 * @return Post|null Post found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getPostByPostId(\PDO $pdo, $postId) : ?Post {
		// sanitize the postId before searching
		try {
			$postId = self::validateUuid($postId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT postId, postProfileId, postContent, postDate FROM post WHERE postId = :postId";
		$statement = $pdo->prepare($query);
		// bind the post id to the place holder in the template
		$parameters = ["postId" => $postId->getBytes()];
		$statement->execute($parameters);
		// grab the post from mySQL
		try {
			$post = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$post = new Post($row["postId"], $row["postProfileId"], $row["postContent"], $row["postDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($post);
	}

	/**
	 * gets the post by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postProfileId profile id to search by
	 * @return \SplFixedArray SplFixedArray of Posts found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getPostByPostProfileId(\PDO $pdo, $postProfileId) : \SPLFixedArray {
		try {
			$postProfileId = self::validateUuid($postProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT postId, postProfileId, postContent, postDate FROM post WHERE postProfileId = :postProfileId";
		$statement = $pdo->prepare($query);
		// bind the post profile id to the place holder in the template
		$parameters = ["postProfileId" => $postProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of posts
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postProfileId"], $row["postContent"], $row["postDate"]);
				$posts[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($posts);
	}

	/**
	 * gets the Post by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $postContent post content to search for
	 * @return \SplFixedArray SplFixedArray of Posts found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getPostByPostContent(\PDO $pdo, string $postContent) : \SPLFixedArray {
		// sanitize the description before searching
		$postContent = trim($postContent);
		$postContent = filter_var($postContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($postContent) === true) {
			throw(new \PDOException("post content is invalid"));
		}
				// escape any mySQL wild cards
		$postContent = str_replace("_", "\\_", str_replace("%", "\\%", $postContent));
		// create query template
		$query = "SELECT postId, postProfileId, postContent, postDate FROM post WHERE postContent LIKE :postContent";
		$statement = $pdo->prepare($query);
		// bind the post content to the place holder in the template
		$postContent = "%$postContent%";
		$parameters = ["postContent" => $postContent];
		$statement->execute($parameters);
		// build an array of posts
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postProfileId"], $row["postContent"], $row["postDate"]);
				$posts[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($posts);
	}

	/**
	 * gets Posts by Date
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param \DateTime $sunrisePostDate beginning date to search for
	 * @param \DateTime $sunsetPostDate ending date to search for
	 * @return \SplFixedArray Posts or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getPostByPostDate(\PDO $pdo, \DateTime $sunrisePostDate, \DateTime $sunsetPostDate) : \SplFixedArray {
		//enforce both dates are present
		if((empty ($sunrisePostDate) === true) || (empty($sunsetPostDate) === true)) {
			throw (new \InvalidArgumentException("dates are empty or insecure"));
		}
		//ensure both dates are in the correct format and are secure
		try {
			$sunrisePostDate = self::validateDateTime($sunrisePostDate);
			$sunsetPostDate = self::validateDateTime($sunsetPostDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//create query template
		$query = "SELECT postId, postProfileId, postContent, postDate FROM post WHERE postDate >= :sunrisePostDate AND postDate <= :sunsetPostDate";
		$statement = $pdo->prepare($query);
		//format the dates so that mySQL can use them
		$formattedSunriseDate = $sunrisePostDate->format("Y-m-d H:i:s.u");
		$formattedSunsetDate = $sunsetPostDate->format("Y-m-d H:i:s.u");
		// bind the post content to the place holder in the template
		$parameters = ["sunrisePostDate" => $formattedSunriseDate, "sunsetPostDate" => $formattedSunsetDate];
		$statement->execute($parameters);
		// build an array of comments
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postProfileId"], $row["postContent"], $row["postDate"]);
				$post[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($posts);
	}

	/**
	 * gets all Posts
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Posts found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllPosts(\PDO $pdo) : \SPLFixedArray {
		// create query template
		$query = "SELECT postId, postProfileId, postContent, postDate FROM post";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of posts
		$posts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$post = new Post($row["postId"], $row["postProfileId"], $row["postContent"], $row["postDate"]);
				$posts[$posts->key()] = $post;
				$posts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($posts);
	}

	/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["postId"] = $this->postId->toString();
		$fields["postProfileId"] = $this->postProfileId->toString();

		//format the date so that the front end can consume it
		$fields["postDate"] = round(floatval($this->postDate->format("U.u")) * 1000);
		return($fields);
	}
}


