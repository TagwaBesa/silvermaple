

<?php

     include 'config1.php';
     if(isset($_POST['submit']))
     {
         $nm=$_POST['name'];
       
        $pass=$_POST['password'];
        if( isset($nm) && isset($pass))
      {
        if(!empty($nm) && !empty($pass) )
        {


          
    
          $stmt = $conn->prepare("SELECT sid, name FROM student WHERE name= ? AND password=?"); 
            $stmt->execute(array($nm,$pass));

             
             $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              // print_r($result);
            if(count($result))
            {
            
            $sid = $result[0]['sid'];
						$name = $result[0]['name'];
						session_start();
            // Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
            
                $_SESSION['islogin'] ="1";
								$_SESSION['sid'] = $sid;
								$_SESSION['name'] = $name;
            
							header("location:../index.php?page=dashboard");
            }
            else
            {
               header("location:../index.php?invalid=y");
            }
            
            
          }else
          {
             header("location:../index.php?invalid=y");
          }
        }
      }

?>