<?php include("translate.php"); ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>How to Internationalize your PHP web sites using gettext</title>
</head>
<body>
<a href="?locale=en_US">English</a> |
<a href="?locale=fr_FR">French</a> |
<a href="?locale=hi_IN">Hindi</a> |
<a href="?locale=ur_PK">Urdu</a>
<br>
<br>
<?php echo _("Hello World");?><br>
<p><?php echo _("My name is");?> Huzoor Bux.</p>
</body>
</html>