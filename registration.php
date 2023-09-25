<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <style>
	    body{
            background: url('<?php $a = array('hatter3.jpg'); echo $a [array_rand($a)];?>');
        }
    </style>

    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["repeat_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                array_push($errors,"Minden mezőt tölts ki!");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email nem létezik!");
            }
            if (strlen($password)<8) {
                array_push($errors,"A jelszó nem lehet kevesebb mint 8 karakter!");
            }
            if ($password!==$passwordRepeat) {
                array_push($errors,"Nem egyezik meg a jelszó!");
            }

            if (count($errors)>0) {
                foreach ($errors as  $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }else{
                require_once "database.php";
                $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt,"sss",$fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>Sikeres regisztrálás!</div>";
                } else {
                    die("Hiba történt!:(");
                }
            }
        }
        ?>
        <form action="index.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Teljes Név:">
            </div>
            <div class="form-group">
                <input type="emamil" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Jelszó:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Jelszó ismétlése:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Regisztráció" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
