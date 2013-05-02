<?php
    require_once("../includes/header.php");
    
    if (isset($_POST['submit']))
    {
        global $databaseConnection;
    
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $statement = $databaseConnection->prepare("SELECT `id`, `username` FROM `users` WHERE `username` = ? AND `password` = ? LIMIT 1;");
        $statement->bind_param('ss', $username, $password);
    
        $statement->execute();
        $statement->store_result();
    
        if ($statement->num_rows == 1)
        {
            $statement->bind_result($_SESSION['userid'], $_SESSION['username']);
            $statement->fetch();
            header ("Location: /");
        }
        else
        {
            echo "Username/password combination is incorrect.";
        }
    }
?>
<div id="fullcol">

    <h2>Log on</h2>
    <form action="logon.php" method="post">
        <fieldset>
            <legend>Log on</legend>
            <ol>
                <li>
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="" id="username" />
                </li>
                <li>
                    <label for="password">Password:</label>
                    <input type="password" name="password" value="" id="password" />
                </li>
            </ol>
            <input type="submit" name="submit" value="Submit" />
        </fieldset>
    </form>
</div>
<?php include ("../includes/footer.php"); ?>