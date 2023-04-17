<?php

namespace Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTime;
use DateInterval;

use Exception;
use Except\InvalidCredentials;

use DAO\UserDAO;
use Model\User;

class AuthController
{

    /**
     * @throws InvalidCredentials
     */
    public static function login($username, $password): string
    {
        $user = UserDAO::getByUsername($username);
        if ($user && $user->getPassword() == $password) {
            return self::generateJwt($user);
        } else {
            throw new InvalidCredentials();
        }
    }

    /**
     * @throws InvalidCredentials
     */
    public static function loginWithJwt(): ?User
    {
        try {
            $headers = getallheaders();
            $userJwt = $headers["x-auth-token"];
            $key = new Key($_ENV["JWT_KEY"], $_ENV["JWT_ALG"]);
            $decodedJwt = (array) JWT::decode($userJwt, $key);
            $userId = $decodedJwt["id"];
            return UserDAO::getById($userId);
        } catch (Exception $ex) {
            throw new InvalidCredentials();
        }
    }

    public static function generateJwt($user): string
    {
        $now = new DateTime();
        $duration = DateInterval::createFromDateString('1 day');
        $payload = array(
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "iat" => $now->getTimestamp(),
            "exp" => $now->add($duration)->getTimestamp(),
        );
        return JWT::encode($payload, $_ENV["JWT_KEY"], $_ENV["JWT_ALG"]);
    }
}
