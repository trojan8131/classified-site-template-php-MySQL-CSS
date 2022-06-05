
<section id="search">
  <form id="search_form">
  
      <h2>Profile details</h2>
    
    <input value="Your UserID: <?= $_SESSION['user']['userid']?>" readonly \><br />
    <input value="Your user name: <?= $_SESSION['user']['username']?>" readonly \><br />
    <input value="Your e-mail: <?= $_SESSION['user']['useremail']?>" readonly \><br />
  </form>




  <form id="search_form" action="<?= $this->baseurl; ?>" method="post">

      <h2>Change your e-mail</h2>
   
    <input type="email" name="email" placeholder="Current e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" required\><br />
    <input type="email" name="email_new" placeholder="New e-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" required\><br />

    <?= (isset($error1) ? "<div class=\"error\">$error1</div>" : ""); ?>
    <button style="width:30%" class="button-79" type="submit">Change your e-mail</button>
  </form>


    <form id="search_form" action="<?= $this->baseurl; ?>" method="post">
      
        <h2>Change your password</h2>
      
      <input type="password" name="pass1" placeholder="Current password" required \><br />
      <input type="password" name="pass2" placeholder="New password" required \><br />
      <input type="password" name="pass3" placeholder="Repeat new password" required \><br />
      <?= (isset($error2) ? "<div class=\"error\">$error2</div>" : ""); ?>
      <button style="width:30%" class="button-79" type="submit">Change your password</button>
    </form>
  </section>