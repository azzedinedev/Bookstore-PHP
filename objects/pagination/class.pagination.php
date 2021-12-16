<?php
/*
+-----------------------------------------------------------------------+
|	class.faqmanager.php											 	|
+-----------------------------------------------------------------------+
|	pagination Class													|
|	Objet of modification: 												|
|		Pagination links with SQL query									|
|		show buttons of pagination										|
+-----------------------------------------------------------------------+
*/
//Include the database class
if (!class_exists('db')) {
	include(dirname(__FILE__)."/../db/class.db.php");
}

class Paging {
		var $sql;
		var $rs;
		var $numrows;
		var $limit;
		var $noofpage;
		var $offset;
		var $page;
		var $style;
		var $parameter;
		var $activestyle;
		var $buttonstyle;		

		//Construct of the calsse
		function Paging() {

			//Database parameters
			$this->database = $dbConnection;
			$this->offset = 0;
			$this->page = 1;

			// texts of pagination by default
			$this->Go = "Go";
			$this->GoToPage = "Go to the page";
			$this->textBack = "Previous";
			$this->textNext = "Next";
			
			$this->activestyle 	= "active";
			$this->style		= "page_style";

			return;
		}

		//set DB
		function setDb($database) {
			$this->database = $database;
			return;
		}

		//set connection to database
		function setDbConnect($databaseConnectopn) {
			$this->database = $databaseConnectopn;
			return;
		}

		//execution of pagination
		function go($query) {
			$this->sql = $query;
			$this->numrows = $this->database->DB_num_rows($this->sql);
			
			return;
		}
		
		//executer the limitation of SQL query
		function resLimit() {
			$req = $this->sql." LIMIT $this->offset,$this->limit";
			$res = $this->database->DB_res($req,'');
			return $res;
		}

		function getNumRows() {
			return $this->numrows;
		}
		function setLimit($no) {
			$this->limit=$no;
		}
		function getLimit() {
			return $this->limit;
		}
		function getNoOfPages() {
			return ceil($this->noofpage=($this->getNumRows()/$this->getLimit()));
		}
		
		function getPageTable() {
			$str="";
			$str=$str."<table width='100%' border='0'><tr>";
			$str=$str."<td align='left' valign='top'>";
			if($this->getPage()>1) {
				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>".$this->textBack."</a>&nbsp;";
			}
			for($i=1;$i<=$this->getNoOfPages();$i++) {
				if($i==$this->getPage()) {
					$str=$str."<span class='".$this->getActiveStyle()."'>".$i."&nbsp;</span>";
				}
				else {
					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;";
				}
			}
			if($this->getPage()<$this->getNoOfPages()) {
				$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='".$this->getStyle()."'>".$this->textNext."</a>";
			}
			$str=$str."</td>";
			$str=$str."<td align='right' valign='top' class='".$this->getStyle()."'>";
			$str=$str."<form name='frmPage' action='".$_SERVER['PHP_SELF']."' method='get'>";
			$str=$str.$this->GoToPage."&nbsp;<input type='text' name='page' size='3' class='".$this->getStyle()."'>&nbsp;";
			$param=split("[& =]",$this->getParameter());
			for($i=2;$i<=count($param);$i=$i+2) {
				$str=$str."<input type='hidden' name='".$param[$i-1]."' value='".$param[$i]."'>";
			}
			$str=$str."<input type='submit' name='btnGo' value='".$this->Go."' class='".$this->getButtonStyle()."'>";
			$str=$str."</form>";
			$str=$str."</td>";
			$str=$str."</tr></table>";
			print $str;
		}
		
		function getPageList() {
			$str="";

			if($this->getNoOfPages()!=1){
			//If the number of pages est greater than 1
				$str=$str."<div class=\"pagination_div\">"."\n";
				$str=$str."<ul class=\"pagination\">"."\n";
				
				if($this->getPage()>1) {
					$str=$str."<li>"."\n";
					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()-1).$this->getParameter()."' class='".$this->getStyle()."'>".$this->textBack."</a>&nbsp;"."\n";
					$str=$str."</li>"."\n";
				}
				for($i=1;$i<=$this->getNoOfPages();$i++) {
					if($i==$this->getPage()) {
						$str=$str."<li class='".$this->getActiveStyle()."'>"."\n";
						$str=$str."<span>".$i."&nbsp;</span>"."\n";
						$str=$str."</li>"."\n";
					}
					else {
						$str=$str."<li>"."\n";
						$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".$i.$this->getParameter()."' class='".$this->getStyle()."'>".$i."</a>&nbsp;"."\n";
						$str=$str."</li>"."\n";
					}
				}
				if($this->getPage()<$this->getNoOfPages()) {
					$str=$str."<li>"."\n";
					$str=$str."<a href='".$_SERVER['PHP_SELF']."?page=".($this->getPage()+1).$this->getParameter()."' class='".$this->getStyle()."'>".$this->textNext."</a>"."\n";
					$str=$str."</li>"."\n";
				}
				$str=$str."</ul>"."\n";
				$str=$str."</div>"."\n";
			}
			print $str;
		}		
		
		function getOffset($page) {
			if($page>$this->getNoOfPages()) {
				$page=$this->getNoOfPages();
			}
			if($page=="") {
				$this->page=1;
				$page=1;
			}
			else {
				$this->page=$page;
			}
			if($page=="1") {
				$this->offset=0;
				return $this->offset;
			}
			else {
				for($i=2;$i<=$page;$i++) {
					$this->offset=$this->offset+$this->getLimit();
				}
				return $this->offset;
			}
		}
		function getPage() {
			return $this->page;
		}
		function setStyle($style) {
			$this->style=$style;
		}
		function getStyle() {
			return $this->style;
		}
		function setActiveStyle($style) {
			$this->activestyle=$style;
		}
		function getActiveStyle() {
			return $this->activestyle;
		}
		function setButtonStyle($style) {
			$this->buttonstyle=$style;
		}
		function getButtonStyle() {
			return $this->buttonstyle;
		}
		function setParameter($parameter) {
			$this->parameter=$parameter;
		}
		function getParameter() {
			return $this->parameter;
		}

}
?>