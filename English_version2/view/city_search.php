<?php
$city = $_POST["region_id"];
$servername = "localhost";
$username = "35804454_php";
$password = "Projekt*90";

$db2 = new mysqli($servername, $username, $password, $username);
$db2->query("SET NAMES 'utf8'");
$query="SELECT * FROM city where region_id = '".$city."' order by name asc";
$cities = mysqli_query($db2,$query);

?>
<option value="" hidden>Choose city </option>
<?php
foreach ($cities as $row) {

?>
  <option value="<?php echo $row["id"]; ?>"><?php echo $row["name"]; ?></option>
<?php  }
?>