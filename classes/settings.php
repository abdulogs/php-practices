<?php
// Set it to true when you will upload this website on cpanel
app::setProduction(false);

// Set database 
app::setHost("localhost");
app::setUsername("root","trixum_fxtrqygxtradeinadmin");
app::setPassword("","oD*B6&bbmBNX");
app::setDatabase("forex","trixum_fxtrqygx");
app::setConnection();

// Set timezone
app::setTimeZone("Asia/Karachi");

// Set url
app::setUrl("default");

// set session
app::startSession(true);

// set email
app::setEmail("admin@forex.com");
