<?php
require_once ("dbConnection.php");

$bulk = new MongoDB\Driver\BulkWrite;
$id = new \MongoDB\BSON\ObjectId($_GET['id']);
$filter = ['_id' => $id];

$bulk->delete($filter);

$client->executeBulkWrite('proiect.users', $bulk);


header('Location: tables.php');