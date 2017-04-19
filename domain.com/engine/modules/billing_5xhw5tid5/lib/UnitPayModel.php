<?php

class UnitPayModel
{
    private $mysqli;

    static function getInstance()
    {
        return new self();
    }

    private function __construct()
    {
        $this->mysqli = @new mysqli (
            Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME
        );
        /* проверка подключения */
        if (mysqli_connect_errno()) {
            throw new Exception('Не удалось подключиться к бд');
        }
    }

    function createPayment($unitpayId, $account, $sum)
    {
        $query = '
            INSERT INTO
                xhw7tid4_unitpay_payments (unitpayId, account, sum, dateCreate, status)
            VALUES
                (
                    "'.$this->mysqli->real_escape_string($unitpayId).'",
                    "'.$this->mysqli->real_escape_string($account).'",
                    "'.$this->mysqli->real_escape_string($sum).'",
                    NOW(),
                    0
                )
        ';

        return $this->mysqli->query($query);
    }

    function getPaymentByUnitpayId($unitpayId)
    {
        $query = '
                SELECT * FROM
                    xhw7tid4_unitpay_payments
                WHERE
                    unitpayId = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
            
        $result = $this->mysqli->query($query);
        return $result->fetch_object();
    }

    function confirmPaymentByUnitpayId($unitpayId)
    {
        $query = '
                UPDATE
                    xhw7tid4_unitpay_payments
                SET
                    status = 1,
                    dateComplete = NOW()
                WHERE
                    unitpayId = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
        return $this->mysqli->query($query);
    }
    
    function getAccountByName($account)
    {
        $sql = "
            SELECT
                name
            FROM
               xhw7tid4_users
            WHERE
               user_id = '".$this->mysqli->real_escape_string($account)."'
            LIMIT 1
         ";
         
        $result = $this->mysqli
            ->query($sql);
            
        return $result->fetch_object();
    }
    
    function donateForAccount($account, $count_time)
    {
         $sql = "
            SELECT
                user_group, time_limit
            FROM
               xhw7tid4_users
            WHERE
               user_id = '".$this->mysqli->real_escape_string($account)."'
            LIMIT 1
         ";
         
         $row = mysqli_fetch_assoc($this->mysqli->query($sql));
         $result_ug =  intval($row['user_group']);
         $result_tl =  intval($row['time_limit']);
          
         $time = time();
                 
         if ($result_ug != '7') $query_ug = ", user_group = '7'";
         else $query_ug = "";
         
         if ($result_tl <= $time) $query_tl = $time + $count_time;
         elseif ($result_tl > $time) $query_tl = $result_tl + $count_time;

       $query = "
            UPDATE
                xhw7tid4_users
            SET
                time_limit = ".$query_tl.$query_ug."
            WHERE
                user_id = '".$this->mysqli->real_escape_string($account)."'
        ";
        
        return $this->mysqli->query($query);

    }
}