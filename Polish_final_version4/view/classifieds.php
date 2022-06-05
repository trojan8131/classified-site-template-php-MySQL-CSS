<?php if (!(isset($_GET['cmd']) and ($_GET['cmd'] == 'show_contact' or $_GET['cmd'] == 'show'))) {


?>
    <section id="search">
        <? echo ("$classifieds") ?>
        <? echo ("$error") ?>
        <form id="search_form" action="<?= $this->baseurl ?>" method="post">
            <h1>Wyszukaj</h1>
            <input type="text" name="body_search" placeholder="Tagi/treść"></input>
            <br>
            <button class="button-79" type="submit">Szukaj</button>
            <input name="main_category" value="" hidden>
            <input name="category" value="" hidden>
            <input name="region" value="" hidden>
            <input name="main_category" value="" hidden>
            <input value="cla_date desc" name="order" hidden>
            <input value="" name="city" hidden>
        </form>
        <button class="button-79" onclick="myFunction()">Zaawansowane wyszukiwanie</button>



        <script>
            function myFunction() {
                document.getElementById("search").style.display = "none";
                document.getElementById("search2").style.display = "block";


            }
        </script>


    </section>
    <section id="search2">
        <? echo ("$classifieds") ?>
        <? echo ("$error") ?>
        <form id="search_form" action="<?= $this->baseurl ?>" method="post">
            <h1>Szukaj</h1>
            <input type="text" name="body_search" placeholder="Tagi/Tekst"></input>
            <br>
            <select name="main_category" id="main_category">
                <option value="" selected disabled hidden>Kategoria</option>
                <?php
                foreach ($category as $row) {
                ?>
                    <option value="<?php echo $row["cat_id"]; ?>"><?php echo $row["cat_name"]; ?></option>
                <?php
                }
                ?>
            </select>






            <select name="category" id="category" disabled>
                <option value="" selected>Subkategoria</option>
                <script>
                    $(document).ready(function() {
                        $('#main_category').on('change', function() {
                            var category_id = this.value;
                            $.ajax({
                                url: "view/subcategory.php",
                                type: "POST",
                                data: {
                                    category_id: category_id
                                },
                                cache: false,
                                success: function(dataResult) {
                                    $("#category").html(dataResult);

                                }
                            })
                            document.getElementById("category").disabled = false;;


                        });
                    });
                </script>
            </select>

            <br>

            <select name="region" id="region">
                <option value="" selected>Województwo</option>
                <?php
                foreach ($region as $row) {
                ?>
                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]; ?></option>
                <?php
                }

                ?>

            </select>
            <select name="city" id="city" disabled>
                <option value="" selected>Miasto</option>
                <script>
                    $(document).ready(function() {
                        $('#region').on('change', function() {
                            var region_id = this.value;
                            $.ajax({
                                url: "view/city_search.php",
                                type: "POST",
                                data: {
                                    region_id: region_id
                                },
                                cache: false,
                                success: function(dataResult) {
                                    $("#city").html(dataResult);

                                }
                            })
                            document.getElementById("city").disabled = false;;


                        });
                    });
                </script>
            </select>




            <select name="order" id="order">
                <option selected value="cla_date desc">Od najnowszych </option>
                <option value="cla_date asc">Od najstarszych</option>


            </select>
            <br>





            <button class="button-79" type="submit">Szukaj</button>
        </form>

    </section>


    <?php if (!$classifieds) { ?>
        <p>Brak ogłoszeń
        </p>
        <?php } else {
        foreach ($classifieds as $k => $v) { ?>

            <a href="?cla_id=<?= $v['cla_id'] ?>&cmd=show">
                <section id="post">

                    <h1><?php print($v['cla_title']) ?></h1>
                    <?php
                    foreach ($cla_images as $k => $row) {
                        // print_r($row["cla_id"]." drugie: ".$v["cla_id"]);
                        if ($row["cla_id"] == $v["cla_id"]) {
                            $imageURL = 'images/' . $row["img_file"];
                    ?>
                            <img src="<?= $imageURL ?>" id="images" class="images" alt="" />

                    <?php
                            break;
                        }
                    } ?>
                    <br>
            </a>



            <footer>
                <p>Utworzone: <?= $v['cla_date'] ?>, Autor: <?= $v['cla_person'] ?> </p>
                <?php if ($this->u['userlevel'] == 10) { ?>

                    <a class="button-79" href="?cla_id=<?= $v['cla_id'] ?>&cmd=delete">Usuń</a>
                <?php } ?>
            </footer>
            



            </section>
        

    <?php }
    ?>
    <?php
    if ($counter > $counter2) {
        
        $back = $page - 1;

        $next = $page + 1;
        if ($back > 0 && $next <= $pages) {
            ?>
        <section id="post" style="background-color:rgb(255, 255, 255, 0.001);;box-shadow:0px 0px 0px #333;"> 
            <a style="float:center" class="button-79" id="POPRZEDNIA" href="?page=<?=$back?>" placeholder="Back">Back</a>&nbsp;&nbsp;
            <a  class="button-79" id="NASTEPNA" href="?page=<?=$next?>" placeholder="Next">Next</a>
        </section>
    <?php
        }else 
        if ($next <= $pages) {
            ?>
             <section id="post" style="background-color:rgb(255, 255, 255, 0.001);;box-shadow:0px 0px 0px #333;">
    <a  class="button-79" id="NASTEPNA" href="?page=<?=$next?>" placeholder="Next">Next</a>
        </section>
        <?php
        }else if ($back > 0){
            ?>
            <section id="post" style="background-color:rgb(255, 255, 255, 0.001);;box-shadow:0px 0px 0px #333;"> 
            <a style="float:center" class="button-79" id="POPRZEDNIA" href="?page=<?=$back?>" placeholder="Back">Back</a>
            </section>
            <?php
        }

    }

?>

    <?php
    }
} else {

    ?>
    <?php $v = $classified_show;

    ?>


    <section id="single_post">
        <h1><?= $v['cla_title'] ?></h1>
        <p>Tagi: <?= $v['cla_summary'] ?></p>
        <p><h3> <?= $v['cla_text'] ?></h6></p>
        <?php
        //print_r($image);

        foreach ($image as $k => $row) {
            // print_r($row["cla_id"]." drugie: ".$v["cla_id"]);
            if ($row["cla_id"] == $v["cla_id"]) {

                $imageURL = 'images/' . $row["img_file"];
        ?>
                <img src="<?= $imageURL ?>" alt="" /></img>
        <?php }
        } ?>
<br>

        <footer>
        <?php if ($_SESSION['user']['userid'] != "Guest") { ?>
        <?php if (isset($_SESSION['show_contact']) and $_SESSION['show_contact']) { ?>
            Numer telefonu: <?= $v["cla_tel"] ?>
            <br>
            E-mail: <?= $v['cla_email'] ?>
            <br>
            <br>
            <br>


        <?php   } }?>


            Miasto: <?= $cities["name"] ?>
            <br>


            Utworzone: <?= $v['cla_date'] ?>, Autor: <?= $v['cla_person'] ?>


            <?php if ($this->u['userlevel'] == 10) { ?>
            <br>
            <a class="button-79" href="?cla_id=<?= $v['cla_id'] ?>&cmd=delete">Usuń</a>
        <?php } ?>
        <a class="button-79" style="background:blue" href="?cla_id=<?= $v['cla_id'] ?>&cmd=show_contact">Pokaz kontakt</a>
        </footer>




        
    </section>



   




<?php
}
?>

</div>
