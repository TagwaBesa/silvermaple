

<?php

     include 'config1.php';
     if(isset($_POST['submit']))
     {
         $nm=$_POST['name'];
       
        $password=$_POST['password'];
        if( isset($nm) && isset($password))
      {
        if(!empty($nm) && !empty($password) )
        {


          
    
          $stmt = $conn->prepare("SELECT id, name FROM admin WHERE name= ? AND password=?"); 
            $stmt->execute(array($nm,$password));

             
             $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
              // print_r($result);
            if(count($result))
            {
            
            $id = $result[0]['id'];
						$name = $result[0]['name'];
						session_start();
            // Use $HTTP_SESSION_VARS with PHP 4.0.6 or less
            
                $_SESSION['islogin'] ="1";
								$_SESSION['id'] = $id;
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