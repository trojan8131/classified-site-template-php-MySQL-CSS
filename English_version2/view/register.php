<section id="login">

  <form id="login_form" action="<?=$this->baseurl;?>" method="post">
     <a name="newuser_form"></a>
     <h2>Register to Zgubi.one</h2>
     <input required type="text" name="userid" placeholder="UserID" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input required type="text" name="username" placeholder="User name"  \><br />
     <input required type="email" name="email" placeholder="E-mail address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2, 4}$" \><br />
     <input required type="password" name="pass1" placeholder="Password"  \><br />
     <input required type="password" name="pass2" placeholder="Repeat password" \><br />
     <?=(isset($error)?"<div class=\"error\">$error</div>":"");?>
     <button class="button-79" type="submit" >Register!</button>
  </form>
</section>    
