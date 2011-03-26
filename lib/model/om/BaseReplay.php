<?php

/**
 * Base class that represents a row from the 'replay' table.
 *
 * 
 *
 * @package    lib.model.om
 */
abstract class BaseReplay extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        ReplayPeer
	 */
	protected static $peer;

	/**
	 * The value for the id field.
	 * @var        int
	 */
	protected $id;

	/**
	 * The value for the user_id field.
	 * @var        int
	 */
	protected $user_id;

	/**
	 * The value for the game_type_id field.
	 * @var        int
	 */
	protected $game_type_id;

	/**
	 * The value for the category_id field.
	 * @var        int
	 */
	protected $category_id;

	/**
	 * The value for the file_name field.
	 * @var        string
	 */
	protected $file_name;

	/**
	 * The value for the game_info field.
	 * @var        string
	 */
	protected $game_info;

	/**
	 * The value for the description field.
	 * @var        string
	 */
	protected $description;

	/**
	 * The value for the avg_apm field.
	 * @var        int
	 */
	protected $avg_apm;

	/**
	 * The value for the players field.
	 * @var        string
	 */
	protected $players;

	/**
	 * The value for the map_name field.
	 * @var        string
	 */
	protected $map_name;

	/**
	 * The value for the download_count field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $download_count;

	/**
	 * The value for the published_at field.
	 * @var        string
	 */
	protected $published_at;

	/**
	 * The value for the created_at field.
	 * @var        string
	 */
	protected $created_at;

	/**
	 * The value for the updated_at field.
	 * @var        string
	 */
	protected $updated_at;

	/**
	 * The value for the reported_count field.
	 * Note: this column has a database default value of: 0
	 * @var        int
	 */
	protected $reported_count;

	/**
	 * @var        sfGuardUser
	 */
	protected $asfGuardUser;

	/**
	 * @var        ReplayGameType
	 */
	protected $aReplayGameType;

	/**
	 * @var        ReplayCategory
	 */
	protected $aReplayCategory;

	/**
	 * @var        array ReplayComment[] Collection to store aggregation of ReplayComment objects.
	 */
	protected $collReplayComments;

	/**
	 * @var        Criteria The criteria used to select the current contents of collReplayComments.
	 */
	private $lastReplayCommentCriteria = null;

	/**
	 * @var        array ReplayOftheweek[] Collection to store aggregation of ReplayOftheweek objects.
	 */
	protected $collReplayOftheweeks;

	/**
	 * @var        Criteria The criteria used to select the current contents of collReplayOftheweeks.
	 */
	private $lastReplayOftheweekCriteria = null;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	// symfony behavior
	
	const PEER = 'ReplayPeer';

	/**
	 * Applies default values to this object.
	 * This method should be called from the object's constructor (or
	 * equivalent initialization method).
	 * @see        __construct()
	 */
	public function applyDefaultValues()
	{
		$this->download_count = 0;
		$this->reported_count = 0;
	}

	/**
	 * Initializes internal state of BaseReplay object.
	 * @see        applyDefaults()
	 */
	public function __construct()
	{
		parent::__construct();
		$this->applyDefaultValues();
	}

	/**
	 * Get the [id] column value.
	 * 
	 * @return     int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get the [user_id] column value.
	 * 
	 * @return     int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}

	/**
	 * Get the [game_type_id] column value.
	 * 
	 * @return     int
	 */
	public function getGameTypeId()
	{
		return $this->game_type_id;
	}

	/**
	 * Get the [category_id] column value.
	 * 
	 * @return     int
	 */
	public function getCategoryId()
	{
		return $this->category_id;
	}

	/**
	 * Get the [file_name] column value.
	 * 
	 * @return     string
	 */
	public function getFileName()
	{
		return $this->file_name;
	}

	/**
	 * Get the [game_info] column value.
	 * 
	 * @return     string
	 */
	public function getGameInfo()
	{
		return $this->game_info;
	}

	/**
	 * Get the [description] column value.
	 * 
	 * @return     string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get the [avg_apm] column value.
	 * 
	 * @return     int
	 */
	public function getAvgApm()
	{
		return $this->avg_apm;
	}

	/**
	 * Get the [players] column value.
	 * 
	 * @return     string
	 */
	public function getPlayers()
	{
		return $this->players;
	}

	/**
	 * Get the [map_name] column value.
	 * 
	 * @return     string
	 */
	public function getMapName()
	{
		return $this->map_name;
	}

	/**
	 * Get the [download_count] column value.
	 * 
	 * @return     int
	 */
	public function getDownloadCount()
	{
		return $this->download_count;
	}

	/**
	 * Get the [optionally formatted] temporal [published_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getPublishedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->published_at === null) {
			return null;
		}


		if ($this->published_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->published_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->published_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [optionally formatted] temporal [created_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getCreatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->created_at === null) {
			return null;
		}


		if ($this->created_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->created_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [optionally formatted] temporal [updated_at] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getUpdatedAt($format = 'Y-m-d H:i:s')
	{
		if ($this->updated_at === null) {
			return null;
		}


		if ($this->updated_at === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->updated_at);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [reported_count] column value.
	 * 
	 * @return     int
	 */
	public function getReportedCount()
	{
		return $this->reported_count;
	}

	/**
	 * Set the value of [id] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = ReplayPeer::ID;
		}

		return $this;
	} // setId()

	/**
	 * Set the value of [user_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setUserId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->user_id !== $v) {
			$this->user_id = $v;
			$this->modifiedColumns[] = ReplayPeer::USER_ID;
		}

		if ($this->asfGuardUser !== null && $this->asfGuardUser->getId() !== $v) {
			$this->asfGuardUser = null;
		}

		return $this;
	} // setUserId()

	/**
	 * Set the value of [game_type_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setGameTypeId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->game_type_id !== $v) {
			$this->game_type_id = $v;
			$this->modifiedColumns[] = ReplayPeer::GAME_TYPE_ID;
		}

		if ($this->aReplayGameType !== null && $this->aReplayGameType->getId() !== $v) {
			$this->aReplayGameType = null;
		}

		return $this;
	} // setGameTypeId()

	/**
	 * Set the value of [category_id] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setCategoryId($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->category_id !== $v) {
			$this->category_id = $v;
			$this->modifiedColumns[] = ReplayPeer::CATEGORY_ID;
		}

		if ($this->aReplayCategory !== null && $this->aReplayCategory->getId() !== $v) {
			$this->aReplayCategory = null;
		}

		return $this;
	} // setCategoryId()

	/**
	 * Set the value of [file_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setFileName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->file_name !== $v) {
			$this->file_name = $v;
			$this->modifiedColumns[] = ReplayPeer::FILE_NAME;
		}

		return $this;
	} // setFileName()

	/**
	 * Set the value of [game_info] column.
	 * 
	 * @param      string $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setGameInfo($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->game_info !== $v) {
			$this->game_info = $v;
			$this->modifiedColumns[] = ReplayPeer::GAME_INFO;
		}

		return $this;
	} // setGameInfo()

	/**
	 * Set the value of [description] column.
	 * 
	 * @param      string $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setDescription($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->description !== $v) {
			$this->description = $v;
			$this->modifiedColumns[] = ReplayPeer::DESCRIPTION;
		}

		return $this;
	} // setDescription()

	/**
	 * Set the value of [avg_apm] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setAvgApm($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->avg_apm !== $v) {
			$this->avg_apm = $v;
			$this->modifiedColumns[] = ReplayPeer::AVG_APM;
		}

		return $this;
	} // setAvgApm()

	/**
	 * Set the value of [players] column.
	 * 
	 * @param      string $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setPlayers($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->players !== $v) {
			$this->players = $v;
			$this->modifiedColumns[] = ReplayPeer::PLAYERS;
		}

		return $this;
	} // setPlayers()

	/**
	 * Set the value of [map_name] column.
	 * 
	 * @param      string $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setMapName($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->map_name !== $v) {
			$this->map_name = $v;
			$this->modifiedColumns[] = ReplayPeer::MAP_NAME;
		}

		return $this;
	} // setMapName()

	/**
	 * Set the value of [download_count] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setDownloadCount($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->download_count !== $v || $this->isNew()) {
			$this->download_count = $v;
			$this->modifiedColumns[] = ReplayPeer::DOWNLOAD_COUNT;
		}

		return $this;
	} // setDownloadCount()

	/**
	 * Sets the value of [published_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setPublishedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->published_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->published_at !== null && $tmpDt = new DateTime($this->published_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->published_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = ReplayPeer::PUBLISHED_AT;
			}
		} // if either are not null

		return $this;
	} // setPublishedAt()

	/**
	 * Sets the value of [created_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setCreatedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->created_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->created_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = ReplayPeer::CREATED_AT;
			}
		} // if either are not null

		return $this;
	} // setCreatedAt()

	/**
	 * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setUpdatedAt($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->updated_at !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->updated_at = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = ReplayPeer::UPDATED_AT;
			}
		} // if either are not null

		return $this;
	} // setUpdatedAt()

	/**
	 * Set the value of [reported_count] column.
	 * 
	 * @param      int $v new value
	 * @return     Replay The current object (for fluent API support)
	 */
	public function setReportedCount($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->reported_count !== $v || $this->isNew()) {
			$this->reported_count = $v;
			$this->modifiedColumns[] = ReplayPeer::REPORTED_COUNT;
		}

		return $this;
	} // setReportedCount()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
			if ($this->download_count !== 0) {
				return false;
			}

			if ($this->reported_count !== 0) {
				return false;
			}

		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->user_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->game_type_id = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->category_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->file_name = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->game_info = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->description = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
			$this->avg_apm = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->players = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
			$this->map_name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
			$this->download_count = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
			$this->published_at = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
			$this->created_at = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
			$this->updated_at = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
			$this->reported_count = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 15; // 15 = ReplayPeer::NUM_COLUMNS - ReplayPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Replay object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

		if ($this->asfGuardUser !== null && $this->user_id !== $this->asfGuardUser->getId()) {
			$this->asfGuardUser = null;
		}
		if ($this->aReplayGameType !== null && $this->game_type_id !== $this->aReplayGameType->getId()) {
			$this->aReplayGameType = null;
		}
		if ($this->aReplayCategory !== null && $this->category_id !== $this->aReplayCategory->getId()) {
			$this->aReplayCategory = null;
		}
	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ReplayPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = ReplayPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

			$this->asfGuardUser = null;
			$this->aReplayGameType = null;
			$this->aReplayCategory = null;
			$this->collReplayComments = null;
			$this->lastReplayCommentCriteria = null;

			$this->collReplayOftheweeks = null;
			$this->lastReplayOftheweekCriteria = null;

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ReplayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseReplay:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				ReplayPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseReplay:delete:post') as $callable)
				{
				  call_user_func($callable, $this, $con);
				}

				$this->setDeleted(true);
				$con->commit();
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(ReplayPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseReplay:save:pre') as $callable)
			{
			  if (is_integer($affectedRows = call_user_func($callable, $this, $con)))
			  {
			    $con->commit();
			
			    return $affectedRows;
			  }
			}

			// symfony_timestampable behavior
			if ($this->isModified() && !$this->isColumnModified(ReplayPeer::UPDATED_AT))
			{
			  $this->setUpdatedAt(time());
			}

			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
				// symfony_timestampable behavior
				if (!$this->isColumnModified(ReplayPeer::CREATED_AT))
				{
				  $this->setCreatedAt(time());
				}

			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseReplay:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				ReplayPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			// We call the save method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->asfGuardUser !== null) {
				if ($this->asfGuardUser->isModified() || $this->asfGuardUser->isNew()) {
					$affectedRows += $this->asfGuardUser->save($con);
				}
				$this->setsfGuardUser($this->asfGuardUser);
			}

			if ($this->aReplayGameType !== null) {
				if ($this->aReplayGameType->isModified() || $this->aReplayGameType->isNew()) {
					$affectedRows += $this->aReplayGameType->save($con);
				}
				$this->setReplayGameType($this->aReplayGameType);
			}

			if ($this->aReplayCategory !== null) {
				if ($this->aReplayCategory->isModified() || $this->aReplayCategory->isNew()) {
					$affectedRows += $this->aReplayCategory->save($con);
				}
				$this->setReplayCategory($this->aReplayCategory);
			}

			if ($this->isNew() ) {
				$this->modifiedColumns[] = ReplayPeer::ID;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = ReplayPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setId($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += ReplayPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			if ($this->collReplayComments !== null) {
				foreach ($this->collReplayComments as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			if ($this->collReplayOftheweeks !== null) {
				foreach ($this->collReplayOftheweeks as $referrerFK) {
					if (!$referrerFK->isDeleted()) {
						$affectedRows += $referrerFK->save($con);
					}
				}
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			// We call the validate method on the following object(s) if they
			// were passed to this object by their coresponding set
			// method.  This object relates to these object(s) by a
			// foreign key reference.

			if ($this->asfGuardUser !== null) {
				if (!$this->asfGuardUser->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->asfGuardUser->getValidationFailures());
				}
			}

			if ($this->aReplayGameType !== null) {
				if (!$this->aReplayGameType->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aReplayGameType->getValidationFailures());
				}
			}

			if ($this->aReplayCategory !== null) {
				if (!$this->aReplayCategory->validate($columns)) {
					$failureMap = array_merge($failureMap, $this->aReplayCategory->getValidationFailures());
				}
			}


			if (($retval = ReplayPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}


				if ($this->collReplayComments !== null) {
					foreach ($this->collReplayComments as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}

				if ($this->collReplayOftheweeks !== null) {
					foreach ($this->collReplayOftheweeks as $referrerFK) {
						if (!$referrerFK->validate($columns)) {
							$failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
						}
					}
				}


			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ReplayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getUserId();
				break;
			case 2:
				return $this->getGameTypeId();
				break;
			case 3:
				return $this->getCategoryId();
				break;
			case 4:
				return $this->getFileName();
				break;
			case 5:
				return $this->getGameInfo();
				break;
			case 6:
				return $this->getDescription();
				break;
			case 7:
				return $this->getAvgApm();
				break;
			case 8:
				return $this->getPlayers();
				break;
			case 9:
				return $this->getMapName();
				break;
			case 10:
				return $this->getDownloadCount();
				break;
			case 11:
				return $this->getPublishedAt();
				break;
			case 12:
				return $this->getCreatedAt();
				break;
			case 13:
				return $this->getUpdatedAt();
				break;
			case 14:
				return $this->getReportedCount();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = ReplayPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getUserId(),
			$keys[2] => $this->getGameTypeId(),
			$keys[3] => $this->getCategoryId(),
			$keys[4] => $this->getFileName(),
			$keys[5] => $this->getGameInfo(),
			$keys[6] => $this->getDescription(),
			$keys[7] => $this->getAvgApm(),
			$keys[8] => $this->getPlayers(),
			$keys[9] => $this->getMapName(),
			$keys[10] => $this->getDownloadCount(),
			$keys[11] => $this->getPublishedAt(),
			$keys[12] => $this->getCreatedAt(),
			$keys[13] => $this->getUpdatedAt(),
			$keys[14] => $this->getReportedCount(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = ReplayPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setUserId($value);
				break;
			case 2:
				$this->setGameTypeId($value);
				break;
			case 3:
				$this->setCategoryId($value);
				break;
			case 4:
				$this->setFileName($value);
				break;
			case 5:
				$this->setGameInfo($value);
				break;
			case 6:
				$this->setDescription($value);
				break;
			case 7:
				$this->setAvgApm($value);
				break;
			case 8:
				$this->setPlayers($value);
				break;
			case 9:
				$this->setMapName($value);
				break;
			case 10:
				$this->setDownloadCount($value);
				break;
			case 11:
				$this->setPublishedAt($value);
				break;
			case 12:
				$this->setCreatedAt($value);
				break;
			case 13:
				$this->setUpdatedAt($value);
				break;
			case 14:
				$this->setReportedCount($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = ReplayPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setUserId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setGameTypeId($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setCategoryId($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setFileName($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setGameInfo($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDescription($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setAvgApm($arr[$keys[7]]);
		if (array_key_exists($keys[8], $arr)) $this->setPlayers($arr[$keys[8]]);
		if (array_key_exists($keys[9], $arr)) $this->setMapName($arr[$keys[9]]);
		if (array_key_exists($keys[10], $arr)) $this->setDownloadCount($arr[$keys[10]]);
		if (array_key_exists($keys[11], $arr)) $this->setPublishedAt($arr[$keys[11]]);
		if (array_key_exists($keys[12], $arr)) $this->setCreatedAt($arr[$keys[12]]);
		if (array_key_exists($keys[13], $arr)) $this->setUpdatedAt($arr[$keys[13]]);
		if (array_key_exists($keys[14], $arr)) $this->setReportedCount($arr[$keys[14]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(ReplayPeer::DATABASE_NAME);

		if ($this->isColumnModified(ReplayPeer::ID)) $criteria->add(ReplayPeer::ID, $this->id);
		if ($this->isColumnModified(ReplayPeer::USER_ID)) $criteria->add(ReplayPeer::USER_ID, $this->user_id);
		if ($this->isColumnModified(ReplayPeer::GAME_TYPE_ID)) $criteria->add(ReplayPeer::GAME_TYPE_ID, $this->game_type_id);
		if ($this->isColumnModified(ReplayPeer::CATEGORY_ID)) $criteria->add(ReplayPeer::CATEGORY_ID, $this->category_id);
		if ($this->isColumnModified(ReplayPeer::FILE_NAME)) $criteria->add(ReplayPeer::FILE_NAME, $this->file_name);
		if ($this->isColumnModified(ReplayPeer::GAME_INFO)) $criteria->add(ReplayPeer::GAME_INFO, $this->game_info);
		if ($this->isColumnModified(ReplayPeer::DESCRIPTION)) $criteria->add(ReplayPeer::DESCRIPTION, $this->description);
		if ($this->isColumnModified(ReplayPeer::AVG_APM)) $criteria->add(ReplayPeer::AVG_APM, $this->avg_apm);
		if ($this->isColumnModified(ReplayPeer::PLAYERS)) $criteria->add(ReplayPeer::PLAYERS, $this->players);
		if ($this->isColumnModified(ReplayPeer::MAP_NAME)) $criteria->add(ReplayPeer::MAP_NAME, $this->map_name);
		if ($this->isColumnModified(ReplayPeer::DOWNLOAD_COUNT)) $criteria->add(ReplayPeer::DOWNLOAD_COUNT, $this->download_count);
		if ($this->isColumnModified(ReplayPeer::PUBLISHED_AT)) $criteria->add(ReplayPeer::PUBLISHED_AT, $this->published_at);
		if ($this->isColumnModified(ReplayPeer::CREATED_AT)) $criteria->add(ReplayPeer::CREATED_AT, $this->created_at);
		if ($this->isColumnModified(ReplayPeer::UPDATED_AT)) $criteria->add(ReplayPeer::UPDATED_AT, $this->updated_at);
		if ($this->isColumnModified(ReplayPeer::REPORTED_COUNT)) $criteria->add(ReplayPeer::REPORTED_COUNT, $this->reported_count);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(ReplayPeer::DATABASE_NAME);

		$criteria->add(ReplayPeer::ID, $this->id);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	/**
	 * Generic method to set the primary key (id column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Replay (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setUserId($this->user_id);

		$copyObj->setGameTypeId($this->game_type_id);

		$copyObj->setCategoryId($this->category_id);

		$copyObj->setFileName($this->file_name);

		$copyObj->setGameInfo($this->game_info);

		$copyObj->setDescription($this->description);

		$copyObj->setAvgApm($this->avg_apm);

		$copyObj->setPlayers($this->players);

		$copyObj->setMapName($this->map_name);

		$copyObj->setDownloadCount($this->download_count);

		$copyObj->setPublishedAt($this->published_at);

		$copyObj->setCreatedAt($this->created_at);

		$copyObj->setUpdatedAt($this->updated_at);

		$copyObj->setReportedCount($this->reported_count);


		if ($deepCopy) {
			// important: temporarily setNew(false) because this affects the behavior of
			// the getter/setter methods for fkey referrer objects.
			$copyObj->setNew(false);

			foreach ($this->getReplayComments() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addReplayComment($relObj->copy($deepCopy));
				}
			}

			foreach ($this->getReplayOftheweeks() as $relObj) {
				if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
					$copyObj->addReplayOftheweek($relObj->copy($deepCopy));
				}
			}

		} // if ($deepCopy)


		$copyObj->setNew(true);

		$copyObj->setId(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Replay Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     ReplayPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new ReplayPeer();
		}
		return self::$peer;
	}

	/**
	 * Declares an association between this object and a sfGuardUser object.
	 *
	 * @param      sfGuardUser $v
	 * @return     Replay The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setsfGuardUser(sfGuardUser $v = null)
	{
		if ($v === null) {
			$this->setUserId(NULL);
		} else {
			$this->setUserId($v->getId());
		}

		$this->asfGuardUser = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the sfGuardUser object, it will not be re-added.
		if ($v !== null) {
			$v->addReplay($this);
		}

		return $this;
	}


	/**
	 * Get the associated sfGuardUser object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     sfGuardUser The associated sfGuardUser object.
	 * @throws     PropelException
	 */
	public function getsfGuardUser(PropelPDO $con = null)
	{
		if ($this->asfGuardUser === null && ($this->user_id !== null)) {
			$this->asfGuardUser = sfGuardUserPeer::retrieveByPk($this->user_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->asfGuardUser->addReplays($this);
			 */
		}
		return $this->asfGuardUser;
	}

	/**
	 * Declares an association between this object and a ReplayGameType object.
	 *
	 * @param      ReplayGameType $v
	 * @return     Replay The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setReplayGameType(ReplayGameType $v = null)
	{
		if ($v === null) {
			$this->setGameTypeId(NULL);
		} else {
			$this->setGameTypeId($v->getId());
		}

		$this->aReplayGameType = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the ReplayGameType object, it will not be re-added.
		if ($v !== null) {
			$v->addReplay($this);
		}

		return $this;
	}


	/**
	 * Get the associated ReplayGameType object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     ReplayGameType The associated ReplayGameType object.
	 * @throws     PropelException
	 */
	public function getReplayGameType(PropelPDO $con = null)
	{
		if ($this->aReplayGameType === null && ($this->game_type_id !== null)) {
			$this->aReplayGameType = ReplayGameTypePeer::retrieveByPk($this->game_type_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aReplayGameType->addReplays($this);
			 */
		}
		return $this->aReplayGameType;
	}

	/**
	 * Declares an association between this object and a ReplayCategory object.
	 *
	 * @param      ReplayCategory $v
	 * @return     Replay The current object (for fluent API support)
	 * @throws     PropelException
	 */
	public function setReplayCategory(ReplayCategory $v = null)
	{
		if ($v === null) {
			$this->setCategoryId(NULL);
		} else {
			$this->setCategoryId($v->getId());
		}

		$this->aReplayCategory = $v;

		// Add binding for other direction of this n:n relationship.
		// If this object has already been added to the ReplayCategory object, it will not be re-added.
		if ($v !== null) {
			$v->addReplay($this);
		}

		return $this;
	}


	/**
	 * Get the associated ReplayCategory object
	 *
	 * @param      PropelPDO Optional Connection object.
	 * @return     ReplayCategory The associated ReplayCategory object.
	 * @throws     PropelException
	 */
	public function getReplayCategory(PropelPDO $con = null)
	{
		if ($this->aReplayCategory === null && ($this->category_id !== null)) {
			$this->aReplayCategory = ReplayCategoryPeer::retrieveByPk($this->category_id);
			/* The following can be used additionally to
			   guarantee the related object contains a reference
			   to this object.  This level of coupling may, however, be
			   undesirable since it could result in an only partially populated collection
			   in the referenced object.
			   $this->aReplayCategory->addReplays($this);
			 */
		}
		return $this->aReplayCategory;
	}

	/**
	 * Clears out the collReplayComments collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addReplayComments()
	 */
	public function clearReplayComments()
	{
		$this->collReplayComments = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collReplayComments collection (array).
	 *
	 * By default this just sets the collReplayComments collection to an empty array (like clearcollReplayComments());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initReplayComments()
	{
		$this->collReplayComments = array();
	}

	/**
	 * Gets an array of ReplayComment objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Replay has previously been saved, it will retrieve
	 * related ReplayComments from storage. If this Replay is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array ReplayComment[]
	 * @throws     PropelException
	 */
	public function getReplayComments($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ReplayPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collReplayComments === null) {
			if ($this->isNew()) {
			   $this->collReplayComments = array();
			} else {

				$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

				ReplayCommentPeer::addSelectColumns($criteria);
				$this->collReplayComments = ReplayCommentPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

				ReplayCommentPeer::addSelectColumns($criteria);
				if (!isset($this->lastReplayCommentCriteria) || !$this->lastReplayCommentCriteria->equals($criteria)) {
					$this->collReplayComments = ReplayCommentPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastReplayCommentCriteria = $criteria;
		return $this->collReplayComments;
	}

	/**
	 * Returns the number of related ReplayComment objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related ReplayComment objects.
	 * @throws     PropelException
	 */
	public function countReplayComments(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ReplayPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collReplayComments === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

				$count = ReplayCommentPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

				if (!isset($this->lastReplayCommentCriteria) || !$this->lastReplayCommentCriteria->equals($criteria)) {
					$count = ReplayCommentPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collReplayComments);
				}
			} else {
				$count = count($this->collReplayComments);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a ReplayComment object to this object
	 * through the ReplayComment foreign key attribute.
	 *
	 * @param      ReplayComment $l ReplayComment
	 * @return     void
	 * @throws     PropelException
	 */
	public function addReplayComment(ReplayComment $l)
	{
		if ($this->collReplayComments === null) {
			$this->initReplayComments();
		}
		if (!in_array($l, $this->collReplayComments, true)) { // only add it if the **same** object is not already associated
			array_push($this->collReplayComments, $l);
			$l->setReplay($this);
		}
	}


	/**
	 * If this collection has already been initialized with
	 * an identical criteria, it returns the collection.
	 * Otherwise if this Replay is new, it will return
	 * an empty collection; or if this Replay has previously
	 * been saved, it will retrieve related ReplayComments from storage.
	 *
	 * This method is protected by default in order to keep the public
	 * api reasonable.  You can provide public methods for those you
	 * actually need in Replay.
	 */
	public function getReplayCommentsJoinsfGuardUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ReplayPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collReplayComments === null) {
			if ($this->isNew()) {
				$this->collReplayComments = array();
			} else {

				$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

				$this->collReplayComments = ReplayCommentPeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		} else {
			// the following code is to determine if a new query is
			// called for.  If the criteria is the same as the last
			// one, just return the collection.

			$criteria->add(ReplayCommentPeer::REPLAY_ID, $this->id);

			if (!isset($this->lastReplayCommentCriteria) || !$this->lastReplayCommentCriteria->equals($criteria)) {
				$this->collReplayComments = ReplayCommentPeer::doSelectJoinsfGuardUser($criteria, $con, $join_behavior);
			}
		}
		$this->lastReplayCommentCriteria = $criteria;

		return $this->collReplayComments;
	}

	/**
	 * Clears out the collReplayOftheweeks collection (array).
	 *
	 * This does not modify the database; however, it will remove any associated objects, causing
	 * them to be refetched by subsequent calls to accessor method.
	 *
	 * @return     void
	 * @see        addReplayOftheweeks()
	 */
	public function clearReplayOftheweeks()
	{
		$this->collReplayOftheweeks = null; // important to set this to NULL since that means it is uninitialized
	}

	/**
	 * Initializes the collReplayOftheweeks collection (array).
	 *
	 * By default this just sets the collReplayOftheweeks collection to an empty array (like clearcollReplayOftheweeks());
	 * however, you may wish to override this method in your stub class to provide setting appropriate
	 * to your application -- for example, setting the initial array to the values stored in database.
	 *
	 * @return     void
	 */
	public function initReplayOftheweeks()
	{
		$this->collReplayOftheweeks = array();
	}

	/**
	 * Gets an array of ReplayOftheweek objects which contain a foreign key that references this object.
	 *
	 * If this collection has already been initialized with an identical Criteria, it returns the collection.
	 * Otherwise if this Replay has previously been saved, it will retrieve
	 * related ReplayOftheweeks from storage. If this Replay is new, it will return
	 * an empty collection or the current collection, the criteria is ignored on a new object.
	 *
	 * @param      PropelPDO $con
	 * @param      Criteria $criteria
	 * @return     array ReplayOftheweek[]
	 * @throws     PropelException
	 */
	public function getReplayOftheweeks($criteria = null, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ReplayPeer::DATABASE_NAME);
		}
		elseif ($criteria instanceof Criteria)
		{
			$criteria = clone $criteria;
		}

		if ($this->collReplayOftheweeks === null) {
			if ($this->isNew()) {
			   $this->collReplayOftheweeks = array();
			} else {

				$criteria->add(ReplayOftheweekPeer::REPLAY_ID, $this->id);

				ReplayOftheweekPeer::addSelectColumns($criteria);
				$this->collReplayOftheweeks = ReplayOftheweekPeer::doSelect($criteria, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return the collection.


				$criteria->add(ReplayOftheweekPeer::REPLAY_ID, $this->id);

				ReplayOftheweekPeer::addSelectColumns($criteria);
				if (!isset($this->lastReplayOftheweekCriteria) || !$this->lastReplayOftheweekCriteria->equals($criteria)) {
					$this->collReplayOftheweeks = ReplayOftheweekPeer::doSelect($criteria, $con);
				}
			}
		}
		$this->lastReplayOftheweekCriteria = $criteria;
		return $this->collReplayOftheweeks;
	}

	/**
	 * Returns the number of related ReplayOftheweek objects.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct
	 * @param      PropelPDO $con
	 * @return     int Count of related ReplayOftheweek objects.
	 * @throws     PropelException
	 */
	public function countReplayOftheweeks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
	{
		if ($criteria === null) {
			$criteria = new Criteria(ReplayPeer::DATABASE_NAME);
		} else {
			$criteria = clone $criteria;
		}

		if ($distinct) {
			$criteria->setDistinct();
		}

		$count = null;

		if ($this->collReplayOftheweeks === null) {
			if ($this->isNew()) {
				$count = 0;
			} else {

				$criteria->add(ReplayOftheweekPeer::REPLAY_ID, $this->id);

				$count = ReplayOftheweekPeer::doCount($criteria, false, $con);
			}
		} else {
			// criteria has no effect for a new object
			if (!$this->isNew()) {
				// the following code is to determine if a new query is
				// called for.  If the criteria is the same as the last
				// one, just return count of the collection.


				$criteria->add(ReplayOftheweekPeer::REPLAY_ID, $this->id);

				if (!isset($this->lastReplayOftheweekCriteria) || !$this->lastReplayOftheweekCriteria->equals($criteria)) {
					$count = ReplayOftheweekPeer::doCount($criteria, false, $con);
				} else {
					$count = count($this->collReplayOftheweeks);
				}
			} else {
				$count = count($this->collReplayOftheweeks);
			}
		}
		return $count;
	}

	/**
	 * Method called to associate a ReplayOftheweek object to this object
	 * through the ReplayOftheweek foreign key attribute.
	 *
	 * @param      ReplayOftheweek $l ReplayOftheweek
	 * @return     void
	 * @throws     PropelException
	 */
	public function addReplayOftheweek(ReplayOftheweek $l)
	{
		if ($this->collReplayOftheweeks === null) {
			$this->initReplayOftheweeks();
		}
		if (!in_array($l, $this->collReplayOftheweeks, true)) { // only add it if the **same** object is not already associated
			array_push($this->collReplayOftheweeks, $l);
			$l->setReplay($this);
		}
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
			if ($this->collReplayComments) {
				foreach ((array) $this->collReplayComments as $o) {
					$o->clearAllReferences($deep);
				}
			}
			if ($this->collReplayOftheweeks) {
				foreach ((array) $this->collReplayOftheweeks as $o) {
					$o->clearAllReferences($deep);
				}
			}
		} // if ($deep)

		$this->collReplayComments = null;
		$this->collReplayOftheweeks = null;
			$this->asfGuardUser = null;
			$this->aReplayGameType = null;
			$this->aReplayCategory = null;
	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseReplay:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseReplay::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseReplay
