<?php
// This is a query builder for the DB. anything the user might want to do, this should build
// the right db query to do it.
// NOTE: this is the flow of the file, it doesnt actually work yet. missing all human interaction code

require_once("../assets/php/dbessential.php");
require_once("../assets/php/dbfetchInfo.php");

// TODO: should pull objects from db directly instead of using manually configured array


// you must use an action on an object
$actions = array("create", "update", "delete");
$objects = array("users", "pins", "content");

if(isset($_POST['fieldSet']))
{
    echo("<form action='/dbformSubmit.php' method='post'>");
}
else
{
    echo("<form action='/dbaccessForm.php' method='post'>");
}

if(!isset($_POST['action']) || !isset($_POST['object']))
{
    echo("What do you want to do? </br>");
    echo("<input type='radio' name='action' value='create'> Create </br>");
    echo("<input type='radio' name='action' value='update'> Update </br>");
    echo("<input type='radio' name='action' value='delete'> Delete </br>");
    echo("<br/>");
    echo("<br/>");
    echo("<input type='radio' name='object' value='users'> users </br>");
    echo("<input type='radio' name='object' value='content'> content </br>");
    echo("<input type='radio' name='object' value='pins'> pins </br>");
    echo("<br/>");
    echo("<br/>");
}

if(isset($_POST['action']) && isset($_POST['object']) && !isset($_POST['fieldSet']))
{
    $action = $_POST['action'];
    $object = $_POST['object'];
    echo("<input type='radio' name='action' value='$action' checked> $action </br>");
    echo("<input type='radio' name='object' value='$object' checked> $object </br>");

    $selectedAction = $_POST['action']; // human interaction to select action
    $selectedObject = $_POST['object']; // human interaction to select object
    $tableFields = getTableFields($selectedObject);
    $tableFieldsStr = "";
    foreach($tableFields as $fieldStr)
    {
        $tableFieldsStr = $tableFieldsStr . "-" . $fieldStr;
    }
    $tableFieldsStr = substr($tableFieldsStr, 1);
    echo("<input type='radio' name='fieldSet' value='$tableFieldsStr' checked> $tableFieldsStr </br>");
    echo("</br></br>");
    foreach($tableFields as $field)
    {
        echo("$field <br />");
        echo("<input type='text' name='$field'>");
        echo("<br/>");
    }
}

if(isset($_POST['fieldSet']))
{
    $fieldsetStr = $_POST['fieldSet'];
    $selectedAction = $_POST['action']; // human interaction to select action
    $selectedObject = $_POST['object']; // human interaction to select object
    $fieldset = explode("-", $fieldsetStr);

    //populate form here with table fields
    $builtQuery = "";
    if($selectedAction == "create")
    {
        $builtQuery = "Insert into " . $selectedObject . " (";
        foreach($fieldset as $field)
        {
            $builtQuery = $builtQuery . $field . ", ";
        }
        $builtQuery = substr($builtQuery, 0, -1);
        $builtQuery = $builtQuery + ") values("
        foreach($fieldset as $field)
        {
            $builtQuery = $builtQuery . $_POST[$field] . ", ";
        }
        $builtQuery = substr($builtQuery, 0, -1);
        $builtQuery = $builtQuery . ");";
    }
    if($selectedAction == "update")
    {
        $builtQuery = "Update " . $selectedObject . " set ";
        foreach($fieldset as $field)
        {
            $builtQuery = $builtQuery . " " . $field . "=" . $_POST[$field] . ",";
        }
        $builtQuery = substr($builtQuery, 0, -1);
        $builtQuery = $builtQuery + " where "
    }
    if($selectedAction == "delete")
    {
        $builtQuery = "Delete from " . $selectedObject . " where ";
        foreach($fieldset as $field)
        {
            $builtQuery = $builtQuery . " " . $field . "=" . $_POST[$field] . " and";
        }
        $builtQuery = substr($builtQuery, 0, -3);
        $builtQuery = $builtQuery + ";";
    }
    echo("<input type='radio' name='fullQuery' value='$builtQuery' checked>$builtQuery</br>");
}

echo("<input type='submit' value='submit'>");
echo("</form>");

?>
