<?php 

namespace Screenshot;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 
use Screenshot\Model\Screenshot;
use Screenshot\Model\ScreenshotTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;


 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }
	 
     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Screenshot\Model\ScreenshotTable' =>  function($sm) {
                     $tableGateway = $sm->get('ScreenshotTableGateway');
                     $table = new ScreenshotTable($tableGateway);
                     return $table;
                 },
                 'ScreenshotTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Screenshot());
                     return new TableGateway('screenshot', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }

 }
 
 ?>