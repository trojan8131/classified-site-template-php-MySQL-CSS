<!DOCTYPE html>
<html>
<script src="funkcje.js"></script>

<head>
    <title >Zgubi.one</title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="style.css">
    
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>


</head>

<body>
    <header>
    <div><a href="index.php"><h2 id="logo_text">ZGUBI.ONE</h2></a></div>
        <div id="menu">
            
            

                <?php if (isset($_SESSION['user'])) { ?>
                    <?php if ($_SESSION['user']['userid'] != "Guest") { ?> <a href="?cmd=logout"><p id="menu_text">Wyloguj</p></a> <?php } ?>
                <?php } ?>

                <?php if ($this->u['userlevel'] == 10) { ?>
                   <a href="?cmd=admin_panel"> <p id="menu_text">Panel Administratora</p></a>
                <?php } ?>
                <?php if ($_SESSION['user']['userid'] != "Guest") { ?>
                    <a href="?cmd=user_informations"><p id="menu_text">Twój profil</p></a>
               
                    <a href="?cmd=user_classifieds"><p id="menu_text">Twoje ogłoszenia</p></a>
                <?php } ?>
                    <a href="?cmd=classifieds"><p id="menu_text"> Wszystkie ogłoszenia</p> </a>

                <?php if ($_SESSION['user']['userid'] == "Guest") { ?>
                    <a href="?cmd=login"><p id="menu_text"> Zaloguj się</p> </a>
                    <a href="?cmd=register"><p id="menu_text"> Zarejestruj się</p> </a>
                <?php } ?>
                <?php if ($_SESSION['user']['userid'] != "Guest") { ?>        
                <a href="?cmd=add_classified"><p id="menu_text">Dodaj ogłoszenie</p></a>
                <?php } ?>
           
        </div>

    </header>