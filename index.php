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

	<style>
		body {
			height: 100vh;
			overflow: hidden;
		}

		.login-logo {
			font-size: 30px;
			margin-top: 25px;
		}

		.sub-name {
			color: #563D7C;
			font-size: 28px;
		}

		@media only screen and (min-width: 768px) {
			body {
				transform: translateY(-10%);
			}

			.login-logo {
				font-size: 38px;
				margin-top: 30px;
			}

			.sub-name {
				color: #563D7C;
				font-size: 35px;
			}
		}
	</style>

	<div class="login-box">
		<div class="login-logo">
			<b>Online Voting System <span class="sub-name"> &nbsp;User login form</span></b>
		</div>

		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your Session</p>

			<form action="login.php" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="voters_key" placeholder="Voter's Key" required>
					<span class="glyphicon glyphicon-user form-control-feedback" style="transform: scale(1.2);"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback" style="transform: scale(1.2);"></span>
				</div>
				<div class="form-group has-feedback text-center">
					<?php
					$sql = "SELECT name FROM captcha ORDER BY RAND() LIMIT 1";
					$vquery = $conn->query($sql);
					if ($vquery) {
						$row = $vquery->fetch_assoc();
						$captchaName = $row['name'];
						echo "<img src='./captcha/$captchaName' alt='Captcha Image' height='35px'><br/><br/>";
					} else {
						echo "Error fetching captcha image";
					}
					?>
					<input type="text" class="form-control " name="captcha" placeholder="Enter Captcha" required>
					<input type="hidden" name="captchaName" value="<?php echo $captchaName; ?>">

				</div>

				<div class="row">
					<div class="col-xs-4 col-xs-offset-4">
						<button type="submit" class="btn btn-primary btn-block btn-rounded" name="login"><i
								class="fa fa-sign-in"></i> &nbsp; Sign In</button>
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
		<div style="font-size: 16px; padding-top:10px">
			<p>
				<center><b>NOTE:</b> To register <a href="register.php">Click here</a>.
				</center>
			</p>
		</div>
	</div>

	<?php include 'includes/scripts.php' ?>
</body>

</html>