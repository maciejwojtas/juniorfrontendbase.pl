<?php
session_start();
if(!isset($_SESSION['logged']))
   {
    header('Location: index.php');
    exit();
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
    <td>Premium days left:</td> <td>$_SESSION[premium] </td>
    </tr>
    
    </table>
    
    
END;
    
    
    ?>
</body>
</html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">