<?php
include 'connessione.php';

session_start(); // Avvia la sessione

// Controlla se l'utente è loggato
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

// Inizializzazione del messaggio di errore
$errorMessage = "";

// Ottieni l'ID Utente dalla sessione
$idUtente = $_SESSION["userId"];

// Gestione dell'insert nella tabella delle prenotazioni
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idCamera = $_POST["idCamera"];
    $dataInizio = $_POST["dataInizio"];
    $dataFine = $_POST["dataFine"];
    $numeroOspiti = $_POST["numeroOspiti"];

    // Controllo se la data di inizio non è prima di quella del giorno successivo
    $dataOggi = date("Y-m-d");
    if ($dataInizio <= $dataOggi) {
        $errorMessage = "La data di inizio deve essere successiva a quella odierna.";
    } else {
        // Controllo se la data di fine è almeno un giorno dopo la data di inizio
        $dataInizioTimestamp = strtotime($dataInizio);
        $dataFineTimestamp = strtotime($dataFine);
        $differenzaGiorni = ($dataFineTimestamp - $dataInizioTimestamp) / (60 * 60 * 24);
        if ($differenzaGiorni < 1) {
            $errorMessage = "La data di fine deve essere almeno un giorno dopo la data di inizio.";
        } else {
            // Altri controlli e inserimento nella tabella delle prenotazioni...
            
            // Inserimento nella tabella delle prenotazioni solo se tutti i controlli sono passati
            $sql_insert = "INSERT INTO prenotazioni (IdUtente, IdCamera, numeroOspiti, dataInizio, dataFine) VALUES ('$idUtente', '$idCamera', '$numeroOspiti', '$dataInizio', '$dataFine')";
            if ($conn->query($sql_insert) === TRUE) {
                // Reindirizza alla pagina di inserimento degli ospiti con l'ID della prenotazione e il numero di ospiti
                $idPrenotazione = $conn->insert_id; // Ottieni l'ID dell'ultima riga inserita
                header("Location: inserimento_ospiti.php?idPrenotazione=$idPrenotazione&numOspiti=$numeroOspiti");
                exit;
            } else {
                $errorMessage = "Errore durante la prenotazione: " . $conn->error;
            }
        }
    }
}

// Query per ottenere tutte le camere
$sql_camere = "SELECT * FROM camera";
$result_camere = $conn->query($sql_camere);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prenotazioni</title>
    <!-- Collegamento con Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Prenota una camera</h1>
        <?php if ($errorMessage !== ""): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="idCamera">ID Camera</label>
                    <input type="text" class="form-control" name="idCamera" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="numeroOspiti">Numero Ospiti</label>
                    <input type="number" class="form-control" name="numeroOspiti" required min="1">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dataInizio">Data Inizio</label>
                    <input type="date" class="form-control" name="dataInizio" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="dataFine">Data Fine</label>
                    <input type="date" class="form-control" name="dataFine" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Prenota</button>
        </form>

        <a href="index.php" class="btn btn-secondary">Torna alla pagina principale</a>

        <h2 class="mt-4">Camere Disponibili</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID Camera</th>
                    <th>Piano</th>
                    <th>Costo</th>
                    <th>Posti Letto</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_camere->num_rows > 0) {
                    while($row_camere = $result_camere->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row_camere["idCamera"]."</td>";
                        echo "<td>".$row_camere["piano"]."</td>";
                        echo "<td>".$row_camere["costo"]."</td>";
                        echo "<td>".$row_camere["postiLetto"]."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nessuna camera disponibile.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Collegamento con Bootstrap JS (opzionale, richiesto solo per alcuni componenti) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
