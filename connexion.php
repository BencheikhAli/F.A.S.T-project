<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/connexion.css">
    <script src="https://kit.fontawesome.com/57bce97453.js" crossorigin="anonymous"></script>
    <title>F.A.S.T - connexion</title>
</head>

<body>
    <header>
        <img src="assets/img/fast.png" alt="logo du site" class="logo">
        <h2 class="title-site">F.A.S.T</h2>
    </header>

    <h1 class="title-page">Connexion :</h1>
    <?php
        //Si la variable row est dans l'url :
        if(isset($_GET['row'])){
    ?>
        <div class="form-container error">Le compte indiqué n'existe pas !! </div>
        <span class="space"></span>
    <?php
        //Si la variable password est dans l'url :
        }elseif(isset($_GET['password'])){
    ?>
        <div class="form-container error">Mot de passe incorrect !!</div>
        <span class="space"></span>
    <?php
        //Si la variable logout est dans l'url :
        }elseif (isset($_GET['logout'])) {
    ?>
        <div class="form-container logout">Vous vous êtes bien déconnecté(e) de l'application !!</div>
        <span class="space"></span>
    <?php
        //Si la variable error est dans l'url et est non nulle :
        }elseif (isset($_GET['error']) && $_GET['error'] == "true") {
    ?>
        <div class="form-container error"> Une erreur est survenue lors de la transmission de votre identifiant!!</div>
        <span class="space"></span> 
    <?php 
        //Si la variable identifiant est dans l'url :   
        }elseif (isset($_GET['identifiant'])) {
   ?>
        <div class="form-container error">Création de compte impossible, cet identifiant est déjà utilisé par un manager!!</div>
        <span class="space"></span> 
    <?php           
        }
        // Verification que la variable type est manager
        if (isset($_GET['type']) && $_GET['type'] == 'manager') {
            //Si la variable newpd est dans l'url et est égale à passwordupdated
            if (isset($_GET['newpwd']) && $_GET['newpwd'] == 'passwordupdated') {
                ?>
                    <div class="form-container logout">Le mot de passe de votre compte a été modifié !!</div>
                    <span class="space"></span>
                <?php
            }
    ?>
    <!-- connexion pour le type manager ou admin -->
        <div class="form-container">
            <form class="log-form" action="./assets/php/connexion-target.php" method="POST">
                <input type="text" name="pseudo" id="pseudo" placeholder="Identifiant" pattern="^[a-zA-Z]{3,20}$" required>
                <input type="password" name="pass" id="pass" placeholder="Mot de passe" required>
                <input type="submit" value="Valider" class="submit-btn" id="submit-manager">
            </form>
            <span class="space"></span>
            <p class="guess-link"><a href="reset-password-page.php">Mot de passe oublier ? </a></p>
        </div>
        <button class="btn btn-manager" onclick="window.location.href='?type=cariste'"><a href="?type=cariste">Cariste</a></button>
        <?php 
        //Si la variable password est dans l'url
        if(isset($_GET['password'])){
        ?>
            <script>
                const id = localStorage.getItem('id');
                var ps = document.getElementById('pseudo');
                var mdp = document.getElementById('pass');
                ps.value = id;
                pass.value = "";

            </script>
        <?php 
        }
        ?>
        <!-- Si la variable type est cariste -->
    <?php }elseif (isset($_GET['type']) && $_GET['type'] == "cariste") {
    ?>
    <!-- connexion pour le type cariste -->
    <div class="form-container">
        <form class="log-form" action="./assets/php/create-cariste.php" method="POST">
            <input type="text" name="pseudo" id="pseudo" placeholder="Scanner votre carte" pattern="^[a-zA-Z]{3,20}$" required autocomplete="on">
            <br>
            <input type="submit" class="submit-btn" id="submit-cariste" value="Valider">
        </form>
    </div>
    <button class="btn btn-manager" onclick="window.location.href='?type=manager'"><a href="?type=manager">Manager</a></button>
    <?php    
    }else {
    ?>
        <span class="space"></span>
        <div class="main">
            <!-- BOUTTONS CHOIX TYPE CONNEXION -->
            <a href="?type=manager" id="manager"><div class="btn-connect-one">Manager</div></a>
            <a href="?type=cariste" id="cariste"><div class="btn-connect-two">Cariste</div></a>
        </div>
    <?php    
    } ?>
    <script>
        var btn = document.getElementsByClassName('submit-btn')[0];
        var pseudo = document.getElementById('pseudo');
        var pass = document.getElementById('pass');
        var val = "";
        pseudo.onkeyup = () => {
            val = pseudo.value;
            pass.value = val;
        }
        //mettre l'identifiant dans le local storage
        btn.addEventListener('click', ()=>{
            localStorage.setItem('id', pseudo.value);
        });
        //focus sur l'input d'identifiant
        pseudo.focus();
        console.log(screen.height)
    </script>
    <span class="break"></span>
    <span class="break"></span>
    <!-- Include Footer -->
    <?php include_once('./assets/includes/footer.php'); ?>
</body>
</html>