<?php
session_start();

$user = "root";
$pass="";
$host="localhost";
$db = "bookstore";

$database = new db();
$database->connectDB($user,$pass,$host,$db);

$auth = new auth($database);
$auth->tbl_user				= "account";
$auth->dbfield_id			= "account_id";
$auth->dbfield_username		= "login";
$auth->dbfield_password 	= "password";
$auth->dbfield_level		= "role";
$auth->dbfield_displayname	= "login";

//==== Coupure de texte sans coupure du dernier mot ====//
function cut_sentence($Texte,$nbcar=0)
{
    if ( strlen($Texte) > $nbcar && (0!=$nbcar) )
    {
        $Tmp_Tb = explode( ' ', $Texte );
        $Tmp_Count = 0;
        $Tmp_O = '';

        while( list(,$v) = each($Tmp_Tb) )
        {
            if ( strlen($Tmp_O) >= $nbcar ) break;
            $Tmp_O .= $v.' ';
        }
        $Tmp_O = substr( $Tmp_O, 0, strlen($Tmp_O)-1 );
        if ( count($Tmp_Tb) > 1 )
        $Tmp_O .= '...';

    }
    else
        $Tmp_O = $Texte;

    return $Tmp_O;
}

?>