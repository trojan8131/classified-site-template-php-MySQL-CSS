
<?php if ($this->u['userlevel'] == 10) { ?>
<section id="search">
<a class="button-79" href="?cmd=categories">Categories</a>
<a class="button-79" href="?cmd=events">Event list</a>
<a class="button-79" href="?cmd=userlist">User List</a>
<?php if(isset($_SESSION['userlist']) and $_SESSION['userlist']){ ?>
<br />
<table><tr><th>Userid</th><th>username</th><th>userlevel</th<th>useremail</th><th>userlevel</th><th></th></tr>
<?php foreach($user as $k=>$v){ ?>
<tr>
<td><?=$v['userid']?></td>
<td><?=$v['username']?></td>

<td><?=($v['userlevel']==10)?'admin':'user';?></td>
<td><?=$v['useremail']?></td>
<td><?=$v['userlevel']?></td>
<td><?php if($v['userid']!='admin'){ ?>
<a  class="button-79" style="background:green" href="?cmd=changeuser&userid=<?=$v['userid']?>">Change level</a>&nbsp;
<a  class="button-79" style="background:red" href="?cmd=deluser&userid=<?=$v['userid']?>">Delete user</a>
<?php } ?></td>
</tr>
<?php } ?>
</table>
<?php } ?>

</section>
<?php } ?>



<?php 

//zabezpieczenie przed ?cmd=category Tylko admin moze się tu dostać
 if(isset($_SESSION['categories']) and $_SESSION['categories']){ ?>
<section id="search">

  <ul>
    <?php foreach ($category as $k => $v) { ?>
      <li><?php print($v['cat_name']." id:".$v["cat_id"]) ?>
        <ul>
          <?php foreach ($subcategory as $p => $c) {
            if ($v["cat_id"] == $c["main_categories"]) { ?>
              <li><?php print($c['cat_name']." id:".$v["cat_id"]) ?>
            <?php }
          } ?>
        </ul>
      <?php } ?>
  </ul>

        
  <form id="search_form" action="<?=$this->baseurl;?>" method="post">
     <h2>Add main category</h2>
     <br>
     <input type="text" name="cat_name" placeholder="Cat_name" required \><br />
     <input type="text" name="cat_desc" placeholder="Cat_description" \><br />
     <?=(isset($error)?"<div class=\"error\">$error</div>":"");?>
     <button style="width:30%" class="button-79" type="submit" >Add main category</button>
  </form>

  <form id="search_form" action="<?=$this->baseurl;?>" method="post">
  <h2>Add subcategory</h2>
     <input type="text" name="cat_name_sub" placeholder="Cat_name_sub" required \><br />
     <input type="text" name="cat_desc_sub" placeholder="Cat_description" \><br />
     <input type="text" name="main_categories" placeholder="ID main category" required\><br />
     <?=(isset($error)?"<div class=\"error\">$error</div>":"");?>
     <button style="width:30%" class="button-79" type="submit" >Add subcategory</button>
  </form>

</section>
<?php } ?>