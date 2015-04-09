<?php
namespace HRNSales;
use HRNSales\connect as connect;
use HRNSales\Connection as connection;
require_once('SYS/aaa.php');
require_once('connect.php');

 abstract class config extends connect\aaa{

	protected $dbc;
	protected $host = connect\aaa::HR_HOST;
    protected $user = connect\aaa::HR_USER; 
    protected $password = connect\aaa::HR_PASSWORD;
    protected $database = connect\aaa::HR_DATABASE;
	protected $port = 3306;
	protected $charset = 'utf8';
	protected $pdo;
	
  public function __construct() {
	  
	 $pdo = connection\PDOConnection::instance();

     $this->pdo = $pdo->getConnection( 'mysql:host='.$this->host.';dbname='.$this->database, $this->user, $this->password );
	  
	 
  }


}


?>