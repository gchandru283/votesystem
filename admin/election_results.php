<?php
	include 'includes/session.php';
	include '../message/message_results.php'; ?>

<?php
	$return = 'home.php';
	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}

	if(isset($_POST['send'])){
		$file = 'electionStatus.ini';
		$content = 'isElectionEnded = true';
		file_put_contents($file, $content);
		sendMessages();
		$_SESSION['success'] = 'Election ended & Result link sent successfully';
		
	}
	else{
		$_SESSION['error'] = "Error sending messages";
	}

	header('location:'.$return);
	exit();
	?>