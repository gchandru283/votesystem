<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';
	include './encryption.php';

	$output = array('error'=>false,'list'=>'');

	$sql = "SELECT * FROM positions";
	$query = $conn->query($sql);

	while($row = $query->fetch_assoc()){

		$description = decryptData($row['description']);
		$max_vote = decryptData($row['max_vote']);

		$position = slugify($description);
		$pos_id = $row['id'];
		if(isset($_POST[$position])){
			if($max_vote > 1){
				if(count($_POST[$position]) > $max_vote){
					$output['error'] = true;
					$output['message'][] = '<li>You can only choose '.$max_vote.' candidates for '.$description.'</li>';
				}
				else{
					foreach($_POST[$position] as $key => $values){
						$sql = "SELECT * FROM candidates WHERE id = '$values'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$cm_firstname = decryptData($cmrow['firstname']);
						$cm_lastname = decryptData($cmrow['lastname']);
						$output['list'] .= "
							<div class='row votelist'>
		                      	<span class='col-sm-4'><span class='pull-right'><b>".$description ." :</b></span></span> 
		                      	<span class='col-sm-8'>". $cm_firstname ." ". $cm_lastname ."</span>
		                    </div>
						";
					}

				}
				
			}
			else{
				$candidate = $_POST[$position];
				$sql = "SELECT * FROM candidates WHERE id = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				$cs_firstname = decryptData($csrow['firstname']);
				$cs_lastname = decryptData($csrow['lastname']);
				$output['list'] .= "
					<div class='row votelist'>
                      	<span class='col-sm-4'><span class='pull-right'><b>".$description." :</b></span></span> 
                      	<span class='col-sm-8'>".$cs_firstname." ".$cs_lastname."</span>
                    </div>
				";
			}

		}
		
	}

	echo json_encode($output);


?>