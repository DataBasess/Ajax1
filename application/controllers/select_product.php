<?php

/*
 * connection database
 */
$Connect = mysql_connect('localhost', 'root', '21519097') or die('Error connecting to MySQL');
mysql_select_db('package', $Connect) or die('Database sysapp does not exist!');
mysql_query('SET NAMES UTF8');
/*
 * check POST
 */
$categorie_id = isset($_POST['categories']) ? $_POST['categories'] : "";
$Query = mysql_query("SELECT * FROM ml_products WHERE categories_id='{$categorie_id}'");
$Rows = mysql_num_rows($Query);
if ($Rows > 0) {
    while ($Result = mysql_fetch_array($Query)) {
        echo "<option value=\"" . $Result['id'] . "\">" . $Result['product_name'] . "</option>";
    }
}else{
    echo "<option value=\"\">ไม่มีสินค้าในหมวดหมู่ที่เลือก</option>";
}