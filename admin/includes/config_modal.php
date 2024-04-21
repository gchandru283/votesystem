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
              <form action = "../../results.php" method="POST">
              <input type="hidden" name="electionEnded" value="false"> 
            <button type="submit" name="results_start" id="hiddenButton" style="display: none;"></form>
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
              <form action = "../../results.php" method="POST">
              <input type="hidden" name="electionEnded" value="true"> 
            <button type="submit" name="results_end" id="hiddenButton" style="display: none;"></form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('visibleButton').addEventListener('click', function() {
        // Activate the hidden button
        console.log(document.getElementById('hiddenButton').click());
    });
</script>

  </body>

  </html>