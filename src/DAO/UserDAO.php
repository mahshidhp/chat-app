<?php

namespace DAO;

use Model\User;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;

class UserDAO
{
    /**
     * @throws PropelException
     */
    public static function create($username, $password): User
    {
        $newUser = new User();
        $newUser
            ->setUsername($username)
            ->setPassword($password)
            ->save();
        return $newUser;
    }

    /**
     * @throws PropelException
     */
    public static function update($user, $username, $password): void
    {
        $user
            ->setUsername($username)
            ->setPassword($password)
            ->save();
    }

    /**
     * @throws PropelException
     */
    public static function delete($user): void
    {
        $user->delete();
    }

    public static function getById($userId): ?User
    {
        return UserQuery::create()->findPk($userId);
    }

    public static function getByUsername($username): ?User
    {
        return UserQuery::create()->findOneByUsername($username);
    }
}
