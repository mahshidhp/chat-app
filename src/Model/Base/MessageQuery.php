<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Message as ChildMessage;
use Model\MessageQuery as ChildMessageQuery;
use Model\Map\MessageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the `message` table.
 *
 * @method     ChildMessageQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildMessageQuery orderBySenderId($order = Criteria::ASC) Order by the sender_id column
 * @method     ChildMessageQuery orderByReceiverId($order = Criteria::ASC) Order by the receiver_id column
 * @method     ChildMessageQuery orderByGroupId($order = Criteria::ASC) Order by the group_id column
 * @method     ChildMessageQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildMessageQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 *
 * @method     ChildMessageQuery groupById() Group by the id column
 * @method     ChildMessageQuery groupBySenderId() Group by the sender_id column
 * @method     ChildMessageQuery groupByReceiverId() Group by the receiver_id column
 * @method     ChildMessageQuery groupByGroupId() Group by the group_id column
 * @method     ChildMessageQuery groupByText() Group by the text column
 * @method     ChildMessageQuery groupByCreatedAt() Group by the created_at column
 *
 * @method     ChildMessageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildMessageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildMessageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildMessageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildMessageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildMessageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildMessageQuery leftJoinsenderId($relationAlias = null) Adds a LEFT JOIN clause to the query using the senderId relation
 * @method     ChildMessageQuery rightJoinsenderId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the senderId relation
 * @method     ChildMessageQuery innerJoinsenderId($relationAlias = null) Adds a INNER JOIN clause to the query using the senderId relation
 *
 * @method     ChildMessageQuery joinWithsenderId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the senderId relation
 *
 * @method     ChildMessageQuery leftJoinWithsenderId() Adds a LEFT JOIN clause and with to the query using the senderId relation
 * @method     ChildMessageQuery rightJoinWithsenderId() Adds a RIGHT JOIN clause and with to the query using the senderId relation
 * @method     ChildMessageQuery innerJoinWithsenderId() Adds a INNER JOIN clause and with to the query using the senderId relation
 *
 * @method     ChildMessageQuery leftJoinreceiverId($relationAlias = null) Adds a LEFT JOIN clause to the query using the receiverId relation
 * @method     ChildMessageQuery rightJoinreceiverId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the receiverId relation
 * @method     ChildMessageQuery innerJoinreceiverId($relationAlias = null) Adds a INNER JOIN clause to the query using the receiverId relation
 *
 * @method     ChildMessageQuery joinWithreceiverId($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the receiverId relation
 *
 * @method     ChildMessageQuery leftJoinWithreceiverId() Adds a LEFT JOIN clause and with to the query using the receiverId relation
 * @method     ChildMessageQuery rightJoinWithreceiverId() Adds a RIGHT JOIN clause and with to the query using the receiverId relation
 * @method     ChildMessageQuery innerJoinWithreceiverId() Adds a INNER JOIN clause and with to the query using the receiverId relation
 *
 * @method     ChildMessageQuery leftJoinGroup($relationAlias = null) Adds a LEFT JOIN clause to the query using the Group relation
 * @method     ChildMessageQuery rightJoinGroup($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Group relation
 * @method     ChildMessageQuery innerJoinGroup($relationAlias = null) Adds a INNER JOIN clause to the query using the Group relation
 *
 * @method     ChildMessageQuery joinWithGroup($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Group relation
 *
 * @method     ChildMessageQuery leftJoinWithGroup() Adds a LEFT JOIN clause and with to the query using the Group relation
 * @method     ChildMessageQuery rightJoinWithGroup() Adds a RIGHT JOIN clause and with to the query using the Group relation
 * @method     ChildMessageQuery innerJoinWithGroup() Adds a INNER JOIN clause and with to the query using the Group relation
 *
 * @method     \Model\UserQuery|\Model\UserQuery|\Model\GroupQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildMessage|null findOne(?ConnectionInterface $con = null) Return the first ChildMessage matching the query
 * @method     ChildMessage findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildMessage matching the query, or a new ChildMessage object populated from the query conditions when no match is found
 *
 * @method     ChildMessage|null findOneById(int $id) Return the first ChildMessage filtered by the id column
 * @method     ChildMessage|null findOneBySenderId(int $sender_id) Return the first ChildMessage filtered by the sender_id column
 * @method     ChildMessage|null findOneByReceiverId(int $receiver_id) Return the first ChildMessage filtered by the receiver_id column
 * @method     ChildMessage|null findOneByGroupId(int $group_id) Return the first ChildMessage filtered by the group_id column
 * @method     ChildMessage|null findOneByText(string $text) Return the first ChildMessage filtered by the text column
 * @method     ChildMessage|null findOneByCreatedAt(string $created_at) Return the first ChildMessage filtered by the created_at column
 *
 * @method     ChildMessage requirePk($key, ?ConnectionInterface $con = null) Return the ChildMessage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOne(?ConnectionInterface $con = null) Return the first ChildMessage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMessage requireOneById(int $id) Return the first ChildMessage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOneBySenderId(int $sender_id) Return the first ChildMessage filtered by the sender_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOneByReceiverId(int $receiver_id) Return the first ChildMessage filtered by the receiver_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOneByGroupId(int $group_id) Return the first ChildMessage filtered by the group_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOneByText(string $text) Return the first ChildMessage filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildMessage requireOneByCreatedAt(string $created_at) Return the first ChildMessage filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildMessage[]|Collection find(?ConnectionInterface $con = null) Return ChildMessage objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildMessage> find(?ConnectionInterface $con = null) Return ChildMessage objects based on current ModelCriteria
 *
 * @method     ChildMessage[]|Collection findById(int|array<int> $id) Return ChildMessage objects filtered by the id column
 * @psalm-method Collection&\Traversable<ChildMessage> findById(int|array<int> $id) Return ChildMessage objects filtered by the id column
 * @method     ChildMessage[]|Collection findBySenderId(int|array<int> $sender_id) Return ChildMessage objects filtered by the sender_id column
 * @psalm-method Collection&\Traversable<ChildMessage> findBySenderId(int|array<int> $sender_id) Return ChildMessage objects filtered by the sender_id column
 * @method     ChildMessage[]|Collection findByReceiverId(int|array<int> $receiver_id) Return ChildMessage objects filtered by the receiver_id column
 * @psalm-method Collection&\Traversable<ChildMessage> findByReceiverId(int|array<int> $receiver_id) Return ChildMessage objects filtered by the receiver_id column
 * @method     ChildMessage[]|Collection findByGroupId(int|array<int> $group_id) Return ChildMessage objects filtered by the group_id column
 * @psalm-method Collection&\Traversable<ChildMessage> findByGroupId(int|array<int> $group_id) Return ChildMessage objects filtered by the group_id column
 * @method     ChildMessage[]|Collection findByText(string|array<string> $text) Return ChildMessage objects filtered by the text column
 * @psalm-method Collection&\Traversable<ChildMessage> findByText(string|array<string> $text) Return ChildMessage objects filtered by the text column
 * @method     ChildMessage[]|Collection findByCreatedAt(string|array<string> $created_at) Return ChildMessage objects filtered by the created_at column
 * @psalm-method Collection&\Traversable<ChildMessage> findByCreatedAt(string|array<string> $created_at) Return ChildMessage objects filtered by the created_at column
 *
 * @method     ChildMessage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildMessage> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class MessageQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Model\Base\MessageQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'chat', $modelName = '\\Model\\Message', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildMessageQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildMessageQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildMessageQuery) {
            return $criteria;
        }
        $query = new ChildMessageQuery();
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
     * @return ChildMessage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(MessageTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = MessageTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildMessage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, sender_id, receiver_id, group_id, text, created_at FROM message WHERE id = :p0';
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
            /** @var ChildMessage $obj */
            $obj = new ChildMessage();
            $obj->hydrate($row);
            MessageTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildMessage|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(MessageTableMap::COL_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(MessageTableMap::COL_ID, $keys, Criteria::IN);

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
                $this->addUsingAlias(MessageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MessageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_ID, $id, $comparison);

        return $this;
    }

    /**
     * Filter the query on the sender_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySenderId(1234); // WHERE sender_id = 1234
     * $query->filterBySenderId(array(12, 34)); // WHERE sender_id IN (12, 34)
     * $query->filterBySenderId(array('min' => 12)); // WHERE sender_id > 12
     * </code>
     *
     * @see       filterBysenderId()
     *
     * @param mixed $senderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySenderId($senderId = null, ?string $comparison = null)
    {
        if (is_array($senderId)) {
            $useMinMax = false;
            if (isset($senderId['min'])) {
                $this->addUsingAlias(MessageTableMap::COL_SENDER_ID, $senderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($senderId['max'])) {
                $this->addUsingAlias(MessageTableMap::COL_SENDER_ID, $senderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_SENDER_ID, $senderId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the receiver_id column
     *
     * Example usage:
     * <code>
     * $query->filterByReceiverId(1234); // WHERE receiver_id = 1234
     * $query->filterByReceiverId(array(12, 34)); // WHERE receiver_id IN (12, 34)
     * $query->filterByReceiverId(array('min' => 12)); // WHERE receiver_id > 12
     * </code>
     *
     * @see       filterByreceiverId()
     *
     * @param mixed $receiverId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByReceiverId($receiverId = null, ?string $comparison = null)
    {
        if (is_array($receiverId)) {
            $useMinMax = false;
            if (isset($receiverId['min'])) {
                $this->addUsingAlias(MessageTableMap::COL_RECEIVER_ID, $receiverId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($receiverId['max'])) {
                $this->addUsingAlias(MessageTableMap::COL_RECEIVER_ID, $receiverId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_RECEIVER_ID, $receiverId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the group_id column
     *
     * Example usage:
     * <code>
     * $query->filterByGroupId(1234); // WHERE group_id = 1234
     * $query->filterByGroupId(array(12, 34)); // WHERE group_id IN (12, 34)
     * $query->filterByGroupId(array('min' => 12)); // WHERE group_id > 12
     * </code>
     *
     * @see       filterByGroup()
     *
     * @param mixed $groupId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGroupId($groupId = null, ?string $comparison = null)
    {
        if (is_array($groupId)) {
            $useMinMax = false;
            if (isset($groupId['min'])) {
                $this->addUsingAlias(MessageTableMap::COL_GROUP_ID, $groupId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($groupId['max'])) {
                $this->addUsingAlias(MessageTableMap::COL_GROUP_ID, $groupId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_GROUP_ID, $groupId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%', Criteria::LIKE); // WHERE text LIKE '%fooValue%'
     * $query->filterByText(['foo', 'bar']); // WHERE text IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $text The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByText($text = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_TEXT, $text, $comparison);

        return $this;
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, ?string $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(MessageTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(MessageTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(MessageTableMap::COL_CREATED_AT, $createdAt, $comparison);

        return $this;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBysenderId($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            return $this
                ->addUsingAlias(MessageTableMap::COL_SENDER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(MessageTableMap::COL_SENDER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterBysenderId() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the senderId relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinsenderId(?string $relationAlias = null, ?string $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('senderId');

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
            $this->addJoinObject($join, 'senderId');
        }

        return $this;
    }

    /**
     * Use the senderId relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function usesenderIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinsenderId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'senderId', '\Model\UserQuery');
    }

    /**
     * Use the senderId relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withsenderIdQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::INNER_JOIN
    ) {
        $relatedQuery = $this->usesenderIdQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the senderId relation to the User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function usesenderIdExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('senderId', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the senderId relation to the User table for a NOT EXISTS query.
     *
     * @see usesenderIdExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function usesenderIdNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('senderId', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the senderId relation to the User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInsenderIdQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('senderId', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the senderId relation to the User table for a NOT IN query.
     *
     * @see usesenderIdInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInsenderIdQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('senderId', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\User object
     *
     * @param \Model\User|ObjectCollection $user The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByreceiverId($user, ?string $comparison = null)
    {
        if ($user instanceof \Model\User) {
            return $this
                ->addUsingAlias(MessageTableMap::COL_RECEIVER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(MessageTableMap::COL_RECEIVER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);

            return $this;
        } else {
            throw new PropelException('filterByreceiverId() only accepts arguments of type \Model\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the receiverId relation
     *
     * @param string|null $relationAlias Optional alias for the relation
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this The current query, for fluid interface
     */
    public function joinreceiverId(?string $relationAlias = null, ?string $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('receiverId');

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
            $this->addJoinObject($join, 'receiverId');
        }

        return $this;
    }

    /**
     * Use the receiverId relation User object
     *
     * @see useQuery()
     *
     * @param string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Model\UserQuery A secondary query class using the current class as primary query
     */
    public function usereceiverIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinreceiverId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'receiverId', '\Model\UserQuery');
    }

    /**
     * Use the receiverId relation User object
     *
     * @param callable(\Model\UserQuery):\Model\UserQuery $callable A function working on the related query
     *
     * @param string|null $relationAlias optional alias for the relation
     *
     * @param string|null $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this
     */
    public function withreceiverIdQuery(
        callable $callable,
        string $relationAlias = null,
        ?string $joinType = Criteria::LEFT_JOIN
    ) {
        $relatedQuery = $this->usereceiverIdQuery(
            $relationAlias,
            $joinType
        );
        $callable($relatedQuery);
        $relatedQuery->endUse();

        return $this;
    }

    /**
     * Use the receiverId relation to the User table for an EXISTS query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     * @param string $typeOfExists Either ExistsQueryCriterion::TYPE_EXISTS or ExistsQueryCriterion::TYPE_NOT_EXISTS
     *
     * @return \Model\UserQuery The inner query object of the EXISTS statement
     */
    public function usereceiverIdExistsQuery($modelAlias = null, $queryClass = null, $typeOfExists = 'EXISTS')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('receiverId', $modelAlias, $queryClass, $typeOfExists);
        return $q;
    }

    /**
     * Use the receiverId relation to the User table for a NOT EXISTS query.
     *
     * @see usereceiverIdExistsQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the exists query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT EXISTS statement
     */
    public function usereceiverIdNotExistsQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useExistsQuery('receiverId', $modelAlias, $queryClass, 'NOT EXISTS');
        return $q;
    }

    /**
     * Use the receiverId relation to the User table for an IN query.
     *
     * @see \Propel\Runtime\ActiveQuery\ModelCriteria::useInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the IN query, like ExtendedBookQuery::class
     * @param string $typeOfIn Criteria::IN or Criteria::NOT_IN
     *
     * @return \Model\UserQuery The inner query object of the IN statement
     */
    public function useInreceiverIdQuery($modelAlias = null, $queryClass = null, $typeOfIn = 'IN')
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('receiverId', $modelAlias, $queryClass, $typeOfIn);
        return $q;
    }

    /**
     * Use the receiverId relation to the User table for a NOT IN query.
     *
     * @see usereceiverIdInQuery()
     *
     * @param string|null $modelAlias sets an alias for the nested query
     * @param string|null $queryClass Allows to use a custom query class for the NOT IN query, like ExtendedBookQuery::class
     *
     * @return \Model\UserQuery The inner query object of the NOT IN statement
     */
    public function useNotInreceiverIdQuery($modelAlias = null, $queryClass = null)
    {
        /** @var $q \Model\UserQuery */
        $q = $this->useInQuery('receiverId', $modelAlias, $queryClass, 'NOT IN');
        return $q;
    }

    /**
     * Filter the query by a related \Model\Group object
     *
     * @param \Model\Group|ObjectCollection $group The related object(s) to use as filter
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByGroup($group, ?string $comparison = null)
    {
        if ($group instanceof \Model\Group) {
            return $this
                ->addUsingAlias(MessageTableMap::COL_GROUP_ID, $group->getId(), $comparison);
        } elseif ($group instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            $this
                ->addUsingAlias(MessageTableMap::COL_GROUP_ID, $group->toKeyValue('PrimaryKey', 'Id'), $comparison);

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
     * Exclude object from result
     *
     * @param ChildMessage $message Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($message = null)
    {
        if ($message) {
            $this->addUsingAlias(MessageTableMap::COL_ID, $message->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the message table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MessageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            MessageTableMap::clearInstancePool();
            MessageTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(MessageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(MessageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            MessageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            MessageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
