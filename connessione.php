<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "BandB2";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $database);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
