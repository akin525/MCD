<?php
session_start();
if(session_destroy()) {
    print "
                    <script language='javascript'>
                        window.location = 'index.php';
                    </script>
                    ";
}
?>