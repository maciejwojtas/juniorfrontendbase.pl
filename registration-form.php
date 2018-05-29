<?php
session_start();

class validation {
    public $validated = true; 

    
   public function init(){
        $this->cacheDom();
        $this->checkLength($this->nick, 'nick', 3, 20);
        $this->checkCharacters($this->nick, 'nick');
        $this->checkEmail($this->email);
        $this->checkLength($this->password1, 'password', 8, 20);
        $this->checkPassword($this->password1, $this->password2);
        $this->checkCheckbox();
        $this->checkCaptcha();
        $this->checkIfExists($this->email, 'email');
        $this->checkIfExists($this->nick, 'user');
       $this->createAccount();





    }
    
   public function cacheDom(){
    $this->nick=$_POST['nick'];
    $this->email=$_POST['email'];
    $this->password1=$_POST['password1'];
    $this->password2=$_POST['password2'];
   
   }
    
    public function checkLength($item, $name, $min, $max){
        if(strlen($item)<$min||strlen($item)>$max)
        {
                $this->validated = false; 
           
$_SESSION[$name.'_error']=$name." must have from ".$min." to ".$max." characters ";
        }else{
            unset($_SESSION[$name.'_error']);
        }

    }
     public function checkCharacters($item, $name){
        if(!ctype_alnum($item))
        {
        $this->validated = false; 
            $_SESSION[$name.'_error']=$name." must contain alphanumeric characters";

           }

    }
     
    public function checkEmail($item){
        $clean_email=filter_var($item, FILTER_SANITIZE_EMAIL);
        
        if(filter_var($item, FILTER_VALIDATE_EMAIL)==false||$clean_email!=$item)
        {
                $this->validated = false; 
$_SESSION['email_error']="Invalid  Email adress";
        }else{
            unset($_SESSION['email_error']);
        }

    }
    public function checkPassword($item, $item2){       
        if($item!=$item2)
        {
                $this->validated = false; 
$_SESSION['password2_error']="typed passwords don't match";
        }else{
            unset($_SESSION['password2_error']);
            $item=password_hash($item, PASSWORD_DEFAULT);
     
        }
return $item;
    }
    //to zbudować, przetłumaczyć na angielski false wyświtla błędy jak ich nie ma
    
    public function checkCheckbox(){
if(!isset($_POST['terms']))
        {
                $this->validated = false; 
$_SESSION['checkbox_error']="You must read and accept terms of use!";
        }else{
            unset($_SESSION['checkbox_error']);
     
        }        
    }
      public function checkCaptcha(){
        $secret_key="6LdJB1wUAAAAAPGqJ9SO4yddYLt23DqSBL5C4lct";
       $secret_key_valid=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key."&response=".$_POST['g-recaptcha-response']);
        
        $captcha_response=json_decode($secret_key_valid);
        if($captcha_response->success==false)
        {
             $this->validated = false; 
$_SESSION['captcha_error']="prove that you're not a robot!";
        }else{
            unset($_SESSION['captcha_error']);
     
        }  

    }
    public function checkIfExists($item, $item_name){
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
    $result = $connection->query("SELECT id FROM uzytkownicy WHERE ".$item_name."='$item'");
    if(!$result) throw new Exception($connection->error);
    
    $record_count = $result->num_rows;
    if($record_count>0)
    {
        if($item_name=='user'){
            $item_name='nick';
        }
             $this->validated = false; 
$_SESSION[$item_name.'_error']="this ".$item_name." is already in use!";
        }      
        
    $connection->close();
}
}
        catch(Exception $e)
        {
         echo "server error";   
        }
    }
    public function displayError($item){
        
        if($this->validated==false&&isset($_SESSION[$item."_error"])){
   echo "<div class='error'>".$_SESSION[$item."_error"]."</div>";
    }
    }
    public function createAccount(){
                require "connect.php";

                    $connection = new mysqli($host, $db_user, $db_password, $db_name);

        if($this->validated==true){
            $password_hash=$this->checkPassword($this->password1, $this->password2);
            if($connection->query("INSERT INTO uzytkownicy VALUES(NULL, '$this->nick', '$password_hash', '$this->email', 100, 100, 100, 14)")){
                echo "success! <a href='index.php'>Sign in!</a>";
            }
            
            
        }
    }
  
}

  
      


if(isset($_POST['nick']))
{
     $form_validation = new validation;
    $form_validation->init();
}


?>
<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join us</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <style>
        .error{
            color: red;
        }
    </style>
</head>

<body>
         <h1>Set up free acc:</h1>
         <p>already have an account? <a href="index.php">Sign in!</a></p>

   <form method="post" >
       <label>Set nickname:</label><br>
       <input type="text" name="nick"><br>
<?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('nick');

}
?>
       <label>Your email:</label><br>
       <input type="text" name="email"><br>
   <?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('email');

}
?>
       <label>Set password</label><br>
       <input type="password" name="password1"><br>
         <?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('password');

}
?>
       <label>Retype password</label><br>
       <input type="password" name="password2"><br>
         <?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('password2');

}
?>
       <label>
       <input type="checkbox" name="terms">
       I accept Terms of Use</label><br>
          <?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('checkbox');

}
?>
       <div class="g-recaptcha" data-sitekey="6LdJB1wUAAAAAL0j5fMmYajzbNSqiLZtJs-dFBNB"></div><br>
                 <?php
       if(isset($_POST['nick']))
{
          $form_validation->displayError('captcha');

}
?>
       <input type="submit" value="Register now!">
       
       
       
   </form>
  
    <br>
</body>
</html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">