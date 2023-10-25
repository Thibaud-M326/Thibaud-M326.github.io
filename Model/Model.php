<?php
require './Model/env.php';
// Etablir la connexion avec la base données
function getConnection()
{
    $user = getenv('BDD');
    $pass = getenv('PASSWORD');

    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=Equilibra;charset=utf8',
            $user,
            $pass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (Exception $e) {
        var_dump($e->getMessage());
        die();
    }
    return $pdo;
}

// Enregister un nouvel utilisateur dans la base de données
function getSignup($nom, $prenom, $sexe, $age, $email, $password, $poids, $taille, $activite)
{
    $pdo = getConnection();
    $isEmailExist = $pdo->prepare("SELECT Email FROM Utilisateur WHERE Email = :emailVerif");
    $isEmailExist->bindParam(':emailVerif', $email);
    $isEmailExist->execute();
    $resultEmail = $isEmailExist->fetch();
    if ($resultEmail) {
    } else {
        $query = $pdo->prepare("INSERT INTO Utilisateur (Nom, Prenom, Sexe, Age, Email, Mdp, Poids, Taille, Activite)
        VALUES (:nom, :prenom, :sexe, :age, :email, :mdp, :poids, :taille, :activite)");
        $query->bindParam(':nom', $nom);
        $query->bindParam(':prenom', $prenom);
        $query->bindParam(':sexe', $sexe);
        $query->bindParam(':age', $age);
        $query->bindParam(':email', $email);
        $query->bindParam(':mdp', $password);
        $query->bindParam(':poids', $poids);
        $query->bindParam(':taille', $taille);
        $query->bindParam(':activite', $activite);
        $result = $query->execute();
    }

    return $result;
}

// Connexion de l'utlisateur avec vérification de ses identifiants
function getLogin($mail, $password)
{
    $pdo = getConnection();
    $query = $pdo->prepare("SELECT Mdp, Id_user FROM Utilisateur WHERE Email = :mail");
    $query->bindParam(':mail', $mail);
    $query->execute();
    $pass = $query->fetch();

    if ($pass != NULL) {
        if ($pass[0] === $password) {
            $userId = $pass[1];
        } else {
            $userId = NULL;
        }
    } else {
        $userId = NULL;
    }
    return $userId;
}

// Récupération de tous les repas d'une journée 
function getDayMeals($dayDate, $id)
{

    $paramDate = $dayDate . '%';

    $pdo = getConnection();
    $query = $pdo->prepare("SELECT Id_repas, Type, Description, Kcal,  DATE_FORMAT(Date, '%d/%m/%Y') as Date, DATE_FORMAT(TIME(Date), '%H:%i') AS heure
                            FROM Repas
                            WHERE Id_user = :id AND Date LIKE :paramDate
                            ORDER BY heure");

    $query->bindParam(':paramDate', $paramDate);
    $query->bindParam(':id', $id);
    $query->execute();
    $meals = $query->fetchAll(PDO::FETCH_ASSOC);

    return $meals;
}

//Récupération des données d'un repas 
function getUserInfo($id)
{
    $pdo = getConnection();
    $query = $pdo->prepare("SELECT Nom, Prenom, Taille, Poids, Activite
                            FROM Utilisateur
                            WHERE Id_user = :id");
    $query->bindParam(':id', $id);
    $query->execute();
    $userInfo = $query->fetch(PDO::FETCH_ASSOC);
    return $userInfo;
}

// Création d'un nouveau repas
function getCreateNewMeal($id, $type, $intitule, $calories, $heureDate)
{
    $pdo = getConnection();
    $query = $pdo->prepare("INSERT INTO Repas (Id_user, Type, Description, Kcal, Date)
        VALUES (:id, :typeRepas, :intitule, :calories, :heureDate)");
    $query->bindParam(':id', $id);
    $query->bindParam(':typeRepas', $type);
    $query->bindParam(':intitule', $intitule);
    $query->bindParam(':calories', $calories);
    $query->bindParam(':heureDate', $heureDate);
    $result = $query->execute();
    return $result;
}

// Récupération des données d'un repas pour les afficher dans le formulaire de modification

function getOneMealInfo($repasId)
{
    $pdo = getConnection();
    $query = $pdo->prepare("SELECT Type, Description, Kcal, Date, TIME(Date) AS heure 
                            FROM Repas 
                            WHERE Id_repas = :id");
    $query->bindParam(':id', $repasId);
    $query->execute();
    $meal = $query->fetchAll(PDO::FETCH_ASSOC);

    return $meal;
}

// Modification d'un repas
function getEditMeal($id, $type, $intitule, $calories, $heureDate)
{
    $pdo = getConnection();
    $query = $pdo->prepare("UPDATE Repas SET Type = :typeRepas, Description = :intitule, Kcal = :calories, Date = :heureDate
    WHERE Id_repas = :id");
    $query->bindParam(':id', $id);
    $query->bindParam(':typeRepas', $type);
    $query->bindParam(':intitule', $intitule);
    $query->bindParam(':calories', $calories);
    $query->bindParam(':heureDate', $heureDate);
    $result = $query->execute();
    return $result;
}

// Modification d'un repas
function getDeleteMeal($id)
{
    $pdo = getConnection();
    $query = $pdo->prepare("DELETE FROM Repas WHERE Id_repas = :id");
    $query->bindParam(':id', $id);

    $result = $query->execute();
    return $result;
}

function getEditUser($id, $nom, $prenom, $sexe, $age, $email, $password, $poids, $taille, $activite)
{

    // $query = $pdo->prepare("INSERT INTO Utilisateur (Nom, Prenom, Sexe, Age, Email, Mdp, Poids, Taille, Activite)

    $pdo = getConnection();
    $query = $pdo->prepare("UPDATE Utilisateur 
                            SET Nom = :nom, Prenom = :prenom, Sexe = :sexe, 
                            Age = :age, Email = :email, Mdp = :mdp, 
                            Poids = :poids, Taille = :taille, Activite = :activite 
                            WHERE Id_user = :id");
    $query->bindParam(':nom', $nom);
    $query->bindParam(':prenom', $prenom);
    $query->bindParam(':sexe', $sexe);
    $query->bindParam(':age', $age);
    $query->bindParam(':email', $email);
    $query->bindParam(':mdp', $password);
    $query->bindParam(':poids', $poids);
    $query->bindParam(':taille', $taille);
    $query->bindParam(':activite', $activite);
    $query->bindParam(':id', $id);
    $result = $query->execute();

    return $result;
}

function getUserChangeInfo($id)
{

    $pdo = getConnection();
    $query = $pdo->prepare("SELECT Nom, Prenom, Taille, Email, 
                                    Sexe, Age, Poids, Taille, Activite
                            FROM Utilisateur
                            WHERE Id_user = :id");
    $query->bindParam(':id', $id);
    $query->execute();
    $userChangeInfo = $query->fetch(PDO::FETCH_ASSOC);

    return $userChangeInfo;
}
