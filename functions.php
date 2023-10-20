<?php

$hostname = "https://fbsit.net/";

class DB{
    public $con;
    private $dbHost     = "localhost";
    private $dbUsername = "trixum_fxtrqygxtradeinadmin";
    private $dbPassword = "oD*B6&bbmBNX";
    private $dbName     = "trixum_fxtrqygx";

    public function __construct(){
      try {
        $this->con = new PDO("mysql:host=".$this->dbHost.";dbname=".$this->dbName, $this->dbUsername, $this->dbPassword);
        // set the PDO error mode to exception
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
      }
    }
  }

class crud extends DB{

  // Show All Record
	public function showRecord($table,$columns,$limit="",$order=""){
		try {
			$cols = "";
			foreach ($columns as $columnames) {
			$cols .= $columnames.", ";
			}
			$cols = substr($cols, 0, -2);
			$sql = "SELECT ".$cols." FROM ".$table." ".$order." ".$limit;
			$query = $this->con->prepare($sql);
			$query->execute();
			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
			$result = $query->fetchAll();
			if ($result) {
				return $result;
			}
		} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
	}
	}
	// Show All Record

  // Select Record
  public function selectRecord($table,$columns,$where,$type="one",$limit="",$order=""){
try {
	$keys = array_keys($where);
	$values = array_values($where);
	$allColumns = "";
	$whereKeys = "";

	foreach ($columns as $name) {
		$allColumns .= $name.", ";
	}
	$allColumns .= substr($allColumns, 0, -2);


	for($i=0; $i < count($where); ++$i) {
		$whereKeys  .= $keys[$i]." = ?  AND ";
	}
	$whereKeys = substr($whereKeys, 0, -4);

	$limit = (!empty($limit)) ? $limit : " " ;
	$sql = " SELECT ".$allColumns." FROM ".$table." WHERE ".$whereKeys." ".$order." ".$limit;
	$query = $this->con->prepare($sql);
	$query->execute($values);
	$result = $query->setFetchMode(PDO::FETCH_ASSOC);
	// Fetch one row
	if ($type == "one") {
		if ($result = $query->fetch()) {
			return $result;
		}
	}
	// Fetch all rows
	if ($type  == "all") {
		if ($result = $query->fetchAll()) {
			return $result;
		}
	}
} catch(PDOException $e) {
echo "Error: " . $e->getMessage();
}
  }

  //Insert Record
	public function insertRecord($table,$fields){
		try {
			$keys = array_keys($fields);
			$values = array_values($fields);
			$allValues = "";
			for($i=0; $i < count($fields); ++$i) {
				 $allValues .= "?,";
			}
			$allValues = substr($allValues, 0, -1);
			 $sql = "INSERT INTO ".$table." (".implode(",", array_keys($fields)).") VALUES (".$allValues.")";
			$query = $this->con->prepare($sql);
			$query->execute($values);
			if ($query) {
				 $lastId = $this->con->lastInsertId();
					return $lastId;
			}
		}
		catch(PDOException $e) {
		echo "Error: " . $e->getMessage();
		}
 }

 /* Delete Record */
 public function deleteRecord($table,$where){
	 try {
		 $keys = array_keys($where);
		 $values = array_values($where);
		 $whereKeys = "";

		 for($i=0; $i < count($where); ++$i) {
			 $whereKeys  .= $keys[$i]." = ?  AND ";
		 }
		 $whereKeys = substr($whereKeys, 0, -4);

	  $sql = "DELETE FROM ".$table." WHERE ".$whereKeys;
		$query = $this->con->prepare($sql);
		$query->execute($values);
		if ($query) {
		return true;
		}
	 } catch(PDOException $e) {
	 echo "Error: " . $e->getMessage();
	 }
 }
 /* Delete Record */


 /* Update Record */
 public function updateRecord($table,$where,$fields){
	 try {
		 $whereKeys = array_keys($where);
	   $whereValues = array_values($where);

	   $fieldsKeys = array_keys($fields);
	   $fieldsValues = array_values($fields);

	   $condition = "";
	   $data = "";

	   for($i=0; $i < count($fields); ++$i) {
	     $data  .= $fieldsKeys[$i]." = ?, ";
	   }
	   $data = substr($data, 0, -2);

	   for($i=0; $i < count($where); ++$i) {
	     $condition  .= $whereKeys[$i]." = ?  AND ";
	   }
	   $condition = substr($condition, 0, -4);

	   $arrayMerger = array_merge($fieldsValues,$whereValues);

	   $sql = " UPDATE ".$table." SET ".$data." WHERE ".$condition;
	   $query = $this->con->prepare($sql);
	   $query->execute($arrayMerger);
	  if ($query) {
	    return true;
	  }
	 } catch(PDOException $e) {
	 echo "Error: " . $e->getMessage();
	 }
 }
 /* Update Record */

 /* Count Record */
 public function countRecord($table,$colName){
	 try {
		 $sql = "SELECT COUNT({$colName}) AS {$colName} FROM {$table}";
	   $query = $this->con->prepare($sql);
	   $query->execute();
	   $result = $query->setFetchMode(PDO::FETCH_ASSOC);
	   if ($result = $query->fetch()) {
	     $count = $result["{$colName}"];
	   return $count;
	   }
	 } catch(PDOException $e) {
	 echo "Error: " . $e->getMessage();
	 }
 }
 /* Count Record */


 /* Count Record with condition*/
 public function countcRecord($table,$colName,$where){
	 try {
		 $keys = array_keys($where);
	   $values = array_values($where);
	   $whereKeys = "";

	   for($i=0; $i < count($where); ++$i) {
	     $whereKeys  .= $keys[$i]." = ?  AND ";
	   }
	   $whereKeys = substr($whereKeys, 0, -4);
	   $sql = "SELECT COUNT({$colName}) AS {$colName} FROM {$table} WHERE ".$whereKeys;
	   $query = $this->con->prepare($sql);
	   $query->execute($values);
	   $result = $query->setFetchMode(PDO::FETCH_ASSOC);
	   if ($result = $query->fetch()) {
	     $count = $result["{$colName}"];
	     return $count;
	   }
	 } catch(PDOException $e) {
	 echo "Error: " . $e->getMessage();
	 }
  }
 /* Count Record with condition*/


 /* Sum Record with condition*/
 public function sumRecord($table,$colName,$where){
  try {
    $keys = array_keys($where);
    $values = array_values($where);
    $whereKeys = "";

    for($i=0; $i < count($where); ++$i) {
      $whereKeys  .= $keys[$i]." = ?  AND ";
    }
    $whereKeys = substr($whereKeys, 0, -4);
    $sql = "SELECT SUM({$colName}) AS {$colName} FROM {$table} WHERE ".$whereKeys;
    $query = $this->con->prepare($sql);
    $query->execute($values);
    $result = $query->setFetchMode(PDO::FETCH_ASSOC);
    if ($result = $query->fetch()) {
      $count = $result["{$colName}"];
      return $count;
    }
  } catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
  }
  }
 /* Sum Record with condition*/

  //multiple images
 public function multipleImages($table,$name,$loc,$uid,$pid){
	 try {
		 for ($i=0; $i < count($_FILES[$name]['name']); $i++) {
		 $fileName = $_FILES[$name]['name'][$i];
		 $fileSize = $_FILES[$name]['size'][$i];
		 $fileTmp = $_FILES[$name]['tmp_name'][$i];
		 $fileType = $_FILES[$name]['type'][$i];
		 $tmp = explode('/', $fileType);
		 $file_extension = end($tmp);
		 $extensions = array("jpeg","jpg","png");
		 $newName = time(). "-".basename($fileName);
		 $target = "../../uploads/".$loc."/".$newName;

		 if (in_array($file_extension,$extensions) === false) {
		   errMsg("Image format must be jpeg, jpg or png");die();
		 } else {
		 if ($fileSize > 2097152) {
		   errMsg("Image size must be 2 mb or lower");die();
		 } else {
		 	$sql = "INSERT INTO {$table} (pid, uid, name) VALUES (?, ?, ?)";
		   $query = $this->con->prepare($sql);
		   $query->execute([$pid,$uid,$newName]);
		      if ($query) {
		        move_uploaded_file($fileTmp,$target);
		      }
		    }
		    }
		    }
	 } catch(PDOException $e) {
	 echo "Error: " . $e->getMessage();
	 }
    }
    //multiple images


    // Search Record
    public function searchRecord($table,$columns,$where,$limit="",$order=""){
  try {
  	$keys = array_keys($where);
  	$values = array_values($where);
  	$allColumns = "";
  	$whereKeys = "";

  	foreach ($columns as $name) {
  		$allColumns .= $name.", ";
  	}
  	$allColumns .= substr($allColumns, 0, -2);

  	for($i=0; $i < count($where); ++$i) {
  		$whereKeys  .= $keys[$i]." LIKE '%' ? '%' OR ";
  	}
  	$whereKeys = substr($whereKeys, 0, -4);

  	$limit = (!empty($limit)) ? $limit : " " ;
    $sql = " SELECT ".$allColumns." FROM ".$table." WHERE ".$whereKeys." ".$order." ".$limit;
  	$query = $this->con->prepare($sql);
  	$query->execute($values);
  	$result = $query->setFetchMode(PDO::FETCH_ASSOC);
  		if ($result = $query->fetchAll()) {
  			return $result;
  		}
  } catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
  }
    }
    // Search Record

    // Show All messages
  	public function chatMessages($table,$columns,$sender,$sendVal,$reciever,$recieveVal){
  		try {
  			$cols = "";
  			foreach ($columns as $columnames) {
  			$cols .= $columnames.", ";
  			}
  			$cols = substr($cols, 0, -2);
  			$sql = "SELECT {$cols} FROM {$table} Where ({$sender} = ? AND {$reciever} = ?) OR ({$sender} = ? AND {$reciever} = ?) ORDER BY 1 ASC";
  			$query = $this->con->prepare($sql);
  			$query->execute([$sendVal,$recieveVal,$recieveVal,$sendVal]);
  			$result = $query->setFetchMode(PDO::FETCH_ASSOC);
  			$result = $query->fetchAll();
  			if ($result) {
  				return $result;
  			}
  		} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
  	}
  	}
  	// Show All messages

    public function successMsg($value=''){
      echo '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>'.$value.'</strong></div>';
    }

    public  function errMsg($value=''){
    echo '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>'.$value.'</strong></div>';
    }
}
class disconnectDB extends DB{
	public function disconnect(){
		$this->con = null;
	}
}

		$db = new DB;
		$select = new crud;
		$insert = new crud;
		$delete = new crud;
		$update = new crud;
		$count = new crud;
    $message = new crud;
    $disDB = new disconnectDB;

 ?>
