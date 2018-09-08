<?php
$locale = false; // initilize language parameter
if (isSet($_GET["locale"])) // check if getting locale value in query string
{
    $locale = $_GET["locale"]; // add selected language in variable
}
else
{
    $locale = "en_US"; // set English US as default language
}

putenv("LANGUAGE=$locale");
setlocale(LC_ALL, $locale);

$domain = "messages";
bindtextdomain($domain, "locale");

bind_textdomain_codeset($domain, "UTF-8");

textdomain($domain);
?>