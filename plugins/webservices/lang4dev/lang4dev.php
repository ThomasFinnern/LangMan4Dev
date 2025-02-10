<?php


use Joomla\CMS\Plugin\CMSPlugin;
//use Joomla\CMS\Router\ApiRouter;
use Joomla\CMS\Router\ApiRouter;

class PlgWebservicesLang4Dev extends CMSPlugin
{
    public function onBeforeApiRoute(&$router)
    {
        $router->createCRUDRoutes(
			'v1/lang4dev', 
			'lang4dev', 
			['component' => 'com_lang4dev']);
    }
	
}






