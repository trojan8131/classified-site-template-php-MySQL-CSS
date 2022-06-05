
<section id="search">
  <form id="search_form">
  
      <h2>Twój profil</h2>
    
    <input value="Twoja nazwa logowania: <?= $_SESSION['user']['userid']?>" readonly \><br />
    <input value="Twoja nazwa użytkownika: <?= $_SESSION['user']['username']?>" readonly \><br />
    <input value="Twój e-mail: <?= $_SESSION['user']['useremail']?>" readonly \><br />
  </form>




  <form id="search_form" action="<?= $this->baseurl; ?>" method="post">

      <h2>Zmień adres E-mail</h2>
   
    <input type="email" name="email" placeholder="Aktualny e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" required\><br />
    <input type="email" name="email_new" placeholder="Nowy e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" required\><br />

    <?= (isset($error1) ? "<div class=\"error\">$error1</div>" : ""); ?>
    <button style="width:30%" class="button-79" type="submit">Zmień adres E-mail</button>
  </form>


    <form id="search_form" action="<?= $this->baseurl; ?>" method="post">
      
        <h2>Zmień hasło</h2>
      
      <input type="password" name="pass1" placeholder="Aktualne hasło" required \><br />
      <input type="password" name="pass2" placeholder="Nowe hasło" required \><br />
      <input type="password" name="pass3" placeholder="Powtórz hasło" required \><br />
      <?= (isset($error2) ? "<div class=\"error\">$error2</div>" : ""); ?>
      <button style="width:30%" class="button-79" type="submit">Zmień swoje hasło</button>
    </form>
  </section>