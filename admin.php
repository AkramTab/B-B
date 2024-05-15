<?php
include 'connessione.php';

session_start(); // Avvia la sessione

// Controllo se l'utente è loggato come amministratore
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit;
}

// Funzione per cancellare una prenotazione e gli ospiti associati
function cancellaPrenotazione($conn, $deletePrenotazioneId) {
    // Query per cancellare gli ospiti associati alla prenotazione
    $sql_delete_ospiti = "DELETE FROM ospiti WHERE idPrenotazione = $deletePrenotazioneId";

    // Eseguo la query di cancellazione degli ospiti
    if ($conn->query($sql_delete_ospiti) === TRUE) {
        // Query per cancellare la prenotazione
        $sql_delete_prenotazione = "DELETE FROM prenotazioni WHERE idPrenotazione = $deletePrenotazioneId";

        // Eseguo la query di cancellazione della prenotazione
        if ($conn->query($sql_delete_prenotazione) === TRUE) {
            echo "Prenotazione e ospiti cancellati con successo.<br>";
        } else {
            echo "Errore durante la cancellazione della prenotazione: " . $conn->error . "<br>";
        }
    } else {
        echo "Errore durante la cancellazione degli ospiti: " . $conn->error . "<br>";
    }
}

// Controllo se è stato inviato un ID prenotazione da cancellare
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deletePrenotazioneId"])) {
    $deletePrenotazioneId = $_GET["deletePrenotazioneId"];
    cancellaPrenotazione($conn, $deletePrenotazioneId);
}

// Controllo se è stato inviato un ID utente da cancellare
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["deleteUserId"])) {
    $deleteUserId = $_GET["deleteUserId"];

    // Query per cancellare l'utente
    $sql_delete_user = "DELETE FROM utente WHERE idUtente = $deleteUserId";

    // Eseguo la query di cancellazione
    if ($conn->query($sql_delete_user) === TRUE) {
        echo "Utente cancellato con successo.<br>";
    } else {
        echo "Errore durante la cancellazione dell'utente: " . $conn->error . "<br>";
    }
}

// Query per ottenere tutti gli utenti
$sql_get_users = "SELECT * FROM utente";
$result_users = $conn->query($sql_get_users);

// Query per ottenere tutte le prenotazioni con l'associazione dell'ID utente al nome e cognome
$sql_get_prenotazioni = "SELECT prenotazioni.*, utente.nome AS nome_utente, utente.cognome AS cognome_utente FROM prenotazioni INNER JOIN utente ON prenotazioni.IdUtente = utente.idUtente";
$result_prenotazioni = $conn->query($sql_get_prenotazioni);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina Admin</title>
    <!-- Collegamento con Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Pagina Admin</h1>

        <h2 class="mt-4">Elenco Utenti</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Elimina</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Visualizza tutti gli utenti
                if ($result_users->num_rows > 0) {
                    while ($row_user = $result_users->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_user["nome"] . "</td>";
                        echo "<td>" . $row_user["cognome"] . "</td>";
                        echo "<td><a href=\"admin.php?deleteUserId=" . $row_user["idUtente"] . "\" class=\"btn btn-danger\">Cancella</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nessun utente trovato.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2 class="mt-4">Elenco Prenotazioni</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Prenotazione</th>
                    <th>ID Utente</th>
                    <th>Nome Utente</th>
                    <th>Cognome Utente</th>
                    <th>ID Camera</th>
                    <th>Data Inizio</th>
                    <th>Data Fine</th>
                    <th>Elimina</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Visualizza tutte le prenotazioni
                if ($result_prenotazioni->num_rows > 0) {
                    while ($row_prenotazione = $result_prenotazioni->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_prenotazione["idPrenotazione"] . "</td>";
                        echo "<td>" . $row_prenotazione["IdUtente"] . "</td>";
                        echo "<td>" . $row_prenotazione["nome_utente"] . "</td>";
                        echo "<td>" . $row_prenotazione["cognome_utente"] . "</td>";
                        echo "<td>" . $row_prenotazione["IdCamera"] . "</td>";
                        echo "<td>" . $row_prenotazione["dataInizio"] . "</td>";
                        echo "<td>" . $row_prenotazione["dataFine"] . "</td>";
                        echo "<td><a href=\"admin.php?deletePrenotazioneId=" . $row_prenotazione["idPrenotazione"] . "\" class=\"btn btn-danger\">Cancella</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Nessuna prenotazione trovata.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Pulsante per tornare alla pagina principale -->
        <a href="index.php" class="btn btn-primary">Torna alla pagina principale</a>
    </div>

    <!-- Collegamento con Bootstrap JS (opzionale, richiesto solo per alcuni componenti) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
