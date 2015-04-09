<?php

namespace Base;

use \MetricData as ChildMetricData;
use \MetricDataQuery as ChildMetricDataQuery;
use \Exception;
use \PDO;
use Map\MetricDataTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'metric_data' table.
 *
 * 
 *
 * @method     ChildMetricDataQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMetricDataQuery orderByMetricId($order = Criteria::ASC) Order by the metric_id column
 * @method     ChildMetricDataQuery orderByTime($order = Criteria::ASC) Order by the time column
 * @method     ChildMetricDataQuery orderBySuccess($order = Criteria::ASC) Order by the success column
 * @method     ChildMetricDataQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method     ChildMetricDataQuery orderByChecked($order = Criteria::ASC) Order by the checked column
 *
 * @method     ChildMetricDataQuery groupById() Group by the id column
 * @method     ChildMetricDataQuery groupByMetricId() Group by the metric_id column
 * @method     ChildMetricDataQuery groupByTime() Group by the time column
 * @method     ChildMetricDataQuery groupBySuccess() Group by the success column
 * @method     ChildMetricDataQuery groupByValue() Group by the value column
 * @method     ChildMetricDataQuery groupByChecked() Group by the checked column
 *
 * @method     ChildMetricDataQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMetricDataQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMetricDataQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMetricDataQuery leftJoinMetric($relationAlias = null) Adds a LEFT JOIN clause to the query using the Metric relation
 * @method     ChildMetricDataQuery rightJoinMetric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Metric relation
 * @method     ChildMetricDataQuery innerJoinMetric($relationAlias = null) Adds a INNER JOIN clause to the query using the Metric relation
 *
 * @method     \MetricQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMetricData findOne(ConnectionInterface $con = null) Return the first ChildMetricData matching the query
 * @method     ChildMetricData findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMetricData matching the query, or a new ChildMetricData object populated from the query conditions when no match is found
 *
 * @method     ChildMetricData findOneById(int $id) Return the first ChildMetricData filtered by the id column
 * @method     ChildMetricData findOneByMetricId(int $metric_id) Return the first ChildMetricData filtered by the metric_id column
 * @method     ChildMetricData findOneByTime(int $time) Return the first ChildMetricData filtered by the time column
 * @method     ChildMetricData findOneBySuccess(int $success) Return the first ChildMetricData filtered by the success column
 * @method     ChildMetricData findOneByValue(string $value) Return the first ChildMetricData filtered by the value column
 * @method     ChildMetricData findOneByChecked(int $checked) Return the first ChildMetricData filtered by the checked column *

 * @method     ChildMetricData requirePk($key, ConnectionInterface $con = null) Return the ChildMetricData by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOne(ConnectionInterface $con = null) Return the first ChildMetricData matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMetricData requireOneById(int $id) Return the first ChildMetricData filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOneByMetricId(int $metric_id) Return the first ChildMetricData filtered by the metric_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOneByTime(int $time) Return the first ChildMetricData filtered by the time column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOneBySuccess(int $success) Return the first ChildMetricData filtered by the success column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOneByValue(string $value) Return the first ChildMetricData filtered by the value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetricData requireOneByChecked(int $checked) Return the first ChildMetricData filtered by the checked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMetricData[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMetricData objects based on current ModelCriteria
 * @method     ChildMetricData[]|ObjectCollection findById(int $id) Return ChildMetricData objects filtered by the id column
 * @method     ChildMetricData[]|ObjectCollection findByMetricId(int $metric_id) Return ChildMetricData objects filtered by the metric_id column
 * @method     ChildMetricData[]|ObjectCollection findByTime(int $time) Return ChildMetricData objects filtered by the time column
 * @method     ChildMetricData[]|ObjectCollection findBySuccess(int $success) Return ChildMetricData objects filtered by the success column
 * @method     ChildMetricData[]|ObjectCollection findByValue(string $value) Return ChildMetricData objects filtered by the value column
 * @method     ChildMetricData[]|ObjectCollection findByChecked(int $checked) Return ChildMetricData objects filtered by the checked column
 * @method     ChildMetricData[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MetricDataQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\MetricDataQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'reporter', $modelName = '\\MetricData', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMetricDataQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMetricDataQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMetricDataQuery) {
            return $criteria;
        }
        $query = new ChildMetricDataQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildMetricData|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MetricDataTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MetricDataTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMetricData A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, metric_id, time, success, value, checked FROM metric_data WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);            
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildMetricData $obj */
            $obj = new ChildMetricData();
            $obj->hydrate($row);
            MetricDataTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildMetricData|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MetricDataTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MetricDataTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the metric_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMetricId(1234); // WHERE metric_id = 1234
     * $query->filterByMetricId(array(12, 34)); // WHERE metric_id IN (12, 34)
     * $query->filterByMetricId(array('min' => 12)); // WHERE metric_id > 12
     * </code>
     *
     * @see       filterByMetric()
     *
     * @param     mixed $metricId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByMetricId($metricId = null, $comparison = null)
    {
        if (is_array($metricId)) {
            $useMinMax = false;
            if (isset($metricId['min'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_METRIC_ID, $metricId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($metricId['max'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_METRIC_ID, $metricId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_METRIC_ID, $metricId, $comparison);
    }

    /**
     * Filter the query on the time column
     *
     * Example usage:
     * <code>
     * $query->filterByTime(1234); // WHERE time = 1234
     * $query->filterByTime(array(12, 34)); // WHERE time IN (12, 34)
     * $query->filterByTime(array('min' => 12)); // WHERE time > 12
     * </code>
     *
     * @param     mixed $time The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByTime($time = null, $comparison = null)
    {
        if (is_array($time)) {
            $useMinMax = false;
            if (isset($time['min'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_TIME, $time['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($time['max'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_TIME, $time['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_TIME, $time, $comparison);
    }

    /**
     * Filter the query on the success column
     *
     * Example usage:
     * <code>
     * $query->filterBySuccess(1234); // WHERE success = 1234
     * $query->filterBySuccess(array(12, 34)); // WHERE success IN (12, 34)
     * $query->filterBySuccess(array('min' => 12)); // WHERE success > 12
     * </code>
     *
     * @param     mixed $success The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterBySuccess($success = null, $comparison = null)
    {
        if (is_array($success)) {
            $useMinMax = false;
            if (isset($success['min'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_SUCCESS, $success['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($success['max'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_SUCCESS, $success['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_SUCCESS, $success, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue('fooValue');   // WHERE value = 'fooValue'
     * $query->filterByValue('%fooValue%'); // WHERE value LIKE '%fooValue%'
     * </code>
     *
     * @param     string $value The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($value)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $value)) {
                $value = str_replace('*', '%', $value);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the checked column
     *
     * Example usage:
     * <code>
     * $query->filterByChecked(1234); // WHERE checked = 1234
     * $query->filterByChecked(array(12, 34)); // WHERE checked IN (12, 34)
     * $query->filterByChecked(array('min' => 12)); // WHERE checked > 12
     * </code>
     *
     * @param     mixed $checked The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByChecked($checked = null, $comparison = null)
    {
        if (is_array($checked)) {
            $useMinMax = false;
            if (isset($checked['min'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_CHECKED, $checked['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($checked['max'])) {
                $this->addUsingAlias(MetricDataTableMap::COL_CHECKED, $checked['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricDataTableMap::COL_CHECKED, $checked, $comparison);
    }

    /**
     * Filter the query by a related \Metric object
     *
     * @param \Metric|ObjectCollection $metric The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildMetricDataQuery The current query, for fluid interface
     */
    public function filterByMetric($metric, $comparison = null)
    {
        if ($metric instanceof \Metric) {
            return $this
                ->addUsingAlias(MetricDataTableMap::COL_METRIC_ID, $metric->getId(), $comparison);
        } elseif ($metric instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MetricDataTableMap::COL_METRIC_ID, $metric->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMetric() only accepts arguments of type \Metric or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Metric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function joinMetric($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Metric');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Metric');
        }

        return $this;
    }

    /**
     * Use the Metric relation Metric object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MetricQuery A secondary query class using the current class as primary query
     */
    public function useMetricQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMetric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Metric', '\MetricQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMetricData $metricData Object to remove from the list of results
     *
     * @return $this|ChildMetricDataQuery The current query, for fluid interface
     */
    public function prune($metricData = null)
    {
        if ($metricData) {
            $this->addUsingAlias(MetricDataTableMap::COL_ID, $metricData->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the metric_data table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricDataTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MetricDataTableMap::clearInstancePool();
            MetricDataTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricDataTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MetricDataTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            MetricDataTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            MetricDataTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MetricDataQuery
