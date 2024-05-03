<?php
include 'includes/header.php'; 
include 'includes/conn.php';
include 'encryption.php';

function displayElectionResults($conn, $electionEnded) {
    $content = '';
    $parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
    $title = $parse['election_title'];

    $content .= '<style>
    body{
        background-color: #f5f5f5;
        margin-top:80px;
    }
    .table {
        border-collapse: collapse;
        width: 100%;
        border: 1.5px solid black;
    }
    .table th, .table td {
        border: 1px solid black;
        padding: 8px;
        text-align: center; 
    }
    .table th {
        background-color: #f2f2f2;
    }
    </style>';
    $content .= '<div class="login-logo"><b>Online Voting System - Results</b></div>';
    $content .= '<h2 align="center"><b>'.$title.'</b></h2>';
    $content .= '<h4 align="center">Tally Result</h4>';
    $content .= '<div class="login-box-body" style="display: flex; justify-content: center;background-color: #f5f5f5;">';
    $content .= '<div class="box-body" style="width:600px">';

    if ($electionEnded == 'true') {
        $sql = "SELECT * FROM positions ORDER BY priority ASC";
        $query = $conn->query($sql);

        while ($row = $query->fetch_assoc()) {
            $description = decryptData($row['description']);
            $id = $row['id'];
            $content .= '<table class="table table-striped">';
            $content .= '<tr><td colspan="2" align="center" style="font-size:18px;"><b>'.$description.'</b></td></tr>';
            $content .= '<tr><td style="font-size:18px;"><b>Candidates</b></td><td style="font-size:18px;"><b>Votes</b></td></tr>';

            $sql = "SELECT * FROM candidates WHERE position_id = '$id' ORDER BY firstname ASC";
            $cquery = $conn->query($sql);

            while ($crow = $cquery->fetch_assoc()) {
                $firstname = decryptData($crow['firstname']);
                $lastname = decryptData($crow['lastname']);
                $sql = "SELECT * FROM votes WHERE candidate_id = '".$crow['id']."'";
                $vquery = $conn->query($sql);
                $votes = $vquery->num_rows;

                $content .= '<tr><td style="font-size:16px;">'.$firstname." ".$lastname.'</td><td style="font-size:16px;">'.$votes.'</td></tr>';
            }

            $content .= '</table><br><br><br>'; // Gap after each position
        }
    } else {
        $content .= '<br><table class="table table-stripped"><tr>
                     <td colspan="2" style="font-size:18px; text-align:center; border:0px; font-weight:bold;">
                     Election not yet ended.</tr></table>
                     <br><br>
                     <center><h4 style="color:black">Elections will be over by 6.00 pm.. Come back after 6.00 pm to view the results.</h4></center>';
    }

    $content .= '</div></div>';
    echo $content;
}

$parse = parse_ini_file('admin/electionStatus.ini', FALSE, INI_SCANNER_RAW);
$electionEnded = $parse['isElectionEnded'];
displayElectionResults($conn, $electionEnded);

echo '    
    <footer>
    <style>
            @media (min-width: 768px) {
                .main-footer {
                    width: 80vw;
                }
                hr{
                    height : 1px;
                }
            }
            @media (max-width: 767px) {
                .pull-right.hidden-xs {
                    display: none;
                }
                .center-text{
                    text-align: center;
                }
                hr{
                    height : 0.5px;
                }			
            }
    
            hr{
                border-width:0; 
                color:gray; 
                background-color:gray; 
                width:95%;
            }';

            if ($electionEnded == 'false'){ echo '
            .container1{
                transform : translateY(50%)
            }';
        }
            else{
               echo '.container1{
                    transform : translateY(50%)
                }';
            }
        echo '
            </style>
    <div class="container1">
          <hr/>
          <div class="container">
              <div class="pull-right hidden-xs">
                  <b>Online Voting System</b>
                </div>
                <div class="center-text">
                <strong> &copy; 2024 Done for<a> Final yr Project</a></strong></div>
                <br>
                <br>
            </div>
    </div>
    
            <!-- /.container -->
        </footer>';
?>
