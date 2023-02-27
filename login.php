<?php

session_start();
$errorMessage = "Enter your username and password to log in.";

if (isset($_POST['submit'])) {
  if (empty($_POST['username'])) {
    $errorMessage = 'Please enter a username.';
  } elseif (empty($_POST['password'])) {
    $errorMessage = 'Please enter a password.';
  } else {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $db = mysqli_connect('localhost', 'root', '', 'users');

    if ($db->connect_error) {
      $errorMessage = "Failed to connect to the database: " . $db->connect_error;
    } else {
      $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
      $stmt->bind_param('s', $username);
      $stmt->execute();

      if (function_exists('mysqli_stmt_get_result')) {
        $result = mysqli_stmt_get_result($stmt);
      } else {
        $stmt->store_result();
        $variables = array();
        $data = array();
        $meta = $stmt->result_metadata();

        while ($field = $meta->fetch_field()) {
          $variables[] = &$data[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $variables);

        $i = 0;
        while ($stmt->fetch()) {
          $array[$i] = array();
          foreach($data as $k => $v) {
            $array[$i][$k] = $v;
          }
          $i++;
        }
        $result = new ArrayObject($array);
      }

      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
          $_SESSION['username'] = $username;
          header('Location: index.php');
          exit;
        } else {
          $errorMessage = 'Invalid username or password.';
        }
      }
      $stmt->close();
      $db->close();
    }
  }
}
?>

<html>
	<head>
		<title>Secure Login</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h1>Login Page</h1>
		<br>
		<div id="errorMsg"><?php echo $errorMessage; ?></div>
		</br>
		<form method="POST">
			<input type="text" id="username" name="username" placeholder="Username"><br>
			<input type="password" id="password" name="password" placeholder="Password">
			<br></br>
			<input type="submit" name="submit" value="Log in">
		</form>
		<h4>Want to create an account? <a href="signup.php">Sign up.</a></h4>
	</body>
</html>