<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="Your description goes here" />
    <meta name="keywords" content="your,keywords,goes,here" />
    <meta name="author" content="Your Name" />
    <link rel="stylesheet" type="text/css" href="sxndata.css" title="Variant Duo" media="screen,projection" />
    <title>BSA Universe</title>
</head>
<body style="background-color:#5D6D7E;vertical-align:middle;text-align:center; ">
<form action="index.php?action=search" id="bsakey" method="post">
  <input type="hidden" name="action" value="search">
  <input type="text" name="key">
  <input type="checkbox" name="logic" value="or">
  <br><input type="submit">
</form>
<form action="index.php?action=store" id="bsacom" method="post">
  <input type="hidden" name="action" value="store">
  <textarea name="com" rows="5" cols="40">
</textarea>
  <br><input type="submit">
</form>


<?php
//=============================================================
$pswd = "amazon";
//=============================================================

//=============================================================
// Connect and open database - create if non-exist
//=============================================================
$mysqli = new mysqli('127.0.0.1', 'root', $pswd,'bsa');
if ($mysqli->connect_errno) 
{
    echo "<br>1Errno: " . $mysqli->connect_errno . "\n";
    echo "<br>2Error: " . $mysqli->connect_error . "\n";
    $mysqli = new mysqli('127.0.0.1', 'root', $pswd) or die(".....");
    $sql = "CREATE DATABASE bsa";
    if (!$result = $mysqli->query($sql)) 
    {
        echo "Query: " . $sql . "\n";
        echo "<br>3Errno: " . $mysqli->errno . "\n";
        echo "<br>4Error: " . $mysqli->error . "\n";
        exit;
    }
    else
    {
        echo("<br>Database created and initated<br>");
        $mysqli->select_db("bsa");
        $sql = sprintf("CREATE TABLE universe (
        id INT(9) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        data TEXT,
        owner INT(6) UNSIGNED,
        ts TIMESTAMP)");
        if (!$result = $mysqli->query($sql)) 
        {
            echo "Query: " . $sql . "\n";
            echo "<br>5Errno: " . $mysqli->errno . "\n";
            echo "<br>6Error: " . $mysqli->error . "\n";
            exit;
        }
        else
        {
            echo("<br>Table created<br>");
        }
    }
    exit;
}
$mysqli->select_db("bsa");


//=============================================================
// GET and POST
//=============================================================
$action = $_GET['action'];

//=============================================================
// Search
//=============================================================
if($action == 'search')
{
    $logic = $_POST['logic'];
    $key = $_POST['key'];
    $pieces = explode(" ", $key);

    //$sql = "SELECT * FROM universe WHERE INSTR(data,'{$key}') > 0";
    $sql = "SELECT * FROM universe WHERE ";
    foreach($pieces as $i =>$key) 
    {
       $i >0;
       if($logic == 'or')
            echo("<b>Search-OR:</b> $key<br>");
        else
            echo("<b>Search-AND:</b> $key<br>");
        
       if($logic == 'or')
            $sql = $sql."INSTR(data,'{$key}') > 0 OR ";
       else
            $sql = $sql."INSTR(data,'{$key}') > 0 AND ";
    }
    if($logic == 'or')
        $sql = $sql."INSTR(data,'123456789') > 0";
    else
        $sql = $sql."INSTR(data,'') > 0";

    //echo "SQL: " . $sql . "\n";
    if (!$result = $mysqli->query($sql)) 
    {
        echo "Query: " . $sql . "\n";
        echo "<br>7Errno: " . $mysqli->errno . "\n";
        echo "<br>8Error: " . $mysqli->error . "\n";
        exit;
    }
    if ($result->num_rows === 0) 
    {
        echo "<i>No match</i>";
    }
    else
    {
        echo("<table border=1><th>Id</th><th>Data</th><th>ts</th>");
        for($ii = 1; $ii <= $result->num_rows; $ii++)
        {
            echo("<tr>");
            $item = $result->fetch_assoc();
            $id = $item['id'];
            echo "<td><a href=\"index.php?action=edit&id=".$id."\">".$id."</a></td> ";
            echo "<td style=\"width: 300px;\">".$item['data'],"</td>";
            echo "<td><a href=\"index.php?action=delete&id=".$id."\">".$item['ts'],"</a></td></tr>";
        }
        echo("</table>");
    }
    $result->free();
}

//=============================================================
// Store data
//=============================================================
if($action == 'store')
{
    $com = $_POST['com'];
    if($com)
    {
        $sql = "INSERT INTO universe (data, owner) VALUES (\"".$com."\", 1)";
        if (!$result = $mysqli->query($sql)) 
        {
            echo "Query: " . $sql . "\n";
            echo "<br>9Errno: " . $mysqli->errno . "\n";
            echo "<br>10Error: " . $mysqli->error . "\n";
            exit;
        }
    }
    else {
            echo("no data added<br>");
       }   
}

//=============================================================
// Delete data
//=============================================================
if($action == 'delete')
{
    $id = $_GET['id'];
    $sql = "DELETE FROM universe WHERE id =".$id;
    if (!$result = $mysqli->query($sql)) 
    {
        echo "Query: " . $sql . "\n";
        echo "<br>11Errno: " . $mysqli->errno . "\n";
        echo "<br>12Error: " . $mysqli->error . "\n";
        exit;
    }
}

$mysqli->close();
?>
</body>
</html>
