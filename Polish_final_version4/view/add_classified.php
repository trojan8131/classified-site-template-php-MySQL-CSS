<section id="add_classified">

    <?php if ($_SESSION['user']['userid'] != "Guest") { ?>
        <form action="<?= $this->baseurl ?>" id="add_form" method="post" enctype="multipart/form-data">
            
                <h2> Znalazłeś coś? </h2>
            
            <input type="text" name="cla_title" required placeholder="Tytuł ogłoszenia"><br />
            <input type="text" name="cla_summary" required placeholder="Tagi"><br />
            <textarea name="cla_text" cols="80" rows="10" required placeholder="Treść ogłoszenia"></textarea><br />
            <script>
                var countries = [<?php
                                    foreach ($city as $k => $row) {
                                        echo ('"' . $row["name"] . '",');
                                    } ?>]
                $(function() {
                    $("#city").autocomplete({
                        source: countries
                    });
                });
                countries.forEach(function(entry) {
                    console.log(entry);
                });
            </script>
            <?php
            ?>
            <input id="city" name="city" placeholder="Miasto" required> </input>
            <br>
            <select name="main_category" id="main_category" required>
                <option value="" selected disabled hidden>Kategoria</option>
                <?php
                foreach ($category as $row) {
                ?>
                    <option value="<?php echo $row["cat_id"]; ?>"><?php echo $row["cat_name"]; ?></option>
                <?php
                }
                ?>
            </select>
            <select name="category" id="category" required disabled>
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
                            document.getElementById("category").disabled=false;
                            ;


                        });
                    });
                </script>
            </select>







            <input type='tel' name="cla_tel" value="" placeholder="Numer telefonu" pattern="[0-9]{9}" required \>
            <input type="hidden" name="cla_date" value="<?= date('Y-m-d H:i:s') ?>" \>
            <input type="hidden" name="cla_person" value="<?= $_SESSION['user']['userid'] ?>" \>
            <input type="hidden" name="cla_email" value="<?= $_SESSION['user']['useremail'] ?>" \>


            <input type="file" name="img_file[]" accept="image/*" multiple>
                    <br>
            <button class="button-79" type="submit">Dodaj</button>
            <?php $error1 = $_SESSION["error"] ?>
            <?= (isset($_SESSION["error"]) ? "<div class=\"error\">($error1)</div>" : ""); ?>
        </form>

    <?php }
    ?>
</section>