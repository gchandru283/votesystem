<?php include 'includes/header.php'; 
      include 'includes/conn.php';
      include  'encryption.php';  ?>

<html>
<body>
<style>
        body{
            background-color: #f5f5f5;
        }
		.login-logo {
			font-size: 30px;
			margin-top: 25px;
		}
        .table {
        border-collapse: collapse;
        width: 100%;
        border: 1.5px solid black;
    }

    .table th, .table td {
        border: 1px solid black;
        padding: 8px;
    }

    .table th {
        background-color: #f2f2f2;
    }

		@media only screen and (min-width: 768px) {
			body {
				transform: translateY(5%);
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
<?php

$electionEnded = false; // Initialize the variable

function displayElectionResults($conn, $electionEnded) {
    // Function to generate the rows for election results
    function generateRow($conn) {
        $contents = '';
        $sql = "SELECT * FROM positions ORDER BY priority ASC";
        $query = $conn->query($sql);
        while($row = $query->fetch_assoc()){
            $description = decryptData($row['description']);
            $id = $row['id'];
            $contents .= '
                <table class="table table-striped">  
                    <tr>
                        <td colspan="2" align="center" style="font-size:18px;"><b>'.$description.'</b></td>
                    </tr>
                    <tr>
                        <td style="font-size:18px;"><b>Candidates</b></td>
                        <td style="font-size:18px;"><b>Votes</b></td>
                    </tr>
            ';

            $sql = "SELECT * FROM candidates WHERE position_id = '$id' ORDER BY firstname ASC";
            $cquery = $conn->query($sql);
            while($crow = $cquery->fetch_assoc()){
                $firstname = decryptData($crow['firstname']);
                $lastname = decryptData($crow['lastname']);
                $sql = "SELECT * FROM votes WHERE candidate_id = '".$crow['id']."'";
                $vquery = $conn->query($sql);
                $votes = $vquery->num_rows;

                $contents .= '
                    <tr>
                        <td style="font-size:16px;">'.$firstname." ".$lastname.'</td>
                        <td style="font-size:16px;">'.$votes.'</td>
                    </tr>
                ';
            }

            $contents .= '</table><br><br><br>'; // Close the table for this position
        }
        return $contents;
    }

    // Parse config file
    $parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
    $title = $parse['election_title'];

    // HTML Content
    $content = '
    <div class="">
    <div class="login-logo">
                <b>Online Voting System Results</b>
            </div>
        <h2 align="center"><b>'.$title.'</b></h2>
        <h4 align="center">Tally Result</h4> 
        <div class="login-box-body" style="display: flex; justify-content: center;background-color: #f5f5f5;">  
        <div class="box-body" style="width:600px">
                    <table class="table table-stripped">
                    
    ';  

    // Determine whether to display election results or message
    if ($electionEnded) {
        $content .= generateRow($conn);  
    } else {
        $content .= '<br><tr><td colspan="2" style="font-size:18px; text-align:center; border:0px; font-weight:bold;">Election not yet ended.</tr>';
    
    }

    $content .= '</table> </div> </div>';  

    // Output HTML
    echo $content;
}

// if (isset($_POST['results_start']) || isset($_POST['results_end'])) {
//     // Check if the form data is being received
//     if(isset($_POST['electionEnded'])){
//         $electionEnded = $_POST['electionEnded'];
//         displayElectionResults($conn, $electionEnded);
//     } else {
//         // Handle error
//     }
// }

 // Check if form data is being received
 if (isset($_POST['results_end'])) {
    $electionEnded = $_POST['electionEnded'];
}

echo "Election Ended: " . ($electionEnded ? "True" : "False") . "<br>";
displayElectionResults($conn, $electionEnded);

        // Check the value of $electionEnded to display the footer
        if (!$electionEnded) {
            echo '<footer class="main-footer" style="background-color: #f5f5f5">
                <style>
                    @media (min-width: 768px) {
                        .main-footer {
                            width: 90vw;
                            transform: translate(-13%, 400%);
                        }
                    }
                </style>
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <b>Online Voting System</b>
                    </div>
                    <strong> &copy; 2024 Done for<a> Final yr Project</a></strong>
                    <br>
                </div>
                <!-- /.container -->
            </footer>';
        }

        if ($electionEnded) {
            echo '<footer class="main-footer"style="background-color: #f5f5f5" >
          <style>
            @media (min-width: 768px) {
              .main-footer {
                width: 80vw;
                transform: translate(-13%);
              }
            }
          </style>
          <div class="container">
            <div class="pull-right hidden-xs">
              <b>Online Voting System</b>
            </div>
            <strong> &copy; 2024 Done for<a> Final yr Project</a></strong>
            <br>
            <br>
          </div>
          <!-- /.container -->
        </footer>';
        }
     

?>


</body>

</html>