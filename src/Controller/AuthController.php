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

class AuthController {

    private static string $JWT_KEY;
    private static string $JWT_ALG;

    public function __construct() {
        self::$JWT_KEY = $_ENV["JWT_KEY"];
        self::$JWT_ALG = $_ENV["JWT_ALG"];
    }

    /**
     * @throws InvalidCredentials
     */
    public static function login($username, $password): string {
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
    public static function loginWithJwt(): ?User {
        try {
            $headers = getallheaders();
            $userJwt = $headers["x-auth-token"];
            $key = new Key(self::$JWT_KEY, self::$JWT_ALG);
            $decodedJwt = (array) JWT::decode($userJwt, $key);
            $userId = $decodedJwt["id"];
            return UserDAO::getById($userId);
        } catch (Exception $ex) {
            throw new InvalidCredentials();
        }
    }

    public static function generateJwt($user): string {
        $now = new DateTime();
        $duration = new DateInterval("12H");
        $payload = array(
            "id" => $user->getId(),
            "username" => $user->getUsername(),
            "iat" => $now->getTimestamp(),
            "exp" => $now->add($duration)->getTimestamp(),
        );
        return JWT::encode($payload, self::$JWT_KEY, self::$JWT_ALG);
    }
}
