<?php

namespace Base;

use \CategoryToMetric as ChildCategoryToMetric;
use \CategoryToMetricQuery as ChildCategoryToMetricQuery;
use \Metric as ChildMetric;
use \MetricData as ChildMetricData;
use \MetricDataQuery as ChildMetricDataQuery;
use \MetricQuery as ChildMetricQuery;
use \Exception;
use \PDO;
use Map\MetricTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'metric' table.
 *
 * 
 *
* @package    propel.generator..Base
*/
abstract class Metric implements ActiveRecordInterface 
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\MetricTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the label field.
     * @var        string
     */
    protected $label;

    /**
     * The value for the description field.
     * @var        string
     */
    protected $description;

    /**
     * The value for the ignoreuntil field.
     * @var        int
     */
    protected $ignoreuntil;

    /**
     * The value for the missingalert field.
     * @var        int
     */
    protected $missingalert;

    /**
     * @var        ObjectCollection|ChildMetricData[] Collection to store aggregation of ChildMetricData objects.
     */
    protected $collMetricDatas;
    protected $collMetricDatasPartial;

    /**
     * @var        ObjectCollection|ChildCategoryToMetric[] Collection to store aggregation of ChildCategoryToMetric objects.
     */
    protected $collCategoryToMetrics;
    protected $collCategoryToMetricsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMetricData[]
     */
    protected $metricDatasScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCategoryToMetric[]
     */
    protected $categoryToMetricsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Metric object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Metric</code> instance.  If
     * <code>obj</code> is an instance of <code>Metric</code>, delegates to
     * <code>equals(Metric)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Metric The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [label] column value.
     * 
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the [description] column value.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the [ignoreuntil] column value.
     * 
     * @return int
     */
    public function getIgnoreuntil()
    {
        return $this->ignoreuntil;
    }

    /**
     * Get the [missingalert] column value.
     * 
     * @return int
     */
    public function getMissingalert()
    {
        return $this->missingalert;
    }

    /**
     * Set the value of [id] column.
     * 
     * @param int $v new value
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[MetricTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [label] column.
     * 
     * @param string $v new value
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function setLabel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->label !== $v) {
            $this->label = $v;
            $this->modifiedColumns[MetricTableMap::COL_LABEL] = true;
        }

        return $this;
    } // setLabel()

    /**
     * Set the value of [description] column.
     * 
     * @param string $v new value
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function setDescription($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->description !== $v) {
            $this->description = $v;
            $this->modifiedColumns[MetricTableMap::COL_DESCRIPTION] = true;
        }

        return $this;
    } // setDescription()

    /**
     * Set the value of [ignoreuntil] column.
     * 
     * @param int $v new value
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function setIgnoreuntil($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->ignoreuntil !== $v) {
            $this->ignoreuntil = $v;
            $this->modifiedColumns[MetricTableMap::COL_IGNOREUNTIL] = true;
        }

        return $this;
    } // setIgnoreuntil()

    /**
     * Set the value of [missingalert] column.
     * 
     * @param int $v new value
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function setMissingalert($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->missingalert !== $v) {
            $this->missingalert = $v;
            $this->modifiedColumns[MetricTableMap::COL_MISSINGALERT] = true;
        }

        return $this;
    } // setMissingalert()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
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
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : MetricTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : MetricTableMap::translateFieldName('Label', TableMap::TYPE_PHPNAME, $indexType)];
            $this->label = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : MetricTableMap::translateFieldName('Description', TableMap::TYPE_PHPNAME, $indexType)];
            $this->description = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : MetricTableMap::translateFieldName('Ignoreuntil', TableMap::TYPE_PHPNAME, $indexType)];
            $this->ignoreuntil = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : MetricTableMap::translateFieldName('Missingalert', TableMap::TYPE_PHPNAME, $indexType)];
            $this->missingalert = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = MetricTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Metric'), 0, $e);
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
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MetricTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildMetricQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMetricDatas = null;

            $this->collCategoryToMetrics = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Metric::setDeleted()
     * @see Metric::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildMetricQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
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
                MetricTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->metricDatasScheduledForDeletion !== null) {
                if (!$this->metricDatasScheduledForDeletion->isEmpty()) {
                    \MetricDataQuery::create()
                        ->filterByPrimaryKeys($this->metricDatasScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->metricDatasScheduledForDeletion = null;
                }
            }

            if ($this->collMetricDatas !== null) {
                foreach ($this->collMetricDatas as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoryToMetricsScheduledForDeletion !== null) {
                if (!$this->categoryToMetricsScheduledForDeletion->isEmpty()) {
                    \CategoryToMetricQuery::create()
                        ->filterByPrimaryKeys($this->categoryToMetricsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoryToMetricsScheduledForDeletion = null;
                }
            }

            if ($this->collCategoryToMetrics !== null) {
                foreach ($this->collCategoryToMetrics as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[MetricTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MetricTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MetricTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(MetricTableMap::COL_LABEL)) {
            $modifiedColumns[':p' . $index++]  = 'label';
        }
        if ($this->isColumnModified(MetricTableMap::COL_DESCRIPTION)) {
            $modifiedColumns[':p' . $index++]  = 'description';
        }
        if ($this->isColumnModified(MetricTableMap::COL_IGNOREUNTIL)) {
            $modifiedColumns[':p' . $index++]  = 'ignoreuntil';
        }
        if ($this->isColumnModified(MetricTableMap::COL_MISSINGALERT)) {
            $modifiedColumns[':p' . $index++]  = 'missingalert';
        }

        $sql = sprintf(
            'INSERT INTO metric (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':                        
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'label':                        
                        $stmt->bindValue($identifier, $this->label, PDO::PARAM_STR);
                        break;
                    case 'description':                        
                        $stmt->bindValue($identifier, $this->description, PDO::PARAM_STR);
                        break;
                    case 'ignoreuntil':                        
                        $stmt->bindValue($identifier, $this->ignoreuntil, PDO::PARAM_INT);
                        break;
                    case 'missingalert':                        
                        $stmt->bindValue($identifier, $this->missingalert, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MetricTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getLabel();
                break;
            case 2:
                return $this->getDescription();
                break;
            case 3:
                return $this->getIgnoreuntil();
                break;
            case 4:
                return $this->getMissingalert();
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
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Metric'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Metric'][$this->hashCode()] = true;
        $keys = MetricTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getLabel(),
            $keys[2] => $this->getDescription(),
            $keys[3] => $this->getIgnoreuntil(),
            $keys[4] => $this->getMissingalert(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }
        
        if ($includeForeignObjects) {
            if (null !== $this->collMetricDatas) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'metricDatas';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'metric_datas';
                        break;
                    default:
                        $key = 'MetricDatas';
                }
        
                $result[$key] = $this->collMetricDatas->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategoryToMetrics) {
                
                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'categoryToMetrics';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'category_to_metrics';
                        break;
                    default:
                        $key = 'CategoryToMetrics';
                }
        
                $result[$key] = $this->collCategoryToMetrics->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Metric
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = MetricTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Metric
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setLabel($value);
                break;
            case 2:
                $this->setDescription($value);
                break;
            case 3:
                $this->setIgnoreuntil($value);
                break;
            case 4:
                $this->setMissingalert($value);
                break;
        } // switch()

        return $this;
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
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = MetricTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setLabel($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setDescription($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIgnoreuntil($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setMissingalert($arr[$keys[4]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Metric The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MetricTableMap::DATABASE_NAME);

        if ($this->isColumnModified(MetricTableMap::COL_ID)) {
            $criteria->add(MetricTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(MetricTableMap::COL_LABEL)) {
            $criteria->add(MetricTableMap::COL_LABEL, $this->label);
        }
        if ($this->isColumnModified(MetricTableMap::COL_DESCRIPTION)) {
            $criteria->add(MetricTableMap::COL_DESCRIPTION, $this->description);
        }
        if ($this->isColumnModified(MetricTableMap::COL_IGNOREUNTIL)) {
            $criteria->add(MetricTableMap::COL_IGNOREUNTIL, $this->ignoreuntil);
        }
        if ($this->isColumnModified(MetricTableMap::COL_MISSINGALERT)) {
            $criteria->add(MetricTableMap::COL_MISSINGALERT, $this->missingalert);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildMetricQuery::create();
        $criteria->add(MetricTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }
        
    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Metric (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setLabel($this->getLabel());
        $copyObj->setDescription($this->getDescription());
        $copyObj->setIgnoreuntil($this->getIgnoreuntil());
        $copyObj->setMissingalert($this->getMissingalert());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getMetricDatas() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMetricData($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategoryToMetrics() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategoryToMetric($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Metric Clone of current object.
     * @throws PropelException
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
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('MetricData' == $relationName) {
            return $this->initMetricDatas();
        }
        if ('CategoryToMetric' == $relationName) {
            return $this->initCategoryToMetrics();
        }
    }

    /**
     * Clears out the collMetricDatas collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addMetricDatas()
     */
    public function clearMetricDatas()
    {
        $this->collMetricDatas = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collMetricDatas collection loaded partially.
     */
    public function resetPartialMetricDatas($v = true)
    {
        $this->collMetricDatasPartial = $v;
    }

    /**
     * Initializes the collMetricDatas collection.
     *
     * By default this just sets the collMetricDatas collection to an empty array (like clearcollMetricDatas());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMetricDatas($overrideExisting = true)
    {
        if (null !== $this->collMetricDatas && !$overrideExisting) {
            return;
        }
        $this->collMetricDatas = new ObjectCollection();
        $this->collMetricDatas->setModel('\MetricData');
    }

    /**
     * Gets an array of ChildMetricData objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMetric is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMetricData[] List of ChildMetricData objects
     * @throws PropelException
     */
    public function getMetricDatas(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collMetricDatasPartial && !$this->isNew();
        if (null === $this->collMetricDatas || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMetricDatas) {
                // return empty collection
                $this->initMetricDatas();
            } else {
                $collMetricDatas = ChildMetricDataQuery::create(null, $criteria)
                    ->filterByMetric($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMetricDatasPartial && count($collMetricDatas)) {
                        $this->initMetricDatas(false);

                        foreach ($collMetricDatas as $obj) {
                            if (false == $this->collMetricDatas->contains($obj)) {
                                $this->collMetricDatas->append($obj);
                            }
                        }

                        $this->collMetricDatasPartial = true;
                    }

                    return $collMetricDatas;
                }

                if ($partial && $this->collMetricDatas) {
                    foreach ($this->collMetricDatas as $obj) {
                        if ($obj->isNew()) {
                            $collMetricDatas[] = $obj;
                        }
                    }
                }

                $this->collMetricDatas = $collMetricDatas;
                $this->collMetricDatasPartial = false;
            }
        }

        return $this->collMetricDatas;
    }

    /**
     * Sets a collection of ChildMetricData objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $metricDatas A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMetric The current object (for fluent API support)
     */
    public function setMetricDatas(Collection $metricDatas, ConnectionInterface $con = null)
    {
        /** @var ChildMetricData[] $metricDatasToDelete */
        $metricDatasToDelete = $this->getMetricDatas(new Criteria(), $con)->diff($metricDatas);

        
        $this->metricDatasScheduledForDeletion = $metricDatasToDelete;

        foreach ($metricDatasToDelete as $metricDataRemoved) {
            $metricDataRemoved->setMetric(null);
        }

        $this->collMetricDatas = null;
        foreach ($metricDatas as $metricData) {
            $this->addMetricData($metricData);
        }

        $this->collMetricDatas = $metricDatas;
        $this->collMetricDatasPartial = false;

        return $this;
    }

    /**
     * Returns the number of related MetricData objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related MetricData objects.
     * @throws PropelException
     */
    public function countMetricDatas(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collMetricDatasPartial && !$this->isNew();
        if (null === $this->collMetricDatas || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMetricDatas) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMetricDatas());
            }

            $query = ChildMetricDataQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMetric($this)
                ->count($con);
        }

        return count($this->collMetricDatas);
    }

    /**
     * Method called to associate a ChildMetricData object to this object
     * through the ChildMetricData foreign key attribute.
     *
     * @param  ChildMetricData $l ChildMetricData
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function addMetricData(ChildMetricData $l)
    {
        if ($this->collMetricDatas === null) {
            $this->initMetricDatas();
            $this->collMetricDatasPartial = true;
        }

        if (!$this->collMetricDatas->contains($l)) {
            $this->doAddMetricData($l);
        }

        return $this;
    }

    /**
     * @param ChildMetricData $metricData The ChildMetricData object to add.
     */
    protected function doAddMetricData(ChildMetricData $metricData)
    {
        $this->collMetricDatas[]= $metricData;
        $metricData->setMetric($this);
    }

    /**
     * @param  ChildMetricData $metricData The ChildMetricData object to remove.
     * @return $this|ChildMetric The current object (for fluent API support)
     */
    public function removeMetricData(ChildMetricData $metricData)
    {
        if ($this->getMetricDatas()->contains($metricData)) {
            $pos = $this->collMetricDatas->search($metricData);
            $this->collMetricDatas->remove($pos);
            if (null === $this->metricDatasScheduledForDeletion) {
                $this->metricDatasScheduledForDeletion = clone $this->collMetricDatas;
                $this->metricDatasScheduledForDeletion->clear();
            }
            $this->metricDatasScheduledForDeletion[]= clone $metricData;
            $metricData->setMetric(null);
        }

        return $this;
    }

    /**
     * Clears out the collCategoryToMetrics collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCategoryToMetrics()
     */
    public function clearCategoryToMetrics()
    {
        $this->collCategoryToMetrics = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCategoryToMetrics collection loaded partially.
     */
    public function resetPartialCategoryToMetrics($v = true)
    {
        $this->collCategoryToMetricsPartial = $v;
    }

    /**
     * Initializes the collCategoryToMetrics collection.
     *
     * By default this just sets the collCategoryToMetrics collection to an empty array (like clearcollCategoryToMetrics());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategoryToMetrics($overrideExisting = true)
    {
        if (null !== $this->collCategoryToMetrics && !$overrideExisting) {
            return;
        }
        $this->collCategoryToMetrics = new ObjectCollection();
        $this->collCategoryToMetrics->setModel('\CategoryToMetric');
    }

    /**
     * Gets an array of ChildCategoryToMetric objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildMetric is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCategoryToMetric[] List of ChildCategoryToMetric objects
     * @throws PropelException
     */
    public function getCategoryToMetrics(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoryToMetricsPartial && !$this->isNew();
        if (null === $this->collCategoryToMetrics || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategoryToMetrics) {
                // return empty collection
                $this->initCategoryToMetrics();
            } else {
                $collCategoryToMetrics = ChildCategoryToMetricQuery::create(null, $criteria)
                    ->filterByMetric($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCategoryToMetricsPartial && count($collCategoryToMetrics)) {
                        $this->initCategoryToMetrics(false);

                        foreach ($collCategoryToMetrics as $obj) {
                            if (false == $this->collCategoryToMetrics->contains($obj)) {
                                $this->collCategoryToMetrics->append($obj);
                            }
                        }

                        $this->collCategoryToMetricsPartial = true;
                    }

                    return $collCategoryToMetrics;
                }

                if ($partial && $this->collCategoryToMetrics) {
                    foreach ($this->collCategoryToMetrics as $obj) {
                        if ($obj->isNew()) {
                            $collCategoryToMetrics[] = $obj;
                        }
                    }
                }

                $this->collCategoryToMetrics = $collCategoryToMetrics;
                $this->collCategoryToMetricsPartial = false;
            }
        }

        return $this->collCategoryToMetrics;
    }

    /**
     * Sets a collection of ChildCategoryToMetric objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $categoryToMetrics A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildMetric The current object (for fluent API support)
     */
    public function setCategoryToMetrics(Collection $categoryToMetrics, ConnectionInterface $con = null)
    {
        /** @var ChildCategoryToMetric[] $categoryToMetricsToDelete */
        $categoryToMetricsToDelete = $this->getCategoryToMetrics(new Criteria(), $con)->diff($categoryToMetrics);

        
        $this->categoryToMetricsScheduledForDeletion = $categoryToMetricsToDelete;

        foreach ($categoryToMetricsToDelete as $categoryToMetricRemoved) {
            $categoryToMetricRemoved->setMetric(null);
        }

        $this->collCategoryToMetrics = null;
        foreach ($categoryToMetrics as $categoryToMetric) {
            $this->addCategoryToMetric($categoryToMetric);
        }

        $this->collCategoryToMetrics = $categoryToMetrics;
        $this->collCategoryToMetricsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CategoryToMetric objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related CategoryToMetric objects.
     * @throws PropelException
     */
    public function countCategoryToMetrics(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoryToMetricsPartial && !$this->isNew();
        if (null === $this->collCategoryToMetrics || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategoryToMetrics) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategoryToMetrics());
            }

            $query = ChildCategoryToMetricQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMetric($this)
                ->count($con);
        }

        return count($this->collCategoryToMetrics);
    }

    /**
     * Method called to associate a ChildCategoryToMetric object to this object
     * through the ChildCategoryToMetric foreign key attribute.
     *
     * @param  ChildCategoryToMetric $l ChildCategoryToMetric
     * @return $this|\Metric The current object (for fluent API support)
     */
    public function addCategoryToMetric(ChildCategoryToMetric $l)
    {
        if ($this->collCategoryToMetrics === null) {
            $this->initCategoryToMetrics();
            $this->collCategoryToMetricsPartial = true;
        }

        if (!$this->collCategoryToMetrics->contains($l)) {
            $this->doAddCategoryToMetric($l);
        }

        return $this;
    }

    /**
     * @param ChildCategoryToMetric $categoryToMetric The ChildCategoryToMetric object to add.
     */
    protected function doAddCategoryToMetric(ChildCategoryToMetric $categoryToMetric)
    {
        $this->collCategoryToMetrics[]= $categoryToMetric;
        $categoryToMetric->setMetric($this);
    }

    /**
     * @param  ChildCategoryToMetric $categoryToMetric The ChildCategoryToMetric object to remove.
     * @return $this|ChildMetric The current object (for fluent API support)
     */
    public function removeCategoryToMetric(ChildCategoryToMetric $categoryToMetric)
    {
        if ($this->getCategoryToMetrics()->contains($categoryToMetric)) {
            $pos = $this->collCategoryToMetrics->search($categoryToMetric);
            $this->collCategoryToMetrics->remove($pos);
            if (null === $this->categoryToMetricsScheduledForDeletion) {
                $this->categoryToMetricsScheduledForDeletion = clone $this->collCategoryToMetrics;
                $this->categoryToMetricsScheduledForDeletion->clear();
            }
            $this->categoryToMetricsScheduledForDeletion[]= clone $categoryToMetric;
            $categoryToMetric->setMetric(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Metric is new, it will return
     * an empty collection; or if this Metric has previously
     * been saved, it will retrieve related CategoryToMetrics from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Metric.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildCategoryToMetric[] List of ChildCategoryToMetric objects
     */
    public function getCategoryToMetricsJoinCategory(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildCategoryToMetricQuery::create(null, $criteria);
        $query->joinWith('Category', $joinBehavior);

        return $this->getCategoryToMetrics($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        $this->id = null;
        $this->label = null;
        $this->description = null;
        $this->ignoreuntil = null;
        $this->missingalert = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collMetricDatas) {
                foreach ($this->collMetricDatas as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategoryToMetrics) {
                foreach ($this->collCategoryToMetrics as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collMetricDatas = null;
        $this->collCategoryToMetrics = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(MetricTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
