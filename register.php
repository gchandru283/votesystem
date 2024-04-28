<?php include 'includes/header.php'; ?>
<?php include 'includes/conn.php'; ?>
<?php include './encryption.php'; ?>
<?php session_start(); ?>

<body class="hold-transition register-page">
	<style>
		.register-logo {
			font-size: 30px;
			margin-top: 25px;
		}
		
		.login-box{
			margin-top:40px ;
			margin-bottom: 40px;
		}

		.sub-name {
			color: #3A833A;
			font-size: 28px;
		}

		@media only screen and (min-width: 768px) {
			
			.register-logo {
				font-size: 38px;
				margin-top: 40px;
			}

			.sub-name {
				color: #3A833A;
				font-size: 38px;
			}

		}
	</style>
	<div class="register-logo">
		<b>Online Voting System <span class="sub-name"> &nbsp;User Registration form</span></b>
	</div>
	<div class="login-box">
		<div class="register-box-body">
			<p class="register-box-msg">Enter your details to register</p>
			<form id="myForm" method="POST" enctype="multipart/form-data" action="validation.php">
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
					<input type="text" class="form-control" name="dob" placeholder="Date of Birth (YYYY-MM-DD)"
						required>
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
					<input type="text" class="form-control" id="uppercaseInput" name="voterid" placeholder="Voter ID" required>
					<span class="fa fa-id-card-o form-control-feedback" style="transform: scale(1.3);"></span>
				</div>
				<div class="form-group has-feedback">
					<label for="photo">Select your Image (size between 500KB and 3 MB) :</label><br />
							<input type="file" class="form-control" id="photo" name="photo" required accept="image/*"
								style="outline: none;cursor:pointer">
							<span class="glyphicon glyphicon-user form-control-feedback"
								style="transform: scale(1.3);"></span><br>
<p><b> Note : </b> This image will be used for verification before voting. So upload only passport size clear image (size between 300KB and 3 MB). </p>
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
						<button type="submit" class="btn btn-md btn-success btn-rounded" name="register"><i
								class="fa fa-sign-in"></i> &nbsp; Register </button>
					</div>
				</div>
			</form>
		</div>
		<?php
		if (isset($_SESSION['error'])) {
			echo "
  				<div class='callout callout-danger text-center mt20'>
			  		<p>" . $_SESSION['error'] . "</p> 
			  	</div>
  			";
			unset($_SESSION['error']);
		}
		?>
		<?php
		if (isset($_SESSION['success'])) {
			echo "
  				<div class='callout callout-success text-center mt20'>
			  		<p>" . $_SESSION['success'] . "</p> 
			  	</div>
  			";
			unset($_SESSION['success']);
		}
		?>
		<div style="font-size: 16px; padding-top:10px">
			<p>
				<center><b>NOTE:</b> To login <a href="index.php">Click here</a>.
				</center>
			</p>
		</div>
	</div>

	<?php include 'includes/footer1.php' ?>
	<?php include 'includes/scripts.php' ?>

<script>
	document.getElementById('uppercaseInput').addEventListener('input', function(event) {
            var inputText = event.target.value;
            event.target.value = inputText.toUpperCase();
        });
    document.getElementById('myForm').addEventListener('submit', function(event) {
        var mobile = document.getElementsByName('mobile')[0].value;
        var aadhar = document.getElementsByName('aadhar')[0].value;
        var voterid = document.getElementsByName('voterid')[0].value;
        var dob = document.getElementsByName('dob')[0].value;

        if (mobile.length !== 10 || isNaN(mobile)) {
            alert('Mobile number should be a 10-digit number.');
            event.preventDefault();
            return;
        }

        if (aadhar.length !== 12 || isNaN(aadhar)) {
            alert('Aadhar number should be a 12-digit number.');
            event.preventDefault();
            return;
        }
        if (!voterid.match(/^[a-zA-Z]{3}\d{7}$/)) {
            alert('Voter ID should be 3 characters followed by 7 numbers.');
            event.preventDefault();
            return;
        }
        if (!dob.match(/^\d{4}-\d{2}-\d{2}$/)) {
            alert('Date of Birth should be in YYYY-MM-DD format.');
            event.preventDefault();
            return;
        }
		var captchaInput = document.getElementsByName('captcha')[0].value;
        var captchaName = document.getElementsByName('captchaName')[0].value;
        // alert('Registration successfully completed');
    });
</script>

</body>
</html>