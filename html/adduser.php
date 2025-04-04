<?php
    include("database.php");
    if(isset($_COOKIE["userhash"])){
        $userhash = $_COOKIE["userhash"];

        $loggedusername = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM loggedusers WHERE userhash = '$userhash'"))["username"];
        if(mysqli_fetch_array(mysqli_query($conn, "SELECT permissions FROM users WHERE username = '$loggedusername'"))["permissions"] != "Administrator") {
            die("Pro přístup musíte být přihlášen jako administrátor");
        }

        if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM loggedusers WHERE userhash = '$userhash'")) != 1){
            die("Pro přístup je nutné se přihlásit");
        }

        if(isset($_POST["addsubmit"])){
            $username = $_POST["username"];
            $permission = $_POST["permission"];
            $password = $_POST["password"];
            $repeatpassword = $_POST["repeatpassword"];

            if($password == $repeatpassword){
                $hashedpassword = hash('sha256', $password);
                mysqli_query($conn, "INSERT INTO users (`id`, `username`, `password`, `permissions`) VALUES (NULL, '$username', '$hashedpassword', '$permission')");

                header("Location: users.php");

            }
            else{
                print("Hesla se neshodují");
            }

        }

    }
    else{
        die("Pro přístup je nutné se přihlásit");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="toppanel">
            <h1>Open External Buttons</h1>
        </div>
        <div class="sidebar">
            <a href="home.php">Domů</a>
            <a href="computers.php">Počítače</a>
            <a href="network.php">Síť</a>
            <a href="system.php">Systém</a>
            <a class="active">Uživatelé</a>
            <a href="logout.php">Odhlásit se</a>
        </div>
        <div class="content">
            <h1>Přidat uživatele</h1>
            <hr>
            <form action="adduser.php" method="post">
                <table>
                    <tr>
                        <th>Jméno</th>
                        <th>Oprávnění</th>
                        <th>Heslo</th>
                        <th>Heslo znovu</th>
                    </tr>
                    <tr>                    
                        <td><input type="text" name="username"></td>
                        <td>
                            <select name="permission">
                                <option value="Administrator">Administrator</option>
                                <option value="User">User</option>
                            </select>    
                        </td>   
                        <td><input type="password" name="password"></td>
                        <td><input type="password" name="repeatpassword"></td> 
                    </tr>
                </table>
                <hr>
                <input type="submit" name="addsubmit" value="Uložit">
            </form>    
        </div>
    </body>       
</html>