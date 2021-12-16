<?php

include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/components/application.php';
include_once dirname(__FILE__) . '/components/page/recovering_password_page.php';

$page = new PasswordRecoveryPage(CreateTableBasedUserManager(), GetMailer());
$page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
$page->OnPasswordResetRequest->AddListener('PasswordResetRequest');
$page->BeginRender();
$page->EndRender();
