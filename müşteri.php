<?php
$host = 'localhost';
$dbname = 'otel';
$username = 'root';
$password = '';

try {
    // Veritabanı bağlantısı
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Rezervasyon işlemi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Formdan gelen veriler
        $adi = $_POST['adi'];
        $soyad = $_POST['soyad'];
        $telefon = $_POST['telefon'];

        // Boş bir oda bul
        $sql = "SELECT oda_no FROM odalar WHERE dolu = FALSE LIMIT 1";
        $stmt = $pdo->query($sql);
        $oda = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($oda) {
            $oda_no = $oda['oda_no'];

            // Rezervasyonu kaydet
            $insertSql = "INSERT INTO rezervasyon (adi, soyad, telefon, oda_no) VALUES (:adi, :soyad, :telefon, :oda_no)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute(['adi' => $adi, 'soyad' => $soyad, 'telefon' => $telefon, 'oda_no' => $oda_no]);

            // Odayı dolu olarak güncelle
            $updateSql = "UPDATE odalar SET dolu = TRUE WHERE oda_no = :oda_no";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute(['oda_no' => $oda_no]);

            echo "<p>Rezervasyon başarıyla tamamlandı! Oda No: $oda_no</p>";
        } else {
            echo "<p>Üzgünüz, şu anda boş oda yok.</p>";
        }
    }

    // Boş oda sayısını göster
    $countSql = "SELECT COUNT(*) AS bos_odalar FROM odalar WHERE dolu = FALSE";
    $countStmt = $pdo->query($countSql);
    $bosOdalar = $countStmt->fetch(PDO::FETCH_ASSOC)['bos_odalar'];

    echo "<h2>Boş Oda Sayısı: $bosOdalar</h2>";

} catch (PDOException $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}
?>

<form method="POST" action="">
    <label>Adı:</label>
    <input type="text" name="adi" required><br>
    <label>Soyadı:</label>
    <input type="text" name="soyad" required><br>
    <label>Telefon:</label>
    <input type="text" name="telefon" required><br>
    <input type="submit" value="Rezervasyon Yap">
</form>
