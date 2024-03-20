<?php
include_once 'orm/Users.php';
include_once 'db/Database.php';

// Vérification de si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupéreration des données du formulaire
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $designation = $_POST['designation'];
    $password = $_POST['password'];
    // Hash du mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Création d'une nouvelle instance de la classe Database pour établir la connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();

    // Création d'une nouvelle instance de la classe Users en passant la connexion à la base de données
    $user = new Users($db);

    // Assignation des valeurs aux propriétés de l'utilisateur
    $user->name = $name;
    $user->email = $email;
    $user->password = $hashedPassword;
    $user->age = $age;
    $user->designation = $designation;

    // Création de l'utilisateur en appelant la méthode createUser de la classe Users
    if ($user->createUser()) 
    {
        // Redirection vers la page de connexion
        header("Location: mdp_avec_http.php");
        exit();
    }    
    else 
    {
        // Affichage des éventuelles erreurs de requête
        echo "Une erreur s'est produite lors de la création de l'utilisateur. Veuillez réessayer.";
        var_dump($stmt->errorInfo());
    }

}
?>
