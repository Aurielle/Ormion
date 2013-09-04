<?php

namespace Ormion\Behavior;

use \Nette\Object;
use Ormion\IRecord;

/**
 * Timestampable behavior
 *
 * @author Jan Marek
 * @license MIT
 */
class Authored extends Object implements IBehavior
{
	/** @var string|null */
	private $created;

	/** @var string|null */
	private $createdBy;

	/** @var string|null */
	private $updated;

	/** @var string|null */
	private $updatedBy;

        const MYSQL_DATETIME_FORMAT = "Y-m-d H:i:s";
        const MYSQL_DATE_FORMAT = "Y-m-d";


	/**
	 * Constructor
	 * @param string|null created
	 * @param string|null updated
	 */
	public function __construct($created = "created", $updated = "updated", $createdBy = "creator_id", $updatedBy = "updator_id")
	{
		$this->created = $created;
                $this->createdBy = $createdBy;
		$this->updated = $updated;
                $this->updatedBy = $updatedBy;
	}



	/**
	 * Setup behavior
	 * @param IRecord record
	 */
	public function setUp(IRecord $record)
	{
		if (isset($this->created) && $this->created) {
                    $record->onBeforeInsert[] = array($this, "updateCreated");
		}
                if ( isset ($this->createdBy) && $this->createdBy) {
                    $record->onBeforeInsert[] = array($this, "updateCreatedBy");
                }

		if (isset($this->updated) && $this->updated) {
                    $record->onBeforeUpdate[] = array($this, "updateUpdated");
                    $record->onBeforeInsert[] = array($this, "updateUpdated");
		}
                if ( isset ($this->updatedBy) && $this->updatedBy) {
                    $record->onBeforeUpdate[] = array($this, "updateUpdatedBy");
                    $record->onBeforeInsert[] = array($this, "updateUpdatedBy");
                }
                
	}



	public function updateCreated(IRecord $record)
	{
		if ( empty($record->{$this->created}) )
			$record->{$this->created} = date(self::MYSQL_DATETIME_FORMAT);
	}



	public function updateUpdated(IRecord $record)
	{
		$record->{$this->updated} = date(self::MYSQL_DATETIME_FORMAT);
	}

        
	
	public function updateCreatedBy(IRecord $record)
	{
		if ( empty($record->{$this->createdBy}) && user()->isLoggedIn()) 
			$record->{$this->createdBy} = user()->getId();
	}



	public function updateUpdatedBy(IRecord $record)
	{
		if (user()->isLoggedIn())
			$record->{$this->updatedBy} = user()->getId();
	}

}
