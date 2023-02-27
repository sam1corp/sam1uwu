<?php

session_start();
$username = $_SESSION['username'];
if (isset($username)) {
	// cunt
} else {
	$problem = "Please, log in to an account.";
	$problem_url = "login.php";
	$_SESSION["problem"] = $problem;
	$_SESSION["problem_url"] = $problem_url;
	header("Location: error.php");
}

?>

<html>
	<head>
		<title>Secure Account</title>
		<link rel="stylesheet" href="style.css">
	</head>
    <body>
        <h2>You are logged in as : <br></br> <?php echo $_SESSION['username']; ?></h2>
		<a href="/gpt/Chat"><input type="submit" value="Chat"></a><br>
		<a href="/gpt/Calc"><input type="submit" value="Calculator"></a><br>
		<a href="logout.php"><input type="submit" value="Log out"></a>
    </body>
</html>