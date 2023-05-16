<?php

//Start a session
session_start();

//Destroy all session data
session_destroy();

//Redirect to start
header("Location: /");
exit();

?>