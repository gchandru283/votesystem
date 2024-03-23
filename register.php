<?php include 'includes/header.php'; ?>
<?php include 'includes/conn.php'; ?>

<body class="hold-transition register-page">
	<div class="login-box">
		<div class="register-logo">
			<b>Voting System</b>
		</div>

		<div class="register-box-body">
			<p class="register-box-msg">Enter your details to register</p>

			<form id="myForm" action="validation.php" method="POST">
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="fname" placeholder="First Name" required>
					<span class="glyphicon glyphicon-user form-control-feedback" style="transform: scale(1.3); "></span>
				</div>
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="lname" placeholder="Last Name" required>
					<span class="glyphicon glyphicon-user form-control-feedback" style="transform: scale(1.3); "></span>
				</div>
				<div class="form-group has-feedback">
					<!-- <span> Date of Birth </span> -->
					<input type="text" class="form-control" name="dob" placeholder="Date of Birth" required>
					<span class="glyphicon glyphicon-calendar form-control-feedback"
						style="transform: scale(1.3); "></span>
				</div>
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="mobile" placeholder="Mobile Number" required>
					<span class="glyphicon glyphicon-phone form-control-feedback" style="transform: scale(1.3);"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="aadhar" placeholder="Aadhar Number" required>
					<span class="fa fa-id-card form-control-feedback" style="transform: scale(1.3);"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="text" class="form-control" name="voterid" placeholder="Voter ID" required>
					<span class="fa fa-id-card form-control-feedback" style="transform: scale(1.3);"></span>
				</div>

				<div class="row">
					<div class="col-xs-4 col-xs-offset-4">
						<button type="submit" class="btn btn-md btn-success btn-rounded" name="register"><i
								class="fa fa-sign-in"></i> &nbsp; Register </button>
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
				<center><b>NOTE:</b> To login <a href="index.php">Click here</a>.
				</center>
			</p>
		</div>
	</div>

	<?php include 'includes/scripts.php' ?>
</body>

</html>

<script>
	document.getElementById('myForm').addEventListener('submit', function (event) {
		var mobile = document.getElementById('mobile').value;
		var aadhar = document.getElementById('aadhar').value;
		var voterid = document.getElementById('voterid').value;

		if (mobile.length !== 10 || isNaN(mobile)) {
			alert('Mobile number should be a 10-digit number.');
			event.preventDefault();
		}

		if (aadhar.length !== 12 || isNaN(aadhar)) {
			alert('Aadhar number should be a 12-digit number.');
			event.preventDefault();
		}

		if (!voterid.match(/^[a-zA-Z]{3}\d{7}$/)) {
			alert('Voter ID should be 3 characters followed by 7 numbers.');
			event.preventDefault();
		}
	});
</script>