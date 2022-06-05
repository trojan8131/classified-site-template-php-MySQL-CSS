<?php
include("Data.php");
require("PHPMailer/PHPMailer.php");
require("PHPMailer/SMTP.php");
require("PHPMailer/Exception.php");

class Notebook
{
    public $u = false, $context, $error = "", $baseurl;
    public $db2, $notes, $user;
    public function __construct()
    {
        $servername = "localhost";
        $username = "35804454_php";
        $password = "Password";
        $this->db2 = new mysqli($servername, $username, $password, $username);
        $this->db2->query("SET NAMES 'utf8'");
        $this->city = new Data($this->db2, "city", array("id", "region_id", "name"), "id", true);
        $this->region = new Data($this->db2, "region", array("id", "name"), "id", true);
        $this->user  = new Data($this->db2, "users", array("userid", "username", "useremail", "userpassword", "userlevel"), "userid", false);
        $this->category  = new Data($this->db2, "main_categories", array("cat_id", "cat_name", "cat_desc"), "cat_id", true);
        $this->subcategory  = new Data($this->db2, "categories", array("cat_id", "cat_name", "cat_desc", "main_categories"), "cat_id", true);
        $this->classifieds  = new Data($this->db2, "classifieds", array("cla_main_category", "cla_id", "cla_title", "cla_summary", "cla_text", "cla_date", "cla_person", "cla_email", "cla_tel", "id_city", "cla_category"), "cla_id", true);
        $this->cla_images = new Data($this->db2, "cla_images", array("cla_id", "slot_id", "img_file"), "slot_id", false);
        $this->guests = new Data($this->db2, "guests", array("id", "date"), "id",false);
        $this->events = new Data($this->db2, "events", array("userid", "IP", "activity", "date", "id_cookie", "id"), "id");
        $this->baseurl = "index.php";
        $this->context =  (isset($_SESSION["context"])) ? $_SESSION["context"] : NULL;
        if (isset($_SESSION["user"]))
            $this->u = $_SESSION["user"];
        else {
            $_SESSION["user"]["userid"] = "Guest";
            $_SESSION["user"]["username"] = "Guest";
            $_SESSION["user"]["userlevel"] = 00;
            $this->u = $_SESSION["user"];
            $this->context="classifieds";
        }
        if (!isset($_COOKIE['id'])) {
            $last_id = $this->guests->getLastItem2("id");
            $new_id = $last_id['id'] + 1;
            $this->guests->insert(array("id" => $new_id, "date" => date("Y-m-d H:i:s")));
            setcookie("id", $new_id, time() + (86400 * 30), "/"); //Jeden dzień
        }
        $admin = $this->user->get("admin");
        if (!isset($admin['userid'])) {
            $x = $this->user->insert(array("userid" => "admin", "username" => "admin", "useremail" => "administrator@aps-edu.pl", "userpassword" => md5("admin"), "userlevel" => 10));
        }
    }
    function died($error)
    {
        echo '<script type="text/javascript"> alert(' . $error . ')</script>';
        die();
    }
    public function add_events($var)
    {
        $this->events->insert(array("userid" => $_SESSION['user']["userid"], "IP" => $_SERVER['HTTP_X_FORWARDED_FOR'], "activity" => $var, "date" => date("Y-m-d H:i:s"), "id_cookie" => $_COOKIE['id']));
    }
    public function login($userid, $pass)
    {
        if (!($this->u = $this->user->get($userid))) {
            $this->error = "Wrong UserID";
            return false;
        }
        if ($this->u["userpassword"] != md5($pass)) {
            $this->error = "Wrong password!";
            return false;
        }
        $_SESSION = array();
        session_regenerate_id();
        $_SESSION["token"] = md5(session_id() . __FILE__);
        $_SESSION["user"] = $this->u;
        $_SESSION["context"] = $this->context = "classifieds";
        $this->add_events("Loging in");

        $this->reload();
    }
    public function change_mail($mail1, $mail2)
    {
        $users = $this->user->getAll();
        if ($this->u["useremail"] != $mail1) {
            $this->error = "Wrong e-mail address!";
            return false;
        }
        foreach ($users as $k => $v) {
            if ($v["useremail"] == $mail2) {
                print_r($v["useremail"]);
                $this->error = "Sorry, that email is already taken.";
                return false;
            }
        }

        $query = "UPDATE `users` SET `useremail` = '" . $mail2 . "' WHERE `users`.`userid` = '" . $this->u["userid"] . "'";
        $this->db2->query($query);
        $_SESSION = array();
        session_regenerate_id();
        $_SESSION["token"] = md5(session_id() . __FILE__);
        $this->u = $this->user->get($this->u["userid"]);
        $_SESSION["user"] = $this->u;
        $_SESSION["context"] = $this->context = "user_informations";
        $this->add_events("E-mail change");
        $this->mail_info($this->u["useremail"], "Zmiana maila", "<p>Dzień dobry,</p>
        <p>Potwierdzamy zmianę e-maila w serwisie Zgubi.one.</p>
        <p></p>
        <p>Pozdrawiamy,</p>
        <p>Zespół Zgubi.one</p>");
        $this->reload();
    }
    public function mail_info($receiver, $mail_title, $mail_text)
    {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = "UTF-8";
        $mail->Host = "serwer2237983.home.pl"; /* Zależne od hostingu poczty*/
        $mail->Port = 587; /* Zależne od hostingu poczty, czasem 587 */
        $mail->SMTPAuth = true;
        $mail->IsHTML(true);
        $mail->Username = "info@aps-edu.pl"; /* login do skrzynki email często adres*/
        $mail->Password = "Projekt*90"; /* Hasło do poczty */
        $mail->setFrom('info@aps-edu.pl', 'ZGUBI.ONE'); /* adres e-mail i nazwa nadawcy */
        $mail->AddAddress($receiver); /* adres lub adresy odbiorców */
        $mail->Subject = $mail_title; /* Tytuł wiadomości */
        $mail->Body = $mail_text;
        if (!$mail->Send()) {
           // echo "Błąd wysyłania e-maila: " . $mail->ErrorInfo;
        } else {
           // echo "Wiadomość została wysłana!";
        }
    }
    public function change_password($pass1, $pass2, $pass3)
    {
        if ($this->u["userpassword"] != md5($pass1)) {
            $this->error = "Wrong password";
            return false;
        }
        if ($pass2 != $pass3) {
            $this->error = "Passwords are not the same";
            return false;
        }
        $query = "UPDATE `users` SET `userpassword` = '" . md5($pass2) . "' WHERE `users`.`userid` = '" . $this->u["userid"] . "'";
        $this->db2->query($query);
        $_SESSION = array();
        session_regenerate_id();
        $_SESSION["token"] = md5(session_id() . __FILE__);
        $this->u = $this->user->get($this->u["userid"]);
        $_SESSION["user"] = $this->u;
        $_SESSION["context"] = $this->context = "user_informations";
        $this->add_events("Zmiana hasła");
        $this->mail_info($this->u["useremail"], "Zmiana hasła w serwisie Zgubi.one", "<p>Dzień dobry,</p>
            <p>Twoje hasło do serwisu Zgubi.one zostało zmienione</p>
            <p></p>
            <p>Pozdrawiamy,</p>
            <p>Zespół Zgubi.one</p>");
        $this->reload();
    }
    public function logout()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        $_SESSION["context"] = $this->context = "classifieds";
        $this->reload();
    }
    public function register($userid, $username, $email, $pass,$pass1)
    {
        if ($u = $this->user->get($userid)) {
            $this->error .= "UserID is already taken.";
            return false;
        }
        if ($pass!=$pass1) {
            $this->error .= "Passwords are not the same.";
            return false;
        }
        if ($u = $this->user->get($username,"username")) {
            $this->error .= "User name is already taken.";
            return false;
        }
        if ($u = $this->user->get($email,"useremail")) {
            $this->error .= "E-mail name is already taken.";
            return false;
        }
        $u = array("userid" => $userid, "username" => $username, "useremail" => $email, "userpassword" => md5($pass), "userlevel" => 0);
        $this->user->insert($u);
        $this->mail_info($email, "Rejestracja w serwisie Zgubi.one", "<p>Dzień dobry,</p>
                <p>Potwierdzamy rejestrację w serwisie Zgubi.one</p>
                <p>Twój UserID: " . $userid . "</p>
                <p></p>
                <p>Pozdrawiamy,</p>
                <p>Zespół Zgubi.one</p>");
        $_SESSION["user"] = $u;
        $_SESSION["context"] = $this->context = "classifieds";
        $this->add_events("Rejestracja użytkownika");
        $this->reload();
        return true;
    }
    public function insert_classified( $cla_title, $cla_summary, $cla_text, $cities, $main_category, $category, $cla_tel, $cla_date, $cla_person, $cla_email)
    {
        return $this->classifieds->insert(array("cla_title" => $cla_title, "cla_summary" => $cla_summary, "cla_text" => $cla_text, "id_city" => $cities, "cla_category" => $category, "cla_main_category" => $main_category, "cla_tel" => $cla_tel, "cla_date" => $cla_date, "cla_person" => $cla_person, "cla_email" => $cla_email));
        $this->reload();
    }
    public function delete_classified($cla_id)
    {
        $classified = $this->classifieds->get($cla_id, "cla_id");
        $user_d=$this->user->get($classified["cla_person"],"userid");
        $this->mail_info($user_d["useremail"], "Usunięcie ogłoszenia w Zgubi.one", "<p>Dzień dobry,</p>
        <p>Twoje ogłoszenie \"" . $classified["cla_title"] . "\" zostało usunięte</p>
        <p></p>
        <p>Pozdrawiamy,</p>
        <p>Zespół Zgubi.one</p>");
        $this->classifieds->delete($cla_id, "cla_id");
        $this->reload();
    }
    public function delete_user($userid)
    {
        $user = $this->user->get($userid, "userid");
        $this->mail_info($user["useremail"], "Usunięcie użytkownika w Zgubi.one", "<p>Dzień dobry,</p>
        <p>Twoje konto zostało usunięte z serwisu Zgubi.one</p>
        <p></p>
        <p>Pozdrawiamy,</p>
        <p>Zespół Zgubi.one</p>");
        if ($this->user->delete($userid, "userid")) {
            $this->reload();
        } else return false;
    }
    public function update_user($userid)
    {
        if ($u = $this->user->get($userid)) {
            $u['userlevel'] = ($u['userlevel'] == 10) ? 0 : 10;
            if ($this->user->update($u)) $this->reload();
            else return false;
        } else return false;
    }
    public function count_botes()
    {
        if ($p = $this->classifieds->getAll()) return count($p);
        else return 0;
    }
    public function last_classified()
    {
        $last = $this->classifieds->getLastItem("cla_date");
        return $last["cla_date"];
    }
    public function process()
    {
        if (isset($_SESSION["token"]) and $_SESSION["token"] != md5(session_id() . __FILE__)) $this->logout();
        if (isset($_POST['userid']) and $_POST['userid'] != "" and isset($_POST['pass'])) {
            if (!$this->login($_POST['userid'], $_POST['pass'])) {
                $data["error1"] = $this->error;
            }
        }
        if (isset($_POST['userid']) and isset($_POST['pass1']) and $_POST['pass1'] != "" ) {
            if (!$this->register($_POST['userid'], $_POST['username'], $_POST['email'], $_POST['pass1'],$_POST['pass2']))
                $data["error"] = $this->error;
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'register') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = "register";
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'user_informations') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = "user_informations";
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'admin_panel') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = "admin_panel";
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'login') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = "login";
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'logout') {
            $this->logout();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'add_classified') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $data["error1"] = $this->error;
            $_SESSION['context'] = $this->context = 'add_classified';
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'classifieds') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = 'classifieds';
            $this->reload();
        }
        if (isset($_GET['cmd']) and $_GET['cmd'] == 'user_classifieds') {
            $this->add_events($_SERVER['REQUEST_URI']);
            $_SESSION['context'] = $this->context = 'user_classifieds';
            $this->reload();
        }
        if ($this->context) {
            $data["user"] = $this->user->getAll();
            $data["events"] = $this->events->getAll(false, false, "date desc");
            $data["region"] = $this->region->getAll(false, false, "id desc");
            $data["city"] = $this->city->getAll(false, false, "id desc");
            $data["category"] = $this->category->getAll(false, false, "cat_id desc");
            $data["subcategory"] = $this->subcategory->getAll(false, false, "cat_id desc");
            $data["classifieds"] = $this->classifieds->getAll(false, false, "cla_id desc");
            $data["cla_images"] = $this->cla_images->getAll(false, false, "cla_id desc");
            $data["date"] = $this->last_classified();
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'userlist') {
                $this->add_events($_SERVER['REQUEST_URI']);
                $_SESSION['userlist'] = ($_SESSION['userlist']) ? false : true;
                $this->reload();
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'categories') {
                $this->add_events($_SERVER['REQUEST_URI']);
                $_SESSION['categories'] = ($_SESSION['categories']) ? false : true;
                $this->reload();
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'events') {
                $this->add_events($_SERVER['REQUEST_URI']);
                $_SESSION['events'] = ($_SESSION['events']) ? false : true;
                $this->reload();
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'changeuser' and $this->u['userlevel'] == 10) {
                if ($_GET['userid'] != "admin") $this->update_user($_GET['userid']);
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'deluser' and $this->u['userlevel'] == 10) {
                if ($_GET['userid'] != "admin") {
                    if ($p = $this->classifieds->getAll($_GET['userid'], 'cla_person')) {
                        foreach ($p as $k) {
                            $this->classifieds->delete($k["cla_person"], "cla_person");
                        }
                    }
                    $this->add_events("Delete user: ".$_GET['userid']);
                    $this->delete_user($_GET['userid']);
                }
            }
            if (
                isset($_POST['userid']) or isset($_POST['id_cookie']) or isset($_POST['IP']) or isset($_POST['acticity'])
                or (isset($_POST['start_search']) and isset($_POST['end_search']))
            ) {
                $start_data = $_POST['start_search'];
                $end_data = $_POST['end_search'];
                if ($data["events"] = $this->events->search_events(
                    array(
                        "userid" => $_POST['userid'], "id_cookie" => $_POST['id_cookie'],
                        "IP" => $_POST['IP'], "activity" => $_POST['acticity']
                    ),
                    date("Y-m-d H:i:s", strtotime("$start_data 00:00:00")),
                    date("Y-m-d H:i:s", strtotime("$end_data 23:59:59"))
                )) {
                    $this->add_events("Seraching for classifieds");
                }
            }
        }
        if ($this->context == 'user_classifieds') {
            $data["user_classifieds"] = $this->classifieds->getAll($this->u["userid"], "cla_person", "cla_date");
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'show') {
                $data['classified_show'] = $this->classifieds->get($_GET['cla_id'], "cla_id");
                $data['cities'] = $this->city->get($data['classified_show']["id_city"], "id")["name"];
                $data["image"] = $this->cla_images->getAll(false, false, "slot_id", true);
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'delete') {
                $this->add_events("Delete classified");
                $this->delete_classified($_GET['cla_id']);
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'refresh') {
                $this->mail_info($this->u["useremail"], "Przedłużenie ogłoszenia w Zgubi.one", "<p>Dzień dobry,</p>
                <p>Twoje ogłoszenie zostało przełużone Zgubi.one</p>
                <p></p>
                <p>Pozdrawiamy,</p>
                <p>Zespół Zgubi.one</p>");
                $this->classifieds->update_refresh("cla_date", date("Y-m-d H:i:s"), "cla_id", $_GET["cla_id"]);
            }
            if (isset($_POST['body_search']) or isset($_POST['main_category']) or isset($_POST['category'])) {

                $data["classifieds"] = $this->classifieds->search2($_POST['body_search'], $_POST['main_category'], $_POST['category']);
            }
        }
        if ($this->context == 'categories') {
      
        }
        if ($this->context == 'user_informations') {
            if (!empty($_GET['send'])) {
                $this->mail_info("info@aps-edu.pl", "Kebaby", "<p>Dzień dobry,</p>
                <p>Potwierdzamy rejestrację w serwisie Zgubi.one.</p>
                <p></p>
                <p>Pozdrawiamy,</p>
                <p>Zespół Zgubi.one</p>");
            }
            if (isset($_POST['email'])) {
                $v=$_POST['email_new'];
                if (!$this->change_mail($_POST['email'], $_POST['email_new'])) {

                    $data["error1"] = $this->error;
                } else {
                    $data["error1"] = "";
       
                }
            }
            if (isset($_POST['pass1'])) {
                if (!$this->change_password($_POST['pass1'], $_POST['pass2'], $_POST['pass3'])) {
                    $data["error2"] = $this->error;
                } else {
                    $data["error2"] = "";
                }
            }
        }
        if ($this->context == 'admin_panel') {
            $data["category"] = $this->category->getAll(false, false, "cat_id ");
            $data["subcategory"] = $this->subcategory->getAll(false, false, "cat_id desc");
            $data["user"] = $this->user->getAll();
            if (isset($_POST['cat_name'])) {
                if (!$this->category->insert(array("cat_name" => $_POST['cat_name'], "cat_desc" => $_POST['cat_desc']))) {
                    $data["error"] = $this->error;
                } else {
                    $data["error"] = "";
                }
                $this->reload();
            }
            if (isset($_POST['cat_name_sub'])) {
                if (!$this->subcategory->insert(array("cat_name" => $_POST['cat_name_sub'], "cat_desc" => $_POST['cat_desc_sub'], "main_categories" => $_POST['main_categories']))) {
                    $data["error"] = $this->error;
                } else {
                    $data["error"] = "";
                }
                $this->reload();
            }
        }
        if ($this->context == 'add_classified') {
            if (isset($_POST['cla_title'])) {



                if (count($_FILES['img_file']['name']) > 3) {
                    $_SESSION["error"] = "To much files, choose max three.";
                    return false;
                }
                if (!$cities = $this->city->get($_POST['city'], "name")) {
                    $_SESSION["error"] = "Wrong city";
                    return false;
                }
                ;
                $this->insert_classified( htmlentities($_POST['cla_title']), htmlentities($_POST['cla_summary']), htmlentities($_POST['cla_text']), $cities["id"], $_POST["main_category"], $_POST["category"], $_POST['cla_tel'], $_POST['cla_date'], htmlentities($_POST['cla_person']), $_POST['cla_email']);
                $targetDir = "images/";
                $countfiles = count($_FILES['img_file']['name']);
                for ($i = 0; $i < $countfiles; $i++) {
                    $fileName = $this->cla_images->max2()["max"] + 1;
                    $targetFilePath = $targetDir . $fileName;
                    if (move_uploaded_file($_FILES["img_file"]["tmp_name"][$i], $targetFilePath)) {
                        // Insert image file name into database
                        $id = $this->cla_images->max();
                        $id2 = $this->cla_images->max2();
                        $insert = $this->cla_images->insert(array("cla_id" => $id["max"], "slot_id" => $id2["max"] + 1, "img_file" => $fileName));
                    }
                }
                unset($_SESSION["error"]);
                $this->reload();
            }
        }
        if ($this->context == 'classifieds') {
            $data["user"] = $this->user->getAll();
            $data["classifieds"] = $this->classifieds->getAll(false, false, "cla_id");
            $data["subcategory"] = $this->subcategory->getAll(false, false, "cat_id desc");
            if (isset($_GET['cmd']) and ($_GET['cmd'] == 'show' or $_GET['cmd'] == 'show_contact')) {
                $data['classified_show'] = $this->classifieds->get($_GET['cla_id'], "cla_id");
                $data['cities'] = $this->city->get($data['classified_show']["id_city"], "id");
                $data["image"] = $this->cla_images->getAll(false, false, "slot_id", true);
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'delete') {
                $this->add_events("Usunięcie ogłoszenia");
                $this->delete_classified($_GET['cla_id']);
            }
            if (isset($_GET['cmd']) and $_GET['cmd'] == 'show_contact') {
                $_SESSION['show_contact'] = ($_SESSION['show_contact']) ? false : true;

            }
            if (isset($_POST['body_search']) or isset($_POST['main_category']) or isset($_POST['category']) or isset($_POST['region']) or isset($_POST['order'])) {
                if ($data["classifieds"] = $this->classifieds->search_classified(
                    array(
                        "cla_category" => $_POST['category'],"id_city"=>$_POST["city"], "cla_main_category" => $_POST['main_category'],
                        "region" => $_POST['region'],"cla_text" => $_POST['body_search']
                    ),
                    $_POST['order']))
                    $this->add_events("Wyszukiwanie");
               // $data["classifieds"] = $this->classifieds->search2($_POST['body_search'], $_POST['main_category'], $_POST['category']);
            }
        }
        return $data;
    }
    public function makepage($data)
    {
        //$data["classifieds"] = $this->classifieds->getAll(false, false, "cla_id");
        $this->delete_old();
        if (!isset($_SESSION['context']))
            $_SESSION['context'] = "classifieds";
        $this->view("header", $data);
        //$this->view("print", $data);
        switch ($_SESSION['context']) {
            case "user_informations":
                $this->view("user_informations", $data);
                break;
            case "register":
                $this->view("register", $data);
                break;
            case "categories":
                $this->view("admin_panel", $data);
                $this->view("events", $data);
                $this->view("categories", $data);
                break;
            case "add_classified":
                $this->view("add_classified", $data);
                break;
            case "classifieds":
                $this->view("classifieds", $data);
                break;
            case "user_classifieds":
                $this->view("user_classifieds", $data);
                break;
            case "login":
                $this->view("login", $data);
                break;
            case "admin_panel":
                $this->view("admin_panel", $data);
                $this->view("events", $data);
                break;
            default:
                $this->view("events", $data);
                $this->view("userinfo", $data);
                $this->view("classifieds", $data);
                break;
        }
        $this->view("footer", $data);
    }
    public function view($view, $data = NULL, $tostring = false)
    {
        $buf = "";
        if ($data) extract($data);
        if ($tostring) ob_start();
        include("view/$view.php");
        if ($tostring) {
            $buf = ob_get_contents();
            ob_end_clean();
            return $buf;
        }
    }
    protected function reload()
    {
        header("Location: $this->baseurl");

        exit;
    }
    public function delete_old()
    {
        /*
        $classifieds = $this->classifieds->getAll(false, false, "cla_id desc");
        foreach ($classifieds as $v) {
            if ((abs(time() - strtotime($v["cla_date"])) / 604800) > 1) {
                $this->classifieds->delete($v["cla_id"], "cla_id");
            }
        }
        */
    }
}
