<?php
require_once("../assets/php/dbparams.php");

echo("<html>");
echo("<body>");
echo("<p>");

$sql = $_POST['fullQuery'];

echo($sql);
echo("<br/><br/>");

// create connection
$conn = getDBConnection();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query($sql);

if($conn->affected_rows < 1)
{
    echo("database operation failed. something went wrong </br></br>");
    echo "Query: " . $sql . "</br></br>";
    echo "Errno: " . $conn->errno . "</br></br>";
    echo "Error: " . $conn->error . "</br></br>";
}
else
{
    echo("database operation completed successfully");
}
echo("</p>");
echo("<p>");
echo("<a href='./cmsForm.php'>return to admin select page </a>");
echo("</p>");
echo("</body>");
echo("</html>");

?>
