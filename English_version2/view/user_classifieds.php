




    <?php if (!(isset($_GET['cmd']) and $_GET['cmd'] == 'show')) { ?>
        
            <?php if (!$user_classifieds) { ?>
                <p>No Classifieds
                </p>
                <?php } else {
                foreach ($user_classifieds as $k => $v) { ?>
        

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
                        City: <?= $cities ?> <br>
                        Created: <?= $v['cla_date'] ?>, Author: <?= $v['cla_person'] ?>
                            <br>
                            <a class="button-79" style="background:green" href="?cla_id=<?= $v['cla_id'] ?>&cmd=refresh">Refresh</a>

                                <a class="button-79" href="?cla_id=<?= $v['cla_id'] ?>&cmd=delete">Delete</a>
                  
                    </footer>
                            </section>
        <?php
        }
        ?>
