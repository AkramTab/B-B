<?php
include 'connessione.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $dataNascita = $_POST["dataNascita"];
    $password = $_POST["password"];

    // Controllo se la data di nascita è almeno 18 anni fa
    $dataNascitaTimestamp = strtotime($dataNascita);
    $dataLimiteTimestamp = strtotime("-18 years");
    
    if ($dataNascitaTimestamp > $dataLimiteTimestamp) {
        $registrationMessage = "Sei minorenne.";
    } else {
        // Query per controllare se l'utente è già presente nel database
        $check_query = "SELECT * FROM utente WHERE nome = '$nome' AND cognome = '$cognome' AND dataNascita = '$dataNascita'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            // L'utente è già registrato, mostra un messaggio di errore
            $registrationMessage = "Sei già registrato.";
        } else {
            // L'utente non è presente nel database e è maggiorenne, esegui l'inserimento
            $sql = "INSERT INTO utente (nome, cognome, dataNascita, password) VALUES ('$nome', '$cognome', '$dataNascita', '$password')";

            if ($conn->query($sql) === TRUE) {
                $registrationMessage = "Registrazione avvenuta con successo.";
            } else {
                $registrationMessage = "Errore durante la registrazione: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <!-- Collegamento con Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 100px; /* Aggiungi spazio sopra il contenuto */
            background-color: #f8f9fa; /* Cambia il colore di sfondo */
        }
        .content {
            text-align: center; /* Allinea il contenuto al centro */
        }
        .form-container {
            max-width: 400px; /* Larghezza massima del form */
            margin: auto; /* Centra il form */
            background-color: #fff; /* Sfondo bianco per il form */
            padding: 20px; /* Spazio interno al form */
            border-radius: 8px; /* Bordi arrotondati */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Ombra leggera */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1 class="mt-4">Registrazione</h1>
            <div class="form-container">
                <?php if(isset($registrationMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $registrationMessage; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="cognome">Cognome:</label>
                        <input type="text" class="form-control" name="cognome" required>
                    </div>
                    <div class="form-group">
                        <label for="dataNascita">Data di Nascita:</label>
                        <input type="date" class="form-control" name="dataNascita" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-success">Registrati</button>
                    <!-- Aggiunta del pulsante "Login" -->
                    <a href="login.php" class="btn btn-primary">Accedi</a>
                </form>
                <br>
                <a href="index.php" class="btn btn-secondary">Torna alla pagina principale</a>
            </div>
        </div>
    </div>

    <!-- Collegamento con Bootstrap JS (opzionale, richiesto solo per alcuni componenti) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
