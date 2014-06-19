<?php
require_once("../core/init.php");
//echo "<pre>";
//$user = new UserProfile();
//print_r($user->get_id('myzani_creed@hotmail.com'));
//$filename ="excelreport.xls";
//$contents = "testdata1 \t testdata2 \t testdata3 \t \n";
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename='.$filename);
//echo $contents;

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment;Filename=document_name.xls");

echo "<html>";
echo "<body>";

echo "<table>";
echo "<tbody>";
echo "<tr>";
echo "<td>hello1</td>";
echo "<td>hello2</td>";
echo "</tr>";
echo "</tbody>";
echo "</table>";


echo "</body>";
echo "</html>";

?>

<html>
    <body>
        <form action="testuser" method="post" enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file"><br>
            <input type="submit" name="submit" value="Submit">
        </form>

    </body>
</html>
