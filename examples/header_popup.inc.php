<?php
// This example header.inc.php is intended to be modfied for your application.
use QCubed as Q;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo(QCUBED_ENCODING); ?>"/>
    <meta content="text/html"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
	<?php if (isset($strPageTitle)){ ?>
        <title><?php _p($strPageTitle); ?></title>
    <?php } ?>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="/qcubed-4/project/assets/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="../assets/css/awesome-bootstrap-checkbox.css" rel="stylesheet"/>
    <link href="/qcubed-4/project/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/qcubed-4/vendor/kukrik/bootstrap-filecontrol/assets/css/jquery.fileupload.css" rel="stylesheet" />
    <link href="/qcubed-4/vendor/kukrik/bootstrap-filecontrol/assets/css/jquery.fileupload-ui.css" rel="stylesheet" />
    <link href="../assets/css/custom.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.fileinfo.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.filemanager.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.uploadhandler.css" rel="stylesheet"/>
    <link href="/qcubed-4/vendor/kukrik/select2/assets/css/select2.css" rel="stylesheet" />
    <link href="../assets/css/select2-web-vauu.css" rel="stylesheet" />
    <link href="../assets/css/wrapper.css" rel="stylesheet"/>

    <link href="../assets/css/croppie.css" rel="stylesheet" />
    <link href="../assets/css/custom-switch.css" rel="stylesheet" />


</head>
<body>