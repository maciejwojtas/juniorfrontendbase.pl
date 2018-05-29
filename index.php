<?php
session_start();
if(isset($_SESSION['logged'])&&($_SESSION['logged']==true))
   {
    header('Location: panel.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serch for talents</title>
    
</head>

<body>
         <h1>Login into your Juniorfrontendbase Profile</h1>

   <form method="post" action="login.php">
       <label>login:</label>
               <br>

       <input type="text" name="nick">
        <br>
         <label>Password:</label>
                 <br>

       <input type="password" name="password">
       <br>
       <input type="submit" value="Login!">
       
       
       
   </form>
   <?php
    if(isset($_SESSION['error']))
    {
        echo $_SESSION['error'];
    }
    unset($_SESSION['error']);
    ?>
    <br>
    not registered yet? 
    <a href="registration-form.php">Set up free acc!</a>
</body>
</html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">