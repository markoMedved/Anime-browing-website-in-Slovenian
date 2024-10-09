<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//preverjanje če je kdo vpisan
if(isset($_SESSION['vpisan']) && $_SESSION['vpisan'] == true){
    echo "<script>
  var vpisan = true;
  </script>";
}
else  echo "<script>
var vpisan = false;
</script>";

?>
