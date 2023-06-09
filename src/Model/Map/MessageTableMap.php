<?php

namespace Model\Map;

use Model\Message;
use Model\MessageQuery;
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
 * This class defines the structure of the 'message' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class MessageTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'Model.Map.MessageTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'chat';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'message';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Message';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\Model\\Message';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'Model.Message';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    public const COL_ID = 'message.id';

    /**
     * the column name for the sender_id field
     */
    public const COL_SENDER_ID = 'message.sender_id';

    /**
     * the column name for the receiver_id field
     */
    public const COL_RECEIVER_ID = 'message.receiver_id';

    /**
     * the column name for the group_id field
     */
    public const COL_GROUP_ID = 'message.group_id';

    /**
     * the column name for the text field
     */
    public const COL_TEXT = 'message.text';

    /**
     * the column name for the created_at field
     */
    public const COL_CREATED_AT = 'message.created_at';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['Id', 'SenderId', 'ReceiverId', 'GroupId', 'Text', 'CreatedAt', ],
        self::TYPE_CAMELNAME     => ['id', 'senderId', 'receiverId', 'groupId', 'text', 'createdAt', ],
        self::TYPE_COLNAME       => [MessageTableMap::COL_ID, MessageTableMap::COL_SENDER_ID, MessageTableMap::COL_RECEIVER_ID, MessageTableMap::COL_GROUP_ID, MessageTableMap::COL_TEXT, MessageTableMap::COL_CREATED_AT, ],
        self::TYPE_FIELDNAME     => ['id', 'sender_id', 'receiver_id', 'group_id', 'text', 'created_at', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['Id' => 0, 'SenderId' => 1, 'ReceiverId' => 2, 'GroupId' => 3, 'Text' => 4, 'CreatedAt' => 5, ],
        self::TYPE_CAMELNAME     => ['id' => 0, 'senderId' => 1, 'receiverId' => 2, 'groupId' => 3, 'text' => 4, 'createdAt' => 5, ],
        self::TYPE_COLNAME       => [MessageTableMap::COL_ID => 0, MessageTableMap::COL_SENDER_ID => 1, MessageTableMap::COL_RECEIVER_ID => 2, MessageTableMap::COL_GROUP_ID => 3, MessageTableMap::COL_TEXT => 4, MessageTableMap::COL_CREATED_AT => 5, ],
        self::TYPE_FIELDNAME     => ['id' => 0, 'sender_id' => 1, 'receiver_id' => 2, 'group_id' => 3, 'text' => 4, 'created_at' => 5, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'Id' => 'ID',
        'Message.Id' => 'ID',
        'id' => 'ID',
        'message.id' => 'ID',
        'MessageTableMap::COL_ID' => 'ID',
        'COL_ID' => 'ID',
        'SenderId' => 'SENDER_ID',
        'Message.SenderId' => 'SENDER_ID',
        'senderId' => 'SENDER_ID',
        'message.senderId' => 'SENDER_ID',
        'MessageTableMap::COL_SENDER_ID' => 'SENDER_ID',
        'COL_SENDER_ID' => 'SENDER_ID',
        'sender_id' => 'SENDER_ID',
        'message.sender_id' => 'SENDER_ID',
        'ReceiverId' => 'RECEIVER_ID',
        'Message.ReceiverId' => 'RECEIVER_ID',
        'receiverId' => 'RECEIVER_ID',
        'message.receiverId' => 'RECEIVER_ID',
        'MessageTableMap::COL_RECEIVER_ID' => 'RECEIVER_ID',
        'COL_RECEIVER_ID' => 'RECEIVER_ID',
        'receiver_id' => 'RECEIVER_ID',
        'message.receiver_id' => 'RECEIVER_ID',
        'GroupId' => 'GROUP_ID',
        'Message.GroupId' => 'GROUP_ID',
        'groupId' => 'GROUP_ID',
        'message.groupId' => 'GROUP_ID',
        'MessageTableMap::COL_GROUP_ID' => 'GROUP_ID',
        'COL_GROUP_ID' => 'GROUP_ID',
        'group_id' => 'GROUP_ID',
        'message.group_id' => 'GROUP_ID',
        'Text' => 'TEXT',
        'Message.Text' => 'TEXT',
        'text' => 'TEXT',
        'message.text' => 'TEXT',
        'MessageTableMap::COL_TEXT' => 'TEXT',
        'COL_TEXT' => 'TEXT',
        'CreatedAt' => 'CREATED_AT',
        'Message.CreatedAt' => 'CREATED_AT',
        'createdAt' => 'CREATED_AT',
        'message.createdAt' => 'CREATED_AT',
        'MessageTableMap::COL_CREATED_AT' => 'CREATED_AT',
        'COL_CREATED_AT' => 'CREATED_AT',
        'created_at' => 'CREATED_AT',
        'message.created_at' => 'CREATED_AT',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('message');
        $this->setPhpName('Message');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Model\\Message');
        $this->setPackage('Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('sender_id', 'SenderId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('receiver_id', 'ReceiverId', 'INTEGER', 'user', 'id', false, null, null);
        $this->addForeignKey('group_id', 'GroupId', 'INTEGER', 'group', 'id', false, null, null);
        $this->addColumn('text', 'Text', 'VARCHAR', true, 255, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', true, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
        $this->addRelation('senderId', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':sender_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('receiverId', '\\Model\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':receiver_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Group', '\\Model\\Group', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':group_id',
    1 => ':id',
  ),
), null, null, null, false);
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
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
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? MessageTableMap::CLASS_DEFAULT : MessageTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Message object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = MessageTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = MessageTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + MessageTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = MessageTableMap::OM_CLASS;
            /** @var Message $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            MessageTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = MessageTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = MessageTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Message $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                MessageTableMap::addInstanceToPool($obj, $key);
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
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(MessageTableMap::COL_ID);
            $criteria->addSelectColumn(MessageTableMap::COL_SENDER_ID);
            $criteria->addSelectColumn(MessageTableMap::COL_RECEIVER_ID);
            $criteria->addSelectColumn(MessageTableMap::COL_GROUP_ID);
            $criteria->addSelectColumn(MessageTableMap::COL_TEXT);
            $criteria->addSelectColumn(MessageTableMap::COL_CREATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.sender_id');
            $criteria->addSelectColumn($alias . '.receiver_id');
            $criteria->addSelectColumn($alias . '.group_id');
            $criteria->addSelectColumn($alias . '.text');
            $criteria->addSelectColumn($alias . '.created_at');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(MessageTableMap::COL_ID);
            $criteria->removeSelectColumn(MessageTableMap::COL_SENDER_ID);
            $criteria->removeSelectColumn(MessageTableMap::COL_RECEIVER_ID);
            $criteria->removeSelectColumn(MessageTableMap::COL_GROUP_ID);
            $criteria->removeSelectColumn(MessageTableMap::COL_TEXT);
            $criteria->removeSelectColumn(MessageTableMap::COL_CREATED_AT);
        } else {
            $criteria->removeSelectColumn($alias . '.id');
            $criteria->removeSelectColumn($alias . '.sender_id');
            $criteria->removeSelectColumn($alias . '.receiver_id');
            $criteria->removeSelectColumn($alias . '.group_id');
            $criteria->removeSelectColumn($alias . '.text');
            $criteria->removeSelectColumn($alias . '.created_at');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(MessageTableMap::DATABASE_NAME)->getTable(MessageTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Message or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Message object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MessageTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Model\Message) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(MessageTableMap::DATABASE_NAME);
            $criteria->add(MessageTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = MessageQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            MessageTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                MessageTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the message table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return MessageQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Message or Criteria object.
     *
     * @param mixed $criteria Criteria or Message object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(MessageTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Message object
        }

        if ($criteria->containsKey(MessageTableMap::COL_ID) && $criteria->keyContainsValue(MessageTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.MessageTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = MessageQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
