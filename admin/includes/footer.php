    <!-- Scripts -->
    <script src="../assets/plugins/jquery/jquery-2.2.0.min.js"></script>
    <script src="../assets/plugins/materialize/js/materialize.min.js"></script>
    <script src="../assets/plugins/material-preloader/js/materialPreloader.min.js"></script>
    <script src="../assets/plugins/jquery-blockui/jquery.blockui.js"></script>
    <script src="../assets/js/alpha.min.js"></script>
    <script src="../assets/js/pages/form_elements.js"></script>
    <script src="../assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/pages/table-data.js"></script>







    <script>
        //Rander for Profile images

        document.getElementById('image-upload').onchange = function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgPreview = document.getElementById('image-preview');
                    imgPreview.src = e.target.result; // Set the src attribute to the image's data URL
                    imgPreview.style.display = 'block'; // Show the image element
                }

                reader.readAsDataURL(file); // Read the file as a Data URL (Base64)
            }
        };

        function updateSalary() {
            var positionSelect = document.getElementById("position");
            var selectedOption = positionSelect.options[positionSelect.selectedIndex];
            var salary = selectedOption.getAttribute("data-salary");
            document.getElementById("salary").value = salary;
        }
        //Rander for Profile images

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profile_image_preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        // Close button functionality
        document.querySelectorAll('.close-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.style.display = 'none'; // Hide the notification
            });
        });

        // Remove query string if there's 'msg' or 'error'
        if (window.location.search.indexOf('msg=') > -1 || window.location.search.indexOf('error=') > -1) {
            const url = window.location.href.split('?')[0]; // Get the URL without the query string
            window.history.replaceState(null, null, url); // Replace the current history state with the clean URL
        }
    </script>