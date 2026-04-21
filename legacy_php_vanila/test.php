<!-- <form method="post" action="">
    <input type="text" name="name" placeholder="Name">
    <input type="email" name="email" placeholder="Email">
    <input type="submit" value="Submit">
</form> -->

<?php
session_start();
$server = $_SESSION;

var_dump($server);