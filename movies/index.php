<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>Sarrera - Filmen Kudeatzailea</title>
</head>
<body>

    <h1>Ongi etorri Filmen Kudeatzailera</h1>

    <!-- Enviamos directamente a main.php usando el método GET -->
    <form action="main.php" method="get">
        <label for="erabiltzailea">Sartu zure izena:</label><br>
        <input type="text" name="erabiltzailea" id="erabiltzailea" required><br><br>
        
        <button type="submit">Sartu</button>
    </form>

</body>
</html>