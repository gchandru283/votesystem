<div class="modal fade" id="face_modal" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" disabled data-dismiss="modal" aria-label="Close" style="cursor: not-allowed;">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- <h4 class="modal-title"><b>Face verification...</b></h4> -->
        <p id="verification_status">Face verification...</p>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="POST" action="face_recognition.php">
          <input type="hidden" class="id" name="id" value="">
          <div class="text-center">
            <h3 class="bold fullname" id="verification_message"><b>Face verification in progress. Don't refresh or press back button...</b></h3>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="verify_button" class="btn btn-default btn-flat" name="verify" disabled></button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Immediately invoked function expression (IIFE) to start verification automatically
  (function() {
    var button = document.getElementById("verify_button");
    var verificationMessage = document.getElementById("verification_message");
    var countdown = 42;
    button.disabled = true;
    button.innerHTML = "Please wait " + countdown + "s";

    var interval = setInterval(function() {
      countdown--;
      button.innerHTML = "Please wait " + countdown + "s";
      if (countdown <= 0) {
        button.innerHTML = "Please wait 0s";
      }
    }, 1000);
  })();
</script>
