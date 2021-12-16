<?php
//+---------------------------------------------------------------------+
//|	class.session.php													|
//+---------------------------------------------------------------------+
//|	Session management and authentication file							|
//+---------------------------------------------------------------------+

class session
{

	function session()
	{
		$this->start();
    }

	function start()
    {
		if ( !$this->isSessionStart() ) {
			session_start();
			$_SESSION["SessionStart"] = time();
		}
    }
	
	function isSessionStart()
	{
		if ( isset( $_SESSION["SessionStart"] ) ){
			return true;
		}else{
			return false;
		}
	}
	
	function toSession($var,$val)
	{
		$_SESSION[$var] = $val;
	}
	
	function fromSession($var)
	{
		return $_SESSION[$var];
	}

	function is_Set($var)
	{
		if( isset($_SESSION[$var]) ) {
			return true;
		}else{
			return false;
		}
	}

}

?>