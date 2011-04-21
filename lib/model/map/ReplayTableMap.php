<?php


/**
 * This class defines the structure of the 'replay' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class ReplayTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.ReplayTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('replay');
		$this->setPhpName('Replay');
		$this->setClassname('Replay');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addForeignKey('USER_ID', 'UserId', 'INTEGER', 'sf_guard_user', 'ID', true, null, null);
		$this->addForeignKey('GAME_TYPE_ID', 'GameTypeId', 'INTEGER', 'replay_game_type', 'ID', true, null, null);
		$this->addForeignKey('CATEGORY_ID', 'CategoryId', 'INTEGER', 'replay_category', 'ID', true, null, null);
		$this->addColumn('GAME_INFO', 'GameInfo', 'LONGVARCHAR', true, null, null);
		$this->addColumn('DESCRIPTION', 'Description', 'LONGVARCHAR', true, null, null);
		$this->addColumn('AVG_APM', 'AvgApm', 'SMALLINT', true, null, null);
		$this->addColumn('PLAYERS', 'Players', 'VARCHAR', true, 255, null);
		$this->addColumn('MAP_NAME', 'MapName', 'VARCHAR', true, 255, null);
		$this->addColumn('DOWNLOAD_COUNT', 'DownloadCount', 'SMALLINT', true, null, 0);
		$this->addColumn('PUBLISHED_AT', 'PublishedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
		$this->addColumn('REPORTED_COUNT', 'ReportedCount', 'SMALLINT', false, null, 0);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('sfGuardUser', 'sfGuardUser', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
    $this->addRelation('ReplayGameType', 'ReplayGameType', RelationMap::MANY_TO_ONE, array('game_type_id' => 'id', ), null, null);
    $this->addRelation('ReplayCategory', 'ReplayCategory', RelationMap::MANY_TO_ONE, array('category_id' => 'id', ), null, null);
    $this->addRelation('ReplayComment', 'ReplayComment', RelationMap::ONE_TO_MANY, array('id' => 'replay_id', ), 'CASCADE', null);
    $this->addRelation('ReplayOftheweek', 'ReplayOftheweek', RelationMap::ONE_TO_MANY, array('id' => 'replay_id', ), null, null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
			'symfony_timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', ),
		);
	} // getBehaviors()

} // ReplayTableMap
