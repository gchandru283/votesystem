<html>
  <body>
<!-- Config Name-->
<div class="modal fade" id="config">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Configure</b></h4>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <?php
                  $parse = parse_ini_file('config.ini', FALSE, INI_SCANNER_RAW);
                  $title = $parse['election_title'];
                ?>
                <form class="form-horizontal" method="POST" action="config_save.php?return=<?php echo basename($_SERVER['PHP_SELF']); ?>">
                  <div class="form-group">
                    <label for="title" class="col-sm-3 control-label">Title</label>

                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
                    </div>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-success btn-flat" name="save"><i class="fa fa-save"></i> Save</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Config Election Start-->
<div class="modal fade" id="election_start">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Starting Election..</b></h4>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <form class="form-horizontal" method="POST" action="election_start.php">
                  <div class="form-group">
                    <h2 class="bold">Send Credentials to Voters ?</h2>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-primary btn-flat" name="send" id="visibleButton"><i class="fa fa-paper-plane"></i>&nbsp; Send</button>
              </form>
            </div>
        </div>
    </div>
</div>

<!-- Config Election Results-->
<div class="modal fade" id="election_results">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><b>Closing Election..</b></h4>
            </div>
            <div class="modal-body">
              <div class="text-center">
                <form class="form-horizontal" method="POST" action="election_results.php">
                  <div class="form-group">
                    <h2 class="bold">Announce Results ?</h2>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
              <button type="submit" class="btn btn-danger btn-flat" name="send"><i class="fa fa-check-square-o "></i>&nbsp; Proceed</button>
              </form>
            </div>
        </div>
    </div>
</div>


<script>
    function setSessionStorage(value) {
    sessionStorage.setItem('isElectionEnded', value);
  }

  // When the "Proceed" button is clicked in the election results modal
  document.getElementById('election_results').addEventListener('submit', function(event) {
    setSessionStorage('true');
    console.log(sessionStorage.getItem('isElectionEnded'));
  });

  // When the "Send" button is clicked in the election start modal
  document.getElementById('election_start').addEventListener('submit', function(event) {
    setSessionStorage('false');
    console.log(sessionStorage.getItem('isElectionEnded'));
  });

  window.onload = function() {
  // Retrieve the value of 'isElectionEnded' from sessionStorage
  var isElectionEnded = sessionStorage.getItem('isElectionEnded');

  // Log the value to verify
  console.log("Sending isElectionEnded:", isElectionEnded);

  // Send the value to results.php using AJAX
  var xhr = new XMLHttpRequest();
  xhr.open('POST', '../results.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
    }
  };
  xhr.send('isElectionEnded=' + isElectionEnded);
};


</script>

  </body>

  </html>