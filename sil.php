<?php
$host = 'localhost';
$dbname = 'otel';
$username = 'root';
$password = '';

try {
    // Veritabanı bağlantısı
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Silme işlemi
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Veritabanından silme işlemi
        $sql = "DELETE FROM rezervasyon WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        // Başarı mesajı
        echo "<p>Rezervasyon başarıyla silindi!</p>";
    }

    // Silinecek rezervasyonların listesini göster
    echo "<h2>Rezervasyonları Sil</h2>";
    echo "<form method='GET' action=''>";
    echo "Silmek istediğiniz rezervasyon ID'sini girin: <input type='number' name='id' required>";
    echo "<input type='submit' value='Sil'>";
    echo "</form>";
} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>
