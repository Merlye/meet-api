<?php
require '../db.php';
require '../key.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === "GET")
{
    switch ($_GET['request']) {
        case 'heartbeat':
        {
            require './get/heartbeat.php';
            $obj = new heartbeat($db);
            $json = $obj->getJson();
            echo $json;
            break;
        }

        
        default:
            echo "Break missing in case!";
            # code...
            break;
    }
    #echo "Request is GET";
    
}
else if ($_SERVER['REQUEST_METHOD'] === "POST")
{
    switch ($_POST['request'])
    {
        case 'register_user':
        {
            echo 'Inndata: ' . $_POST['data'];
            require './post/register_user.php';
            $obj = new register_user($db, $_POST['data']);
            echo $obj->out;
            break;
        }
        case 'login_user':
        {
            require './post/login_user.php';
            $obj = new login_user($db, $_POST['data'], $secret);
            echo $obj->out;
            break;
        }
        
        case 'list_users':
        {
            if (hasKey($_POST['key']) == true)
            {

            }
        }

        default:
            echo $_POST['request'] . ' is not a valid request';
            break;
    }

}

function hasKey($key)
{
    $res = mysqli_query($db, "SELECT token FROM `users` WHERE token='".$key."';");
    if (mysqli_num_rows($res) == 1)
    {
        return true;
    }

    $this->out = json_encode(array(
        "status" => true,
        "hasKey" => "failed",
        "hasKeyExit" => 1,
        "message" => "Key is not valid, request rejected!"
    ));

    return false;
}


mysqli_close($db);
?>