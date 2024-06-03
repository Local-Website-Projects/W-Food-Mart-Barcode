<?php
session_start();
session_unset();
session_destroy();
echo "
<script>
        document.cookie = 'alert = 2;';
        window.location.href='Login';
</script>
";