<?php
session_start();
if(!isset($_SESSION['logged']))
   {
    header('Location: index.php');
    exit();
}
   if(isset($_POST['site']))
{
     $updateProfile = new updateProfile;
    $updateProfile->init();

}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>profile</title>
</head>

<body>
<?php

echo<<<END
    Welcome $_SESSION[user] ! <a href="logout.php">Logout</a>
    <br>
    <table>
    <tr>
    <td>Email:</td> <td>$_SESSION[email] </td>
    </tr>
    <tr>
    <td>Your domain:</td> <td><a href='$_SESSION[site]'>$_SESSION[site]<a> </td>
    </tr>
     <tr>
    <td>Premium days left:</td> <td>$_SESSION[premium] </td>
    </tr>
    
    </table>
    
    
END;
    class updateProfile
    {
        public function init(){
        $this->cacheDom();
        $this->pushSite($this->site);
        }
        public function cacheDom(){
    $this->site=$_POST['site'];
        }
        
        public function pushSite($item){
        $item=filter_var($item, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED);
        require "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);
        
        try
        {
            $connection = new mysqli($host, $db_user, $db_password, $db_name);
if($connection->connect_errno!=0)
{
throw new Exception(mysqli_connect_errno());
}else
{
    $result = $connection->query("UPDATE uzytkownicy
SET site = '$item' WHERE user = '$_SESSION[user]';");
    $result2 = $connection->query("SELECT*FROM uzytkownicy WHERE user ='$_SESSION[user]' ;");
    if($result2){
        $row = $result2->fetch_assoc();
        $_SESSION['site'] = $row['site'];
    }
        
    if(!$result) throw new Exception($connection->error);
     


     
        
    $connection->close();

}
}
        catch(Exception $e)
        {
         echo "server error";   
        }
    }
    }
 
    ?>
    <form method="post" >
       <label>Set Your site's URL:</label><br>
       <input type="text" name="site"><br>
       <input type="submit" value="update your profile!">
    </form>
</body>
</html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">