<?php
	include("./config.php");
	$needle = strtolower($_POST['input']);

	if(strlen($needle) > 0) {
		$checkName = $con->prepare("SELECT * FROM users WHERE userName=?");
		$checkName->bind_param("s", $needle);
		$checkName->execute();
		$checkName->store_result();
		if($checkName->num_rows > 0) {
	?>
	1
	<?php } else { ?>
	0
	<?php
		};
		$checkName->close();
	};
?>
