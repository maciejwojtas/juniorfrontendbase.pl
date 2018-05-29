<?php
session_start();

if(!isset($_POST['nick'])||!isset($_POST['password']))
{
    header('Location: index.php');
    exit();
}

require_once "connect.php";
$connection = @new mysqli($host, $db_user, $db_password, $db_name);

if($connection->connect_errno!=0)
{
    echo "Error".$connection->connect_errno;
}else
{
    

$nick=$_POST['nick'];
$password=$_POST['password'];
    
$nick = htmlentities($nick, ENT_QUOTES, "UTF-8") ;   

if($result = @$connection->query(
    sprintf("SELECT*FROM uzytkownicy WHERE user ='%s'",
           mysqli_real_escape_string($connection,$nick),
            mysqli_real_escape_string($connection,$password))))
{
    $user_count = $result->num_rows;
    if($user_count>0)
    {
        $row = $result->fetch_assoc();
        if(password_verify($password,$row['pass']))
        {
        $_SESSION['logged']=true;
        
        $_SESSION['id']=$row['id'];
        $_SESSION['user'] = $row['user'];
        $_SESSION['site'] = $row['site'];
        $_SESSION['stone'] = $row['kamien'];
        $_SESSION['grain'] = $row['zboze'];
        $_SESSION['premium'] = $row['dnipremium'];
        $_SESSION['email'] = $row['email'];
unset($_SESSION['error']);

        
        $result->close();
        
        
        header('Location:panel.php');
        }else{

        $_SESSION['error']='<span style="color:red">Incorrect login or password !</span>';
        header('Location: index.php');
    }
    }else{
        $_SESSION['error']='<span style="color:red">Incorrect login or password!</span>';
        header('Location: index.php');
    }
}
$connection->close();
}


?>
