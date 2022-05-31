<!-- le menu deroulant pour les admin-->
<nav>
    <ul class="dropdown" id="d">
        <input type="text" value="Mon Compte" required disabled id="v">
        <i class="fa fa-chevron-down" aria-hidden="true"></i>
        <!--Options-->
        <ul class="options" id="o">
            <li onclick="window.location.href='admin-account.php'"><a href="admin-account.php">Accueill Admin</a></li>
            <li onclick="window.location.href='register-page.php'"><a href="register-page.php">Ajouter un utilisateur</a></li>
            <li onclick="window.location.href='admin-account-users.php'"><a href="admin-account-users.php">Voir tous les utilisateurs</a></li>
            <li onclick="window.location.href='admin-account-delete.php'"><a href="admin-account-delete.php">Supprimer un utilisateur</a></li>
            <li onclick="window.location.href='logout.php'"><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>Deconnexion</a></li>
        </ul>
    </ul>
</nav>


