<?php

namespace Controller;

use DAO\UserDAO;
use Except\UsernameAlreadyExist;
use Except\UserNotFound;
use Propel\Runtime\Exception\PropelException;

class UserController
{
    /**
     * @throws PropelException
     * @throws UsernameAlreadyExist
     */
    public static function signup($username, $password): string
    {
        $user = UserDAO::getByUsername($username);
        if ($user) {
            throw new UsernameAlreadyExist();
        }
        $newUser = UserDAO::create($username, $password);
        return AuthController::generateJwt($newUser);
    }

    /**
     * @throws PropelException
     */
    public static function updateProfile($user, $username, $password): void
    {
        UserDAO::update($user, $username, $password);
    }

    /**
     * @throws PropelException
     */
    public static function deleteProfile($user): void
    {
        UserDAO::delete($user);
    }

    /**
     * @throws UserNotFound
     */
    public static function getById($userId): array
    {
        $user = UserDAO::getById($userId);
        if (!$user) {
            throw new UserNotFound();
        }
        return $user->toArray();
    }

    /**
     * @throws UserNotFound
     */
    public static function getByUsername($username): array
    {
        $user = UserDAO::getByUsername($username);
        if (!$user) {
            throw new UserNotFound();
        }
        return $user->toArray();
    }
}
