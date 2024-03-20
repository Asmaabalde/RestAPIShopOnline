<?php
include_once '../Token_rest_api/db/Database.php';
include_once '../Token_rest_api/orm/Session.php';

// Création d'une instance de la classe Session
$session = new Session();

// Récupéreration de l'ID de l'utilisateur depuis la session
$userId = $session->getSessionUser('userId');

// Récupéreration du token à partir de la session en fonction de l'ID de l'utilisateur
$sessionToken = $session->getTokenByUserId($userId);
var_dump($sessionToken);

// utilisation du token récupéré de la session dans l'en-tête de la requête
$headers = array();
$headers[] = "x-auth-token: $sessionToken"; // Utilisation le token de session

$url = "http://localhost/ece2025/ShopOnline/Token_rest_api/getAllProducts.php"; // Lien complet à compléter
$client = curl_init($url);
curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($client);
$results = json_decode($response);
var_dump($response);
var_dump($results);

// Affichage des résultats
echo "<table>";
    foreach ($results as $result) {
        echo "<tr><td>product_id:</td><td>$result->product_id</td></tr>";
        echo "<tr><td>product_name:</td><td>$result->product_name</td></tr>";
        echo "<tr><td>product_description:</td><td>$result->product_description</td></tr>";
        echo "<tr><td>dossier:</td><td>$result->dossier</td></tr>";
        echo "<tr><td>category_id:</td><td>$result->category_id</td></tr>";
        echo "<tr><td>in_stock:</td><td>$result->in_stock</td></tr>";
        echo "<tr><td>price:</td><td>$result->price</td></tr>";
        echo "<tr><td>brand:</td><td>$result->brand</td></tr>";
        echo "<tr><td>nbr_image:</td><td>$result->nbr_image</td></tr>";
        echo "<tr><td>date_added:</td><td>$result->date_added</td></tr>";
    }
    echo "</table>";

?>
