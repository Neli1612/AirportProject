<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="emp_index.css">
</head>
<body>

    
    <header>
        <div class="logo">Varna Airport</div>
    </header>

        
        <div id="login">
            <h3>Employee Log in</h3>
            <form action="emp_login.php" method="post">
                <label for="id_emp">Employee ID:</label><br>
                <input type="text" name="id_emp"><br>
                <label for="emp_password">Password:</label><br>
                <input type="password" name="emp_password" id="pwd"><br>
                <input type="checkbox" onclick="myFunction()">Show Password<br><br>
                <input type="submit" value="Log in"></a>
            </form>
        </div>
   

    
    <footer>
        <p>&copy; 2025 Varna Airport. All rights reserved.</p>
    </footer>

    <script>
function myFunction() {
  var x = document.getElementById("pwd");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

</body>
</html>