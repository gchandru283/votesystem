<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include '../encryption.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
  
  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Candidates List
        </h1>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Candidates</li>
        </ol>
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
              <div class="box-header with-border">
                <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i>
                  New</a>
              </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th class="hidden"></th>
                    <th>Position</th>
                    <th>Photo</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Mobile No</th>
                    <th>Aadhar No</th>
                    <th>Voter ID</th>
                    <th>Platform</th>
                    <th>Tools</th>
                  </thead>
                  <tbody>
                    <?php
                    $sql = "SELECT *, candidates.id AS canid FROM candidates LEFT JOIN positions ON positions.id=candidates.position_id ORDER BY positions.priority ASC";
                    $query = $conn->query($sql);
                    while ($row = $query->fetch_assoc()) {
                      $firstname = decryptData($row['firstname']);
                      $lastname = decryptData($row['lastname']);
                      $platform = decryptData($row['platform']);
                      $mobile = decryptData($row['mobile']);
                      $aadhar = decryptData($row['aadhar']);
                      $voterid = decryptData($row['voterid']);
                      $image = (!empty($row['photo'])) ? '../images/' . $row['photo'] : '../images/profile.jpg';
                      $sql1 = "SELECT * FROM positions WHERE id = {$row['position_id']}";
                      $query1 = $conn->query($sql1);
                      $row1 = $query1->fetch_assoc();
                      $desc = decryptData($row1['description']);
                      echo "
                        <tr>
                          <td class='hidden'></td>
                          <td>" . $desc . "</td>
                          <td>
                            <img src='" . $image . "' width='30px' height='30px'>
                            <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='" . $row['canid'] . "'><span class='fa fa-edit'></span></a>
                          </td>
                          <td>" . $firstname . "</td>
                          <td>" . $lastname . "</td>
                          <td>" . $mobile . "</td>
                          <td>" . $aadhar . "</td>
                          <td>" . $voterid . "</td>
                          <td><a href='#platform' data-toggle='modal' class='btn btn-info btn-sm btn-flat platform' data-id='" . $row['canid'] . "'><i class='fa fa-search'></i> View</a></td>
                          <td>
                            <button class='btn btn-success btn-sm edit btn-flat' data-id='" . $row['canid'] . "'><i class='fa fa-edit'></i> Edit</button>
                            <button class='btn btn-danger btn-sm delete btn-flat' data-id='" . $row['canid'] . "'><i class='fa fa-trash'></i> Delete</button>
                          </td>
                        </tr>
                      ";
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
    <?php include 'includes/candidates_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

  <script>
    $(function () {
      $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        $('#edit').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        $('#delete').modal('show');
        var id = $(this).data('id');
        getRow(id);
      });

      $(document).on('click', '.photo', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
      });

      $(document).on('click', '.platform', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        getRow(id);
      });

    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'candidates_row.php',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
          $('.id').val(response.canid);
          var decrypted_firstname = decryptData(response.firstname);
          var decrypted_lastname = decryptData(response.lastname);
          var decrypted_mobile = decryptData(response.mobile);
          var decrypted_aadhar = decryptData(response.aadhar);
          var decrypted_voterid = decryptData(response.voterid);
          var decrypted_platform = decryptData(response.platform);
          var decrypted_position_id = decryptData(response.position_id);
          var decrypted_description = decryptData(response.description);
          $('#posselect').val(decrypted_position_id).html(decrypted_description);
          $('#edit_firstname').val(decrypted_firstname);
          $('#edit_lastname').val(decrypted_lastname);
          $('#edit_mobile').val(decrypted_mobile);
          $('#edit_voterid').val(decrypted_voterid);
          $('#edit_aadhar').val(decrypted_aadhar);
          $('#edit_platform').val(decrypted_platform);
          $('.fullname').html(decrypted_firstname + ' ' + decrypted_lastname);
          $('#desc').html(decrypted_platform);
        }
      });
    }

    function decryptData(data) {
    console.log("Decrypting data:", data);
    
    // Check if input data is null or undefined
    if (data === null || data === undefined) {
        console.error("Input data is null or undefined");
        return ""; // Or handle the error appropriately
    }
    
    // Proceed with decryption logic
    var key = CryptoJS.enc.Hex.parse('acdb62d64e2029a1873cd28ef52c6bc8c2e1b486400d5c7b40e741f1e28bdf3a');
    var cipher = "aes-256-cbc";
    var dataBytes = CryptoJS.enc.Base64.parse(data);
    var iv = dataBytes.clone().words.slice(0, 4); // Extract IV from the data
    var encrypted = dataBytes.clone().words.slice(4); // Extract encrypted data
    var decrypted = CryptoJS.AES.decrypt({ciphertext: CryptoJS.lib.WordArray.create(encrypted)}, key, {iv: CryptoJS.lib.WordArray.create(iv), mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.Pkcs7});
    console.log("Decrypted result:", decrypted.toString(CryptoJS.enc.Utf8));
    return decrypted.toString(CryptoJS.enc.Utf8);
}


  </script>
</body>

</html>