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
class SeoUrl extends Object implements IBehavior
{
	/** @var string|null */
	private $fieldName;

	/** @var string|null */
	private $valueField;

	
	/**
         *
         * @param string $valueField
         * @param string $fieldName
         */
	public function __construct($valueField = "name",$fieldName = "seoUrl" )
	{
		$this->valueField = $valueField;
                $this->fieldName = $fieldName;
		
	}



	/**
	 * Setup behavior
	 * @param IRecord record
	 */
	public function setUp(IRecord $record)
	{
		
            $record->onBeforeUpdate[] = array($this, "updateSeoUrl");
            $record->onAfterInsert[] = array($this, "insertSeoUrl");
                
                
	}



	public function updateSeoUrl(IRecord $record)
	{
            $value = $record->getPrimary() . "-" . \Nette\String::webalize( $record->{$this->valueField} );

            $record->{$this->fieldName} = $value;
	}

        public function insertSeoUrl(IRecord $record)
	{           
            $this->updateSeoUrl($record);
            $record->save();
	}



	

}