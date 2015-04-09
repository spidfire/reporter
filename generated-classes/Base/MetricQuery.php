<?php

namespace Base;

use \Metric as ChildMetric;
use \MetricQuery as ChildMetricQuery;
use \Exception;
use \PDO;
use Map\MetricTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'metric' table.
 *
 * 
 *
 * @method     ChildMetricQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMetricQuery orderByLabel($order = Criteria::ASC) Order by the label column
 * @method     ChildMetricQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method     ChildMetricQuery orderByIgnoreuntil($order = Criteria::ASC) Order by the ignoreuntil column
 * @method     ChildMetricQuery orderByMissingalert($order = Criteria::ASC) Order by the missingalert column
 *
 * @method     ChildMetricQuery groupById() Group by the id column
 * @method     ChildMetricQuery groupByLabel() Group by the label column
 * @method     ChildMetricQuery groupByDescription() Group by the description column
 * @method     ChildMetricQuery groupByIgnoreuntil() Group by the ignoreuntil column
 * @method     ChildMetricQuery groupByMissingalert() Group by the missingalert column
 *
 * @method     ChildMetricQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMetricQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMetricQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMetricQuery leftJoinMetricData($relationAlias = null) Adds a LEFT JOIN clause to the query using the MetricData relation
 * @method     ChildMetricQuery rightJoinMetricData($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MetricData relation
 * @method     ChildMetricQuery innerJoinMetricData($relationAlias = null) Adds a INNER JOIN clause to the query using the MetricData relation
 *
 * @method     ChildMetricQuery leftJoinCategoryToMetric($relationAlias = null) Adds a LEFT JOIN clause to the query using the CategoryToMetric relation
 * @method     ChildMetricQuery rightJoinCategoryToMetric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CategoryToMetric relation
 * @method     ChildMetricQuery innerJoinCategoryToMetric($relationAlias = null) Adds a INNER JOIN clause to the query using the CategoryToMetric relation
 *
 * @method     \MetricDataQuery|\CategoryToMetricQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMetric findOne(ConnectionInterface $con = null) Return the first ChildMetric matching the query
 * @method     ChildMetric findOneOrCreate(ConnectionInterface $con = null) Return the first ChildMetric matching the query, or a new ChildMetric object populated from the query conditions when no match is found
 *
 * @method     ChildMetric findOneById(int $id) Return the first ChildMetric filtered by the id column
 * @method     ChildMetric findOneByLabel(string $label) Return the first ChildMetric filtered by the label column
 * @method     ChildMetric findOneByDescription(string $description) Return the first ChildMetric filtered by the description column
 * @method     ChildMetric findOneByIgnoreuntil(int $ignoreuntil) Return the first ChildMetric filtered by the ignoreuntil column
 * @method     ChildMetric findOneByMissingalert(int $missingalert) Return the first ChildMetric filtered by the missingalert column *

 * @method     ChildMetric requirePk($key, ConnectionInterface $con = null) Return the ChildMetric by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetric requireOne(ConnectionInterface $con = null) Return the first ChildMetric matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMetric requireOneById(int $id) Return the first ChildMetric filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetric requireOneByLabel(string $label) Return the first ChildMetric filtered by the label column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetric requireOneByDescription(string $description) Return the first ChildMetric filtered by the description column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetric requireOneByIgnoreuntil(int $ignoreuntil) Return the first ChildMetric filtered by the ignoreuntil column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMetric requireOneByMissingalert(int $missingalert) Return the first ChildMetric filtered by the missingalert column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMetric[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildMetric objects based on current ModelCriteria
 * @method     ChildMetric[]|ObjectCollection findById(int $id) Return ChildMetric objects filtered by the id column
 * @method     ChildMetric[]|ObjectCollection findByLabel(string $label) Return ChildMetric objects filtered by the label column
 * @method     ChildMetric[]|ObjectCollection findByDescription(string $description) Return ChildMetric objects filtered by the description column
 * @method     ChildMetric[]|ObjectCollection findByIgnoreuntil(int $ignoreuntil) Return ChildMetric objects filtered by the ignoreuntil column
 * @method     ChildMetric[]|ObjectCollection findByMissingalert(int $missingalert) Return ChildMetric objects filtered by the missingalert column
 * @method     ChildMetric[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class MetricQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\MetricQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'reporter', $modelName = '\\Metric', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMetricQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMetricQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildMetricQuery) {
            return $criteria;
        }
        $query = new ChildMetricQuery();
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
     * @return ChildMetric|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MetricTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MetricTableMap::DATABASE_NAME);
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
     * @return ChildMetric A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, label, description, ignoreuntil, missingalert FROM metric WHERE id = :p0';
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
            /** @var ChildMetric $obj */
            $obj = new ChildMetric();
            $obj->hydrate($row);
            MetricTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildMetric|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MetricTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MetricTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MetricTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MetricTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the label column
     *
     * Example usage:
     * <code>
     * $query->filterByLabel('fooValue');   // WHERE label = 'fooValue'
     * $query->filterByLabel('%fooValue%'); // WHERE label LIKE '%fooValue%'
     * </code>
     *
     * @param     string $label The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByLabel($label = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($label)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $label)) {
                $label = str_replace('*', '%', $label);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MetricTableMap::COL_LABEL, $label, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MetricTableMap::COL_DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the ignoreuntil column
     *
     * Example usage:
     * <code>
     * $query->filterByIgnoreuntil(1234); // WHERE ignoreuntil = 1234
     * $query->filterByIgnoreuntil(array(12, 34)); // WHERE ignoreuntil IN (12, 34)
     * $query->filterByIgnoreuntil(array('min' => 12)); // WHERE ignoreuntil > 12
     * </code>
     *
     * @param     mixed $ignoreuntil The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByIgnoreuntil($ignoreuntil = null, $comparison = null)
    {
        if (is_array($ignoreuntil)) {
            $useMinMax = false;
            if (isset($ignoreuntil['min'])) {
                $this->addUsingAlias(MetricTableMap::COL_IGNOREUNTIL, $ignoreuntil['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ignoreuntil['max'])) {
                $this->addUsingAlias(MetricTableMap::COL_IGNOREUNTIL, $ignoreuntil['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricTableMap::COL_IGNOREUNTIL, $ignoreuntil, $comparison);
    }

    /**
     * Filter the query on the missingalert column
     *
     * Example usage:
     * <code>
     * $query->filterByMissingalert(1234); // WHERE missingalert = 1234
     * $query->filterByMissingalert(array(12, 34)); // WHERE missingalert IN (12, 34)
     * $query->filterByMissingalert(array('min' => 12)); // WHERE missingalert > 12
     * </code>
     *
     * @param     mixed $missingalert The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function filterByMissingalert($missingalert = null, $comparison = null)
    {
        if (is_array($missingalert)) {
            $useMinMax = false;
            if (isset($missingalert['min'])) {
                $this->addUsingAlias(MetricTableMap::COL_MISSINGALERT, $missingalert['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($missingalert['max'])) {
                $this->addUsingAlias(MetricTableMap::COL_MISSINGALERT, $missingalert['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MetricTableMap::COL_MISSINGALERT, $missingalert, $comparison);
    }

    /**
     * Filter the query by a related \MetricData object
     *
     * @param \MetricData|ObjectCollection $metricData the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMetricQuery The current query, for fluid interface
     */
    public function filterByMetricData($metricData, $comparison = null)
    {
        if ($metricData instanceof \MetricData) {
            return $this
                ->addUsingAlias(MetricTableMap::COL_ID, $metricData->getMetricId(), $comparison);
        } elseif ($metricData instanceof ObjectCollection) {
            return $this
                ->useMetricDataQuery()
                ->filterByPrimaryKeys($metricData->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMetricData() only accepts arguments of type \MetricData or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MetricData relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function joinMetricData($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MetricData');

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
            $this->addJoinObject($join, 'MetricData');
        }

        return $this;
    }

    /**
     * Use the MetricData relation MetricData object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \MetricDataQuery A secondary query class using the current class as primary query
     */
    public function useMetricDataQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMetricData($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MetricData', '\MetricDataQuery');
    }

    /**
     * Filter the query by a related \CategoryToMetric object
     *
     * @param \CategoryToMetric|ObjectCollection $categoryToMetric the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildMetricQuery The current query, for fluid interface
     */
    public function filterByCategoryToMetric($categoryToMetric, $comparison = null)
    {
        if ($categoryToMetric instanceof \CategoryToMetric) {
            return $this
                ->addUsingAlias(MetricTableMap::COL_ID, $categoryToMetric->getMetricId(), $comparison);
        } elseif ($categoryToMetric instanceof ObjectCollection) {
            return $this
                ->useCategoryToMetricQuery()
                ->filterByPrimaryKeys($categoryToMetric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCategoryToMetric() only accepts arguments of type \CategoryToMetric or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CategoryToMetric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function joinCategoryToMetric($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CategoryToMetric');

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
            $this->addJoinObject($join, 'CategoryToMetric');
        }

        return $this;
    }

    /**
     * Use the CategoryToMetric relation CategoryToMetric object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \CategoryToMetricQuery A secondary query class using the current class as primary query
     */
    public function useCategoryToMetricQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCategoryToMetric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CategoryToMetric', '\CategoryToMetricQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildMetric $metric Object to remove from the list of results
     *
     * @return $this|ChildMetricQuery The current query, for fluid interface
     */
    public function prune($metric = null)
    {
        if ($metric) {
            $this->addUsingAlias(MetricTableMap::COL_ID, $metric->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the metric table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MetricTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MetricTableMap::clearInstancePool();
            MetricTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MetricTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MetricTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            
            MetricTableMap::removeInstanceFromPool($criteria);
        
            $affectedRows += ModelCriteria::delete($con);
            MetricTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // MetricQuery
