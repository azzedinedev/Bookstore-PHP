<?php
//+---------------------------------------------------------------------+
//|	class.auth.php														|
//+---------------------------------------------------------------------+
//|	Authentification class												|
//+---------------------------------------------------------------------+
//|	Created 															|
//+---------------------------------------------------------------------+

class auth
{
	
	function auth($database)
	{
		//appel de classes
		$this->session 	= new session();
		$this->database = $database;
		$this->email 	= new simplemail();
			
		$this->init();
 	 }

	function init()
	{
		$this->getLevel();
		$this->getUser();
		$this->getName();
    }

	function getLevel()
	{
		if( !$this->session->is_Set("level_") ){
			$this->session->toSession("level_","0,");
		}
		return $this->session->fromSession("level_");
    }
	
	function getUser()
	{
		if( !$this->session->is_Set("user") ){
			$this->session->toSession("user","");
		}
		return $this->session->fromSession("user");
		
    }

	function setUser($user)
	{
		$this->session->toSession("user",$user);
    }
	
	function getId()
	{
		if( !$this->session->is_Set("userid") ){
			$this->session->toSession("userid","0");
		}
		return $this->session->fromSession("userid");
		
    }

	function setId($userId)
	{
		$this->session->toSession("userid",$userId);
    }
	
	function setLevel($level)
	{
		$this->session->toSession("level_",$level."0,");
    }
	
	function setName($name)
	{
		$this->session->toSession("name",$name);
    }
	
	function getName()
	{
		if( !$this->session->is_Set("name") ){
			$this->session->toSession("name","");
		}
		return $this->session->fromSession("name");
    }

	function connect($user,$pass)
	{
	
		$passMD5 = $pass;//md5($this->user_md5_code."-".$pass);
		$sql = "SELECT * FROM ".$this->database->escapeDB($this->tbl_user)." WHERE ".$this->database->escapeDB($this->dbfield_username)."='".$this->database->escapeDB($user)."' AND ".$this->database->escapeDB($this->dbfield_password)."='".$this->database->escapeDB($passMD5)."'";
		$num = $this->database->DB_num_rows($sql);
		$this->auth_error = "";
		if( $num == 0 ){
			$this->auth_error = "login";
			return false;
		}else{
			$row 	= $this->database->DB_fetch($sql);
			$id 	= $this->database->stripDB($row[$this->dbfield_id]);
			$user 	= $this->database->stripDB($row[$this->dbfield_username]);
			$level 	= $this->database->stripDB($row[$this->dbfield_level]);
			$name 	= $this->database->stripDB($row[$this->dbfield_displayname]);
			
			$this->setUser($user);
			$this->setLevel($level);
			$this->setName($name);
			$this->setId($id);

			header("location:".$_SERVER['PHP_SELF']);
/*
			echo ''
			.'<script language="JavaScript">'."\n"
			.'locatio.href = "'.$_SERVER['PHP_SELF'].'"'."\n"
    		.'</script>'."\n";
*/
			return true;
		}
		
	}

	function disconnect()
	{
		$this->setUser("");
		$this->setLevel("");
		$this->setName("");
		$this->setId("");
		
		header("location:".$_SERVER['PHP_SELF']);
		/*
		echo ''
		.'<script language="JavaScript">'."\n"
		.'locatio.href = "'.$_SERVER['PHP_SELF'].'"'."\n"
   		.'</script>'."\n";
		   */
		return true;
	}
	
	function fromUser($user)
	{
		$sql = "SELECT * FROM ".$this->database->escapeDB($this->tbl_user)." WHERE ".$this->database->escapeDB($this->dbfield_username)."='".$this->database->escapeDB($user)."'";
		$num = $this->database->DB_num_rows($sql);
		if( $num == 0 ){
			$this->auth_error = "user";
			return array();
		}else{
			$row = $this->database->DB_fetch($sql);
			foreach( $row as $key => $val ){
				$row_user[$key] = $this->database->stripDB($val);
			}
			$row_user = $row;
			return $row_user;
		}		
    }
		
	function returnNewPass()
	{
		// on declare une chaine de caract�res
		$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@";
		//nombre de caract�res dans le mot de passe
		$nb_caract = 8;
		// on fait une variable contenant le futur pass
		$pass = "";
		//on fait une boucle
		for($u = 1; $u <= $nb_caract; $u++) {
			//on compte le nombre de caract�res pr�sents dans notre chaine
		    $nb = strlen($chaine);
			// on choisie un nombre au hasard entre 0 et le nombre de caract�res de la chaine
		    $nb = mt_rand(0,($nb-1));
			// on ajoute la lettre a la valeur de $pass
		    $pass.=$chaine[$nb];
		}
		// on retourn le r�sultat

		return $pass;
    }
	
	function mailNewPass($user)
	{
		$row = $this->fromUser($user);
		$this->email->addrecipient($row[$this->dbfield_mail],$row[$this->dbfield_firstname." ".$this->dbfield_lastname]);
		$this->email->addsubject($this->auth_subject_mail_pass);
		$this->email->addfrom($this->from_mail,$this->mail_from_name);
		$this->email->texte = $this->auth_body_mail_pass;
		$this->email->type 	= 'html';		
		$this->email->sendmail();
    }
	
	function showHTMLLogin()
	{
		if( $this->getUser() == "" ){
			$this->showConnectlogin();
		}else{
			$this->showDeconnectlogin();
		}		
    }
	
	function showConnectlogin()
	{
		$test_form = 0;
		if( isset($_POST['connect_submit']) ){
			$this->connect($_POST["connect_user"],$_POST["connect_pass"]);
			if( $this->auth_error == "login" ){
				$test_form = 1;
			}else{
				$test_form = 2;
			}
		}
		
		if( $test_form != 2 ){
			if( $test_form == 1 ){
				echo $this->auth_error_connect."<br>";
			}
			echo '<form name="connect_form" id="connect_form" action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
			echo '<table class="connect">'."\n";
			echo '<tr><td>'."\n".$this->label_user.' : <input id="connect_user" name="connect_user" type="text" value="" />'."\n".'</td></tr>'."\n";
			echo '<tr><td>'."\n".$this->label_pass.' : <input id="connect_pass" name="connect_pass" type="password" value="" />'."\n".'</td></tr>'."\n";
			echo '<td>'."\n".' <button id="connect_submit" name="connect_submit" type="submit">'.$this->bouton_valider.'</button>'."\n".'</td></tr>'."\n";
			echo '</table>'."\n";
			echo '</form>'."\n";
		}else{
			$this->showDeconnectlogin();
		}
		return;
	}
	
	function showDeconnectlogin()
	{
		if( !isset($_POST['deconnect_submit']) ){
			$row = $this->fromUser($this->getUser());
			echo '<form name="deconnect_form" id="deconnect_form" action="'.$_SERVER['PHP_SELF'].'" method="POST">'."\n";
			echo '<p class="connect">'."\n";
			echo '<span>'."\n".$this->auth_label_hello_user.": ".$row["Name"].' - '.$row["Name_en"].'</span>'."\n";
			echo '<br>'."\n".'<button id="deconnect_submit" name="deconnect_submit" type="submit">'.$this->bouton_disconnecter.'</button>'."\n"."\n";
			echo '</p>'."\n";
			echo '</form>'."\n";
		}else{
			$this->disconnect();
			$this->showConnectlogin();
		}
	}

}
?>