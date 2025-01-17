<?php 
if ($_SESSION["type"] != "administrateur") {
    header("Location: /index");
}

if (empty($_POST["name"])){
    $_SESSION["equipeErreur"] = "Nom de l'équipe manquant";
    header("Location: /dashb-admin.php");
}
if (empty($_POST["sport"])){
    $_SESSION["equipeErreur"] = "Sport de l'équipe manquant";
    header("Location: /dashb-admin.php");
}
if (empty($_POST["name-capitaine"])){
    $_SESSION["equipeErreur"] = "Nom du capitaine manquant";
    header("Location: /dashb-admin.php");
}
if (empty($_POST["firstname-capitaine"])){
    $_SESSION["equipeErreur"] = "Prenom du capitaine manquant";
    header("Location: /dashb-admin.php");
}

$nom = $pdo->quote($_POST["name"]);
$sport = $pdo->quote($_POST["sport"]);
$nom_cap = $pdo->quote($_POST["name-capitaine"]);
$prenom = $pdo->quote($_POST["firstname-capitaine"]);

$users = $pdo->prepare('SELECT ID_User from Utilisateurs where Nom =:varnom and Prenom=:varprenom');
$users->execute(['varnom' => $nom_cap,'varprenom' => $prenom]);

$user = $users->fetch();
if($user){
    $id_user = $user[0];
} else{
    $id_user = 1;
}
$id_user=$pdo->quote($id_user);

$sql = "INSERT INTO Equipe VALUES (0, $nom, $sport, $id_user);";
$res = $pdo->exec($sql);
if (!$res) {
    $_SESSION["equipeErreur"] = "La création de l'équipe a échoué, veuillez réessayer. Si l\'erreur persiste, contactez le support.";
    header("Location: /dashb-admin");
} else {
    $sql = "SELECT max(ID_Tournoi) FROM Tournoi;";
    $id = $pdo->query(($sql))->fetch()[0];
}

header("Location: /dashb-admin");
    