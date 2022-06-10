<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

  <title><?php if (isset($subject)){ echo $subject; } ?></title>

<style>
body {
margin: 0;
padding: 0;
}


.html_link_spiel {
font-size: 0.8em;
}

.confirm_button {
font-size: 16px;
font-family: Helvetica, Arial, sans-serif; color: #ffffff;
text-decoration: none;
border-radius: 3px;
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
background-color: #EB7035;
border-top: 12px solid #EB7035;
border-bottom: 12px solid #EB7035;
border-right: 18px solid #EB7035;
border-left: 18px solid #EB7035;
display: inline-block;
}

img {
border: 0px; 
}

  dt {
    font-weight: bold;
    text-decoration: underline;
  }
  dd {
    margin: 0;
    padding: 0 0 0.5em 0;
  }


</style>


</head>
<body><div id="content"><?php echo $message; ?></div>
<ul id="bottom_adbar">
</ul>
</body>
</html>