<?php
    if(!isset($session->login($user->user))) {
        header("Location: index.php");
        exit();
    }
?>

<header>
    <h1>Sample Test</h1>
    <p></p>
</header>
