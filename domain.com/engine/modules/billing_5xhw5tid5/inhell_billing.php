<?php

define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', '../../..' );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

error_reporting(0);

require_once ENGINE_DIR.'/data/unitpay.php';
include 'lib/UnitPayModel.php';
include 'lib/UnitPay.php';

class UnitPayEvent
{
    public function check($params)
    {    
         $unitPayModel = UnitPayModel::getInstance();         
         
         if ($unitPayModel->getAccountByName(((($params['account']+11)/7)-1203)))
         {
            return true;      
         }  
         return 'Character not found';
    }

    public function pay($params)
    {
         $unitPayModel = UnitPayModel::getInstance();

          if ($params['sum'] >= 480)     $countItems = 31536000; 
          elseif ($params['sum'] >= 240) $countItems = 15724800;
          elseif ($params['sum'] >= 120)  $countItems = 7862400;
          elseif ($params['sum'] >= 60)   $countItems = 2592000;
       
         $unitPayModel->donateForAccount(((($params['account']+11)/7)-1203), $countItems);
    }
}

$payment = new UnitPay(
    new UnitPayEvent()
);

echo $payment->getResult();
