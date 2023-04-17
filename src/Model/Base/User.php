<?php

namespace Model\Base;

use \Exception;
use \PDO;
use Model\Group as ChildGroup;
use Model\GroupQuery as ChildGroupQuery;
use Model\Membership as ChildMembership;
use Model\MembershipQuery as ChildMembershipQuery;
use Model\Message as ChildMessage;
use Model\MessageQuery as ChildMessageQuery;
use Model\User as ChildUser;
use Model\UserQuery as ChildUserQuery;
use Model\Map\GroupTableMap;
use Model\Map\MembershipTableMap;
use Model\Map\MessageTableMap;
use Model\Map\UserTableMap;
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
 * Base class that represents a row from the 'user' table.
 *
 *
 *
 * @package    propel.generator.Model.Base
 */
abstract class User implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\Model\\Map\\UserTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the username field.
     *
     * @var        string
     */
    protected $username;

    /**
     * The value for the password field.
     *
     * @var        string
     */
    protected $password;

    /**
     * @var        ObjectCollection|ChildGroup[] Collection to store aggregation of ChildGroup objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildGroup> Collection to store aggregation of ChildGroup objects.
     */
    protected $collGroups;
    protected $collGroupsPartial;

    /**
     * @var        ObjectCollection|ChildMessage[] Collection to store aggregation of ChildMessage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildMessage> Collection to store aggregation of ChildMessage objects.
     */
    protected $collMessagesRelatedBySenderId;
    protected $collMessagesRelatedBySenderIdPartial;

    /**
     * @var        ObjectCollection|ChildMessage[] Collection to store aggregation of ChildMessage objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildMessage> Collection to store aggregation of ChildMessage objects.
     */
    protected $collMessagesRelatedByReceiverId;
    protected $collMessagesRelatedByReceiverIdPartial;

    /**
     * @var        ObjectCollection|ChildMembership[] Collection to store aggregation of ChildMembership objects.
     * @phpstan-var ObjectCollection&\Traversable<ChildMembership> Collection to store aggregation of ChildMembership objects.
     */
    protected $collMemberships;
    protected $collMembershipsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildGroup[]
     * @phpstan-var ObjectCollection&\Traversable<ChildGroup>
     */
    protected $groupsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMessage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildMessage>
     */
    protected $messagesRelatedBySenderIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMessage[]
     * @phpstan-var ObjectCollection&\Traversable<ChildMessage>
     */
    protected $messagesRelatedByReceiverIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildMembership[]
     * @phpstan-var ObjectCollection&\Traversable<ChildMembership>
     */
    protected $membershipsScheduledForDeletion = null;

    /**
     * Initializes internal state of Model\Base\User object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>User</code> instance.  If
     * <code>obj</code> is an instance of <code>User</code>, delegates to
     * <code>equals(User)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
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
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
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
     * Get the [username] column value.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the [password] column value.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[UserTableMap::COL_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [username] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setUsername($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->username !== $v) {
            $this->username = $v;
            $this->modifiedColumns[UserTableMap::COL_USERNAME] = true;
        }

        return $this;
    }

    /**
     * Set the value of [password] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setPassword($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->password !== $v) {
            $this->password = $v;
            $this->modifiedColumns[UserTableMap::COL_PASSWORD] = true;
        }

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : UserTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : UserTableMap::translateFieldName('Username', TableMap::TYPE_PHPNAME, $indexType)];
            $this->username = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : UserTableMap::translateFieldName('Password', TableMap::TYPE_PHPNAME, $indexType)];
            $this->password = (null !== $col) ? (string) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = UserTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Model\\User'), 0, $e);
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
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UserTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildUserQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collGroups = null;

            $this->collMessagesRelatedBySenderId = null;

            $this->collMessagesRelatedByReceiverId = null;

            $this->collMemberships = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see User::setDeleted()
     * @see User::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildUserQuery::create()
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
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(UserTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
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
                UserTableMap::addInstanceToPool($this);
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
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
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

            if ($this->groupsScheduledForDeletion !== null) {
                if (!$this->groupsScheduledForDeletion->isEmpty()) {
                    foreach ($this->groupsScheduledForDeletion as $group) {
                        // need to save related object because we set the relation to null
                        $group->save($con);
                    }
                    $this->groupsScheduledForDeletion = null;
                }
            }

            if ($this->collGroups !== null) {
                foreach ($this->collGroups as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->messagesRelatedBySenderIdScheduledForDeletion !== null) {
                if (!$this->messagesRelatedBySenderIdScheduledForDeletion->isEmpty()) {
                    \Model\MessageQuery::create()
                        ->filterByPrimaryKeys($this->messagesRelatedBySenderIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->messagesRelatedBySenderIdScheduledForDeletion = null;
                }
            }

            if ($this->collMessagesRelatedBySenderId !== null) {
                foreach ($this->collMessagesRelatedBySenderId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->messagesRelatedByReceiverIdScheduledForDeletion !== null) {
                if (!$this->messagesRelatedByReceiverIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->messagesRelatedByReceiverIdScheduledForDeletion as $messageRelatedByReceiverId) {
                        // need to save related object because we set the relation to null
                        $messageRelatedByReceiverId->save($con);
                    }
                    $this->messagesRelatedByReceiverIdScheduledForDeletion = null;
                }
            }

            if ($this->collMessagesRelatedByReceiverId !== null) {
                foreach ($this->collMessagesRelatedByReceiverId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->membershipsScheduledForDeletion !== null) {
                if (!$this->membershipsScheduledForDeletion->isEmpty()) {
                    \Model\MembershipQuery::create()
                        ->filterByPrimaryKeys($this->membershipsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->membershipsScheduledForDeletion = null;
                }
            }

            if ($this->collMemberships !== null) {
                foreach ($this->collMemberships as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[UserTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . UserTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $modifiedColumns[':p' . $index++]  = 'username';
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $modifiedColumns[':p' . $index++]  = 'password';
        }

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES (%s)',
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
                    case 'username':
                        $stmt->bindValue($identifier, $this->username, PDO::PARAM_STR);

                        break;
                    case 'password':
                        $stmt->bindValue($identifier, $this->password, PDO::PARAM_STR);

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
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();

            case 1:
                return $this->getUsername();

            case 2:
                return $this->getPassword();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param bool $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = [], bool $includeForeignObjects = false): array
    {
        if (isset($alreadyDumpedObjects['User'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['User'][$this->hashCode()] = true;
        $keys = UserTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getUsername(),
            $keys[2] => $this->getPassword(),
        ];
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collGroups) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'groups';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'groups';
                        break;
                    default:
                        $key = 'Groups';
                }

                $result[$key] = $this->collGroups->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMessagesRelatedBySenderId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'messages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'messages';
                        break;
                    default:
                        $key = 'Messages';
                }

                $result[$key] = $this->collMessagesRelatedBySenderId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMessagesRelatedByReceiverId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'messages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'messages';
                        break;
                    default:
                        $key = 'Messages';
                }

                $result[$key] = $this->collMessagesRelatedByReceiverId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collMemberships) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'memberships';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'memberships';
                        break;
                    default:
                        $key = 'Memberships';
                }

                $result[$key] = $this->collMemberships->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = UserTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setUsername($value);
                break;
            case 2:
                $this->setPassword($value);
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
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = UserTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setUsername($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setPassword($arr[$keys[2]]);
        }

        return $this;
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
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
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
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(UserTableMap::DATABASE_NAME);

        if ($this->isColumnModified(UserTableMap::COL_ID)) {
            $criteria->add(UserTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(UserTableMap::COL_USERNAME)) {
            $criteria->add(UserTableMap::COL_USERNAME, $this->username);
        }
        if ($this->isColumnModified(UserTableMap::COL_PASSWORD)) {
            $criteria->add(UserTableMap::COL_PASSWORD, $this->password);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildUserQuery::create();
        $criteria->add(UserTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
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
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \Model\User (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setUsername($this->getUsername());
        $copyObj->setPassword($this->getPassword());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getGroups() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addGroup($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMessagesRelatedBySenderId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMessageRelatedBySenderId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMessagesRelatedByReceiverId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMessageRelatedByReceiverId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getMemberships() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMembership($relObj->copy($deepCopy));
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
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Model\User Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
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
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName): void
    {
        if ('Group' === $relationName) {
            $this->initGroups();
            return;
        }
        if ('MessageRelatedBySenderId' === $relationName) {
            $this->initMessagesRelatedBySenderId();
            return;
        }
        if ('MessageRelatedByReceiverId' === $relationName) {
            $this->initMessagesRelatedByReceiverId();
            return;
        }
        if ('Membership' === $relationName) {
            $this->initMemberships();
            return;
        }
    }

    /**
     * Clears out the collGroups collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addGroups()
     */
    public function clearGroups()
    {
        $this->collGroups = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collGroups collection loaded partially.
     *
     * @return void
     */
    public function resetPartialGroups($v = true): void
    {
        $this->collGroupsPartial = $v;
    }

    /**
     * Initializes the collGroups collection.
     *
     * By default this just sets the collGroups collection to an empty array (like clearcollGroups());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initGroups(bool $overrideExisting = true): void
    {
        if (null !== $this->collGroups && !$overrideExisting) {
            return;
        }

        $collectionClassName = GroupTableMap::getTableMap()->getCollectionClassName();

        $this->collGroups = new $collectionClassName;
        $this->collGroups->setModel('\Model\Group');
    }

    /**
     * Gets an array of ChildGroup objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildGroup[] List of ChildGroup objects
     * @phpstan-return ObjectCollection&\Traversable<ChildGroup> List of ChildGroup objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getGroups(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collGroups) {
                    $this->initGroups();
                } else {
                    $collectionClassName = GroupTableMap::getTableMap()->getCollectionClassName();

                    $collGroups = new $collectionClassName;
                    $collGroups->setModel('\Model\Group');

                    return $collGroups;
                }
            } else {
                $collGroups = ChildGroupQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collGroupsPartial && count($collGroups)) {
                        $this->initGroups(false);

                        foreach ($collGroups as $obj) {
                            if (false == $this->collGroups->contains($obj)) {
                                $this->collGroups->append($obj);
                            }
                        }

                        $this->collGroupsPartial = true;
                    }

                    return $collGroups;
                }

                if ($partial && $this->collGroups) {
                    foreach ($this->collGroups as $obj) {
                        if ($obj->isNew()) {
                            $collGroups[] = $obj;
                        }
                    }
                }

                $this->collGroups = $collGroups;
                $this->collGroupsPartial = false;
            }
        }

        return $this->collGroups;
    }

    /**
     * Sets a collection of ChildGroup objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $groups A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setGroups(Collection $groups, ?ConnectionInterface $con = null)
    {
        /** @var ChildGroup[] $groupsToDelete */
        $groupsToDelete = $this->getGroups(new Criteria(), $con)->diff($groups);


        $this->groupsScheduledForDeletion = $groupsToDelete;

        foreach ($groupsToDelete as $groupRemoved) {
            $groupRemoved->setUser(null);
        }

        $this->collGroups = null;
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        $this->collGroups = $groups;
        $this->collGroupsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Group objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Group objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countGroups(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collGroupsPartial && !$this->isNew();
        if (null === $this->collGroups || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collGroups) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getGroups());
            }

            $query = ChildGroupQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collGroups);
    }

    /**
     * Method called to associate a ChildGroup object to this object
     * through the ChildGroup foreign key attribute.
     *
     * @param ChildGroup $l ChildGroup
     * @return $this The current object (for fluent API support)
     */
    public function addGroup(ChildGroup $l)
    {
        if ($this->collGroups === null) {
            $this->initGroups();
            $this->collGroupsPartial = true;
        }

        if (!$this->collGroups->contains($l)) {
            $this->doAddGroup($l);

            if ($this->groupsScheduledForDeletion and $this->groupsScheduledForDeletion->contains($l)) {
                $this->groupsScheduledForDeletion->remove($this->groupsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildGroup $group The ChildGroup object to add.
     */
    protected function doAddGroup(ChildGroup $group): void
    {
        $this->collGroups[]= $group;
        $group->setUser($this);
    }

    /**
     * @param ChildGroup $group The ChildGroup object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeGroup(ChildGroup $group)
    {
        if ($this->getGroups()->contains($group)) {
            $pos = $this->collGroups->search($group);
            $this->collGroups->remove($pos);
            if (null === $this->groupsScheduledForDeletion) {
                $this->groupsScheduledForDeletion = clone $this->collGroups;
                $this->groupsScheduledForDeletion->clear();
            }
            $this->groupsScheduledForDeletion[]= $group;
            $group->setUser(null);
        }

        return $this;
    }

    /**
     * Clears out the collMessagesRelatedBySenderId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addMessagesRelatedBySenderId()
     */
    public function clearMessagesRelatedBySenderId()
    {
        $this->collMessagesRelatedBySenderId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collMessagesRelatedBySenderId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialMessagesRelatedBySenderId($v = true): void
    {
        $this->collMessagesRelatedBySenderIdPartial = $v;
    }

    /**
     * Initializes the collMessagesRelatedBySenderId collection.
     *
     * By default this just sets the collMessagesRelatedBySenderId collection to an empty array (like clearcollMessagesRelatedBySenderId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMessagesRelatedBySenderId(bool $overrideExisting = true): void
    {
        if (null !== $this->collMessagesRelatedBySenderId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MessageTableMap::getTableMap()->getCollectionClassName();

        $this->collMessagesRelatedBySenderId = new $collectionClassName;
        $this->collMessagesRelatedBySenderId->setModel('\Model\Message');
    }

    /**
     * Gets an array of ChildMessage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMessage[] List of ChildMessage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMessage> List of ChildMessage objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getMessagesRelatedBySenderId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collMessagesRelatedBySenderIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedBySenderId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMessagesRelatedBySenderId) {
                    $this->initMessagesRelatedBySenderId();
                } else {
                    $collectionClassName = MessageTableMap::getTableMap()->getCollectionClassName();

                    $collMessagesRelatedBySenderId = new $collectionClassName;
                    $collMessagesRelatedBySenderId->setModel('\Model\Message');

                    return $collMessagesRelatedBySenderId;
                }
            } else {
                $collMessagesRelatedBySenderId = ChildMessageQuery::create(null, $criteria)
                    ->filterBysenderId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMessagesRelatedBySenderIdPartial && count($collMessagesRelatedBySenderId)) {
                        $this->initMessagesRelatedBySenderId(false);

                        foreach ($collMessagesRelatedBySenderId as $obj) {
                            if (false == $this->collMessagesRelatedBySenderId->contains($obj)) {
                                $this->collMessagesRelatedBySenderId->append($obj);
                            }
                        }

                        $this->collMessagesRelatedBySenderIdPartial = true;
                    }

                    return $collMessagesRelatedBySenderId;
                }

                if ($partial && $this->collMessagesRelatedBySenderId) {
                    foreach ($this->collMessagesRelatedBySenderId as $obj) {
                        if ($obj->isNew()) {
                            $collMessagesRelatedBySenderId[] = $obj;
                        }
                    }
                }

                $this->collMessagesRelatedBySenderId = $collMessagesRelatedBySenderId;
                $this->collMessagesRelatedBySenderIdPartial = false;
            }
        }

        return $this->collMessagesRelatedBySenderId;
    }

    /**
     * Sets a collection of ChildMessage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $messagesRelatedBySenderId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setMessagesRelatedBySenderId(Collection $messagesRelatedBySenderId, ?ConnectionInterface $con = null)
    {
        /** @var ChildMessage[] $messagesRelatedBySenderIdToDelete */
        $messagesRelatedBySenderIdToDelete = $this->getMessagesRelatedBySenderId(new Criteria(), $con)->diff($messagesRelatedBySenderId);


        $this->messagesRelatedBySenderIdScheduledForDeletion = $messagesRelatedBySenderIdToDelete;

        foreach ($messagesRelatedBySenderIdToDelete as $messageRelatedBySenderIdRemoved) {
            $messageRelatedBySenderIdRemoved->setsenderId(null);
        }

        $this->collMessagesRelatedBySenderId = null;
        foreach ($messagesRelatedBySenderId as $messageRelatedBySenderId) {
            $this->addMessageRelatedBySenderId($messageRelatedBySenderId);
        }

        $this->collMessagesRelatedBySenderId = $messagesRelatedBySenderId;
        $this->collMessagesRelatedBySenderIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Message objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Message objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countMessagesRelatedBySenderId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collMessagesRelatedBySenderIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedBySenderId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedBySenderId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMessagesRelatedBySenderId());
            }

            $query = ChildMessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBysenderId($this)
                ->count($con);
        }

        return count($this->collMessagesRelatedBySenderId);
    }

    /**
     * Method called to associate a ChildMessage object to this object
     * through the ChildMessage foreign key attribute.
     *
     * @param ChildMessage $l ChildMessage
     * @return $this The current object (for fluent API support)
     */
    public function addMessageRelatedBySenderId(ChildMessage $l)
    {
        if ($this->collMessagesRelatedBySenderId === null) {
            $this->initMessagesRelatedBySenderId();
            $this->collMessagesRelatedBySenderIdPartial = true;
        }

        if (!$this->collMessagesRelatedBySenderId->contains($l)) {
            $this->doAddMessageRelatedBySenderId($l);

            if ($this->messagesRelatedBySenderIdScheduledForDeletion and $this->messagesRelatedBySenderIdScheduledForDeletion->contains($l)) {
                $this->messagesRelatedBySenderIdScheduledForDeletion->remove($this->messagesRelatedBySenderIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMessage $messageRelatedBySenderId The ChildMessage object to add.
     */
    protected function doAddMessageRelatedBySenderId(ChildMessage $messageRelatedBySenderId): void
    {
        $this->collMessagesRelatedBySenderId[]= $messageRelatedBySenderId;
        $messageRelatedBySenderId->setsenderId($this);
    }

    /**
     * @param ChildMessage $messageRelatedBySenderId The ChildMessage object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeMessageRelatedBySenderId(ChildMessage $messageRelatedBySenderId)
    {
        if ($this->getMessagesRelatedBySenderId()->contains($messageRelatedBySenderId)) {
            $pos = $this->collMessagesRelatedBySenderId->search($messageRelatedBySenderId);
            $this->collMessagesRelatedBySenderId->remove($pos);
            if (null === $this->messagesRelatedBySenderIdScheduledForDeletion) {
                $this->messagesRelatedBySenderIdScheduledForDeletion = clone $this->collMessagesRelatedBySenderId;
                $this->messagesRelatedBySenderIdScheduledForDeletion->clear();
            }
            $this->messagesRelatedBySenderIdScheduledForDeletion[]= clone $messageRelatedBySenderId;
            $messageRelatedBySenderId->setsenderId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MessagesRelatedBySenderId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMessage[] List of ChildMessage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMessage}> List of ChildMessage objects
     */
    public function getMessagesRelatedBySenderIdJoinGroup(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMessageQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getMessagesRelatedBySenderId($query, $con);
    }

    /**
     * Clears out the collMessagesRelatedByReceiverId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addMessagesRelatedByReceiverId()
     */
    public function clearMessagesRelatedByReceiverId()
    {
        $this->collMessagesRelatedByReceiverId = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collMessagesRelatedByReceiverId collection loaded partially.
     *
     * @return void
     */
    public function resetPartialMessagesRelatedByReceiverId($v = true): void
    {
        $this->collMessagesRelatedByReceiverIdPartial = $v;
    }

    /**
     * Initializes the collMessagesRelatedByReceiverId collection.
     *
     * By default this just sets the collMessagesRelatedByReceiverId collection to an empty array (like clearcollMessagesRelatedByReceiverId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMessagesRelatedByReceiverId(bool $overrideExisting = true): void
    {
        if (null !== $this->collMessagesRelatedByReceiverId && !$overrideExisting) {
            return;
        }

        $collectionClassName = MessageTableMap::getTableMap()->getCollectionClassName();

        $this->collMessagesRelatedByReceiverId = new $collectionClassName;
        $this->collMessagesRelatedByReceiverId->setModel('\Model\Message');
    }

    /**
     * Gets an array of ChildMessage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMessage[] List of ChildMessage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMessage> List of ChildMessage objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getMessagesRelatedByReceiverId(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collMessagesRelatedByReceiverIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByReceiverId || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMessagesRelatedByReceiverId) {
                    $this->initMessagesRelatedByReceiverId();
                } else {
                    $collectionClassName = MessageTableMap::getTableMap()->getCollectionClassName();

                    $collMessagesRelatedByReceiverId = new $collectionClassName;
                    $collMessagesRelatedByReceiverId->setModel('\Model\Message');

                    return $collMessagesRelatedByReceiverId;
                }
            } else {
                $collMessagesRelatedByReceiverId = ChildMessageQuery::create(null, $criteria)
                    ->filterByreceiverId($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMessagesRelatedByReceiverIdPartial && count($collMessagesRelatedByReceiverId)) {
                        $this->initMessagesRelatedByReceiverId(false);

                        foreach ($collMessagesRelatedByReceiverId as $obj) {
                            if (false == $this->collMessagesRelatedByReceiverId->contains($obj)) {
                                $this->collMessagesRelatedByReceiverId->append($obj);
                            }
                        }

                        $this->collMessagesRelatedByReceiverIdPartial = true;
                    }

                    return $collMessagesRelatedByReceiverId;
                }

                if ($partial && $this->collMessagesRelatedByReceiverId) {
                    foreach ($this->collMessagesRelatedByReceiverId as $obj) {
                        if ($obj->isNew()) {
                            $collMessagesRelatedByReceiverId[] = $obj;
                        }
                    }
                }

                $this->collMessagesRelatedByReceiverId = $collMessagesRelatedByReceiverId;
                $this->collMessagesRelatedByReceiverIdPartial = false;
            }
        }

        return $this->collMessagesRelatedByReceiverId;
    }

    /**
     * Sets a collection of ChildMessage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $messagesRelatedByReceiverId A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setMessagesRelatedByReceiverId(Collection $messagesRelatedByReceiverId, ?ConnectionInterface $con = null)
    {
        /** @var ChildMessage[] $messagesRelatedByReceiverIdToDelete */
        $messagesRelatedByReceiverIdToDelete = $this->getMessagesRelatedByReceiverId(new Criteria(), $con)->diff($messagesRelatedByReceiverId);


        $this->messagesRelatedByReceiverIdScheduledForDeletion = $messagesRelatedByReceiverIdToDelete;

        foreach ($messagesRelatedByReceiverIdToDelete as $messageRelatedByReceiverIdRemoved) {
            $messageRelatedByReceiverIdRemoved->setreceiverId(null);
        }

        $this->collMessagesRelatedByReceiverId = null;
        foreach ($messagesRelatedByReceiverId as $messageRelatedByReceiverId) {
            $this->addMessageRelatedByReceiverId($messageRelatedByReceiverId);
        }

        $this->collMessagesRelatedByReceiverId = $messagesRelatedByReceiverId;
        $this->collMessagesRelatedByReceiverIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Message objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Message objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countMessagesRelatedByReceiverId(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collMessagesRelatedByReceiverIdPartial && !$this->isNew();
        if (null === $this->collMessagesRelatedByReceiverId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMessagesRelatedByReceiverId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMessagesRelatedByReceiverId());
            }

            $query = ChildMessageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByreceiverId($this)
                ->count($con);
        }

        return count($this->collMessagesRelatedByReceiverId);
    }

    /**
     * Method called to associate a ChildMessage object to this object
     * through the ChildMessage foreign key attribute.
     *
     * @param ChildMessage $l ChildMessage
     * @return $this The current object (for fluent API support)
     */
    public function addMessageRelatedByReceiverId(ChildMessage $l)
    {
        if ($this->collMessagesRelatedByReceiverId === null) {
            $this->initMessagesRelatedByReceiverId();
            $this->collMessagesRelatedByReceiverIdPartial = true;
        }

        if (!$this->collMessagesRelatedByReceiverId->contains($l)) {
            $this->doAddMessageRelatedByReceiverId($l);

            if ($this->messagesRelatedByReceiverIdScheduledForDeletion and $this->messagesRelatedByReceiverIdScheduledForDeletion->contains($l)) {
                $this->messagesRelatedByReceiverIdScheduledForDeletion->remove($this->messagesRelatedByReceiverIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMessage $messageRelatedByReceiverId The ChildMessage object to add.
     */
    protected function doAddMessageRelatedByReceiverId(ChildMessage $messageRelatedByReceiverId): void
    {
        $this->collMessagesRelatedByReceiverId[]= $messageRelatedByReceiverId;
        $messageRelatedByReceiverId->setreceiverId($this);
    }

    /**
     * @param ChildMessage $messageRelatedByReceiverId The ChildMessage object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeMessageRelatedByReceiverId(ChildMessage $messageRelatedByReceiverId)
    {
        if ($this->getMessagesRelatedByReceiverId()->contains($messageRelatedByReceiverId)) {
            $pos = $this->collMessagesRelatedByReceiverId->search($messageRelatedByReceiverId);
            $this->collMessagesRelatedByReceiverId->remove($pos);
            if (null === $this->messagesRelatedByReceiverIdScheduledForDeletion) {
                $this->messagesRelatedByReceiverIdScheduledForDeletion = clone $this->collMessagesRelatedByReceiverId;
                $this->messagesRelatedByReceiverIdScheduledForDeletion->clear();
            }
            $this->messagesRelatedByReceiverIdScheduledForDeletion[]= $messageRelatedByReceiverId;
            $messageRelatedByReceiverId->setreceiverId(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related MessagesRelatedByReceiverId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMessage[] List of ChildMessage objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMessage}> List of ChildMessage objects
     */
    public function getMessagesRelatedByReceiverIdJoinGroup(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMessageQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getMessagesRelatedByReceiverId($query, $con);
    }

    /**
     * Clears out the collMemberships collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return $this
     * @see addMemberships()
     */
    public function clearMemberships()
    {
        $this->collMemberships = null; // important to set this to NULL since that means it is uninitialized

        return $this;
    }

    /**
     * Reset is the collMemberships collection loaded partially.
     *
     * @return void
     */
    public function resetPartialMemberships($v = true): void
    {
        $this->collMembershipsPartial = $v;
    }

    /**
     * Initializes the collMemberships collection.
     *
     * By default this just sets the collMemberships collection to an empty array (like clearcollMemberships());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param bool $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMemberships(bool $overrideExisting = true): void
    {
        if (null !== $this->collMemberships && !$overrideExisting) {
            return;
        }

        $collectionClassName = MembershipTableMap::getTableMap()->getCollectionClassName();

        $this->collMemberships = new $collectionClassName;
        $this->collMemberships->setModel('\Model\Membership');
    }

    /**
     * Gets an array of ChildMembership objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildUser is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildMembership[] List of ChildMembership objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMembership> List of ChildMembership objects
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getMemberships(?Criteria $criteria = null, ?ConnectionInterface $con = null)
    {
        $partial = $this->collMembershipsPartial && !$this->isNew();
        if (null === $this->collMemberships || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collMemberships) {
                    $this->initMemberships();
                } else {
                    $collectionClassName = MembershipTableMap::getTableMap()->getCollectionClassName();

                    $collMemberships = new $collectionClassName;
                    $collMemberships->setModel('\Model\Membership');

                    return $collMemberships;
                }
            } else {
                $collMemberships = ChildMembershipQuery::create(null, $criteria)
                    ->filterByUser($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collMembershipsPartial && count($collMemberships)) {
                        $this->initMemberships(false);

                        foreach ($collMemberships as $obj) {
                            if (false == $this->collMemberships->contains($obj)) {
                                $this->collMemberships->append($obj);
                            }
                        }

                        $this->collMembershipsPartial = true;
                    }

                    return $collMemberships;
                }

                if ($partial && $this->collMemberships) {
                    foreach ($this->collMemberships as $obj) {
                        if ($obj->isNew()) {
                            $collMemberships[] = $obj;
                        }
                    }
                }

                $this->collMemberships = $collMemberships;
                $this->collMembershipsPartial = false;
            }
        }

        return $this->collMemberships;
    }

    /**
     * Sets a collection of ChildMembership objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param Collection $memberships A Propel collection.
     * @param ConnectionInterface $con Optional connection object
     * @return $this The current object (for fluent API support)
     */
    public function setMemberships(Collection $memberships, ?ConnectionInterface $con = null)
    {
        /** @var ChildMembership[] $membershipsToDelete */
        $membershipsToDelete = $this->getMemberships(new Criteria(), $con)->diff($memberships);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->membershipsScheduledForDeletion = clone $membershipsToDelete;

        foreach ($membershipsToDelete as $membershipRemoved) {
            $membershipRemoved->setUser(null);
        }

        $this->collMemberships = null;
        foreach ($memberships as $membership) {
            $this->addMembership($membership);
        }

        $this->collMemberships = $memberships;
        $this->collMembershipsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Membership objects.
     *
     * @param Criteria $criteria
     * @param bool $distinct
     * @param ConnectionInterface $con
     * @return int Count of related Membership objects.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function countMemberships(?Criteria $criteria = null, bool $distinct = false, ?ConnectionInterface $con = null): int
    {
        $partial = $this->collMembershipsPartial && !$this->isNew();
        if (null === $this->collMemberships || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMemberships) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMemberships());
            }

            $query = ChildMembershipQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByUser($this)
                ->count($con);
        }

        return count($this->collMemberships);
    }

    /**
     * Method called to associate a ChildMembership object to this object
     * through the ChildMembership foreign key attribute.
     *
     * @param ChildMembership $l ChildMembership
     * @return $this The current object (for fluent API support)
     */
    public function addMembership(ChildMembership $l)
    {
        if ($this->collMemberships === null) {
            $this->initMemberships();
            $this->collMembershipsPartial = true;
        }

        if (!$this->collMemberships->contains($l)) {
            $this->doAddMembership($l);

            if ($this->membershipsScheduledForDeletion and $this->membershipsScheduledForDeletion->contains($l)) {
                $this->membershipsScheduledForDeletion->remove($this->membershipsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildMembership $membership The ChildMembership object to add.
     */
    protected function doAddMembership(ChildMembership $membership): void
    {
        $this->collMemberships[]= $membership;
        $membership->setUser($this);
    }

    /**
     * @param ChildMembership $membership The ChildMembership object to remove.
     * @return $this The current object (for fluent API support)
     */
    public function removeMembership(ChildMembership $membership)
    {
        if ($this->getMemberships()->contains($membership)) {
            $pos = $this->collMemberships->search($membership);
            $this->collMemberships->remove($pos);
            if (null === $this->membershipsScheduledForDeletion) {
                $this->membershipsScheduledForDeletion = clone $this->collMemberships;
                $this->membershipsScheduledForDeletion->clear();
            }
            $this->membershipsScheduledForDeletion[]= clone $membership;
            $membership->setUser(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this User is new, it will return
     * an empty collection; or if this User has previously
     * been saved, it will retrieve related Memberships from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in User.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param ConnectionInterface $con optional connection object
     * @param string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildMembership[] List of ChildMembership objects
     * @phpstan-return ObjectCollection&\Traversable<ChildMembership}> List of ChildMembership objects
     */
    public function getMembershipsJoinGroup(?Criteria $criteria = null, ?ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildMembershipQuery::create(null, $criteria);
        $query->joinWith('Group', $joinBehavior);

        return $this->getMemberships($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->id = null;
        $this->username = null;
        $this->password = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
            if ($this->collGroups) {
                foreach ($this->collGroups as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMessagesRelatedBySenderId) {
                foreach ($this->collMessagesRelatedBySenderId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMessagesRelatedByReceiverId) {
                foreach ($this->collMessagesRelatedByReceiverId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collMemberships) {
                foreach ($this->collMemberships as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collGroups = null;
        $this->collMessagesRelatedBySenderId = null;
        $this->collMessagesRelatedByReceiverId = null;
        $this->collMemberships = null;
        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(UserTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
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
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
