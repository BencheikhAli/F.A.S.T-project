<!-- le menu deroulant pour les utilisateurs-->
<nav>
    <ul class="dropdown" id="d">
        <input type="text" value="Mon Compte" disabled id="v">
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
        <!--Options-->
        <ul class="options" id="o">
            <li onclick="window.location.href='index.php'"><a href="index.php">Accueil</a></li>
            <li onclick="window.location.href='user-account-infos.php'"><a href="user-account-infos.php">Mes Informations</a></li>
            <li onclick="window.location.href='user-account-myreports.php'"><a href="user-account-myreports.php">Mes Signalements</a></li>
            <li onclick="window.location.href='logout.php'"><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Deconnexion</a></li>
        </ul>
    </ul>
</nav>