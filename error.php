<?php
session_start();
$prob = $_SESSION["problem"];
$url = $_SESSION["problem_url"];
if (isset($prob) || isset($url)) {
    // cunt
} else {
    $prob = "Please, try again later.";
    $url = "login.php";
}
?>

<html>
    <head>
        <title>Error</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Something went wrong :(</h1>
        <h2><?php echo $prob ?></h2><br>
        <a href="<?php echo $url ?>"><input type="submit" value="Log in"></a>
    </body>
</html>