<?php

//  define('SHOW_VARIABLES', 1);
//  define('DEBUG_LEVEL', 1);

//  error_reporting(E_ALL ^ E_NOTICE);
//  ini_set('display_errors', 'On');

set_include_path('.' . PATH_SEPARATOR . get_include_path());


include_once dirname(__FILE__) . '/' . 'components/utils/system_utils.php';
include_once dirname(__FILE__) . '/' . 'components/mail/mailer.php';
include_once dirname(__FILE__) . '/' . 'components/mail/phpmailer_based_mailer.php';
require_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';

//  SystemUtils::DisableMagicQuotesRuntime();

SystemUtils::SetTimeZoneIfNeed('Europe/Belgrade');

function GetGlobalConnectionOptions()
{
    return
        array(
          'server' => 'localhost',
          'port' => '3306',
          'username' => 'root',
          'database' => 'bookstore',
          'client_encoding' => 'utf8'
        );
}

function HasAdminPage()
{
    return false;
}

function HasHomePage()
{
    return true;
}

function GetHomeURL()
{
    return 'index.php';
}

function GetHomePageBanner()
{
    return '';
}

function GetPageGroups()
{
    $result = array();
    $result[] = array('caption' => 'Administration', 'description' => '');
    $result[] = array('caption' => 'Books management', 'description' => '');
    $result[] = array('caption' => 'Orders', 'description' => '');
    $result[] = array('caption' => 'My profile', 'description' => '');
    return $result;
}

function GetPageInfos()
{
    $result = array();
    $result[] = array('caption' => 'Account', 'short_caption' => 'Account', 'filename' => 'account.php', 'name' => 'account03', 'group_name' => 'Administration', 'add_separator' => false, 'description' => 'Manage access account to the plateforme');
    $result[] = array('caption' => 'Owners', 'short_caption' => 'Owners', 'filename' => 'owners.php', 'name' => 'owner', 'group_name' => 'Administration', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Users', 'short_caption' => 'Users', 'filename' => 'users.php', 'name' => 'user', 'group_name' => 'Administration', 'add_separator' => false, 'description' => 'Manage users to the plateforme');
    $result[] = array('caption' => 'Books', 'short_caption' => 'Books', 'filename' => 'books.php', 'name' => 'Query-Books-with-authors', 'group_name' => 'Books management', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Authors', 'short_caption' => 'Authors', 'filename' => 'authors.php', 'name' => 'author', 'group_name' => 'Books management', 'add_separator' => false, 'description' => 'Management of book\'s authors');
    $result[] = array('caption' => 'Book by authors', 'short_caption' => 'Book by authors', 'filename' => 'book-authors.php', 'name' => 'book_author', 'group_name' => 'Books management', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Genres', 'short_caption' => 'Genres', 'filename' => 'genres.php', 'name' => 'genre', 'group_name' => 'Books management', 'add_separator' => false, 'description' => 'Genre of books');
    $result[] = array('caption' => 'Publishers', 'short_caption' => 'Publishers', 'filename' => 'publishers.php', 'name' => 'publisher', 'group_name' => 'Books management', 'add_separator' => false, 'description' => 'Publishers of books');
    $result[] = array('caption' => 'List of orders', 'short_caption' => 'Orders', 'filename' => 'orders-list.php', 'name' => 'order', 'group_name' => 'Orders', 'add_separator' => false, 'description' => 'Management of orders');
    $result[] = array('caption' => 'Order books', 'short_caption' => 'Order Book', 'filename' => 'order-books.php', 'name' => 'order_book', 'group_name' => 'Orders', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Order history', 'short_caption' => 'Order History', 'filename' => 'order-history.php', 'name' => 'order_history', 'group_name' => 'Orders', 'add_separator' => false, 'description' => 'Orders history');
    $result[] = array('caption' => 'Update my password', 'short_caption' => 'Update my password', 'filename' => 'my-password.php', 'name' => 'account02', 'group_name' => 'My profile', 'add_separator' => false, 'description' => '');
    $result[] = array('caption' => 'Update the profile', 'short_caption' => 'Update the profile', 'filename' => 'my-profile.php', 'name' => 'Query-Profile01', 'group_name' => 'My profile', 'add_separator' => false, 'description' => '');
    return $result;
}

function GetPagesHeader()
{
    return
        '<h1 style="float:left;height: 20px;font-size: 20px;padding: 0;margin: 10px;">BOOKSTORE (Backend)</h1>';
}

function GetPagesFooter()
{
    return
        'Online bookstore - 2021';
}

function ApplyCommonPageSettings(Page $page, Grid $grid)
{
    $page->SetShowUserAuthBar(true);
    $page->setShowNavigation(true);
    $page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');
    $page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
    $page->OnGetCustomExportOptions->AddListener('Global_OnGetCustomExportOptions');
    $page->getDataset()->OnGetFieldValue->AddListener('Global_OnGetFieldValue');
    $page->getDataset()->OnGetFieldValue->AddListener('OnGetFieldValue', $page);
    $grid->BeforeUpdateRecord->AddListener('Global_BeforeUpdateHandler');
    $grid->BeforeDeleteRecord->AddListener('Global_BeforeDeleteHandler');
    $grid->BeforeInsertRecord->AddListener('Global_BeforeInsertHandler');
    $grid->AfterUpdateRecord->AddListener('Global_AfterUpdateHandler');
    $grid->AfterDeleteRecord->AddListener('Global_AfterDeleteHandler');
    $grid->AfterInsertRecord->AddListener('Global_AfterInsertHandler');
}

function GetAnsiEncoding() { return 'windows-1252'; }

function Global_OnGetCustomPagePermissionsHandler(Page $page, PermissionSet &$permissions, &$handled)
{
    /*
    // do not apply these rules for site admins
    
    if (!GetApplication()->HasAdminGrantForCurrentUser()) {    
    
        // retrieving all user roles 
    if( $pageName == 'account.php'){
        $sql =        
    
          "SELECT AC.role " .
    
          "FROM account AC " .
    
          "WHERE AC.account_id = %d";    
    
        //$userId = $page->GetCurrentUserId();
    
        $result = $connection->fetchAll(sprintf($sql, $userId));
    
     
        // iterating through retrieved roles
    
        if (!empty($result)) {
    
           foreach ($result as $row) {
    
               // is current user a member of the Sales role?
    
               if ($row['role'] === 'admin') {
    
                 // if yes, allow all actions.
    
                 // otherwise default permissions for this page will be applied
    
                 $permissions->setGrants(true, true, true, true);
    
                 break;
    
               }else{
                  $permissions->setGrants(false, false, false, false);
               }                 
    
           }
    
        }
    
    }
    //echo '<pre>'.print_r($page,true).'</pre>';
    }
    
     */
}

function Global_CustomHTMLHeaderHandler($page, &$customHtmlHeaderText)
{

}

function Global_GetCustomTemplateHandler($type, $part, $mode, &$result, &$params, CommonPage $page = null)
{

}

function Global_OnGetCustomExportOptions($page, $exportType, $rowData, &$options)
{

}

function Global_OnGetFieldValue($fieldName, &$value, $tableName)
{

}

function Global_GetCustomPageList(CommonPage $page, PageList $pageList)
{

}

function Global_BeforeUpdateHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeDeleteHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_BeforeInsertHandler($page, &$rowData, &$cancel, &$message, &$messageDisplayTime, $tableName)
{

}

function Global_AfterUpdateHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterDeleteHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function Global_AfterInsertHandler($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
{

}

function GetDefaultDateFormat()
{
    return 'Y-m-d';
}

function GetFirstDayOfWeek()
{
    return 0;
}

function GetPageListType()
{
    return PageList::TYPE_MENU;
}

function GetNullLabel()
{
    return null;
}

function UseMinifiedJS()
{
    return true;
}

function GetOfflineMode()
{
    return false;
}

function GetInactivityTimeout()
{
    return 0;
}

function GetMailer()
{
    $smtpOptions = new SMTPOptions('smtp.gmail.com', 587, true, 'bookstore.mailing@gmail.com', 'b00k5t0re', '');
    $mailerOptions = new MailerOptions(MailerType::SMTP, 'bookstore.mailing@gmail.com', 'Bookstore adminsitrator', $smtpOptions);
    
    return PHPMailerBasedMailer::getInstance($mailerOptions);
}

function sendMailMessage($recipients, $messageSubject, $messageBody, $attachments = '', $cc = '', $bcc = '')
{
    GetMailer()->send($recipients, $messageSubject, $messageBody, $attachments, $cc, $bcc);
}

function createConnection()
{
    $connectionOptions = GetGlobalConnectionOptions();
    $connectionOptions['client_encoding'] = 'utf8';

    $connectionFactory = MySqlIConnectionFactory::getInstance();
    return $connectionFactory->CreateConnection($connectionOptions);
}
