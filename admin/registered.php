<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include '../encryption.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Voters List
        </h1>

      </section>
      <!-- Main content -->
      <section class="content">
        <?php
        if (isset($_SESSION['error'])) {
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              " . $_SESSION['error'] . "
            </div>
          ";
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
        <div class="row">
          <div class="col-xs-12">
            <div class="box">

              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Photo</th>
                    <th>Date of Birth</th>
                    <th>Age</th>
                    <th>Mobile No</th>
                    <th>Aadhar No</th>
                    <th>Voter ID</th>
                    <th>
                      <center>Tools</center>
                    </th>

                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT * FROM registered";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                      $firstname = decryptData($row['firstname']);
                      $lastname = decryptData($row['lastname']);
                      $dob = decryptData($row['dob']);
                      $age = decryptData($row['age']);
                      $mobile = decryptData($row['mobile']);
                      $aadhar = decryptData($row['aadhar']);
                      $voterid = decryptData($row['voterid']);

                      $image = (!empty($row['photo'])) ? '../uploads/' . $row['photo'] : '../images/profile.jpg';
                      echo "
        <tr>
            <td>" . $firstname . "</td>
            <td>" . $lastname . "</td>
            <td>
                <img src='" . $image . "' width='35px' height='35px'>
            </td>
            <td>" . $dob . "</td>
            <td>" . $age . "</td>
            <td>" . $mobile . "</td>
            <td>" . $aadhar . "</td>
            <td>" . $voterid . "</td>
            <td>
            <center>
                <button class='btn btn-success btn-sm add' data-id='" . $row['id'] . "'><i class='glyphicon glyphicon-plus-sign'></i> Add</button>
                <button class='btn btn-danger btn-sm delete' data-id='" . $row['id'] . "'><i class='glyphicon glyphicon-minus-sign'></i> Remove</button>
            </center>
            </td>
         </tr> ";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/registered_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

  <script>
$(function () {
    $(document).on('click', '.add', function (e) {
        e.preventDefault();
        $('#add').modal('show');
        var id = $(this).data('id');
        console.log("Clicked add button. ID:", id);
        getRow(id);
    });
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        console.log("Clicked delete button. ID:", id);
        getRow(id);
    });
    $(document).on('click', '.photo', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        console.log("Clicked photo button. ID:", id);
        getRow(id);
    });
});

function getRow(id) {
    console.log("Fetching row with ID:", id);
    $.ajax({
        type: 'POST',
        url: 'registered_row.php',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            console.log("Received response:", response);
            $('.id').val(response.id);
            var decrypted_firstname = decryptData(response.firstname);
            var decrypted_lastname = decryptData(response.lastname);
            $('#edit_firstname').val(decrypted_firstname);
            $('#edit_lastname').val(decrypted_lastname);
            $('.fullname').html(decrypted_firstname + ' ' + decrypted_lastname);
        },
        error: function (xhr, status, error) {
            console.log("Error occurred while fetching data:", error);
        }
    });
}

function decryptData(data) {
    var key = CryptoJS.enc.Hex.parse('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');
    var cipher = "aes-256-cbc";
    var dataBytes = CryptoJS.enc.Base64.parse(data);
    var iv = dataBytes.clone().words.slice(0, 4); // Extract IV from the data
    var encrypted = dataBytes.clone().words.slice(4); // Extract encrypted data
    var decrypted = CryptoJS.AES.decrypt({ciphertext: CryptoJS.lib.WordArray.create(encrypted)}, key, {iv: CryptoJS.lib.WordArray.create(iv), mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7});
    return decrypted.toString(CryptoJS.enc.Utf8);
}

</script>

</body>

</html>