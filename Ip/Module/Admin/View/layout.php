<?php
/**
 * This comment block is used just to make IDE suggestions to work
 * @var $this \Ip\View
 */
?>
<?php echo $this->doctypeDeclaration(); ?>
<html<?php echo $this->htmlAttributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo  ipConfig()->coreModuleUrl('Assets/assets/fonts/font-awesome/font-awesome.css') ?>" type="text/css" rel="stylesheet" media="screen" />

    <?php ipPrintHead() ?>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="manage">
    <div class="ip">
        <?php echo ipBlock('main'); ?>
    </div>
<?php ipPrintJavascript() ?>
</body>
</html>