<?php
defined("_JEXEC") or die("Restricted access");
require('controller.php');

class plgSystemDavidix_japi extends JPlugin
{
	
	 public function onAfterInitialise()
	 {
		 	$app 	=	JFactory::getApplication();
			$opt 	=	$app->input->get('opt', '', 'string');
			$Rest	=	new dixRest();
			
			switch($opt)
			{
				case "items" :
				$Rest->items();
				break;
				
				case "login" :
				$Rest->login();
				break;
				
			}
			
			
		
	 }
	 

	 
	
   
}