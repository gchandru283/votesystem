
<?php
include 'includes/session.php';
include 'includes/slugify.php';
include './encryption.php';

if(isset($_POST['vote'])) {
    $positionsSelected = array(); // Array to track selected positions

    // Iterate through all positions to check for selected candidates
    $sql = "SELECT * FROM positions";
    $query = $conn->query($sql);
    $error = false;
    $sql_array = array();

    while($row = $query->fetch_assoc()) {
        $description = decryptData($row['description']);
        $max_vote = decryptData($row['max_vote']);
        $position = slugify($description);
        $pos_id = $row['id'];

        if(isset($_POST[$position])) {
            $positionsSelected[$pos_id] = true; // Mark that this position has candidates selected

            if($max_vote > 1) {
                if(count($_POST[$position]) > $max_vote) {
                    $error = true;
                    $_SESSION['error'][] = 'You can only choose '.$max_vote.' candidates for '.$description;
                } else {
                    foreach($_POST[$position] as $key => $values) {
                        $sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('".$voter['id']."', '$values', '$pos_id')";
                    }
                }
            } else {
                $candidate = $_POST[$position];
                $sql_array[] = "INSERT INTO votes (voters_id, candidate_id, position_id) VALUES ('".$voter['id']."', '$candidate', '$pos_id')";
            }
        }
    }

    // Check if all positions have candidates selected
    $allPositionsSelected = true;
    $sql = "SELECT * FROM positions";
    $query = $conn->query($sql);
    while($row = $query->fetch_assoc()) {
        $pos_id = $row['id'];
        if(!isset($positionsSelected[$pos_id])) {
            $allPositionsSelected = false; // Not all positions have candidates selected
            break;
        }
    }

    if(!$allPositionsSelected) {
        $_SESSION['error'][] = 'Select candidates to vote for all positions';
    } elseif(!$error) {
        foreach($sql_array as $sql_row) {
            $conn->query($sql_row);
        }

        unset($_SESSION['post']);
        $_SESSION['success'] = 'Ballot Submitted';
    }
} else {
    $_SESSION['error'][] = 'Select candidates to vote first';
}

header('location: home.php');
?>
