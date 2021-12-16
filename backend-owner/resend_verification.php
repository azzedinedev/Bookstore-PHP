<?php

include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/components/application.php';
include_once dirname(__FILE__) . '/components/page/resend_verification_page.php';

$page = new ResendVerificationPage(CreateTableBasedUserManager(), GetMailer());
$page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
$page->BeginRender();
$page->EndRender();
