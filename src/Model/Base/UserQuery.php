<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\UserTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `user` table.
 *
 * @method     ChildUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildUserQuery orderByUsername($order = Criteria::ASC) Order by the username column
 * @method     ChildUserQuery orderByPassword($order = Criteria::ASC) Order by the password column
 *
 * @method     ChildUserQuery groupById() Group by the id column
 * @method     ChildUserQuery groupByUsername() Group by the username column
 * @method     ChildUserQuery groupByPassword() Group by the password column
 *
 * @method     ChildUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUserQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUserQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUserQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUserQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildUserQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildUserQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     ChildUserQuery joinWithGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Group relation
 *
 * @method     ChildUserQuery leftJoinWithGroup() Adds a LEFT JOIN clause and with to the query using the Group relation
 * @method     ChildUserQuery rightJoinWithGroup() Adds a RIGHT JOIN clause and with to the query using the Group relation
 * @method     ChildUserQuery innerJoinWithGroup() Adds a INNER JOIN clause and with to the query using the Group relation
 *
 * @method     ChildUserQuery leftJoinMessageRelatedBySenderId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MessageRelatedBySenderId relation
 * @method     ChildUserQuery rightJoinMessageRelatedBySenderId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MessageRelatedBySenderId relation
 * @method     ChildUserQuery innerJoinMessageRelatedBySenderId($relationAlias = null) Adds a INNER JOIN clause to the query using the MessageRelatedBySenderId relation
 *
 * @method     ChildUserQuery joinWithMessageRelatedBySenderId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MessageRelatedBySenderId relation
 *
 * @method     ChildUserQuery leftJoinWithMessageRelatedBySenderId() Adds a LEFT JOIN clause and with to the query using the MessageRelatedBySenderId relation
 * @method     ChildUserQuery rightJoinWithMessageRelatedBySenderId() Adds a RIGHT JOIN clause and with to the query using the MessageRelatedBySenderId relation
 * @method     ChildUserQuery innerJoinWithMessageRelatedBySenderId() Adds a INNER JOIN clause and with to the query using the MessageRelatedBySenderId relation
 *
 * @method     ChildUserQuery leftJoinMessageRelatedByReceiverId($relationAlias = null) Adds a LEFT JOIN clause to the query using the MessageRelatedByReceiverId relation
 * @method     ChildUserQuery rightJoinMessageRelatedByReceiverId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the MessageRelatedByReceiverId relation
 * @method     ChildUserQuery innerJoinMessageRelatedByReceiverId($relationAlias = null) Adds a INNER JOIN clause to the query using the MessageRelatedByReceiverId relation
 *
 * @method     ChildUserQuery joinWithMessageRelatedByReceiverId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the MessageRelatedByReceiverId relation
 *
 * @method     ChildUserQuery leftJoinWithMessageRelatedByReceiverId() Adds a LEFT JOIN clause and with to the query using the MessageRelatedByReceiverId relation
 * @method     ChildUserQuery rightJoinWithMessageRelatedByReceiverId() Adds a RIGHT JOIN clause and with to the query using the MessageRelatedByReceiverId relation
 * @method     ChildUserQuery innerJoinWithMessageRelatedByReceiverId() Adds a INNER JOIN clause and with to the query using the MessageRelatedByReceiverId relation
 *
 * @method     ChildUserQuery leftJoinMembership($relationAlias = null) Adds a LEFT JOIN clause to the query using the Membership relation
 * @method     ChildUserQuery rightJoinMembership($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Membership relation
 * @method     ChildUserQuery innerJoinMembership($relationAlias = null) Adds a INNER JOIN clause to the query using the Membership relation
 *
 * @method     ChildUserQuery joinWithMembership($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Membership relation
 *
 * @method     ChildUserQuery leftJoinWithMembership() Adds a LEFT JOIN clause and with to the query using the Membership relation
 * @method     ChildUserQuery rightJoinWithMembership() Adds a RIGHT JOIN clause and with to the query using the Membership relation
 * @method     ChildUserQuery innerJoinWithMembership() Adds a INNER JOIN clause and with to the query using the Membership relation
 *
 * @method     \Model\GroupQuery|\Model\MessageQuery|\Model\MessageQuery|\Model\MembershipQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUser|null findOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query
 * @method     ChildUser findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildUser matching the query, or a new ChildUser object populated from the query conditions when no match is found
 *
 * @method     ChildUser|null findOneById(int $id) Return the first ChildUser filtered by the id column
 * @method     ChildUser|null findOneByUsername(string $username) Return the first ChildUser filtered by the username column
 * @method     ChildUser|null findOneByPassword(string $password) Return the first ChildUser filtered by the password column
 *
 * @method     ChildUser requirePk($key, ?ConnectionInterface $con = null) Return the ChildUser by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOne(?ConnectionInterface $con = null) Return the first ChildUser matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser requireOneById(int $id) Return the first ChildUser filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByUsername(string $username) Return the first ChildUser filtered by the username column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUser requireOneByPassword(string $password) Return the first ChildUser filtered by the password column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUser[]|Collection find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildUser> find(?ConnectionInterface $con = null) Return ChildUser objects based on current ModelCriteria
 *
 * @method     ChildUser[]|Collection findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildUser> findById(int|array<int> $id) Return ChildUser objects filtered by the id column
 * @method     ChildUser[]|Collection findByUsername(string|array<string> $username) Return ChildUser objects filtered by the username column
 * @psalm-method Collection&\Traversable<ChildUser> findByUsername(string|array<string> $username) Return ChildUser objects filtered by the username column
 * @method     ChildUser[]|Collection findByPassword(string|array<string> $password) Return ChildUser objects filtered by the password column
 * @psalm-method Collection&\Traversable<ChildUser> findByPassword(string|array<string> $password) Return ChildUser objects filtered by the password column
 *
 * @method     ChildUser[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildUser> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class UserQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\UserQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'chat', $modelName = '\\Model\\User', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUserQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildUserQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildUserQuery) {
            return $criteria;
        }
        $query = new ChildUserQuery();
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
     * @return ChildUser|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = UserTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUser A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, username, password FROM user WHERE id = :p0';
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
            /** @var ChildUser $obj */
            $obj = new ChildUser();
            $obj->hydrate($row);
            UserTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildUser|array|mixed the result, formatted by the current formatter
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
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
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
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(UserTableMap::COL_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(UserTableMap::COL_ID, $keys, Criteria::IN);

        return $this;
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
     * @param mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterById($id = null, ?string $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(UserTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the username column
     *
     * Example usage:
     * <code>
     * $query->filterByUsername('fooValue');   // WHERE username = 'fooValue'
     * $query->filterByUsername('%fooValue%', Criteria::LIKE); // WHERE username LIKE '%fooValue%'
     * $query->filterByUsername(['foo', 'bar']); // WHERE username IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $username The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByUsername($username = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($username)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_USERNAME, $username, $comparison);

        return $this;
    }

    /**
     * Filter the query on the password column
     *
     * Example usage:
     * <code>
     * $query->filterByPassword('fooValue');   // WHERE password = 'fooValue'
     * $query->filterByPassword('%fooValue%', Criteria::LIKE); // WHERE password LIKE '%fooValue%'
     * $query->filterByPassword(['foo', 'bar']); // WHERE password IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $password The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPassword($password = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($password)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(UserTableMap::COL_PASSWORD, $password, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\Group object
     *
     * @param \Model\Group|ObjectCollection $group the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGroup($group, ?string $comparison = null)
    {
        if ($group instanceof \Model\Group) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $group->getCreatorId(), $comparison);

            return $this;
        } elseif ($group instanceof ObjectCollection) {
            $this
                ->useGroupQuery()
                ->filterByPrimaryKeys($group->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByGroup() only accepts arguments of type \Model\Group or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Group relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinGroup(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Group');

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
            $this->addJoinObject($join, 'Group');
        }

        return $this;
    }

    /**
     * Use the Group relation Group object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\GroupQuery A secondary query class using the current class as primary query
     */
    public function useGroupQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinGroup($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Group', '\Model\GroupQuery');
    }

    /**
     * Use the Group relation Group object
     *
     * @param callable(\Model\GroupQuery):\Model\GroupQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withGroupQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useGroupQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Group table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\GroupQuery The inner query object of the EXISTS statement
     */
    public function useGroupExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\GroupQuery */
        $q = $this->useExistsQuery('Group', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Group table for a NOT EXISTS query.
     *
     * @see useGroupExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\GroupQuery The inner query object of the NOT EXISTS statement
     */
    public function useGroupNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\GroupQuery */
        $q = $this->useExistsQuery('Group', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Group table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\GroupQuery The inner query object of the IN statement
     */
    public function useInGroupQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\GroupQuery */
        $q = $this->useInQuery('Group', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Group table for a NOT IN query.
     *
     * @see useGroupInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\GroupQuery The inner query object of the NOT IN statement
     */
    public function useNotInGroupQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\GroupQuery */
        $q = $this->useInQuery('Group', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Message object
     *
     * @param \Model\Message|ObjectCollection $message the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMessageRelatedBySenderId($message, ?string $comparison = null)
    {
        if ($message instanceof \Model\Message) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $message->getSenderId(), $comparison);

            return $this;
        } elseif ($message instanceof ObjectCollection) {
            $this
                ->useMessageRelatedBySenderIdQuery()
                ->filterByPrimaryKeys($message->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByMessageRelatedBySenderId() only accepts arguments of type \Model\Message or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MessageRelatedBySenderId relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinMessageRelatedBySenderId(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MessageRelatedBySenderId');

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
            $this->addJoinObject($join, 'MessageRelatedBySenderId');
        }

        return $this;
    }

    /**
     * Use the MessageRelatedBySenderId relation Message object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\MessageQuery A secondary query class using the current class as primary query
     */
    public function useMessageRelatedBySenderIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMessageRelatedBySenderId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MessageRelatedBySenderId', '\Model\MessageQuery');
    }

    /**
     * Use the MessageRelatedBySenderId relation Message object
     *
     * @param callable(\Model\MessageQuery):\Model\MessageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withMessageRelatedBySenderIdQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useMessageRelatedBySenderIdQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the MessageRelatedBySenderId relation to the Message table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\MessageQuery The inner query object of the EXISTS statement
     */
    public function useMessageRelatedBySenderIdExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useExistsQuery('MessageRelatedBySenderId', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the MessageRelatedBySenderId relation to the Message table for a NOT EXISTS query.
     *
     * @see useMessageRelatedBySenderIdExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\MessageQuery The inner query object of the NOT EXISTS statement
     */
    public function useMessageRelatedBySenderIdNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useExistsQuery('MessageRelatedBySenderId', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the MessageRelatedBySenderId relation to the Message table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\MessageQuery The inner query object of the IN statement
     */
    public function useInMessageRelatedBySenderIdQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useInQuery('MessageRelatedBySenderId', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the MessageRelatedBySenderId relation to the Message table for a NOT IN query.
     *
     * @see useMessageRelatedBySenderIdInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\MessageQuery The inner query object of the NOT IN statement
     */
    public function useNotInMessageRelatedBySenderIdQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useInQuery('MessageRelatedBySenderId', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Message object
     *
     * @param \Model\Message|ObjectCollection $message the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMessageRelatedByReceiverId($message, ?string $comparison = null)
    {
        if ($message instanceof \Model\Message) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $message->getReceiverId(), $comparison);

            return $this;
        } elseif ($message instanceof ObjectCollection) {
            $this
                ->useMessageRelatedByReceiverIdQuery()
                ->filterByPrimaryKeys($message->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByMessageRelatedByReceiverId() only accepts arguments of type \Model\Message or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the MessageRelatedByReceiverId relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinMessageRelatedByReceiverId(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('MessageRelatedByReceiverId');

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
            $this->addJoinObject($join, 'MessageRelatedByReceiverId');
        }

        return $this;
    }

    /**
     * Use the MessageRelatedByReceiverId relation Message object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\MessageQuery A secondary query class using the current class as primary query
     */
    public function useMessageRelatedByReceiverIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMessageRelatedByReceiverId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'MessageRelatedByReceiverId', '\Model\MessageQuery');
    }

    /**
     * Use the MessageRelatedByReceiverId relation Message object
     *
     * @param callable(\Model\MessageQuery):\Model\MessageQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withMessageRelatedByReceiverIdQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->useMessageRelatedByReceiverIdQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the MessageRelatedByReceiverId relation to the Message table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\MessageQuery The inner query object of the EXISTS statement
     */
    public function useMessageRelatedByReceiverIdExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useExistsQuery('MessageRelatedByReceiverId', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the MessageRelatedByReceiverId relation to the Message table for a NOT EXISTS query.
     *
     * @see useMessageRelatedByReceiverIdExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\MessageQuery The inner query object of the NOT EXISTS statement
     */
    public function useMessageRelatedByReceiverIdNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useExistsQuery('MessageRelatedByReceiverId', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the MessageRelatedByReceiverId relation to the Message table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\MessageQuery The inner query object of the IN statement
     */
    public function useInMessageRelatedByReceiverIdQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useInQuery('MessageRelatedByReceiverId', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the MessageRelatedByReceiverId relation to the Message table for a NOT IN query.
     *
     * @see useMessageRelatedByReceiverIdInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\MessageQuery The inner query object of the NOT IN statement
     */
    public function useNotInMessageRelatedByReceiverIdQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MessageQuery */
        $q = $this->useInQuery('MessageRelatedByReceiverId', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Membership object
     *
     * @param \Model\Membership|ObjectCollection $membership the related object to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByMembership($membership, ?string $comparison = null)
    {
        if ($membership instanceof \Model\Membership) {
            $this
                ->addUsingAlias(UserTableMap::COL_ID, $membership->getUserId(), $comparison);

            return $this;
        } elseif ($membership instanceof ObjectCollection) {
            $this
                ->useMembershipQuery()
                ->filterByPrimaryKeys($membership->getPrimaryKeys())
                ->endUse();

            return $this;
        } else {
            throw new PropelException('filterByMembership() only accepts arguments of type \Model\Membership or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Membership relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinMembership(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Membership');

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
            $this->addJoinObject($join, 'Membership');
        }

        return $this;
    }

    /**
     * Use the Membership relation Membership object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\MembershipQuery A secondary query class using the current class as primary query
     */
    public function useMembershipQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMembership($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Membership', '\Model\MembershipQuery');
    }

    /**
     * Use the Membership relation Membership object
     *
     * @param callable(\Model\MembershipQuery):\Model\MembershipQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withMembershipQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->useMembershipQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the relation to Membership table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\MembershipQuery The inner query object of the EXISTS statement
     */
    public function useMembershipExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\MembershipQuery */
        $q = $this->useExistsQuery('Membership', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the relation to Membership table for a NOT EXISTS query.
     *
     * @see useMembershipExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\MembershipQuery The inner query object of the NOT EXISTS statement
     */
    public function useMembershipNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MembershipQuery */
        $q = $this->useExistsQuery('Membership', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the relation to Membership table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\MembershipQuery The inner query object of the IN statement
     */
    public function useInMembershipQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\MembershipQuery */
        $q = $this->useInQuery('Membership', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the relation to Membership table for a NOT IN query.
     *
     * @see useMembershipInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\MembershipQuery The inner query object of the NOT IN statement
     */
    public function useNotInMembershipQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\MembershipQuery */
        $q = $this->useInQuery('Membership', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Exclude object from result
     *
     * @param ChildUser $user Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($user = null)
    {
        if ($user) {
            $this->addUsingAlias(UserTableMap::COL_ID, $user->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the user table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UserTableMap::clearInstancePool();
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UserTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UserTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UserTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
