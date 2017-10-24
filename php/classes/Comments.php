<?php
/**
 * Comments entity for reddit
 *
 * This is the comments entity that stores comments of Profiles.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 7.1
 **/
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;


class Comments implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this comments; this is the primary key
	 * @var Uuid $commentsId
	 **/
	private $commentsId;
	/**
	 * id for the profile this comments is assigned to; this is a foreign key
	 * @var Uuid $commentsProfileId
	 **/
	private $commentsProfileId;
	/**
	 * id for the post this comments is on; this is a foreign key
	 * @var Uuid $commentsPostId
	 **/
	private $commentsPostId;
	/**
	 * id for the comments this comments is on; this is a recursive foreign key
	 * @var Uuid $commentsCommentsId
	 **/
	private $commentsCommentsId;
	/**
	 * this is the content of the comments
	 * @var string $commentsContent
	 **/
	private $commentsContent;
	/**
	 * this is the date comments was created
	 * @var \DateTime $commentsDate
	 **/
	private $commentsDate;

	/**
	 * constructor for this Comments
	 *
	 * @param Uuid $newCommentsId id of this comments or null if a new comments
	 * @param Uuid $newCommentsProfileId id of the Profile that wrote this comments
	 * @param Uuid $newCommentsPostId id of the Post this comments is on
	 * @param Uuid $newCommentsCommentsId id of the comments this comments is on
	 * @param string $newCommentsContent string containing actual comments data
	 * @param \DateTime|string|null $newCommentsDate date and time comments was made or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newCommentsId, $newCommentsProfileId, $newCommentsPostId, $newCommentsCommentsId, string $newCommentsContent, $newCommentsDate = null) {
		try {
			$this->setCommentsId($newCommentsId);
			$this->setCommentsProfileId($newCommentsProfileId);
			$this->setCommentsPostId($newCommentsPostId);
			$this->setCommentsCommentsId($newCommentsCommentsId);
			$this->setCommentsContent($newCommentsContent);
			$this->setCommentsDate($newCommentsDate);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for comments id
	 *
	 * @return Uuid value of comments id
	 **/
	public function getCommentsId(): Uuid {
		return $this->commentsId;
	}

	/**
	 * mutator method for comments id
	 *
	 * @param Uuid $newCommentsId new value of comments id
	 * @throws \UnexpectedValueException if $newCommentsId is not a UUID
	 **/
	public function setCommentsId($newCommentsId) : void {
		try {
			$uuid = self::validateUuid($newCommentsId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comments id
		$this->commentsId = $uuid;
	}

	/**
	 * accessor method for comments profile id
	 *
	 * @return Uuid value of comments profile id
	 **/
	public function getCommentsProfileId() {
		return $this->commentsProfileId;
	}

	/**
	 * mutator method for comments profile id
	 *
	 * @param Uuid $newCommentsProfileId new value of comments profile id
	 * @throws \UnexpectedValueException if $newCommentsProfileId is not a UUID
	 **/
	public function setCommentsProfileId($newCommentsProfileId) : void {
		try {
			$uuid = self::validateUuid($newCommentsProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comments profile id
		$this->commentsProfileId = $uuid;
	}

	/**
	 * accessor method for comments post id
	 *
	 * @return Uuid value of comments post id
	 **/
	public function getCommentsPostId() {
		return $this->commentsPostId;
	}

	/**
	 * mutator method for comments post id
	 *
	 * @param Uuid $newCommentsPostId new value of comments post id
	 * @throws \UnexpectedValueException if $newCommentsPostId is not a UUID
	 **/
	public function setCommentsPostId($newCommentsPostId) : void {
		try {
			$uuid = self::validateUuid($newCommentsPostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comments post id
		$this->commentsPostId = $uuid;
	}

	/**
	 * accessor method for comments comments id
	 *
	 * @return Uuid value of comments comments id
	 **/
	public function getCommentsCommentsId() {
		return $this->commentsCommentsId;
	}

	/**
	 * mutator method for comments comments id
	 *
	 * @param Uuid $newCommentsCommentsId new value of comments comments id
	 * @throws \UnexpectedValueException if $newCommentsCommentsId is not a UUID
	 **/
	public function setCommentsCommentsId($newCommentsCommentsId) : void {
		try {
			$uuid = self::validateUuid($newCommentsCommentsId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the comments comments id
		$this->commentsCommentsId = $uuid;
	}

	/**
	 * accessor method for comments content
	 *
	 * @return string value of comments content
	 **/
	public function getCommentsContent() {
		return $this->commentsContent;
	}

	/**
	 * mutator method for comments content
	 *
	 * @param string $newCommentsContent new value of comments content
	 * @throws \InvalidArgumentException if $newCommentsContent is not a string or insecure
	 * @throws \RangeException if $newCommentsContent is > 3000 characters
	 *@throws \TypeError if $newCommentsContent is not a string
	 **/
	public function setCommentsContent($newCommentsContent) : void {
		//verify the post content is secure
		$newCommentsContent = trim($newCommentsContent);
		$newCommentsContent = filter_var($newCommentsContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentsContent) === true) {
			throw(new \InvalidArgumentException("comments content is empty or insecure"));
		}
		//verify the comments content will fit in the database
		if(strlen($newCommentsContent) > 3000) {
			throw(new \RangeException("comments content too large"));
		}
		//store the comments content
		$this->commentsContent = $newCommentsContent;
	}

	/**
	 * @return \DateTime value of comments date
	 **/
	public function getCommentsDate() : \DateTime {
		return($this->commentsDate);
	}

	/**
	 * mutator method for comments date
	 *
	 * @param \DateTime|string|null $newCommentsDate comments date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newCommentsDate is not a valid object or string
	 * @throws \RangeException if $newCommentsDate is a date that does not exist
	 **/
	public function setCommentsDate($newCommentsDate = null) : void {
		// base case: if the date is null, use the current date and time
		if($newCommentsDate === null) {
			$this->commentsDate = new \DateTime();
			return;
		}
		// store the comments date using the ValidateDate trait
		try {
			$newCommentsDate = self::validateDateTime($newCommentsDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->commentsDate = $newCommentsDate;
	}

	/**
	 * inserts this Comments into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		/** @noinspection SqlResolve */
		// create query template
		$query = "INSERT INTO comments(commentsId, commentsProfileId, commentsPostId, commentsCommentsId, commentsContent, commentsDate) VALUES(:commentsId, :commentsProfileId, :commentsPostId, :commentsCommentsId, :commentsContent, :commentsDate)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$formattedDate = $this->commentsDate->format("Y-m-d H:i:s.u");
		$parameters = ["commentsId" => $this->commentsId->getBytes(), "commentsProfileId" => $this->commentsProfileId->getBytes(), "commentsPostId" => $this->commentsPostId->getBytes(), "commentsCommentsId" => $this->commentsCommentsId->getBytes(),"postContent" => $this->commentsContent, "commentsDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Comments from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		/** @noinspection SqlResolve */
		// create query template
		$query = "DELETE FROM comments WHERE commentsId = :commentsId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["postId" => $this->commentsId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Comments in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		/** @noinspection SqlResolve */
		// create query template
		$query = "UPDATE comments SET commentsProfileId = :commentsProfileId, commentsPostId = :commentsPostId, commentsCommentsId = :commentsCommentsId, commentsContent = :commentsContent, commentsDate = :commentsDate WHERE commentsId = :commentsId";
		$statement = $pdo->prepare($query);
		$formattedDate = $this->commentsDate->format("Y-m-d H:i:s.u");
		$parameters = ["commentsId" => $this->commentsId->getBytes(), "commentsProfileId" => $this->commentsProfileId->getBytes(), "commentsPostId" => $this->commentsPostId->getBytes(), "commentsCommentsId" => $this->commentsCommentsId->getBytes(),"postContent" => $this->commentsContent, "commentsDate" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * gets the Comments by commentsId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentsId comment id to search for
	 * @return Comments|null Comments found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getCommentsByCommentsId(\PDO $pdo, $commentsId) : ?Comments {
		// sanitize the commentsId before searching
		try {
			$commentsId = self::validateUuid($commentsId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentCommentsId, commentsContent, commentsDate FROM comments WHERE commentsId = :commentsId";
		$statement = $pdo->prepare($query);
		// bind the comments id to the place holder in the template
		$parameters = ["commentsId" => $commentsId->getBytes()];
		$statement->execute($parameters);
		// grab the comments from mySQL
		try {
			$comments = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($comments);
	}

	/**
	 * gets the Comments by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentsProfileId profile id to search by
	 * @return \SplFixedArray SplFixedArray of Posts found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentsByCommentsProfileId(\PDO $pdo, $commentsProfileId) : \SPLFixedArray {
		try {
			$commentsProfileId = self::validateUuid($commentsProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentCommentsId, commentsContent, commentsDate FROM comments WHERE commentsProfileId = :commentsProfileId";
		$statement = $pdo->prepare($query);
		// bind the comments profile id to the place holder in the template
		$parameters = ["commentsProfileId" => $commentsProfileId->getBytes()];
		$statement->execute($parameters);
		// build an array of comments
		$commentsArray = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
				$commentsArray[$commentsArray->key()] = $comments;
				$commentsArray->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($commentsArray);
	}

	/**
	 * gets the Comments by Post id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentsPostId post id to search by
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentsByCommentsPostId(\PDO $pdo, $commentsPostId) : \SPLFixedArray {
		try {
			$commentsPostId = self::validateUuid($commentsPostId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentCommentsId, commentsContent, commentsDate FROM comments WHERE commentsPostId = :commentsPostId";
		$statement = $pdo->prepare($query);
		// bind the comments post id to the place holder in the template
		$parameters = ["commentsProfileId" => $commentsPostId->getBytes()];
		$statement->execute($parameters);
		// build an array of comments
		$commentsArray = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
				$commentsArray[$commentsArray->key()] = $comments;
				$commentsArray->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($commentsArray);
	}

	/**
	 * gets the Comments by content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $commentsContent comments content to search for
	 * @return \SplFixedArray SplFixedArray of Comments found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getCommentsByCommentsContent(\PDO $pdo, string $commentsContent) : \SPLFixedArray {
		// sanitize the description before searching
		$commentsContent = trim($commentsContent);
		$commentsContent = filter_var($commentsContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($commentsContent) === true) {
			throw(new \PDOException("comments content is invalid"));
		}
		// escape any mySQL wild cards
		$commentsContent = str_replace("_", "\\_", str_replace("%", "\\%", $commentsContent));
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentCommentsId, commentsContent, commentsDate FROM comments WHERE commentsContent LIKE :commentsContent";
		$statement = $pdo->prepare($query);
		// bind the comments content to the place holder in the template
		$commentsContent = "%$commentsContent%";
		$parameters = ["commentsContent" => $commentsContent];
		$statement->execute($parameters);
		// build an array of posts
		$commentsArray = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
				$commentsArray[$commentsArray->key()] = $comments;
				$commentsArray->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($commentsArray);
	}

	/**
	 * gets Comments by Date
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param \DateTime $sunriseCommentsDate beginning date to search for
	 * @param \DateTime $sunsetCommentsDate ending date to search for
	 * @return \SplFixedArray Comments or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getCommentsByDate(\PDO $pdo, \DateTime $sunriseCommentsDate, \DateTime $sunsetCommentsDate) : \SplFixedArray {
		//enforce both date are present
		if((empty ($sunriseCommentsDate) === true) || (empty($sunsetCommentsDate) === true)) {
			throw (new \InvalidArgumentException("dates are empty of insecure"));
		}
		//ensure both dates are in the correct format and are secure
		try {
			$sunriseCommentsDate = self::validateDateTime($sunriseCommentsDate);
			$sunsetCommentsDate = self::validateDateTime($sunsetCommentsDate);
		} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		/** @noinspection SqlResolve */
		//create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentsCommentsId, commentsContent, commentsDate FROM comments WHERE commentsDate >= :sunriseCommentsDate AND commentsDate <= :sunsetCommentsDate";
		$statement = $pdo->prepare($query);
		//format the dates so that mySQL can use them
		$formattedSunriseDate = $sunriseCommentsDate->format("Y-m-d H:i:s.u");
		$formattedSunsetDate = $sunsetCommentsDate->format("Y-m-d H:i:s.u");
		// bind the comments content to the place holder in the template
		$parameters = ["sunriseCommentsDate" => $formattedSunriseDate, "sunsetCommentsDate" => $formattedSunsetDate];
		$statement->execute($parameters);
		// build an array of comments
		$commentsArray = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
				$comments[$commentsArray->key()] = $comments;
				$commentsArray->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($commentsArray);
	}

	/**
	 * gets all Comments
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Comments found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllComments(\PDO $pdo) : \SPLFixedArray {
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT commentsId, commentsProfileId, commentsPostId, commentCommentsId, commentsContent, commentsDate FROM comments";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of comments
		$commentsArray = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$comments = new Comments($row["commentsId"], $row["commentsProfileId"], $row["commentsPostId"], $row["commentsCommentsId"], $row["commentsContent"], $row["commentsDate"]);
				$commentsArray[$commentsArray->key()] = $comments;
				$commentsArray->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($commentsArray);
	}

	/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["commentsId"] = $this->commentsId->toString();
		$fields["commentsProfileId"] = $this->commentsProfileId->toString();
		$fields["commentsPostId"] = $this->commentsPostId->toString();
		$fields["commentsCommentsId"] = $this->commentsCommentsId->toString();
		//format the date so that the front end can consume it
		$fields["commentsDate"] = round(floatval($this->commentsDate->format("U.u")) * 1000);
		return($fields);
	}
}