<?php
// Define la contraseña que quieres usar
$password = '123456';

// Genera el hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// Muestra el hash en pantalla
echo "¡Tu hash 100% correcto está listo!<br><br>";
echo "Copia esta línea completa:<br><br>";
echo "<strong>" . $hash . "</strong>";

?>