<?php 
if (isset($_COOKIE['uid'])) {
	setcookie("uid", "", time() - 3600);
}
?>
<script>
window.location.href="/index.php";
</script>