<?php
include 'connessione.php';

session_start(); // Avvia la sessione

// Controllo se l'utente è loggato
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

// Controllo se è stato inviato un ID prenotazione valido tramite GET
if (!isset($_GET["idPrenotazione"]) || empty($_GET["idPrenotazione"])) {
    echo "ID prenotazione non valido.";
    exit;
}

$idPrenotazione = $_GET["idPrenotazione"];

// Recupera il numero di ospiti dal parametro GET
$numOspiti = isset($_GET["numOspiti"]) ? $_GET["numOspiti"] : 1;

// Controllo se il metodo di richiesta è POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupero i dati inviati dal form
    $dataNascita = $_POST["dataNascita"];
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];

    // Inserisco gli ospiti nel database
    for ($i = 0; $i < $numOspiti; $i++) {
        // Escapo i dati per prevenire attacchi di SQL injection
        $nomeOspite = mysqli_real_escape_string($conn, $nome[$i]);
        $cognomeOspite = mysqli_real_escape_string($conn, $cognome[$i]);
        $dataNascitaOspite = mysqli_real_escape_string($conn, $dataNascita[$i]);

        // Costruisco la query di inserimento
        $sql_insert_ospite = "INSERT INTO ospiti (idPrenotazione, nome, cognome, dataNascita) VALUES ('$idPrenotazione', '$nomeOspite', '$cognomeOspite', '$dataNascitaOspite')";

        // Eseguo la query di inserimento
        if ($conn->query($sql_insert_ospite) === TRUE) {
            echo "Ospite " . ($i + 1) . " inserito con successo.<br>";
        } else {
            echo "Errore durante l'inserimento dell'ospite: " . $conn->error . "<br>";
        }
    }

    // Reindirizzo alla pagina di riepilogo dopo l'inserimento degli ospiti
    header("Location: riepilogo.php?idPrenotazione=$idPrenotazione");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento Ospiti</title>
    <!-- Collegamento con Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Inserimento Ospiti</h1>
        
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?idPrenotazione=$idPrenotazione&numOspiti=$numOspiti");?>">
            <?php for ($i = 0; $i < $numOspiti; $i++): ?>
                <h3>Ospite <?php echo $i + 1; ?></h3>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" name="nome[]" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cognome">Cognome</label>
                        <input type="text" class="form-control" name="cognome[]" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="dataNascita">Data di Nascita</label>
                        <input type="date" class="form-control" name="dataNascita[]" required>
                    </div>
                </div>
            <?php endfor; ?>
            
            <button type="submit" class="btn btn-primary">Inserisci Ospiti</button>
        </form>

        <a href="prenotazioni.php" class="btn btn-secondary mt-3">Torna a Prenotazioni</a>
        <a href="index.php" class="btn btn-secondary mt-3">Torna alla pagina principale</a>
    </div>

    <!-- Collegamento con Bootstrap JS (opzionale, richiesto solo per alcuni componenti) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
