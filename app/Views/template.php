<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="en" class="js">
<?php include(APPPATH.'/Views/_head.php'); ?>
<body class="nk-body npc-crypto bg-white has-sidebar">
<div class="nk-app-root">
	<div class="nk-main">
		<?php include(APPPATH.'/Views/_sidebar.php'); ?>
		<div class="nk-wrap">
			<?php include(APPPATH.'/Views/_header.php'); ?>
			<div class="nk-content nk-content-fluid">

			</div>
			<?php include(APPPATH.'/Views/_footer.php'); ?>
		</div>
	</div>
</div>
<?php include(APPPATH.'/Views/_scripts.php'); ?>
</body>
</html>
