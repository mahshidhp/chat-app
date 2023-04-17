<?php

namespace Controller;

use DAO\GroupDAO;
use Except\UserIsNotMemberOfGroup;
use Model\Group;
use Propel\Runtime\Exception\PropelException;
use Except\UserIsNotGroupCreator;

class GroupController
{
    /**
     * @throws PropelException
     */
    public static function createGroup($groupName, $creatorId): void
    {
        GroupDAO::create($groupName, $creatorId);
    }

    /**
     * @throws PropelException
     * @throws UserIsNotGroupCreator
     */
    public static function deleteGroup($groupId, $creator_id): void
    {
        $group = self::getById($groupId);
        if ($group->getCreatorId() != $creator_id) {
            throw new UserIsNotGroupCreator();
        }
        GroupDAO::delete($group);
    }

    /**
     * @throws PropelException
     * @throws UserIsNotMemberOfGroup
     */
    public static function updateGroup($groupId, $groupName, $userId): void
    {
        $isMember = self::checkIsGroupMember($groupId, $userId);
        if (!$isMember) {
            throw new UserIsNotMemberOfGroup();
        }
        GroupDAO::update($groupId, $groupName);
    }

    public static function getById($groupId): ?Group
    {
        return GroupDAO::getById($groupId);
    }

    /**
     * @throws PropelException
     */
    public static function getGroupMembers($groupId): array
    {
        return GroupDAO::getMembers($groupId);
    }

    /**
     * @throws PropelException
     */
    public static function checkIsGroupMember($groupId, $userId): bool
    {
        $members = self::getGroupMembers($groupId);
        return in_array($userId, $members);
    }

    /**
     * @throws PropelException
     */
    public static function joinGroup($groupId, $userId): void
    {
        GroupDAO::join($groupId, $userId);
    }

    /**
     * @throws PropelException
     */
    public static function leaveGroup($groupId, $userId): void
    {
        GroupDAO::leave($groupId, $userId);
    }
}