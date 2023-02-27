<?php

session_start();
$errorMessage = "Fill in the form to create an account.";

if (isset($_POST['submit'])) {
  if (empty($_POST['username'])) {
    $errorMessage = 'Please enter a username.';
  } elseif (empty($_POST['email'])) {
    $errorMessage = 'Please enter an email address.';
  } elseif (empty($_POST['password'])) {
    $errorMessage = 'Please enter a password.';
  } elseif (empty($_POST['confirmPassword'])) {
    $errorMessage = 'Please confirm your password.';
  } elseif ($_POST['password'] !== $_POST['confirmPassword']) {
    $errorMessage = 'Passwords do not match.';
  } else {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $db = mysqli_connect('localhost', 'root', '', 'users');
    if ($db->connect_error) {
      $errorMessage = "Failed to connect to the database: " . $db->connect_error;
    } else {
      $stmt = $db->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
      $stmt->bind_param('ss', $username, $email);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $errorMessage = 'Username or email already exists.';
      } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $username, $email, $passwordHash);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
          $_SESSION['username'] = $username;
          header('Location: index.php');
          exit;
        } else {
          $errorMessage = 'Unable to create account.';
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
		<title>Secure Signup</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h1>Signup Page</h1>
		<br>
		<div id="errorMsg"><?php echo $errorMessage; ?></div>
		</br>
		<form method="POST">
      <input type="text" id="username" name="username" placeholder="Username"><br>
      <input type="text" id="email" name="email" placeholder="Email"><br>
			<input type="password" id="password" name="password" placeholder="Password"><br>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
			<br></br>
			<input type="submit" name="submit" value="Create Account">
		</form>
		<h4>Already have an account? <a href="login.php">Log in.</a></h4>
	</body>
</html>