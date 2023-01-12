<?php
    require_once("config.php");

    $output = '';

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

            $password = password_hash($password, PASSWORD_DEFAULT);

            function getToken($len=32){
                return substr(mad5(openssl_random_pseudo_bytes(20)), -$len);
            }
            $token = getToken(10);

            $insert = $con->prepare("INSERT INTO users SET email=:email, password=:password,token=:token");
            $insert->execute(array(
                ':email' => $email,
                ':password' => $password,
                ':token' => $token));
            
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
        <form action="/" method="post">
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
        </form> 
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>

