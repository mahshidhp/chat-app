<?php

namespace DAO;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Exception\PropelException;

use Model\Message;
use Model\MessageQuery;


class MessageDAO
{
    /**
     * @throws PropelException
     */
    public static function createPrivateMessage($senderId, $receiverId, $text, $createAt): void
    {
        $newMessage = new Message();
        $newMessage
            ->setSenderId($senderId)
            ->setReceiverId($receiverId)
            ->setText($text)
            ->setCreatedAt($createAt)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function createGroupMessage($senderId, $groupId, $text, $createAt): void
    {
        $newMessage = new Message();
        $newMessage
            ->setSenderId($senderId)
            ->setGroupId($groupId)
            ->setText($text)
            ->setCreatedAt($createAt)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function getPrivateConversation($userId1, $userId2, $page=1, $maxPerPage=100): array
    {
        $messages =  MessageQuery::create()
            ->filterByReceiverId([$userId1, $userId2])
            ->filterBySenderId([$userId1, $userId2])
            ->orderByCreatedAt(Criteria::DESC)
            ->paginate($page, $maxPerPage);

        return array_reverse($messages);
    }

    public static function getGroupConversation($groupId, $page=1, $maxPerPage=100): array
    {
        $messages = MessageQuery::create()
            ->filterByGroupId($groupId)
            ->orderByCreatedAt(Criteria::DESC)
            ->paginate($page, $maxPerPage);

        return array_reverse($messages);
    }
}
