<?php

namespace Controller;

use DAO\MessageDAO;
use Except\UserNotFound;
use Except\UserIsNotMemberOfGroup;
use Propel\Runtime\Exception\PropelException;

class MessageController
{
    /**
     * @throws PropelException
     */
    public static function sendPrivateMessage($senderId, $receiverId, $text, $createAt)
    {
        MessageDAO::createPrivateMessage($senderId, $receiverId, $text, $createAt);
    }

    /**
     * @throws PropelException
     * @throws UserIsNotMemberOfGroup
     */
    public static function sendGroupMessage($senderId, $groupId, $text, $createAt)
    {
        $isMember = GroupController::checkIsGroupMember($groupId, $senderId);
        if (!$isMember) {
            throw new UserIsNotMemberOfGroup();
        }
        MessageDAO::createGroupMessage($senderId, $groupId, $text, $createAt);
    }

    /**
     * @throws PropelException
     */
    public static function getPrivateChat($userId1, $userId2): array
    {
        $messages = MessageDAO::getPrivateConversation($userId1, $userId2);
        return $messages;
    }

    /**
     * @throws PropelException
     * @throws UserIsNotMemberOfGroup
     */
    public static function getGroupChat($groupId, $userId): array
    {
        $isMember = GroupController::checkIsGroupMember($groupId, $userId);
        if (!$isMember) {
            throw new UserIsNotMemberOfGroup();
        }
        $messages = MessageDAO::getGroupConversation($groupId);
        return $messages;
    }
}
