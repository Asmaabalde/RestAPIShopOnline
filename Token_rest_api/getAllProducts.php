<?php
include_once 'db/Database.php';
include_once 'orm/Session.php';
include_once 'orm/Users.php';
$db_table = "products";
$database = new Database();
$db = $database->getConnection();
$user = new Users($db);
$result = $user->getAllProducts();
$session = new Session();
$userId = $session->getSessionUser('userId');
//à recupérer depuis une base de données
//Chaque Token a une duré de vie
$sessionToken = $session->getTokenByUserId($userId); // Récupéreration du token de la session

$headers  = getallheaders();
if (array_key_exists("x-auth-token", $headers)) {
    // Utilisation du token récupéré de la session
    if($headers["x-auth-token"] === $sessionToken ) {
        echo json_encode($result);
    }
}
?>
