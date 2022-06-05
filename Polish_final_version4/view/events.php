<nav>
    <section class="user-info">

        <?php if (isset($_SESSION['events']) and $_SESSION['events']) { ?>
            <br>
            <form action="<?= $this->baseurl ?>" method="post">

                <header>
                    <h2>Search for events</h2>
                </header>


                <input style="width:auto" type="input" name="userid" placeholder="UserID">
                <input style="width:auto" type="input" name="id_cookie" placeholder="CookieID">
                <input style="width:auto" type="input" name="IP" placeholder="IP">
                <input style="width:auto" type="input" name="acticity" placeholder="Event">
                <br>
                From<input style="width:auto" type="date" name="start_search"> To <input style="width:auto" type="date" name="end_search"><br>
                <button type="submit">Szukaj</button>
            </form>

            <br />
            <table>
                <tr>
                    <th>UserID</th>
                    <th>CookieID</th>
                    <th>IP</th>
                    <th>Event</th>
                    <th>Date</th>
                </tr>
                <?php foreach ($events as $k => $v) { ?>
                    <tr>
                        <td><?= $v['userid'] ?></td>
                        <td><?= $v['id_cookie'] ?></td>
                        <td><?= $v['IP'] ?></td>
                        <td><?= $v['activity'] ?></td>
                        <td><?= $v['date'] ?></td>
                    <?php } ?>
                    </tr>
                <?php } ?>
            </table>

    </section>
</nav>