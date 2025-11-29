    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="<?php echo base_url(); ?>assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="<?php echo base_url(); ?>assets/js/base.js"></script>

    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>

    <script>
        function togglePassword() {
            var pwd = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");

            if (pwd.type === "password") {
                pwd.type = "text";
                eyeIcon.setAttribute("name", "eye-off-outline");
            } else {
                pwd.type = "password";
                eyeIcon.setAttribute("name", "eye-outline");
            }
        }
    </script>