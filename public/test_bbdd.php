<?php 

$myServer = "127.0.0.1"; 
$myUser = "sa"; 
$myPass = "qwerty"; 
$myDB = "[RC RCA 2007]"; 

$s = @mssql_connect($myServer, $myUser, $myPass) 
 or die("Couldn't connect to SQL Server on $myServer"); 

$d = @mssql_select_db($myDB, $s) 
 or die("Couldn't open database $myDB"); 

$query = "SELECT [PERS_LOGIN], [PERS_PASSWORD] FROM [RC RCA 2007].[dbo].[ACC_LOGIN]"; 

$result = mssql_query($query); 
$numRows = mssql_num_rows($result); 

 echo "<h1>" . $numRows . " Row" . ($numRows == 1 ? "" : "s") . " Returned </h1>"; 

 while($row = mssql_fetch_array($result)) 
 { 
 echo "<li>" . "PERS_LOGIN: " .$row["PERS_LOGIN"] . " - PERS_PASSWORD: " .$row["PERS_PASSWORD"] . "</li>"; 
 } 


// Close the link to MSSQL
mssql_close($s);
?>
