<?php

setcookie('userid', '', time()-60*60*24*365);
setcookie('name', '', time()-60*60*24*365);

header('Location: index.php');

?>