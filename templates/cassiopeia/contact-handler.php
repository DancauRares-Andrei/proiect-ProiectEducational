<?php
// Conectare la baza de date Joomla
$host = "localhost"; // Schimbă dacă e necesar
$user = "root"; // Utilizatorul MySQL
$pass = ""; // Parola MySQL (de obicei goală pe localhost)
$dbname = "joomla_db"; // Baza de date Joomla

$conn = new mysqli($host, $user, $pass, $dbname);

// Verificare conexiune
if ($conn->connect_error) {
    die(json_encode(["message" => "❌ Eroare de conexiune la baza de date."]));
}

// Preluare date și curățare
$nume = htmlspecialchars(strip_tags($_POST["nume"] ?? ""));
$email = filter_var($_POST["email"] ?? "", FILTER_SANITIZE_EMAIL);
$mesaj = htmlspecialchars(strip_tags($_POST["mesaj"] ?? ""));

// Validare date
if (strlen($nume) < 3 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($mesaj) < 10) {
    echo json_encode(["message" => "⚠️ Date invalide."]);
    exit;
}

// Salvare în baza de date
$stmt = $conn->prepare("INSERT INTO contact_messages (nume, email, mesaj) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nume, $email, $mesaj);

if ($stmt->execute()) {
    echo json_encode(["message" => "✅ Mesaj salvat cu succes!"]);
} else {
    echo json_encode(["message" => "❌ Eroare la salvarea mesajului."]);
}

$stmt->close();
$conn->close();
?>

