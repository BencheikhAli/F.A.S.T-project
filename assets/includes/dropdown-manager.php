<!-- le menu deroulant pour les manager-->
<nav>
    <ul class="dropdown" id="d">
        <input type="text" value="Mon Compte" required disabled id="v">
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
        <ul class="options" id="o">
            <li onclick="window.location.href='index.php'"><a href="index.php">Accueil</a></li>
            <li onclick="window.location.href='manager-account-infos.php'"><a href="manager-account-infos.php">Mes Informations</a></li>
            <li onclick="window.location.href='manager-account-reports.php?filter=notall'"><a href="manager-account-reports.php?filter=notall">Les signalements de mon site</a></li>
            <li onclick="window.location.href='manager-account-pie.php'"><a href="manager-account-pie.php">Diagrammes localisation</a></li>
            <li onclick="window.location.href='register-page.php'"><a href="register-page.php">Ajouter un manager</a></li>
            <li onclick="window.location.href='logout.php'"><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Deconnexion</a></li>
        </ul>
    </ul>
</nav>



