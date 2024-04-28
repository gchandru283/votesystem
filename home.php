<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php';
include './encryption.php'; ?>


<body class="hold-transition skin-blue layout-top-nav">
    <div class="wrapper">
        <?php include 'includes/navbar.php'; ?>
        <div class="content-wrapper">
            <div class="container">
                <!-- Main content -->
                <section class="content">
                    <center><h4 id="countdown"></h4><br></center>
                    <?php
                    $parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
                    $title = $parse['election_title'];
                    ?>
                    <h1 class="page-header text-center title"><b>
                            <?php echo strtoupper($title); ?>
                        </b> </h1>
                    <div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <?php
                            if (isset($_SESSION['error'])) {
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <ul>
                                        <?php
                                        foreach ($_SESSION['error'] as $error) {
                                            echo "
                                                <li>" . $error . "</li>
                                            ";
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <?php
                                unset($_SESSION['error']);

                            }
                            if (isset($_SESSION['success'])) {
                                echo "
                                <div class='alert alert-success alert-dismissible'>
                                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                                    <h4><i class='icon fa fa-check'></i> Success!</h4>
                                " . $_SESSION['success'] . "
                                </div>
                            ";
                                unset($_SESSION['success']);
                            }

                            ?>

                            <div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <span class="message"></span>
                            </div>

                            <?php
                            $sql = "SELECT * FROM votes WHERE voters_id = '" . $voter['id'] . "'";
                            $vquery = $conn->query($sql);
                            if ($vquery->num_rows > 0) {
                                ?>
                                <div class="text-center">
                                    <h3>You have already voted for this election.</h3>
                                    <br />
                                    <a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">View
                                        Ballot</a>
                                </div>
                                <?php
                            } else {
                                ?>
                                <!-- Voting Ballot -->
                                <form method="POST" id="ballotForm" action="submit_ballot.php">
                                    <?php
                                    include 'includes/slugify.php';

                                    $candidate = '';
                                    $sql = "SELECT * FROM positions ORDER BY priority ASC";
                                    $query = $conn->query($sql);
                                    while ($row = $query->fetch_assoc()) {
                                        $max_vote = decryptData($row['max_vote']);
                                        $description = decryptData($row['description']);
                                        $sql = "SELECT * FROM candidates";
                                        $cquery = $conn->query($sql);
                                        while ($crow = $cquery->fetch_assoc()) {
                                            // $c_position_id = decryptData($crow['position_id']);
                                            if ($crow['position_id'] == $row['id']) {
                                                $c_firstname = decryptData($crow['firstname']);
                                                $c_lastname = decryptData($crow['lastname']);
                                                $c_platform = decryptData($crow['platform']);
                                                $slug = slugify(decryptData($row['description']));
                                                $checked = '';
                                                if (isset($_SESSION['post'][$slug])) {
                                                    $value = $_SESSION['post'][$slug];

                                                    if (is_array($value)) {
                                                        foreach ($value as $val) {
                                                            if ($val == $crow['id']) {
                                                                $checked = 'checked';
                                                            }
                                                        }
                                                    } else {
                                                        if ($value == $crow['id']) {
                                                            $checked = 'checked';
                                                        }
                                                    }
                                                }

                                                $input = ($max_vote > 1) ? '<input type="checkbox" class="flat-red ' . $slug . '" name="' . $slug . "[]" . '" value="' . $crow['id'] . '" ' . $checked . '>' : '<input type="radio" class="flat-red ' . $slug . '" name="' . slugify($description) . '" value="' . $crow['id'] . '" ' . $checked . '>';
                                                $image = (!empty($crow['photo'])) ? 'images/' . $crow['photo'] : 'images/profile.jpg';
                                                $candidate .= '
                                                <li>
                                                    ' . $input . '<button type="button" class="btn btn-primary btn-sm btn-flat clist platform" data-platform="' . $c_platform . '" data-fullname="' . $c_firstname . ' ' . $c_lastname . '"><i class="fa fa-search"></i> Platform</button><img src="' . $image . '" height="100px" width="100px" class="clist"><span class="cname clist">' . $c_firstname . ' ' . $c_lastname . '</span>
                                                </li>
                                            ';
                                            }
                                        }
                                        $instruct = ($max_vote > 1) ? 'You may select up to ' . $max_vote . ' candidates' : 'Select only one candidate';

                                        echo '
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="box box-solid" id="' . $row['id'] . '">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><b>' . $description . '</b></h3>
                                                        </div>
                                                        <div class="box-body">
                                                            <p>' . $instruct . '
                                                                <span class="pull-right">
                                                                    <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="' . slugify($description) . '"><i class="fa fa-refresh"></i> Reset</button>
                                                                </span>
                                                            </p>
                                                            <div id="candidate_list">
                                                                <ul>
                                                                    ' . $candidate . '
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ';

                                        $candidate = '';

                                    }

                                    ?>
                                    <h4 style="line-height: 1.3;"><b> Verify your Face to enable the Submit button!. To verify face <a  href="#" class="open-modal-link">Click here.</a></b></h4><br><br>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-success btn-flat" id="preview"><i
                                                class="fa fa-file-text"></i> Preview</button>
                                        <button type="submit" class="btn btn-primary btn-flat" name="vote" id="submitBtn"><i
                                                class="fa fa-check-square-o"></i> Submit</button>
                                                
                                    </div>
                                </form>
                                <!-- End Voting Ballot -->
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </section>

            </div>
        </div>

        <?php include 'includes/footer.php'; ?>
        <?php include 'includes/ballot_modal.php'; ?>
        <?php include 'includes/face_recog_modal.php'; ?>
    </div>

    <?php include 'includes/scripts.php'; ?>
    <script>
        $(function () {
            $('.content').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });

            $(document).on('click', '.reset', function (e) {
                e.preventDefault();
                var desc = $(this).data('desc');
                $('.' + desc).iCheck('uncheck');
            });

            $(document).on('click', '.platform', function (e) {
                e.preventDefault();
                $('#platform').modal('show');
                var platform = $(this).data('platform');
                var fullname = $(this).data('fullname');
                $('.candidate').html(fullname);
                $('#plat_view').html(platform);
            });

            $('#preview').click(function (e) {
                e.preventDefault();
                var form = $('#ballotForm').serialize();
                if (form == '') {
                    $('.message').html('You must vote atleast one candidate');
                    $('#alert').show();
                }
                else {
                    $.ajax({
                        type: 'POST',
                        url: 'preview.php',
                        data: form,
                        dataType: 'json',
                        success: function (response) {
                            if (response.error) {
                                var errmsg = '';
                                var messages = response.message;
                                for (i in messages) {
                                    errmsg += messages[i];
                                }
                                $('.message').html(errmsg);
                                $('#alert').show();
                            }
                            else {
                                $('#preview_modal').modal('show');
                                $('#preview_body').html(response.list);
                            }
                        }
                    });
                }

            });
        });

        $(function () {
    // Initially disable the submit button
    $('#submitBtn').prop('disabled', true);

    // Add click event listener to trigger verification
    $('a.open-modal-link').click(function(e) {
        e.preventDefault(); // Prevent default link behavior

        // Call the openModal function
        openModal('face_modal', <?php echo $voter['id'] ?>);
    });

    // Define the function to accept the ID and the value arguments
    function openModal(modalId, idValue) {
        // Set the value of the input field with id "id"
        $('#' + modalId).find('.id').val(idValue);
        
        // Show the modal with the provided ID
        $('#' + modalId).modal('show');

        // Make an AJAX request to perform face verification
        $.ajax({
            url: 'face_recognition.php',
            type: 'POST',
            data: { verify: true, id: idValue },
            dataType: 'json',
            success: function(response) {
                if (response.result === true) {
                    // Face verified successfully, set flag in local storage
                    localStorage.setItem('faceVerified', 'true');
                    // Enable submit button
                    $('#submitBtn').prop('disabled', false);
                } else {
                    // Handle verification failure if needed
                    localStorage.setItem('faceVerified', 'false');
                    console.error('Face verification failed.');
                }

                // Check if redirect URL is provided
                if (response.redirect) {
                    // Redirect to the specified URL
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Check if face verification flag is set in local storage
    if (localStorage.getItem('faceVerified') === 'true') {
        // Enable submit button
        $('#submitBtn').prop('disabled', false);
    }

    // Add click event listener to logout button
    $('#logoutBtn').click(function() {
        // Clear the face verification flag from local storage
        localStorage.removeItem('faceVerified');
        // Disable submit button
        $('#submitBtn').prop('disabled', true);
    });
});

// Function to update the countdown
function updateCountdown() {
    // Get the initial timestamp from localStorage
    var initialTimestamp = localStorage.getItem('initialTimestamp');
    if (!initialTimestamp) {
        // If initialTimestamp is not set, set it to the current time
        initialTimestamp = Math.floor(Date.now() / 1000); // Convert milliseconds to seconds
        localStorage.setItem('initialTimestamp', initialTimestamp);
    }

    // Calculate the remaining time
    var currentTime = Math.floor(Date.now() / 1000); // Convert milliseconds to seconds
    var elapsedTime = currentTime - initialTimestamp;
    var remainingTime = 4 * 60 + 60 - elapsedTime;

    // Update the countdown element
    var countdownElement = document.getElementById('countdown');
    if (remainingTime > 0) {
        var minutes = Math.floor(remainingTime / 60);
        var seconds = remainingTime % 60;
        countdownElement.textContent = "This session will expire in " + minutes + " minutes " + seconds + " seconds";
    } else {
        countdownElement.textContent = "Session expired";
        window.location.href = 'logout.php';
        // Clear the localStorage
        localStorage.removeItem('initialTimestamp');
    }
}

// Call updateCountdown function when the page loads
updateCountdown();

// Update countdown every second
setInterval(updateCountdown, 1000);
document.getElementById('logoutBtn').addEventListener('click', function() {
    localStorage.removeItem('initialTimestamp');
});
</script>

    </script>
</body>

</html>
