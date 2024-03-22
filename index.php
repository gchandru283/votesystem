<?php
session_start();
if (isset ($_SESSION['admin'])) {
	header('location: admin/home.php');
}

if (isset ($_SESSION['voter'])) {
	header('location: home.php');
}
?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/conn.php'; ?>

<body class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<b>Voting System</b>
		</div>

		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your Session</p>

			<form action="login.php" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="voter" placeholder="Voter's ID" required>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback text-center">
					<?php
					$sql = "SELECT name FROM captcha";
					$vquery = $conn->query($sql);
					if ($vquery) {
						$row = $vquery->fetch_assoc();
						$captchaName = $row['name'];
						echo "<img src='./captcha/$captchaName' alt='Captcha Image' height='35px'><br/><br/>";
					} else {
						echo "Error fetching captcha image";
					}
					?>
					<input type="password" class="form-control " name="Captcha" placeholder="Enter Captcha" required>

				</div>
				<div class="row">
					<div class="col-xs-4 col-xs-offset-4">
						<button type="submit" class="btn btn-primary btn-block btn-rounded" name="login"><i
								class="fa fa-sign-in"></i> Sign In</button>
					</div>
				</div>
			</form>
		</div>
		<?php
		if (isset ($_SESSION['error'])) {
			echo "
  				<div class='callout callout-danger text-center mt20'>
			  		<p>" . $_SESSION['error'] . "</p> 
			  	</div>
  			";
			unset($_SESSION['error']);
		}
		?>
	</div>

	<?php include 'includes/scripts.php' ?>
</body>

<footer>
	<p>
		<center><b>NOTE:</b> To Create New Voter's ID and Password- Login to Admin Panel, Check Voters List and Add New
			Account. The System Automatically Generates VotersID
	</p>
	</center>
	</div>

	</html>