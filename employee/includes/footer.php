        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
        <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
        <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
        <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
        <script src="../assets/plugins/waypoints/jquery.waypoints.min.js"></script>
        <script src="../assets/plugins/counter-up-master/jquery.counterup.min.js"></script>
        <script src="../assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="../assets/plugins/chart.js/chart.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="../assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="../assets/plugins/curvedlines/curvedLines.js"></script>
        <script src="../assets/plugins/peity/jquery.peity.min.js"></script>
        <script src="../assets/js/alpha.min.js"></script>
        <script src="../assets/js/pages/form_elements.js"></script>
        <script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/pages/table-data.js"></script>
        <script src="assets/js/pages/ui-modals.js"></script>
        <script src="assets/plugins/google-code-prettify/prettify.js"></script>
        <script src="assets/js/pages/form-input-mask.js"></script>
        <script src="assets/plugins/jquery-inputmask/jquery.inputmask.bundle.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>


  <!-- Javascripts -->




<script>
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    // Function to resize the canvas correctly, taking device pixel ratio into account
    function resizeCanvas() {
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        signaturePad.clear(); // Clears the canvas after resizing
    }

    // Adjust canvas when window resizes
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas(); // Initial resize

    // Clear button to clear the signature pad
    document.getElementById('clear').addEventListener('click', function() {
        signaturePad.clear();
    });

    // On form submit, check if the pad is not empty and capture the signature as Base64
    document.getElementById('signature-form').addEventListener('submit', function(event) {
        if (signaturePad.isEmpty()) {
            alert("Please provide a signature first.");
            event.preventDefault(); // Prevent form submission if no signature
        } else {
            // Save signature data as base64
            var dataURL = signaturePad.toDataURL();
            document.getElementById('signature-data').value = dataURL;
        }
    });
</script>



