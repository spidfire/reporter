<?php

namespace Map;

use \MetricData;
use \MetricDataQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'metric_data' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class MetricDataTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = '.Map.MetricDataTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'reporter';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'metric_data';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\MetricData';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'MetricData';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'metric_data.id';

    /**
     * the column name for the metric_id field
     */
    const COL_METRIC_ID = 'metric_data.metric_id';

    /**
     * the column name for the time field
     */
    const COL_TIME = 'metric_data.time';

    /**
     * the column name for the success field
     */
    const COL_SUCCESS = 'metric_data.success';

    /**
     * the column name for the value field
     */
    const COL_VALUE = 'metric_data.value';

    /**
     * the column name for the checked field
     */
    const COL_CHECKED = 'metric_data.checked';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'MetricId', 'Time', 'Success', 'Value', 'Checked', ),
        self::TYPE_CAMELNAME     => array('id', 'metricId', 'time', 'success', 'value', 'checked', ),
        self::TYPE_COLNAME       => array(MetricDataTableMap::COL_ID, MetricDataTableMap::COL_METRIC_ID, MetricDataTableMap::COL_TIME, MetricDataTableMap::COL_SUCCESS, MetricDataTableMap::COL_VALUE, MetricDataTableMap::COL_CHECKED, ),
        self::TYPE_FIELDNAME     => array('id', 'metric_id', 'time', 'success', 'value', 'checked', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'MetricId' => 1, 'Time' => 2, 'Success' => 3, 'Value' => 4, 'Checked' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'metricId' => 1, 'time' => 2, 'success' => 3, 'value' => 4, 'checked' => 5, ),
        self::TYPE_COLNAME       => array(MetricDataTableMap::COL_ID => 0, MetricDataTableMap::COL_METRIC_ID => 1, MetricDataTableMap::COL_TIME => 2, MetricDataTableMap::COL_SUCCESS => 3, MetricDataTableMap::COL_VALUE => 4, MetricDataTableMap::COL_CHECKED => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'metric_id' => 1, 'time' => 2, 'success' => 3, 'value' => 4, 'checked' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('metric_data');
        $this->setPhpName('MetricData');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\MetricData');
        $this->setPackage('');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('metric_id', 'MetricId', 'INTEGER', 'metric', 'id', true, null, null);
        $this->addColumn('time', 'Time', 'INTEGER', true, null, null);
        $this->addColumn('success', 'Success', 'INTEGER', true, 1, null);
        $this->addColumn('value', 'Value', 'LONGVARCHAR', false, null, null);
        $this->addColumn('checked', 'Checked', 'INTEGER', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Metric', '\\Metric', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':metric_id',
    1 => ':id',
  ),
), 'CASCADE', 'CASCADE', null, false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }
    
    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? MetricDataTableMap::CLASS_DEFAULT : MetricDataTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (MetricData object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = MetricDataTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MetricDataTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MetricDataTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MetricDataTableMap::OM_CLASS;
            /** @var MetricData $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MetricDataTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();
    
        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MetricDataTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MetricDataTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var MetricData $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MetricDataTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MetricDataTableMap::COL_ID);
            $criteria->addSelectColumn(MetricDataTableMap::COL_METRIC_ID);
            $criteria->addSelectColumn(MetricDataTableMap::COL_TIME);
            $criteria->addSelectColumn(MetricDataTableMap::COL_SUCCESS);
            $criteria->addSelectColumn(MetricDataTableMap::COL_VALUE);
            $criteria->addSelectColumn(MetricDataTableMap::COL_CHECKED);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.metric_id');
            $criteria->addSelectColumn($alias . '.time');
            $criteria->addSelectColumn($alias . '.success');
            $criteria->addSelectColumn($alias . '.value');
            $criteria->addSelectColumn($alias . '.checked');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(MetricDataTableMap::DATABASE_NAME)->getTable(MetricDataTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(MetricDataTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(MetricDataTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new MetricDataTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a MetricData or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or MetricData object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricDataTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \MetricData) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MetricDataTableMap::DATABASE_NAME);
            $criteria->add(MetricDataTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MetricDataQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MetricDataTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MetricDataTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the metric_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return MetricDataQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a MetricData or Criteria object.
     *
     * @param mixed               $criteria Criteria or MetricData object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricDataTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from MetricData object
        }

        if ($criteria->containsKey(MetricDataTableMap::COL_ID) && $criteria->keyContainsValue(MetricDataTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MetricDataTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MetricDataQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // MetricDataTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
MetricDataTableMap::buildTableMap();
