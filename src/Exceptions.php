<?php

namespace Except;

use Exception;

class InvalidCredentials extends Exception {}

class UsernameAlreadyExist extends Exception {}

class UserNotFound extends Exception {}

class UserIsNotMemberOfGroup extends Exception {}

class UserIsNotGroupCreator extends Exception {}