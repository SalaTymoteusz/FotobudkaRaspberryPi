<?php

  exec('tail /var/www/html/error.log', $error_logs);

  foreach($error_logs as $error_log) {

       echo "<br />".$error_log;
  }

 ?>
