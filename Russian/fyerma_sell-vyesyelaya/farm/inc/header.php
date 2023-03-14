<?php
if(isset($_GET['gr']))header("Location: ../gr.php?id=".htmlspecialchars($_GET['gr'])."&ok");
if(isset($_GET['gr2']))header("Location: ../gr.php?id=".htmlspecialchars($_GET['gr2'])."&sob_ok");
?>