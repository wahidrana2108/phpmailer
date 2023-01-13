<?php
    require_once("config.php");

    $output = '';

    
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require 'vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);


    if($_POST){
        if(isset( $_POST['email'])){
            $email = $_POST['email'];
            if($email == ''){
                unset($email);
            }
        }
        if(isset($_POST['password'])){
            $password = $_POST['password'];
            if($password == ''){
                unset($password);
            }
        }

        if(!empty($email) && !empty($password)){

            $email = $_POST['email'];
            $password = password_hash($password, PASSWORD_DEFAULT);

            function getToken($len=32){
                return substr(md5(openssl_random_pseudo_bytes(20)), -$len);
            }
            $token = getToken(10);

            $insert = $con->prepare("INSERT INTO users SET email=:email, password=:password,token=:token");
            $insert->execute(array(
                ':email' => $email,
                ':password' => $password,
                ':token' => $token));

                
                try {

                    $send_to = $_POST['email'];

                    //Server settings
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'jacquelinechavezkh@gmail.com';                     //SMTP username
                    $mail->Password   = 'password';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                  
                    //Recipients
                    $mail->setFrom('jacquelinechavezkh@gmail.com', 'Bhoot');
                    $mail->addAddress($send_to);     //Add a recipient
                  
                  
                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = 'Here is not the subject';
                    $mail->Body    = 'click the link to activate you account. <a href="http://localhost/phpmailer/verification.php?email=' . $email . '&token=' . $token . '"> Click here</a>';
                  
                    $mail->send();
                    $output =  'Message has been sent';
                  } catch (Exception $e) {
                    $output =  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  }
            
        }

    }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration From</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
  <body>

    <div class="container mt-5">
        <form action="index.php" method="post">
            <h3 class="fw-bolder text-center mb-3">Registration From</h3>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="floatingInput" placeholder="name@example.com" required>
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Register</button>

            <?php echo $output; ?>
        </form> 
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>

