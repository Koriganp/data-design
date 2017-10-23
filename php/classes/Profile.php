<?php
/**
 * Profile entity for reddit
 *
 * This is the profile entity that stores Profiles of users. This is a top level entity that holds the keys to the other entities.
 *
 * @author Korigan Payne <kpayne11@cnm.edu>
 * @version 7.1
 **/
namespace Edu\Cnm\DataDesign;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "../vendor/autoload.php");

use Ramsey\Uuid\Uuid;


class Profile implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for this profile; this is the primary key
	 * @var Uuid $profileId
	 **/
	private $profileId;
	/**
	 * token handed out to verify that the profile is valid and not malicious
	 * @var $profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * User name of this profile; this is a unique index
	 * @var string $profileUserName
	 **/
	private $profileUserName;
	/**
	 * email for this Profile; this is a unique index
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * hash for profile password
	 * @var $profileHash
	 **/
	private $profileHash;
	/**
	 * salt for profile password
	 * @var $profileSalt
	 **/
	private $profileSalt;

	/**
	 * constructor for this Profile
	 *
	 * @param uuid $newProfileId id of this Profile or null if a new Profile
	 * @param string $newProfileUserName string containing newAtHandle
	 * @param string $newProfileActivationToken activation token to safe guard against malicious accounts
	 * @param string $newProfileEmail string containing email
	 * @param string $newProfileHash string containing password hash
	 * @param string $newProfileSalt string containing passowrd salt
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newProfileId, string $newProfileUserName, ?string $newProfileActivationToken, string $newProfileEmail, string $newProfileHash, string $newProfileSalt) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileUserName($newProfileUserName);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileHash($newProfileHash);
			$this->setProfileSalt($newProfileSalt);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 * @return uuid value of profile id (or null if new Profile)
	 **/
	public function getProfileId(): Uuid {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param Uuid|null $newProfileId value of new profile id
	 * @throws \TypeError if $newProfileId is not a Uuid
	 **/
	public function setProfileId($newProfileId) : void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the post
		$this->ProfileId = $uuid;
	}

	/**
	 * accessor method for user name
	 *
	 * @return string value of user name
	 **/
	public function getProfileUserName(): string {
		return ($this->profileUserName);
	}

	/**
	 * mutator method for user name
	 *
	 * @param string $newProfileUserName new value of user name
	 * @throws \InvalidArgumentException if $newProfileUserName is not a string or insecure
	 * @throws \RangeException if $newProfileUserName is > 128 characters
	 * @throws \TypeError if $newProfileUserName is not a string
	 **/
	public function setProfileUserName(string $newProfileUserName) : void {
		// verify the user name is secure
		$newProfileUserName = trim($newProfileUserName);
		$newProfileUserName = filter_var($newProfileUserName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileUserName) === true) {
			throw(new \InvalidArgumentException("profile user name is empty or insecure"));
		}
		// verify the user name will fit in the database
		if(strlen($newProfileUserName) > 128) {
			throw(new \RangeException("profile user name is too large"));
		}
		// store the user name
		$this->profileUserName = $newProfileUserName;
	}

	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 **/
	public function getProfileActivationToken() : ?string {
		return ($this->profileActivationToken);
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setProfileActivationToken(?string $newProfileActivationToken): void {
		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(new\RangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newProfileActivationToken) !== 32) {
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor method for email
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for profileHash
	 * @return string value of hash
	 **/
	public function getProfileHash(): string {
		return $this->profileHash;
	}

	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newProfileHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setProfileHash(string $newProfileHash): void {
		//enforce that the hash is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 characters"));
		}
		//store the hash
		$this->profileHash = $newProfileHash;
	}

	/**
	 *accessor method for profile salt
	 * @return string representation of the salt hexadecimal
	 **/
	public function getProfileSalt(): string {
		return $this->profileSalt;
	}

	/**
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if profile salt is not a string
	 **/
	public function setProfileSalt(string $newProfileSalt): void {
		//enforce that the salt is properly formatted
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		//enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new \InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the salt is exactly 64 characters.
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt must be 64 characters"));
		}
		//store the salt
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * inserts this Profile into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		// enforce the profileId is null (don't insert a profile that already exists)
		if($this->profileId !== null) {
			throw(new \PDOException("not a new profile"));
		}
		/** @noinspection SqlResolve */
		// create query template
		$query = "INSERT INTO profile(profileId, profileActivationToken, profileUserName, profileEmail, profileHash, profileSalt) VALUES (:profileId, :profileActivationToken, :profileUserName, :profileEmail, :profileHash, :profileSalt)";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId->getBytes(), "profileActivationToken" => $this->profileActivationToken, "profileUserName" => $this->profileUserName, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt];
		$statement->execute($parameters);
	}

	/**
	 * deletes this profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not null (i.e.. don't delete a profile that does not exist)
	 **/
	public function delete(\PDO $pdo): void {
		//enforce the profileId is not null (don't delete a profile that does not exist)
		if($this->profileId === null) {
			throw(new \PDOException("unable to delete a profile that does not exist"));
		}
		/** @noinspection SqlResolve */
		//create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters = ["profileId" => $this->profileId];
		$statement->execute($parameters);
	}

	/**
	 * updates this profile from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		//Enforce the profileId is not null (don't update a profile that does not exist)
		if($this->profileId === null) {
			throw(new \PDOException("unable to update a profile that does not exist"));
		}
		/** @noinspection SqlResolve */
		//create query template
		$query = "UPDATE profile SET profileId = :profileId, profileUserName = :profileUserName, profileActivationToken = :profileActivationToken, profileEmail = :profileEmail, profileHash = :profileHash, profileSalt = :profileSalt";
		$statement = $pdo->prepare($query);
		//bind the member variables to the place holders in the template
		$parameters=["profileId=>$this->profileId", "profileUserName=>$this->profileUserName", "profileActivationToken" => $this->profileActivationToken, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash, "profileSalt" =>$this->profileSalt];
		$statement->execute($parameters);
	}

	/**
	 * gets the Profile by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $profileId profile id to search for
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getProfileByProfileId(\PDO $pdo, int $profileId):?Profile {
		//sanitize the profile id before searching
		try {
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		/** @noinspection SqlResolve */
		//create query template
		$query="SELECT profileId, profileUserName, profileActivationToken, profileEmail, profileHash, profileSalt FROM profile WHERE profileId = :profileId";
		$statement=$pdo->prepare($query);
		//bind the profile id to the place holder in the template
		$parameters=["profileId"=>$profileId];
		$statement->execute($parameters);
		//grab the Profile from mySQL
		try{
			$profile=null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row=$statement->fetch();
			if($row !== false) {
				$profile=new Profile($row["profileId"], $row["profileUserName"],$row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"]);
			}
		}
		catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new\PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * get the Profile by user name
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileUserName at handle to search for
	 * @return \SPLFixedArray of all profiles found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not correct data type
	 **/
	public static function getProfileByProfileUserName(\PDO $pdo, string $profileUserName) : \SPLFixedArray {
		// sanitize the user name before searching
		$profileUserName = trim($profileUserName);
		$profileUserName = filter_var($profileUserName, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($profileUserName) === true) {
			throw(new \PDOException("not a valid user name"));
		}
		/** @noinspection SqlResolve */
		// create query template
		$query = "SELECT profileId, profileUserName, profileActivationToken, profileEmail, profileHash, profileSalt FROM profile WHERE profileUserName = :profileUserName";
		$statement = $pdo->prepare($query);
		//bind the profile user name to the place holder in the template
		$parameters = ["profileUserName" => $profileUserName];
		$statement->execute($parameters);
		$profiles = new \SPLFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$profile = new Profile($row["profileId"], $row["profileUserName"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"]);
				$profiles[$profiles->key()] = $profile;
				$profiles->next();
			} catch (\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($profiles);
	}

	/**
	 * get the Profile by profile activation token
	 *
	 * @param string $profileActivationToken
	 * @param \PDO object $pdo
	 * @return Profile|null profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function	getProfileByProfileActivationToken(\PDO $pdo, string $profileActivationToken) : ?Profile {
		//make sure activation token is in the right format and that it is a string representation of a hexadecimal
		$profileActivationToken = trim($profileActivationToken);
		if(ctype_xdigit($profileActivationToken) === false) {
			throw(new \InvalidArgumentException("profile activation token is empty or in the wrong format"));
		}
		//create the query template
		/** @noinspection SqlResolve */
		$query = "SELECT profileId, profileUserName, profileActivationToken, profileEmail, profileHash, profileSalt FROM profile WHERE profileActivationToken = :profileActivationToken";
		$statement = $pdo->prepare($query);
		//bind the profile activation token to the placeholder in the template
		$parameters = ["profileActivationToken" => $profileActivationToken];
		$statement->execute($parameters);
		//grab the Profile from mySQL
		try {
			$profile = null;
			$statement-> setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileUserName"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"]);
			}
		}
		catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
	}

	/**
	 * get the profile by profile email
	 *
	 * @param \PDO $pdo pdo PDO connection object
	 * @param string $profileEmail profile email to search for
	 * @return Profile|null Profile or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getProfileByProfileEmail(\PDO $pdo, string $profileEmail) : ?Profile {
		//sanitize email before searching
		$profileEmail = trim($profileEmail);
		$profileEmail = filter_var($profileEmail, FILTER_SANITIZE_EMAIL);
		if(empty($profileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		/** @noinspection SqlResolve */
		//create query template
		$query="SELECT profileId, profileUserName, profileActivationToken, profileEmail, profileHash, profileSalt FROM profile WHERE profileEmail = :profileEmail";
		$statement=$pdo->prepare($query);
		//bind the profile email to the place holder in the template
		$parameters=["profileEmail"=>$profileEmail];
		$statement->execute($parameters);
		//grab the Profile from mySQL
		try {
			$profile = null;
			$statement-> setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileUserName"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"]);
			}
		}
		catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($profile);
	}

		/**
	 * formats the state variables for JSON serialize
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["profileId"] = $this->profileId;
		$fields["profileUserName"] = $this->profileUserName;
		$fields["profileActivationToken"] = $this->profileActivationToken;
		$fields["profileEmail"] = $this->profileEmail;
		$fields["profileHash"] = $this->profileHash;
		$fields["profileSalt"] = $this->profileSalt;
		return($fields);
	}
}


