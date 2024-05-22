<?php
session_start();

require_once ("dbConnection.php");
$query = new MongoDB\Driver\Query([]);
$cursor = $client->executeQuery('proiect.users', $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $isValid = false;
    foreach ($cursor as $document) {
        if ($document->email == $email) {
            if (password_verify($password, $document->password)) {
                $isValid = true;
                $user = $document;
                break;
            } else {
                echo "Invalid password.";
            }
        }
    }

    if ($isValid) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user'] = $user->first_name . ' ' . $user->last_name;

        if (isset($_POST['remember'])) {
            setcookie('email', $email, time() + (86400 * 30), "/");
            setcookie('password', $password, time() + (86400 * 30), "/");
        } else {
            setcookie('email', '', time() - 3600, "/");
            setcookie('password', '', time() - 3600, "/");
        }

        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid email or password.';
        echo $error;
    }

   
} else {
    if (isset ($_COOKIE['email'])) {
        $email = $_COOKIE['email'];
        $password = $_COOKIE['password'];
        $checked = true;
    } else {
        $email = '';
        $password = '';
        $checked = false;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="login.php" method="post">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp" name="email"
                                                placeholder="Enter Email Address..."
                                                value="<?php echo htmlspecialchars($email); ?>">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                class="form-control form-control-user" id="exampleInputPassword"
                                                placeholder="Password" value="<?php
                                                echo htmlspecialchars($password) ?>">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck"
                                                    name="remember" checked=<?php echo $checked ?>>
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <input id="submit" type="submit" class="btn btn-primary btn-user btn-block"
                                            placeholder="Login" />
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>