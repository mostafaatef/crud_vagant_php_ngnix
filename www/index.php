<?php

$meminstance = new Memcache();
$meminstance->connect("localhost", 11211);

$connect=mysql_connect("localhost","root","123");
mysql_select_db("crud",$connect);

$user_name;
$msg_content;
$msg_date;

if(isset($_POST["insert"])){
	if($_POST["insert"]=="yes"){
	$user_name=$_POST["user_name"];
	$msg_content=$_POST["msg_content"];

$query="insert into messages(user_name, msg_content, msg_date) values('$user_name', '$msg_content',NOW())";
if(mysql_query($query))
echo "<center>Message Inserted!</center><br>";
	}
	header("Location: " . $_SERVER["SCRIPT_NAME"]);
	exit();
}

if(isset($_POST["update"])){
	if($_POST["update"]=="yes"){
	$user_name=$_POST["user_name"];
	$msg_content=$_POST["msg_content"];

$query="update messages set user_name='$user_name' , msg_content='$msg_content' where msg_id=".$_POST['msg_id'];
if(mysql_query($query))
echo "<center>Message Updated</center><br>";
	}
		header("Location: " . $_SERVER["SCRIPT_NAME"]);
	exit();
}

if(isset($_GET['operation'])){
if($_GET['operation']=="delete"){
$query="delete from messages where msg_id=".$_GET['msg_id'];	
if(mysql_query($query))
echo "<center>Message Deleted!</center><br>";

}
	header("Location: " . $_SERVER["SCRIPT_NAME"]);
	exit();
}
?>
<html>
<body>
<form method="post" action="index.php">
<table align="center" border="0">
<tr>
<td>user name:</td>
<td><input type="text" name="user_name" /></td>
</tr>
<tr>
<td>content:</td>
<td><input type="text" name="msg_content" /></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right">
<input type="hidden" name="insert" value="yes" />
<input type="submit" value="Insert Record"/>
</td>
</tr>
</table>
</form>
<?php

if(isset($_GET['operation'])){
if($_GET['operation']=="edit"){
?>
<form method="post" action="index.php">
<table align="center" border="0">
<tr>
<td>user name:</td>
<td><input type="text" name="user_name" value="<?php echo $_GET['user_name']; ?>" /></td>
</tr>
<tr>
<td>content:</td>
<td><input type="text" name="msg_content" value="<?php echo $_GET['msg_content']; ?>"/></td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="right">
<input type="hidden" name="msg_id" value="<?php echo $_GET['msg_id'] ?>" />
<input type="hidden" name="update" value="yes" />
<input type="submit" value="update Record"/>
</td>
</tr>
</table>
</form>
<?php
}}
?>

<?php
$query="select * from messages order by msg_id DESC";

$querykey = "KEY" . md5($query);

$result = $meminstance->get($querykey);

if (!$result) {
$result=mysql_query($query);
}

if(mysql_num_rows($result)>0){
	echo "<table align='center' border='1'>";
	echo "<tr>";
	echo "<th>Id</th>";
	echo "<th>User name</th>";
	echo "<th>Content</th>";
	echo "<th>Date</th>";
	echo "</tr>";
	while($row=mysql_fetch_array($result)){
	echo "<tr>";
	echo "<td>".$row['msg_id']."</td>";	
	echo "<td>".$row['user_name']."</td>";	
	echo "<td>".$row['msg_content']."</td>";
	echo "<td>".$row['msg_date']."</td>";
	echo "<td><a href='index.php?operation=edit&msg_id=".$row['msg_id']."&user_name=".$row['user_name']."&msg_content=".$row['msg_content']."'>edit</a></td>";
	echo "<td><a href='index.php?operation=delete&msg_id=".$row['msg_id']."'>delete</a></td>";	
	echo "</tr>";
	}
	echo "</table>";
}
else{
echo "<center>No Messages Found!</center>";	
}

?>
</body>
</html>