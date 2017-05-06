<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = intval($_GET['q']);



mysqli_select_db($con,"ajax_demo");
$sql="SELECT * FROM ticket WHERE id_ticket = '".$q."'";
$result = mysqli_query($sql);

echo "<table>
<tr>
<th>id_ticket</th>
<th>name_ticket</th>
<th>price_kid</th>
<th>price_adult</th>
<th>price_older</th>
<th>detail_ticket</th>
</tr>";
while($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['id_ticket'] . "</td>";
    echo "<td>" . $row['name_ticket'] . "</td>";
    echo "<td>" . $row['price_kid'] . "</td>";
    echo "<td>" . $row['price_older'] . "</td>";
    echo "<td>" . $row['detail_ticket'] . "</td>";
    echo "</tr>";
}
echo "</table>";

?>
</body>
</html>
