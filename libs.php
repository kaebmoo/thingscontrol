<?php

function overlap($StartA, $EndA, $StartB, $EndB) {
  if(max($StartA, $StartB) <= min($EndA, $EndB)) {
    return true;
  }
  else {
    return false;
  }
}

?>
