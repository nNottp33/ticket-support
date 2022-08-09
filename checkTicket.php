<?php

date_default_timezone_set('Asia/Bangkok');

$servername = "localhost";
$username = "admin";
$password = "Scm@2021";
$dbname = "ticket_support";

$date = new DateTime();
$date_string = $date->modify("-3 days")->format('Y-m-d 23:59:59');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_query = 'SELECT createdAt, id FROM ticket_task WHERE status = 2 AND createdAt <= ' . strtotime($date_string);
$result = $conn->query($sql_query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $id[] = $row['id'];
    }

    $id = implode(",", $id);
    $query_delete = 'UPDATE ticket_task SET status = 4 WHERE id IN (' . $id . ')';
    $result_delete = $conn->query($query_delete);

    if ($result_delete) {
        echo "Updated ";
    }
} else {
    echo "0 results";
}
$conn->close();
