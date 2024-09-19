<?php
require_once dirname(__FILE__).'/config.php';

//przekierowanie przeglądarki klienta (redirect)
header("Location: "._APP_URL."/views/index.php");

//przekazanie żądania do następnego dokumentu ("forward")
//include _ROOT_PATH.'/views/index.php';