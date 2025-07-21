<?php

require(QCUBED_PROJECT_MODEL_GEN_DIR . '/SlidersListGen.php');

/**
* The SlidersList class defined here contains any
* customized code for the SlidersList class in the
* Object Relational Model. It represents the "sliders_list" table
* in the database and extends from the code generated abstract SlidersListGen
* class, which contains all the basic CRUD-type functionality as well as
* basic methods to handle relationships and index-based loading.
*
* @package My QCubed Application
* @subpackage Model
*
*/
class SlidersList extends SlidersListGen
{
    /**
     * Default "to string" handler
     * Allows pages to _p()/echo()/print() this object, and to define the default
     * way this object would be outputted.
     *
     * @return string a nicely formatted string representation of this object
     */
    public function __toString(): string
    {
        return 'SlidersList Object ' . $this->primaryKey();
    }

    // NOTE: Remember that when introducing a new custom function,
    // you must specify types for the function parameters as well as for the function return type!

    // Override or Create New load/count methods
    // (For obvious reasons, these methods are commented out...
    // But feel free to use these as a starting point)
/*

    public static function loadArrayBySample($strParam1, $intParam2, $objOptionalClauses = null) {
        // This will return an array of SlidersList objects
        return SlidersList::queryArray(
            QQ::AndCondition(
                QQ::Equal(QQN::SlidersList()->Param1, $strParam1),
                QQ::GreaterThan(QQN::SlidersList()->Param2, $intParam2)
            ),
            $objOptionalClauses
        );
    }


    public static function loadBySample($strParam1, $intParam2, $objOptionalClauses = null) {
        // This will return a single SlidersList object
        return SlidersList::querySingle(
            QQ::AndCondition(
                QQ::Equal(QQN::SlidersList()->Param1, $strParam1),
                QQ::GreaterThan(QQN::SlidersList()->Param2, $intParam2)
            ),
            $objOptionalClauses
        );
    }


    public static function countBySample($strParam1, $intParam2, $objOptionalClauses = null) {
        // This will return a count of SlidersList objects
        return SlidersList::queryCount(
            QQ::AndCondition(
                QQ::Equal(QQN::SlidersList()->Param1, $strParam1),
                QQ::Equal(QQN::SlidersList()->Param2, $intParam2)
            ),
            $objOptionalClauses
        );
    }


    public static function loadArrayBySample($strParam1, $intParam2, $objOptionalClauses) {
        // Performing the load manually (instead of using QCubed Query)

        // Get the Database Object for this Class
        $objDatabase = SlidersList::getDatabase();

        // Properly Escape All Input Parameters using Database->SqlVariable()
        $strParam1 = $objDatabase->SqlVariable($strParam1);
        $intParam2 = $objDatabase->SqlVariable($intParam2);

        // Setup the SQL Query
        $strQuery = sprintf('
            SELECT
                `sliders_list`.*
            FROM
                `sliders_list` AS `sliders_list`
            WHERE
                param_1 = %s AND
                param_2 < %s',
            $strParam1, $intParam2);

        // Perform the Query and Instantiate the Result
        $objDbResult = $objDatabase->Query($strQuery);
        return SlidersList::instantiateDbResult($objDbResult);
    }
*/

    // Override or Create New Properties and Variables
    // For performance reasons, these variables and __set and __get override methods
    // are commented out.  But if you wish to implement or override any
    // of the data-generated properties, please feel free to uncomment them.
/*
    protected $strSomeNewProperty;

    protected function __set(string $strName, mixed $mixValue): void
    {
        switch ($strName) {
            case 'SomeNewProperty': return $this->strSomeNewProperty;

            default:
                try {
                    return parent::__get($strName);
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }

    public function __set(string $strName, mixed $mixValue): void
    {
        switch ($strName) {
            case 'SomeNewProperty':
                try {
                    return ($this->strSomeNewProperty = \QCubed\Type::Cast($mixValue, \QCubed\Type::String));
                } catch (QInvalidCastException $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    return (parent::__set($strName, $mixValue));
                } catch (Caller $objExc) {
                    $objExc->incrementOffset();
                    throw $objExc;
                }
        }
    }
*/

}
