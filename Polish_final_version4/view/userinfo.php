<nav class="user-info">
<?php  if(isset($_SESSION['user'])){ ?>
<?php if($_SESSION['user']['userid']!="Guest"){ ?> <a href="?cmd=logout" >WYLOGUJ</a> <?php } ?>
<?php } ?>
</nav>
