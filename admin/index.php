<?php
session_start();
if (isset ($_SESSION['admin'])) {
	header('location:home.php');
}
?>
<?php include 'includes/header.php'; ?>

<style>
	.body {
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
		.login-box {
			transform: translateY(-12%);
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

<body class="hold-transition login-page">

	<div class="login-box">
		<div class="login-logo">
			<b>Online Voting System <span class="sub-name"> &nbsp;Admin login form</span></b>
		</div>

		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your Session</p>

			<form action="login.php" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="username" placeholder="Username" required>
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" name="password" placeholder="Password" required>
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-4 col-xs-offset-4">
						<button type="submit" class="btn btn-rounded btn-primary btn-block" name="login"><i
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
		<div style="font-size: 16px; padding-top:40px">
			<p>
				<center><b>NOTE:</b> This is for Admin. For Citizen login, <a href="../index.php">Click here</a>.
					<center>
			</p>

		</div>
	</div>

	<?php include 'includes/scripts.php' ?>
</body>


</html>