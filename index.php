<?php

// Include packages and files for PHPMailer and SMTP protocol

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Initialize PHP mailer, configure to use SMTP protocol and add credentials

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 1;
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "<paste your gmail account here>";
$mail->Password   = "<paste Google password or app password here>";


$success = "";
$error = "";
$name = $message = $email = "";
$errors = array('name' => '', 'email' => '', 'message' => '');

if (isset($_POST["submit"])) {
    if (empty(trim($_POST["name"]))) {
        $errors['name'] = "Your name is required";
    } else {
        $name = SanitizeString($_POST["name"]);
        if (!preg_match('/^[a-zA-Z\s]{6,50}$/', $name)) {
            $errors['name'] = "Only letters and spaces allowed";
        }
    }

    if (empty(trim($_POST["email"]))) {
        $errors["email"] = "Your email is required";
    } else {
        $email = SanitizeString($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Pls give a proper email address";
        }
    }

    if (empty(trim($_POST["message"]))) {
        $errors["message"] = "Please type your message";
    } else {
        $message = SanitizeString($_POST["message"]);
        if (!preg_match("/^[a-zA-Z\d\s]+$/", $message)) {
            $errors["message"] = "Only letters, spaces and maybe numbers allowed";
        }
    }

    if (array_filter($errors)) {
    } else {
        try {

            $mail->setFrom('<paste your gmail account here>', '<paste your name here>');

            $mail->addAddress($email, $name);

            $mail->Subject = 'Build a contact form with PHP';

            $mail->Body = $message;

            // send mail

            $mail->send();

            // empty users input

            $name = $message = $email = "";

            $success = "Message sent successfully";
        } catch (Exception $e) {

            // echo $e->errorMessage(); use for testing & debugging purposes
            $error = "Sorry message could not send, try again";
        } catch (Exception $e) {

            // echo $e->getMessage(); use for testing & debugging purposes
            $error = "Sorry message could not send, try again";
        }
    }
}

function SanitizeString($var)
{
    $var = strip_tags($var);
    $var = htmlentities($var);
    return stripslashes($var);
}

?>

<!DOCTYPE html>
<html>

<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">


    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        .error {
            color: white;
            background-color: crimson;
            border-radius: 7px;
            text-align: center;
        }

        .success {
            background-color: darkgreen;
            color: white;
            border-radius: 7px;
            text-align: center;
        }
    </style>
</head>


<body>

    <main class="container">
        <h4>Contact</h4>

        <hr>

        <div class="row">
            <div class="col s12 l5">
                <span class="bold">Get in touch with me via email</span>
            </div>
            <div class="col s12 l5 offset-l2">

                <div class="success"><?php echo $success ?></div>
                <div class="error"><?php echo $error ?></div>
                <form action="index.php" method="post">

                    <div class="input-field">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name) ?>">

                        <label for="name">Your name*</label>
                        <div class="error"><?php echo $errors["name"] ?></div>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">email</i>
                        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email) ?>">
                        <label for="email">Your email*</label>
                        <div class="error"><?php echo $errors["email"] ?></div>
                    </div>
                    <div class="input-field">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea id="message" class="materialize-textarea" name="message"><?php echo htmlspecialchars($message) ?></textarea>
                        <label for="message">Your Message*</label>
                        <div class="error"><?php echo $errors["message"] ?></div>
                    </div>
                    <div class="input-field center">
                        <input type="submit" class="btn-large z-depth-0" name="submit" id="submit" value="Send"></input>
                    </div>

                </form>


            </div>
        </div>


    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>

</html>