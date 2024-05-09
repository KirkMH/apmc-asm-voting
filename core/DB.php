<?php
date_default_timezone_set('Asia/Manila');
define('DB_CHAR', 'utf8');
echo "Connecting database... "
class DB
{

    private static $_instance = null;
    private $_pdo,
            $_query, 
            $_error = false,
            $_results, 
            $_count = 0,
            $_lastId = 0,
            $_errorInfo;
    
    protected static $instance = null;

    function __construct() {
        try{
            $this->_pdo = new PDO('mysql:host=' . constant('DB_HOST') . ';dbname='. constant('DB_NAME') , constant('DB_USER') , constant('DB_PASSWORD'));

        }catch(PDOException $e){
            echo $e->getMessage(); 
            die($e->getMessage());
         }


    }

    protected function __clone() {}

    public static function instance()
    {
        try {
            if (self::$instance === null)
            {
                $opt  = array(
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => FALSE,
                );
                $dsn = 'mysql:host='.constant('DB_HOST').';dbname='.constant('DB_NAME').';charset='.constant('DB_CHARSET');
                self::$instance = new PDO($dsn, constant('DB_USER'), constant('DB_PASSWORD'), $opt);
            }
            return self::$instance;
        } catch (Exception $e) {
            echo $e->getMessage(); 
            die($e->getMessage());
            return null;
        }
    }


    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = [])
    {
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }


    public static function insert($table, array $dataArray){

    $data   = array();

    foreach ($dataArray as $key => $value) {
      $data[] = $key."='".$value."'";
    }

      $data = implode(', ', $data);


      $sql = DB::run("INSERT INTO {$table} SET {$data}");
      return $sql;

    }

    public static function update($table, $filter, $id, array $dataArray){

        $data   = array();

        foreach ($dataArray as $key => $value) {
          $data[] = $key."='".$value."'";
        }

      $data = implode(', ', $data);
      $sql = DB::run("UPDATE {$table} SET {$data} WHERE {$filter} = {$id}");
      return $sql;

    }

    public static function delete($table, $filter, $id){

      $sql = DB::run("DELETE FROM {$table} WHERE {$filter} = {$id}");
      return $sql;

    }


    public static function select_all($data, $table, $where = ''){
      
      $sql = DB::run("SELECT {$data} FROM {$table} WHERE {$where}");

      return $sql;
    }


    public static function select($data, $table, $where = ''){
      
      $sql = DB::run("SELECT {$data} FROM {$table} WHERE {$where}")->fetch();

      return $sql;
    }

    public static function select_one($field, $table, $where = ''){
      
      $sql = DB::run("SELECT $field FROM {$table} WHERE $where")->fetchColumn(); 

      return $sql;
    }

    public static function toBill($amount){
      
      $data = number_format($amount,2);

      return $data;
    }


    public static function isWhole_number($value){

                if($value > 0){

                    $numpart = explode(".", $value); 

                         if($numpart[1] > 0){
                           $value = $value;
                         }
                         else
                         {
                           $value = $numpart[0];
                        }

                }else{

                     $value = 0;
                 }

            return $value;
    }

    public static function checkCount($table, $where = ''){
        

         $sql = DB::run("SELECT COUNT(*) FROM {$table} WHERE {$where}")->fetchColumn();  /* Check the number of rows that match the SELECT statement */
            
          if ($sql > 0){
            return true;
          }else{
            return false;
        }

    }

    public static function Unique($table, $condition, $args = []){

        $query = "SELECT * FROM {$table} WHERE {$condition}";
        $same = self::instance()->prepare($query);
        $same->execute($args);
        if($count = $same->rowCount()){
          return false;  
        }else{
          return true;
        }
    }

public static function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}
    public static function lastID(){
        $maxId = self::instance()->lastInsertId();
        return $maxId;
    }


    //use this for saving trails
    public function saveTrail($action, $user_no) {
        $time = date("Y-m-d H:i:s");
        $data = array(
            'action_date' => $time,
            'action' => $action, 
            'recordedFrom' => 'Back End', 
            'user_no' => $user_no
            );
        $this->insert('tbl_audit_trail', $data);
  }

  //users log  
   public static function userlog($date, $time, $action, $user, $activityFrom)
    { 
        $data = array(
          'date' => $date,
          'time' => $time,
          'action' => $action,
          'user_id' => $user,
          'activityFrom'=> $activityFrom
          );

        $sql = DB::insert('tbl_userlog', $data);
    }

   public static function logTrail($action, $table, $spec){
        $newData = "";
        if (is_array($spec)) {
          foreach ($spec  as $postedKey => $postedValue) {
             $newData .= $postedKey . ' = ' .  $postedValue . ', ';
          }
        }
        if (is_string($spec)) {
            $newData = $spec;
        }

        $data = array(
            'user' => $_SESSION['user_no'],
            'action' => $action,
            'table_name' => $table,
            'fieldspec' => $newData,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s')
        );

        $db = DB::insert('tbl_trail', $data);
    }


    // checks if the password matches the hash generated by phpass (WP)
    // @password is the one inputted by the user
    // @field is the password field in the table
    // @table is the db table containing the username/password
    // @where is for the filtering, esp. username = inputted_un
    // @returns the record of a valid account; false if not
    public static function check_password($password, $field, $table, $where) {
      require_once 'class-phpass.php';

      $passnya = DB::select_one($field, $table, $where);
      $hasher = new PasswordHash(8, TRUE);
      // compare plain password with hashed password
      if ($hasher->CheckPassword( $password, $passnya )) {
        return DB::select("*", $table, $where);
      }
      else {
        return false;
      }
    }

    // generates phpass hashed password
    // @password the password to be hashed
    // @returns the hashed password
    public static function hash_password($password) {
      require_once 'class-phpass.php';
      $hasher = new PasswordHash(8, TRUE);
      return $hasher->HashPassword($password);
    }

}