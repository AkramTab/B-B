<?php
include 'connessione.php';

session_start(); // Avvia la sessione

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomeUtente = $_POST["nomeUtente"];
    $password = $_POST["password"];

    // Query per controllare se l'utente è amministratore
    $sql = "SELECT * FROM utente WHERE nome='$nomeUtente' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["nome"] == "Admin") {
            $_SESSION["isAdmin"] = true; // Imposta la variabile di sessione per l'amministratore
            header("Location: admin.php"); // Reindirizza l'amministratore alla pagina admin
            exit; // Aggiunto per interrompere l'esecuzione dello script dopo il reindirizzamento
        } else {
            $_SESSION["userId"] = $row["idUtente"]; // Imposta la variabile di sessione per l'ID Utente
            header("Location: prenotazioni.php"); // Reindirizza l'utente normale alla pagina delle prenotazioni
            exit; // Aggiunto per interrompere l'esecuzione dello script dopo il reindirizzamento
        }
    } else {
        $errorMessage = "Credenziali non valide.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso</title>
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
            <h1 class="mt-4">Accesso</h1>
            <div class="form-container">
                <?php if(isset($errorMessage)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="nomeUtente">Nome Utente:</label>
                        <input type="text" class="form-control" name="nomeUtente" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Accedi</button>
                    <!-- Aggiunta del pulsante "Registrati" -->
                    <a href="registrazione.php" class="btn btn-success">Registrati</a>
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
