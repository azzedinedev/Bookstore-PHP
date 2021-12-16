<?php

require_once 'phpgen_settings.php';
require_once 'components/application.php';
require_once 'components/security/permission_set.php';
require_once 'components/security/user_authentication/table_based_user_authentication.php';
require_once 'components/security/grant_manager/user_grant_manager.php';
require_once 'components/security/grant_manager/composite_grant_manager.php';
require_once 'components/security/grant_manager/hard_coded_user_grant_manager.php';
require_once 'components/security/grant_manager/table_based_user_grant_manager.php';
require_once 'components/security/table_based_user_manager.php';

include_once 'components/security/user_identity_storage/user_identity_session_storage.php';

require_once 'database_engine/mysql_engine.php';

$grants = array('guest' => 
        array()
    ,
    'defaultUser' => 
        array('account03' => new PermissionSet(false, false, false, false),
        'account03.owner' => new PermissionSet(false, false, false, false),
        'account03.user' => new PermissionSet(false, false, false, false),
        'owner' => new AdminPermissionSet(),
        'user' => new AdminPermissionSet(),
        'user.order' => new AdminPermissionSet(),
        'user.user_address' => new AdminPermissionSet(),
        'user_address' => new AdminPermissionSet(),
        'Query-Books-with-authors' => new AdminPermissionSet(),
        'Query-Books-with-authors.book_author' => new AdminPermissionSet(),
        'author' => new AdminPermissionSet(),
        'author.book_author' => new AdminPermissionSet(),
        'book_author' => new AdminPermissionSet(),
        'genre' => new AdminPermissionSet(),
        'genre.book' => new AdminPermissionSet(),
        'publisher' => new AdminPermissionSet(),
        'publisher.book' => new AdminPermissionSet(),
        'order' => new AdminPermissionSet(),
        'order.order_book' => new AdminPermissionSet(),
        'order.order_history' => new AdminPermissionSet(),
        'order_book' => new AdminPermissionSet(),
        'order_history' => new AdminPermissionSet(),
        'account02' => new AdminPermissionSet(),
        'Query-Profile01' => new AdminPermissionSet())
    ,
    'owner' => 
        array('account03' => new PermissionSet(false, false, false, false),
        'account03.owner' => new PermissionSet(false, false, false, false),
        'account03.user' => new PermissionSet(false, false, false, false),
        'owner' => new PermissionSet(false, false, false, false),
        'user' => new PermissionSet(true, false, false, false),
        'user.order' => new PermissionSet(false, false, false, false),
        'user.user_address' => new PermissionSet(false, false, false, false),
        'user_address' => new PermissionSet(true, false, false, false),
        'Query-Books-with-authors' => new PermissionSet(false, false, false, false),
        'Query-Books-with-authors.book_author' => new PermissionSet(false, false, false, false),
        'author' => new PermissionSet(false, false, false, false),
        'author.book_author' => new PermissionSet(false, false, false, false),
        'book_author' => new PermissionSet(false, false, false, false),
        'genre' => new PermissionSet(false, false, false, false),
        'genre.book' => new PermissionSet(false, false, false, false),
        'publisher' => new PermissionSet(false, false, false, false),
        'publisher.book' => new PermissionSet(false, false, false, false),
        'order' => new PermissionSet(false, false, false, false),
        'order.order_book' => new PermissionSet(false, false, false, false),
        'order.order_history' => new PermissionSet(false, false, false, false),
        'order_book' => new PermissionSet(false, false, false, false),
        'order_history' => new PermissionSet(false, false, false, false),
        'account02' => new PermissionSet(false, false, false, false),
        'Query-Profile01' => new PermissionSet(false, false, false, false))
    ,
    'user1' => 
        array('account03' => new PermissionSet(false, false, false, false),
        'account03.owner' => new PermissionSet(false, false, false, false),
        'account03.user' => new PermissionSet(false, false, false, false),
        'owner' => new PermissionSet(false, false, false, false),
        'user' => new PermissionSet(false, false, false, false),
        'user.order' => new PermissionSet(false, false, false, false),
        'user.user_address' => new PermissionSet(false, false, false, false),
        'user_address' => new PermissionSet(false, false, false, false),
        'Query-Books-with-authors' => new PermissionSet(false, false, false, false),
        'Query-Books-with-authors.book_author' => new PermissionSet(false, false, false, false),
        'author' => new PermissionSet(false, false, false, false),
        'author.book_author' => new PermissionSet(false, false, false, false),
        'book_author' => new PermissionSet(false, false, false, false),
        'genre' => new PermissionSet(false, false, false, false),
        'genre.book' => new PermissionSet(false, false, false, false),
        'publisher' => new PermissionSet(false, false, false, false),
        'publisher.book' => new PermissionSet(false, false, false, false),
        'order' => new PermissionSet(false, false, false, false),
        'order.order_book' => new PermissionSet(false, false, false, false),
        'order.order_history' => new PermissionSet(false, false, false, false),
        'order_book' => new PermissionSet(false, false, false, false),
        'order_history' => new PermissionSet(false, false, false, false),
        'account02' => new PermissionSet(false, false, false, false),
        'Query-Profile01' => new PermissionSet(false, false, false, false))
    ,
    'admin' => 
        array('account03' => new AdminPermissionSet(),
        'account03.owner' => new PermissionSet(false, false, false, false),
        'account03.user' => new PermissionSet(false, false, false, false),
        'owner' => new PermissionSet(false, false, false, false),
        'user' => new AdminPermissionSet(),
        'user.order' => new PermissionSet(false, false, false, false),
        'user.user_address' => new PermissionSet(false, false, false, false),
        'user_address' => new AdminPermissionSet(),
        'Query-Books-with-authors' => new AdminPermissionSet(),
        'Query-Books-with-authors.book_author' => new PermissionSet(false, false, false, false),
        'author' => new AdminPermissionSet(),
        'author.book_author' => new AdminPermissionSet(),
        'book_author' => new AdminPermissionSet(),
        'genre' => new AdminPermissionSet(),
        'genre.book' => new AdminPermissionSet(),
        'publisher' => new AdminPermissionSet(),
        'publisher.book' => new AdminPermissionSet(),
        'order' => new AdminPermissionSet(),
        'order.order_book' => new PermissionSet(false, false, false, false),
        'order.order_history' => new PermissionSet(false, false, false, false),
        'order_book' => new AdminPermissionSet(),
        'order_history' => new AdminPermissionSet(),
        'account02' => new AdminPermissionSet(),
        'Query-Profile01' => new AdminPermissionSet())
    );

$appGrants = array('guest' => new PermissionSet(false, false, false, false),
    'defaultUser' => new PermissionSet(false, false, false, false),
    'owner' => new PermissionSet(false, false, false, false),
    'user1' => new PermissionSet(false, false, false, false),
    'admin' => new PermissionSet(false, false, false, false));

$dataSourceRecordPermissions = array();

$tableCaptions = array('account03' => 'Account',
'account03.owner' => 'Account->Owners',
'account03.user' => 'Account->Users',
'owner' => 'Owners',
'user' => 'Users',
'user.order' => 'Users->Orders',
'user.user_address' => 'Users->User Address',
'user_address' => 'User Address',
'Query-Books-with-authors' => 'Books',
'Query-Books-with-authors.book_author' => 'Books->Book authors',
'author' => 'Authors',
'author.book_author' => 'Authors->Books',
'book_author' => 'Book by authors',
'genre' => 'Genres',
'genre.book' => 'Genres->Books',
'publisher' => 'Publishers',
'publisher.book' => 'Publishers->Books',
'order' => 'List of orders',
'order.order_book' => 'List of orders->Order details',
'order.order_history' => 'List of orders->Order history',
'order_book' => 'Order books',
'order_history' => 'Order history',
'account02' => 'Update my password',
'Query-Profile01' => 'Update the profile');

$usersTableInfo = array(
    'TableName' => 'account',
    'UserId' => 'account_id',
    'UserName' => 'login',
    'Password' => 'password',
    'Email' => 'email',
    'UserToken' => 'token',
    'UserStatus' => 'status'
);

function EncryptPassword($password, &$result)
{

}

function VerifyPassword($enteredPassword, $encryptedPassword, &$result)
{

}

function BeforeUserRegistration($username, $email, $password, &$allowRegistration, &$errorMessage)
{

}    

function AfterUserRegistration($username, $email)
{
    /* Insert account */
    $sql1 = "UPDATE `account` ".
           "SET `role` = 'owner', ".
           "SET `token` = '".md5('token-'.$username)."', ".
           "SET `status` = '0' ".
           "WHERE `login` = '".mysql_escape_string($username)."'";
           
    $this->GetConnection()->ExecSQL($sql1);
    
    /* Insert owner */
    $sql2 = "INSERT IGNORE INTO `owner` ".
           "SET `account_id` = ( SELECT `account_id` FROM `account` WHERE `login` = '".mysqli_real_escape_string($username)."' )";
           
    $this->GetConnection()->ExecSQL($sql2);
}    

function PasswordResetRequest($username, $email)
{

}

function PasswordResetComplete($username, $email)
{

}

function CreatePasswordHasher()
{
    $hasher = CreateHasher('');
    if ($hasher instanceof CustomStringHasher) {
        $hasher->OnEncryptPassword->AddListener('EncryptPassword');
        $hasher->OnVerifyPassword->AddListener('VerifyPassword');
    }
    return $hasher;
}

function CreateTableBasedGrantManager()
{
    return null;
}

function CreateTableBasedUserManager() {
    global $usersTableInfo;
    return new TableBasedUserManager(MySqlIConnectionFactory::getInstance(), GetGlobalConnectionOptions(), $usersTableInfo, CreatePasswordHasher(), true);
}

function SetUpUserAuthorization()
{
    global $grants;
    global $appGrants;
    global $dataSourceRecordPermissions;

    $hasher = CreatePasswordHasher();

    $hardCodedGrantManager = new HardCodedUserGrantManager($grants, $appGrants);
    $tableBasedGrantManager = CreateTableBasedGrantManager();
    $grantManager = new CompositeGrantManager();
    $grantManager->AddGrantManager($hardCodedGrantManager);
    if (!is_null($tableBasedGrantManager)) {
        $grantManager->AddGrantManager($tableBasedGrantManager);
    }

    $userAuthentication = new TableBasedUserAuthentication(new UserIdentitySessionStorage($hasher), false, $hasher, CreateTableBasedUserManager(), false, true, true);

    GetApplication()->SetUserAuthentication($userAuthentication);
    GetApplication()->SetUserGrantManager($grantManager);
    GetApplication()->SetDataSourceRecordPermissionRetrieveStrategy(new HardCodedDataSourceRecordPermissionRetrieveStrategy($dataSourceRecordPermissions));
}
