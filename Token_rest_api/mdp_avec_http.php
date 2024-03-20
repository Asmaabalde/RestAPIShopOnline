<?php
include_once 'db/Database.php';
include_once 'orm/Users.php';
include_once 'orm/Session.php';

// Vérification de si les informations d'identification de l'utilisateur sont fournies
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'L\'utilisateur a appuyé sur annuler ' ;
    exit;
} else {
    // Récupéreration des informations d'identification fournies par l'utilisateur
    $name = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];

    // Création d'une nouvelle instance de la classe Database pour établir la connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Création d'une nouvelle instance de la classe Users en passant la connexion à la base de données
    $user = new Users($db);
   
    // Récupéreration des informations de l'utilisateur depuis la base de données
    $userInfo = $user->getSingleUser($name);

    // Vérification si l'utilisateur existe et si le mot de passe correspond
    if ($userInfo && password_verify($password, $userInfo['password'])) {
        // Si Authentification réussie
        // Génération du jeton
        $token = bin2hex(random_bytes(32)); // Jeton de 64 caractères hexadécimaux

        // Date d'expiration du jeton (par exemple, expiration dans 2 heure dans mon cas)
        $tokenExpire = date('Y-m-d H:i:s', strtotime('+2 hour'));
       
        // Création de la session dans la base de données
        $session = new Session($db);
        if (isset($userInfo['id'])) {
            $session->createSession($token, $userInfo['id'], $tokenExpire);
            $userId = $userInfo['id'];
            $session->setSessionUser($userId);
        } else {
            echo "Une erreur est survenue";
        }

        // Renvoi du jeton à l'utilisateur sous forme de réponse JSON
        header('Content-Type: application/json');
        echo json_encode(array('token' => $token));
        
    } else {
        // si les identifiants sont incorrects
        header('HTTP/1.0 401 Unauthorized');
        echo 'Identifiants incorrects';
        exit;
    }
}
?>
