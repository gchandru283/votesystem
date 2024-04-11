<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

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
                      $image = (!empty($row['photo'])) ? '../uploads/' . $row['photo'] : '../images/profile.jpg';
                      echo "
                        <tr>
                        <td>" . $row['firstname'] . "</td>
                          <td>" . $row['lastname'] . "</td>
                          <td>
                            <img src='" . $image . "' width='35px' height='35px'>
                            
                          </td>
                          <td>" . $row['dob'] . "</td>
                          <td>" . $row['age'] . "</td>
                          <td>" . $row['mobile'] . "</td>
                          <td>" . $row['aadhar'] . "</td>
                          <td>" . $row['voterid'] . "</td>
                          <td><center>
                            <button class='btn btn-success btn-sm add' data-id='" . $row['id'] . "'><i class='glyphicon glyphicon-plus-sign'></i> Add</button>
                            <button class='btn btn-danger btn-sm delete' data-id='" . $row['id'] . "'><i class='glyphicon glyphicon-minus-sign'></i> Remove</button>
                            </center></td>
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
    <?php include 'includes/registered_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>
  <script>
    $(function () {

      $(document).on('click', '.add', function (e) {
        e.preventDefault();
        $('#add').modal('show');
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

    });

    function getRow(id) {
      $.ajax({
        type: 'POST',
        url: 'registered_row.php',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
          $('.id').val(response.id);
          $('#edit_firstname').val(response.firstname);
          $('#edit_lastname').val(response.lastname);
          $('#edit_password').val(response.password);
          $('.fullname').html(response.firstname + ' ' + response.lastname);
        }
      });
    }
  </script>
</body>

</html>