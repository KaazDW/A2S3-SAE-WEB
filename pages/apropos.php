<?php session_start();
if (empty($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = false;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php include '../modules/head.php'; ?>
</head>

<body>
        <?php include '../modules/header.php'; ?>

    <main class="main-apropos">
        <h2 class="title">A propos</h2>
        <section>
            
        </section>
    </main> 


    <footer>
        <!-- <h3>footer</h5> -->
        <a href="">©TUNIV</a>
    </footer>
    <script src="/app.js"></script>
</body>

</html>