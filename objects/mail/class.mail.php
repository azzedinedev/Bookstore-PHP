<?php
class simplemail{

	var $lang,$n_lang;
	var $titre = '';								## Titre par dfaut du email
	var $texte = '';	 							## Texte par dfaut du email
	
	var $email = '';								## Email  qui on envois
	var $name  = '';	 							## Nom  qui on envois
	
	var $cc_name = '';								## Nom de la 1e copy du message
	var $cc_email = '';								## Email de la 1e copy du message
	var $bcc_name = '';								## Nom de la 2e copy du message
	var $bcc_email = '';							## Nom de la 2e copy du message
	
	var $my_name = '';   							## Nom de l'auteur du email
	var $my_email = '';								## Adresse email de l'auteur du email
	var $url_site = '';								## Url du site pour les images du email HTML
	
	var $type = 'text';								## Type de email envoy     **|html|text|none|**
	var $post_text = false;							## Envoy avec le email un fichier text du email     **|false|true|**
	var $post_html = false;							## Envoy avec le email un fichier html du email     **|false|true|**
	
	var $priority = 3;								## Priorit du message      **|1|2|3|4|5|**
	var $attachment = array();						## Tableau contenant les fichiers attachs.
	var $uploaddir = '';
	
	function simplemail(){
		$this->type=='text';
		$this->email = '';
		$this->texte = '';
	}
	
	function send(){

		if($this->email != '' && $this->texte != ''){
			
			$limite = "_parties_".md5(uniqid (rand()));
						
			$mail_mime  = "Date: ".date("l j F Y, G:i")."\n";
			$mail_mime .= "MIME-Version: 1.0\n";
			$mail_mime .= "Content-Type: multipart/alternative;\n";
			$mail_mime .= "Subject: ".$this->titre." \r\n";
			if($this->name != ''){
				$to = $this->name." <".$this->email.">";
			}
			else{
				$to = $this->email;
			}
			
			$mail_mime .= "To: $to\r\n";
			
			if($this->my_name != ''){
				$de = $this->my_name." <".$this->my_email.">";
			}
			else{
				$de = $this->my_email;
			}
			$mail_mime .= "From: $de\r\n";
			
			
			$mail_mime .= "Reply-To: ".$this->my_email."\r\n";
			
			if($this->cc_email != '')
				$mail_mime .= "Cc: ".$this->cc_name." <".$this->cc_email.">\r\n";
			if($this->bcc_email != '')
				$mail_mime .= "Bcc: ".$this->bcc_name." <".$this->bcc_email.">\r\n";
			$mail_mime .= "X-Priority: ".$this->priority."\r\n";
			$mail_mime .= "X-Mailer: ISION Mail Sender\r\n";			
			$mail_mime .= " boundary=\"----=$limite\"\r\n";
			
			 
			$texte  = '';
			$this->type=='html';
			if($this->type=='html'){
				$texte .= "MIME-Version: 1.0\n";
				$texte .= "Content-type: text/html; charset=utf-8\r\n";
				$texte .= "Content-Transfer-Encoding: 8bit\n\n";
				$texte .= $this->body($this->texte);
			}
			elseif($this->type=='none'){
				
			}
			else{
				$texte .= "MIME-Version: 1.0\n";
				$texte .= "Content-type: text/plain; charset=utf-8\r\n";
				$texte .= "Content-Transfer-Encoding: 8bit\n\n";
				$texte .= $this->texte;
			}
			
			if($this->post_html){
				$texte .= "\n\n\n------=$limite\n";
				$texte .= "MIME-Version: 1.0\n";
				$texte .= "Content-type: text/html; charset=utf-8\r\n";
				$texte .= "Content-Transfer-Encoding: 8bit\n\n";
				$texte .= $this->body($this->texte);
			}
			
			if($this->post_text){
				$texte .= "\n\n\n------=$limite\n";
				$texte .= "MIME-Version: 1.0\n";
				$texte .= "Content-type: text/plain; charset=utf-8\r\n";
				$texte .= "Content-Transfer-Encoding: 8bit\n\n";
				$texte .= $this->texte;
			}
			
			
			if(count($this->attachment)>0){
				$attachement = '';
				for($i=0;$i<count($this->attachment);$i++){
					if($i==0){
						$attachement .= "\n\n\n------=$limite\n";
						$attachement .= "Content-Type: application/octet-stream; name=\"".$this->attachment[$i]."\"\n";
						$attachement .= "Content-Transfer-Encoding:base64\n";
						$attachement .= "Content-disposition: attachment; filename=\"".$this->attachment[$i]."\"\n\n";
						$attachement .= chunk_split(base64_encode(implode("",file($this->attachment[$i]))));
						$attachement .= "\n\n\n------=$limite\n";
					}
					else{
						$attachement .= "Content-Type: application/octet-stream; name=\"".$this->attachment[$i]."\"\n";
						$attachement .= "Content-Transfer-Encoding:base64\n";
						$attachement .= "Content-disposition: attachment; filename=\"".$this->attachment[$i]."\"\n\n";
						$attachement .= chunk_split(base64_encode(implode("", file($this->attachment[$i]))));
						$attachement .= "\n\n\n------=$limite\n";
					}
				}
			}
			else{
				$attachement = '';
			}
			
			$attachement = "";
			$to  = $this->name." <".$this->email.">" . ", " ;
			echo "mail it".$mail_mime." <hr/>";
			mail($to, $this->titre, $texte.$attachement, $mail_mime);
		}
	}
	
	function addrecipient($email,$name='') {
		$this->email = $email;
		$this->name = $name;
	}

	function addfrom($email,$name='') {
		$this->my_email = $email;
		$this->my_name 	= $name;
	}
	
	function addsubject($subject) {
		$this->titre = $subject;
	}
		
	function file($fichier){
		$this->attachment[] = $fichier;
	}

	function body($texte){
		//$body="$texte";		
		$body 	= '<html>'
				.'<head>'
				.'<title>'.$this->titre.'</title>'
				.'<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
				.'</head>'
				.'<body text="#000000" link="#640c0b" vlink="#640c0b" alink="#640c0b" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">'
				.$texte
				.'</body>'
				.'</html>';

		$body 	= '<b>'.$this->titre.'</b><br>'				
				.$texte;

		return $body;
	}
}

?>
