<?php if (!(isset($_GET['cmd']) and ($_GET['cmd'] == 'show_contact' or $_GET['cmd'] == 'show'))) {


?>

    <section id="search">
        <? echo ("$classifieds") ?>
        <? echo ("$error") ?>
        <form id="search_form" action="<?= $this->baseurl ?>" method="post">
            <h1>Search</h1>
            <input type="text" name="body_search" placeholder="Text/Tags"></input>
            <br>
            <button class="button-79" type="submit">Search</button>
            <input name="main_category" value="" hidden>
            <input name="category" value="" hidden>
            <input name="region" value="" hidden>
            <input name="main_category" value="" hidden>
            <input value="cla_date desc" name="order" hidden>
            <input value="" name="city" hidden>
        </form>
        <button class="button-79" onclick="myFunction()">Advance</button>



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
            <h1>Search</h1>
            <input type="text" name="body_search" placeholder="Text/Tags"></input>
            <br>
            <select name="main_category" id="main_category">
                <option value="" selected disabled hidden>Category</option>
                <?php
                foreach ($category as $row) {
                ?>
                    <option value="<?php echo $row["cat_id"]; ?>"><?php echo $row["cat_name"]; ?></option>
                <?php
                }
                ?>
            </select>






            <select name="category" id="category" disabled>
                <option value="" selected>Subcategory</option>
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
                <option value="" selected>Region</option>
                <?php
                foreach ($region as $row) {
                ?>
                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]; ?></option>
                <?php
                }

                ?>

            </select>
            <select name="city" id="city" disabled>
                <option value="" selected>city</option>
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
                <option selected value="cla_date desc">From the latest </option>
                <option value="cla_date asc">From the oldest</option>


            </select>
            <br>





            <button class="button-79" type="submit">Search</button>
        </form>

    </section>


    <?php if (!$classifieds) { ?>
        <p>No classifieds
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
                <p>Created: <?= $v['cla_date'] ?>, Author: <?= $v['cla_person'] ?> </p>
                <?php if ($this->u['userlevel'] == 10) { ?>

                    <a class="button-79" href="?cla_id=<?= $v['cla_id'] ?>&cmd=delete">Delete</a>
                <?php } ?>
            </footer>


            </section>


    <?php }
    }
} else {

    ?>
    <?php $v = $classified_show;

    ?>


    <section id="single_post">
        <h1><?= $v['cla_title'] ?></h1>
        <p>Tags: <?= $v['cla_summary'] ?></p>
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
            Phone number: <?= $v["cla_tel"] ?>
            <br>
            E-mail: <?= $v['cla_email'] ?>
            <br>
            <br>
            <br>


        <?php   } }?>


            City: <?= $cities["name"] ?>
            <br>


            Created: <?= $v['cla_date'] ?>, Author: <?= $v['cla_person'] ?>


            <?php if ($this->u['userlevel'] == 10) { ?>
            <br>
            <a class="button-79" href="?cla_id=<?= $v['cla_id'] ?>&cmd=delete">Delete</a>
        <?php } ?>
        <a class="button-79" style="background:blue" href="?cla_id=<?= $v['cla_id'] ?>&cmd=show_contact">Show contact</a>
        </footer>





    </section>







<?php
}


?>
</div>