<?php

/**
* +-------------------------------------------------------------------------------------------------------------------------------------------------------------+			
* | 		Example to creation of forms 																			                                            |
* +-------------------------------------------------------------------------------------------------------------------------------------------------------------+
* |	$this->openForm("form-name", $this->form_action, $this->form_method);																			            |
* |                                                                                                                                                             |
* |	$this->openFieldset("Informations details");																						                        |
* |	$this->addHTMLForms("title","Title","text",$this->stripDB($this->request["title"]),"","notempty", "The title is required","");								|
* |	$this->addHTMLForms("description","Description","text",$this->stripDB($this->request["description"]),"","notempty", "The description is required","");      |
* |	$this->closeFieldset();																													                    |
* |                                                                                                                                                             |
* |	$this->openFieldset("Date details");																						                                |
* |	$this->addHTMLForms("start","Start","text",$this->stripDB($this->request["start"]),"","date", "The start date is required","");							    |
* |	$this->addHTMLForms("end","End","text",$this->stripDB($this->request["end"]),"","date","The end date is required","");                                      |
* |	$this->addHTMLForms("duration","Duration","text",$this->stripDB($this->request["duration"]),"","number", "The duration is required","");		            |
* |	$this->closeFieldset();																													                    |
* |                                                                                                                                                             |
* |	$this->showSubmit($this->fieldSubmit,$this->labelSubmit);																			                    	|
* |	$this->showReset($this->fieldReset,$this->labelReset);																				            			|
* |	$this->closeForm();																														            		|
* |                                                                                                                                                             |
* +-------------------------------------------------------------------------------------------------------------------------------------------------------------+
*/

/**
 * +------------------------------------------------------------------------------------------------------------------------------------------------------------+			
 * |		Example of form validation processing																								                |
 * +------------------------------------------------------------------------------------------------------------------------------------------------------------+			
 * |  Example to use for function call 																|
 * |  $this->onSucces(array("fonction", array(array("a" => 15, "b" => 15),'if($a == 15){ $c = $a/2;} return ($c*2)+$b;')));									    |
 * +------------------------------------------------------------------------------------------------------------------------------------------------------------+
 * |	Example to use for XML show 																                                                        	|
 * | 	$this->onSucces(array("xmlshow"));																														|
 * |+-----------------------------------------------------------------------------------------------------------------------------------------------------------+
 */
        
?>				