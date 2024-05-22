<?php
require_once ("dbConnection.php");
$bulk = new MongoDB\Driver\BulkWrite;

if (isset($_POST["submit"])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeat_password'];
    $target = "./images/" . basename($_FILES['image']['name']);

    if ($password != $repeatPassword) {
        die('Passwords do not match.');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $user = [
        "_id" => new MongoDB\BSON\ObjectId,
        "firstName" => $firstName,
        "lastName" => $lastName,
        "email" => $email,
        "password" => $hashedPassword,
        'image' => $target
    ];
    $bulk->insert($user);
    $client->executeBulkWrite('proiect.users', $bulk);
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        header('Location: tables.php');
    } else {
        $msg = "Vai! Vai! Vai!!!";
    }
}
?>