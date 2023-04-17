<?php

namespace DAO;

use Model\Group;
use Model\GroupQuery;
use Model\Membership;
use Model\MembershipQuery;
use Propel\Runtime\Exception\PropelException;

class GroupDAO
{

    /**
     * @throws PropelException
     */
    public static function create($groupName, $creatorId): void
    {
        $group = new Group();
        $group
            ->setName($groupName)
            ->setCreatorId($creatorId)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function delete($group): void
    {
        $group->delete();
    }

    /**
     * @throws PropelException
     */
    public static function update($groupId, $groupName): void
    {
        $group = self::getById($groupId);
        $group
            ->setName($groupName)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function getMembers($groupId): array
    {
        $group = self::getById($groupId);
        return $group
            ->getMemberships()
            ->getColumnValues("user_id");
    }

    /**
     * @throws PropelException
     */
    public static function join($groupId, $userId): void
    {
        $membership = new Membership();
        $membership
            ->setGroupId($groupId)
            ->setUserId($userId)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function leave($groupId, $userId): void
    {
        $membership = MembershipQuery::create()
            ->filterByGroupId($groupId)
            ->filterByUserId($userId)
            ->findOne();

        if ($membership) {
            $membership->delete();
        }
    }

    public static function getById($groupId): ?Group
    {
        return GroupQuery::create()->findPk($groupId);
    }

}
