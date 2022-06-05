<?php ?>

<nav>
 <h2 style="color:white"> Info: </h2>
<?php
print_r($_SESSION["test"]."<br>");
print_r($_SESSION["test0"]."<br>");
print_r($_SESSION["error"]."<br>");
print_r($_SESSION["error1"]."<br>");
print_r("<br>Sesja:".$_SESSION["context"]);
print_r("<br>userlist:".$_SESSION['userlist']);
?>
<br>
Zalogowany jako: <?=$this->u['username'];?> (<?=$this->u['userid'];?>)








</nav>