<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->initDatabaseMapFromDumps(array (
  'chat' => 
  array (
    'tablesByName' => 
    array (
      'group' => '\\Model\\Map\\GroupTableMap',
      'membership' => '\\Model\\Map\\MembershipTableMap',
      'message' => '\\Model\\Map\\MessageTableMap',
      'user' => '\\Model\\Map\\UserTableMap',
    ),
    'tablesByPhpName' => 
    array (
      '\\Group' => '\\Model\\Map\\GroupTableMap',
      '\\Membership' => '\\Model\\Map\\MembershipTableMap',
      '\\Message' => '\\Model\\Map\\MessageTableMap',
      '\\User' => '\\Model\\Map\\UserTableMap',
    ),
  ),
));
