<section id="login">

  <form id="login_form" action="<?=$this->baseurl;?>" method="post">
     <a name="newuser_form"></a>
     <h2>Zarejestruj się do Zgubi.one</h2>
     <input required type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input required type="text" name="username" placeholder="Nazwa użytkownika"  \><br />
     <input required type="email" name="email" placeholder="E-mail" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" \><br />
     <input required type="password" name="pass1" placeholder="Hasło"  \><br />
     <input required type="password" name="pass2" placeholder="Powtórz hasło" \><br />
     <?=(isset($error)?"<div class=\"error\">$error</div>":"");?>
     <button class="button-79" type="submit" >Zarejestruj!</button>
  </form>
</section>    
