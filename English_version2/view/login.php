<section id="login">

    <form id="login_form" action="<?=$this->baseurl;?>" method="post">
     <a name="login_form"></a>
     <h2>Login to Zgubi.one</h2>
     <input type="text" name="userid" placeholder="User name" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input type="password" name="pass" placeholder="Password" \><br />
     <?=(isset($error1)?"<div class=\"error\">($error1)</div>":"");?>
     <button class="button-79" role="button">Login</button>

  </form>
</section>    
