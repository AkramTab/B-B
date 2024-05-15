<?php
include 'connessione.php';

session_start(); // Avvia la sessione

// Controlla se l'utente è loggato
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

// Controlla se è stata passata un'ID prenotazione tramite GET
if (!isset($_GET["idPrenotazione"])) {
    header("Location: prenotazioni.php");
    exit;
}

// Ottieni l'ID Utente dalla sessione
$idUtente = $_SESSION["userId"];
$idPrenotazione = $_GET["idPrenotazione"];

// Recupera i dettagli della prenotazione
$sql_prenotazione = "SELECT * FROM prenotazioni WHERE idPrenotazione = '$idPrenotazione'";
$result_prenotazione = $conn->query($sql_prenotazione);
if ($result_prenotazione->num_rows > 0) {
    $row_prenotazione = $result_prenotazione->fetch_assoc();
    $idCamera = $row_prenotazione["IdCamera"];
    $dataInizio = $row_prenotazione["dataInizio"];
    $dataFine = $row_prenotazione["dataFine"];
} else {
    echo "Errore nel recuperare i dettagli della prenotazione.";
    exit;
}

// Recupera i dettagli della camera
$sql_camera = "SELECT * FROM camera WHERE idCamera = '$idCamera'";
$result_camera = $conn->query($sql_camera);
if ($result_camera->num_rows > 0) {
    $row_camera = $result_camera->fetch_assoc();
    $piano = $row_camera["piano"];
    $costo = $row_camera["costo"];
    $postiLetto = $row_camera["postiLetto"];
} else {
    echo "Errore nel recuperare i dettagli della camera.";
    exit;
}

// Recupera i dettagli degli ospiti associati alla prenotazione
$sql_ospiti = "SELECT * FROM ospiti WHERE idPrenotazione = '$idPrenotazione'";
$result_ospiti = $conn->query($sql_ospiti);
$ospiti = array();
if ($result_ospiti->num_rows > 0) {
    while ($row_ospite = $result_ospiti->fetch_assoc()) {
        $ospiti[] = $row_ospite;
    }
} else {
    echo "Nessun ospite trovato per questa prenotazione.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riepilogo Prenotazione</title>
    <!-- Collegamento con Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Riepilogo Prenotazione</h1>
        <br>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h2>Dettagli Camera</h2>
                <table class="table">
                    <tbody>
                        <tr>
                            <th>ID Camera</th>
                            <td><?php echo $idCamera; ?></td>
                        </tr>
                        <tr>
                            <th>Piano</th>
                            <td><?php echo $piano; ?></td>
                        </tr>
                        <tr>
                            <th>Costo</th>
                            <td><?php echo $costo; ?></td>
                        </tr>
                        <tr>
                            <th>Posti Letto</th>
                            <td><?php echo $postiLetto; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="col-md-6">
                <h2>Periodo di Prenotazione</h2>
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Data Inizio</th>
                            <td><?php echo $dataInizio; ?></td>
                        </tr>
                        <tr>
                            <th>Data Fine</th>
                            <td><?php echo $dataFine; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Ospiti</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Data di Nascita</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ospiti as $ospite): ?>
                    <tr>
                        <td><?php echo $ospite["nome"]; ?></td>
                        <td><?php echo $ospite["cognome"]; ?></td>
                        <td><?php echo $ospite["dataNascita"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-primary mt-3">Torna alla pagina principale</a>
    </div>

    <!-- Collegamento con Bootstrap JS (opzionale, richiesto solo per alcuni componenti) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
