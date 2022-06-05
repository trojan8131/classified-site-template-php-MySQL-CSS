<?php
$cat = $_POST["category_id"];
$servername = "localhost";
$username = "35804454_php";
$password = "Projekt*90";

$db2 = new mysqli($servername, $username, $password, $username);
$db2->query("SET NAMES 'utf8'");
$query="SELECT * FROM categories where main_categories = '".$cat."'";
$subcategory2 = mysqli_query($db2,$query);

?>


<option value="" hidden>Wybierz podkategorię</option>
<?php
foreach ($subcategory2 as $row) {
if($cat==$row["main_categories"])
{
?>
  <option value="<?php echo $row["cat_id"]; ?>"><?php echo $row["cat_name"]; ?></option>
<?php } }
?>