<?php
session_start();
if (isset ($_SESSION['admin'])) {
	header('location:home.php');
}
?>
<?php include 'includes/header.php'; ?>

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

        <!-- Main Login Form -->
        <form id="loginForm" action="login.php" method="post">
            <!-- Username -->
            <div class="form-group has-feedback">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            
            <!-- Password -->
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <!-- OTP -->
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-primary" id="sendOTP"><i style="transform: scale(1.3);" class="fa fa-whatsapp"></i>&nbsp; &nbsp; Send OTP</button>
                    </span>
                    <input type="text" class="form-control" id="otpInput" name="otp" placeholder="Enter OTP" required>
                </div>
            </div>
            
            <!-- OTP validation result will be shown here -->
            <div id="otpValidationResult"></div>

            <div class="row">
                <div class="col-xs-4 col-xs-offset-4">
                    <button type="submit" class="btn btn-rounded btn-primary btn-block" name="login" id="loginBtn" disabled><i class="fa fa-sign-in"></i> Sign In</button>
                </div>
            </div>
        </form>

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
                <center><b>NOTE:</b> This is for Admin. For Citizen login, <a href="../index.php">Click here</a>.</center>
            </p>
        </div>
    </div>
</div>


	<?php include 'includes/scripts.php' ?>

	<!-- JavaScript to handle OTP validation and enabling submit button -->
	<script>
		    // Function to send OTP
			var set = '1234567890abcdeVWXYZ';

			// Generate a 6-character OTP
			var otpGenerated = '';
			for (var i = 0; i < 6; i++) {
			otpGenerated += set.charAt(Math.floor(Math.random() * set.length));
			}
			// console.log("Generated Otp : " + otpGenerated);
			function sendOTP(username) {
				if (username.trim() === "") {
				alert("Please enter your username.");
				return;
    }
			$.ajax({
			url: "otp.php",
			type: "POST",
			data: { username: username, otpGenerated : otpGenerated },
			success: function(response) {
				if (response !== 'error') {
					// console.log("Sending OTP for username: " + username);
					alert("OTP sent to your registered mobile number.");
				} else {
					alert("Username not found");
				}
			},
			error: function(xhr, status, error) {
				console.error("Error:", error);
				alert("An error occurred while sending OTP.");
			}
		});
    }

    // When Send OTP button is clicked
    document.getElementById('sendOTP').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // Get the username from the input field
        var username = document.getElementsByName('username')[0].value;
        
        // Call the sendOTP function
        sendOTP(username);
    });

    // Function to validate OTP
    function validateOTP(otp) {
        // Assuming OTP is hardcoded for demonstration, replace it with your actual validation logic
        // console.log(otpGenerated);
		console.log("Otp :"+ otp);
		return otp === otpGenerated ;
		
    }

    // When OTP input changes
    document.getElementById('otpInput').addEventListener('input', function() {
        var otp = this.value;
        // Assume there's JavaScript function to validate OTP
        var isValid = validateOTP(otp);
        if (isValid) {
            document.getElementById('otpValidationResult').innerText = "OTP Validated!";
            document.getElementById('loginBtn').removeAttribute('disabled');
            // Copy the username to the hidden field in the login form
            var username = document.getElementsByName('username')[0].value;
            document.getElementById('usernameInput').value = username;
        } else {
            document.getElementById('otpValidationResult').innerText = "Invalid OTP!";
            document.getElementById('loginBtn').setAttribute('disabled', 'disabled');
        }
    });
</script>
</body>


</html>