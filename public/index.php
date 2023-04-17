<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

use Controller\AuthController;
use Controller\UserController;
use Controller\GroupController;
use Controller\MessageController;

use Except\UsernameAlreadyExist;
use Except\InvalidCredentials;
use Except\UserNotFound;
use Except\UserIsNotGroupCreator;
use Except\UserIsNotMemberOfGroup;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Authentication APIs

$app->post('/login', function (Request $request, Response $response, $args) {
    try {
        $username = $args["username"];
        $password = $args["password"];
        $jwt = AuthController::login($username, $password);
        $responseBody = json_encode(["jwt" => $jwt], JSON_PRETTY_PRINT);
        $response->getBody()->write($responseBody);
        return $response->withStatus(200);
    } catch (InvalidCredentials $e) {
        $response->getBody()->write($e->getMessage());
        return $response->withStatus(403);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('/signup', function (Request $request, Response $response, $args) {
    try {
        $username = $args["username"];
        $password = $args["password"];
        $jwt = UserController::signup($username, $password);
        $responseBody = json_encode(["jwt" => $jwt], JSON_PRETTY_PRINT);
        $response->getBody()->write($responseBody);
        return $response->withStatus(200);
    } catch (UsernameAlreadyExist $e) {
        $response->getBody()->write($e->getMessage());
        return $response->withStatus(400);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

// User APIs

$app->get('/user/{userId}', function (Request $request, Response $response, $args) {
    try {
        $userId = $args['userId'];
        $user = UserController::getById($userId);
        unset($user["password"]);
        $responseBody = json_encode(["user" => $user], JSON_PRETTY_PRINT);
        $response->getBody()->write($responseBody);
        return $response->withStatus(200);
    } catch (UserNotFound $e) {
        $response->getBody()->write($e->getMessage());
        return $response->withStatus(404);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('/user', function (Request $request, Response $response, $args) {
    try {

        $user = AuthController::loginWithJwt();
        $username = $args["username"];
        $password = $args["password"];
        UserController::updateProfile($user, $username, $password);
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->delete('/user', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        UserController::deleteProfile($user);
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

// group APIs

$app->post('/group', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupName = $args["groupName"];
        GroupController::createGroup($groupName, $user->getId());
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->delete('/group/{groupId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        GroupController::deleteGroup($groupId, $user->getId());
        return $response->withStatus(200);
    } catch (UserIsNotGroupCreator $e) {
        return $response->withStatus(403);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('/group/{groupId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        $groupName = $args["groupName"];
        GroupController::updateGroup($groupId, $groupName, $user->getId());
        return $response->withStatus(200);
    } catch (UserIsNotMemberOfGroup $e) {
        return $response->withStatus(403);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->get('/group/{groupId}/members', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        GroupController::getGroupMembers($groupId);
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('/group/{groupId}/members', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        GroupController::joinGroup($groupId, $user->getId());
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->delete('/group/{groupId}/members', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        GroupController::leaveGroup($groupId, $user->getId());
        return $response->withStatus(200);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

// private message APIs

$app->get('/message/{userId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $userId = $args["userId"];
        $messages = MessageController::getPrivateChat($user->getId(), $userId);
        $responseBody = json_encode(["messages" => $messages], JSON_PRETTY_PRINT);
        $response->getBody()->write($responseBody);
        return $response->withStatus(200);
    }
    catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('/message/{userId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $receiverId = $args["userId"];
        $text = $args["text"];
        $createdAt = $args["created_at"];
        MessageController::sendPrivateMessage($user->getId(), $receiverId, $text, $createdAt);

        return $response->withStatus(200);
    }
    catch (Exception $e) {
        return $response->withStatus(500);
    }
});

// group message APIs

$app->get('message/group/{groupId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        $messages = MessageController::getGroupChat($groupId, $user->getId());
        $responseBody = json_encode(["messages" => $messages], JSON_PRETTY_PRINT);
        $response->getBody()->write($responseBody);
        return $response->withStatus(200);
    } catch (UserIsNotMemberOfGroup $e) {
        return $response->withStatus(403);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});

$app->post('message/group/{groupId}', function (Request $request, Response $response, $args) {
    try {
        $user = AuthController::loginWithJwt();
        $groupId = $args["groupId"];
        $text = $args["text"];
        $createdAt = $args["created_at"];
        MessageController::sendGroupMessage($user->getId(), $groupId, $text, $createdAt);
        return $response->withStatus(200);
    } catch (UserIsNotMemberOfGroup $e) {
        return $response->withStatus(403);
    } catch (Exception $e) {
        return $response->withStatus(500);
    }
});


$app->run();
