<?php session_start();

include("db.php");

$idArbitre = $pdo->prepare("SELECT ID_User FROM MatchTournoi WHERE ID_Match = :varId;");
$idArbitre->execute(['varId' => $_GET['id']]);
$idArbitre = $idArbitre->fetch()[0];

if ($_SESSION["type"]!="administrateur" || ($_SESSION["type"]=="arbitre" && $_SESSION["userId"]!=$idArbitre)) {
    header("Location: ../../index.php");
}

$matchFini = $pdo->prepare("SELECT Etat FROM MatchTournoi WHERE ID_Match = :varId");
$matchFini->execute(['varId' => $_GET["id"]]);
$matchFini = $matchFini->fetch()[0];

$tournoi = $pdo->prepare("SELECT ID_Tournoi FROM MatchTournoi WHERE ID_Match = :varId");
$tournoi->execute(['varId' => $_GET["id"]]);
$tournoi = $tournoi->fetch()[0];

if ($matchFini==1) {
    header("Location: ../pages/match-tournois.php?id=" . $tournoi);
}

$idsEquipes = $pdo->prepare("SELECT ID_Equipe FROM Jouer WHERE ID_Match = :varId;");
$idsEquipes->execute(['varId' => $_GET['id']]);
$idsEquipes = $idsEquipes->fetchAll();
$listeIds = [];

$i = 0;
foreach($idsEquipes as $idEquipe){
    $listeIds[$i] = $idEquipe["ID_Equipe"];
    $i++;
}

$scoresEquipes = $pdo->prepare("SELECT Score FROM Jouer WHERE ID_Equipe = :varId AND ID_Match = :varMatch");
$scoresEquipes->execute(['varId' => $listeIds[0], 'varMatch' => $_GET["id"]]);
$scoreEquipe1 = $scoresEquipes->fetch()[0];
$scoresEquipes->execute(['varId' => $listeIds[1], 'varMatch' => $_GET["id"]]);
$scoreEquipe2 = $scoresEquipes->fetch()[0];

$majScore = $pdo->prepare("UPDATE Participer SET Score= Score+:varScore WHERE ID_Equipe= :varEquipe AND ID_Tournoi = :varTournoi");

if ($scoreEquipe1>$scoreEquipe2){
    $majScore->execute(['varScore' => 3, 'varEquipe' => $listeIds[0], 'varTournoi' => $tournoi]);
} else if ($scoreEquipe2>$scoreEquipe1){
    $majScore->execute(['varScore' => 3, 'varEquipe' => $listeIds[1], 'varTournoi' => $tournoi]);
} else {
    $majScore->execute(['varScore' => 1, 'varEquipe' => $listeIds[0], 'varTournoi' => $tournoi]);
    $majScore->execute(['varScore' => 1, 'varEquipe' => $listeIds[1], 'varTournoi' => $tournoi]);
}

$verrouillage = $pdo->prepare("UPDATE MatchTournoi SET Etat = 1 WHERE ID_Match = :varId");
$verrouillage->execute(['varId' => $_GET["id"]]);

header("Location: ../pages/match-tournois.php?id=" . $tournoi);