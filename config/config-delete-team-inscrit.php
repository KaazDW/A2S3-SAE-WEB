<?php session_start();

if ($_SESSION["type"]!="administrateur") {
    header("Location: ../index.php");
}

include '../config/db.php';

$inscrit = $pdo->prepare('DELETE from Participer where ID_Equipe = :varEquipe AND ID_Tournoi= :varTournoi');
$inscrit->execute(['varEquipe' =>$_GET["idEquipe"], 'varTournoi' => $_GET['idTournoi']]);    

$inscrit = $pdo->prepare('DELETE FROM MatchTournoi WHERE ID_Match IN (SELECT ID_Match FROM MatchTournoi WHERE ID_Match IN (SELECT ID_Match FROM MatchTournoi WHERE ID_Tournoi = :varTournoi) AND ID_Match IN (SELECT ID_Match FROM Jouer WHERE ID_Equipe= :varEquipe)); ');
$inscrit->execute(['varEquipe' => $_GET['idEquipe'], 'varTournoi' => $_GET['idTournoi']]);

header("Location: ../pages/match-tournois.php?id=" . $_GET['idTournoi']);
     
?>