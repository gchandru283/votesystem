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
        Ballot Position
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ballot Preview</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>

      <div class="row">
        <div class="col-xs-10 col-xs-offset-1" id="content">
        </div>
      </div>
      
    </section>

    
  </div>
    
  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<script>

$(function(){
  fetch();

  $(document).on('click', '.reset', function(e){
    e.preventDefault();
    var desc = $(this).data('desc');
    $('.'+desc).iCheck('uncheck');
  });

  $(document).on('click', '.moveup', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#'+id).animate({
      'marginTop' : "-300px"
    });
    $.ajax({
      type: 'POST',
      url: 'ballot_up.php',
      data:{id:id},
      dataType: 'json',
      success: function(response){
        if(!response.error){
          fetch();
        }
      }
    });
  });

  $(document).on('click', '.movedown', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    $('#'+id).animate({
      'marginTop' : "+300px"
    });
    $.ajax({
      type: 'POST',
      url: 'ballot_down.php',
      data:{id:id},
      dataType: 'json',
      success: function(response){
        if(!response.error){
          fetch();
        }
      }
    });
  });

});

function fetch() {
    $.ajax({
        type: 'POST',
        url: 'ballot_fetch.php',
        dataType: 'json',
        success: function(response) {
            var $response = $(response); // Convert response HTML string to jQuery object
            
            // Decrypt and format data inside '.box-title b' elements
            var encryptedTitles = $response.find('.box-title b');
            encryptedTitles.each(function(index, element) {
                var encryptedData = $(element).text().trim(); // Extract encrypted data from the element
                var decryptedData = decryptData(encryptedData); // Decrypt the encrypted data
                $(element).text(decryptedData); // Replace the encrypted data with the decrypted data
            });
            // Decrypt and format data inside '.box-body p' elements
            // var boxBodies = $response.find('.box-body p');
            // boxBodies.each(function(index, element) {
            //     var encryptedData1 = $(element).text().trim(); // Extract encrypted data from the element
            //     encryptedData1 = encryptedData1.replace('You may select up to', '').replace(' candidates', '');var decryptedData1 = decryptData(encryptedData1); // Decrypt the encrypted data
            //     $(element).text(decryptedData1); // Append the decrypted data as text node
            // });

            // Log the response and update the content
            console.log(response);
            $('#content').html($response).iCheck({checkboxClass: 'icheckbox_flat-green', radioClass: 'iradio_flat-green'});
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
    var dataBytes = CryptoJS.enc.Base64.parse(data);
    var iv = dataBytes.clone().words.slice(0, 4); // Extract IV from the data
    var encrypted = dataBytes.clone().words.slice(4); // Extract encrypted data
    var decrypted;
    
    try {
        decrypted = CryptoJS.AES.decrypt({ciphertext: CryptoJS.lib.WordArray.create(encrypted)}, key, {iv: CryptoJS.lib.WordArray.create(iv), padding: CryptoJS.pad.Pkcs7}).toString(CryptoJS.enc.Utf8);
        console.log("Decrypted result:", decrypted);
    } catch (error) {
        console.error("Error decoding decrypted data:", error);
        decrypted = data; // Return the original encrypted data on decryption failure
    }
    
    return decrypted;
}



</script>
</body>
</html>
