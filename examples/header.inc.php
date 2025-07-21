<?php
    // ADD TO THE BEGINNING OF THE FILE NOW! Before outputting any HTML!

    // Cache and freshness check
    header('Cache-Control: no-cache, no-store, must-revalidate'); // No caching allowed
    header('Pragma: no-cache'); // Backward compatibility with older proxies/browsers
    header('Expires: 0'); // Page expires immediately

    // Security headers
    header('X-Content-Type-Options: nosniff'); // Disable content-type sniffing
    header('X-Frame-Options: SAMEORIGIN'); // The page can only be loaded in an iframe from the same domain
    header('Referrer-Policy: strict-origin-when-cross-origin'); // Share reference information more limitedly
    header('X-XSS-Protection: 1; mode=block'); // Activate XSS protection (older browsers)

    // (Optional) Want even higher security?
    // header('Content-Security-Policy: default-src \'self\'');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?php echo(QCUBED_ENCODING); ?>"/>
    <meta content="text/html"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php if (isset($strPageTitle)){ ?><title><?php _p($strPageTitle); ?></title><?php } ?>

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/font-awesome.css" rel="stylesheet"/>
    <link href="../assets/css/awesome-bootstrap-checkbox.css" rel="stylesheet"/>
    <link href="<?= QCUBED_BOOTSTRAP_CSS ?>" rel="stylesheet"/>
    <link href="../assets/css/jquery.bxslider.css" rel="stylesheet"/>
    <link href="../assets/css/custom.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.fileinfo.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.filemanager.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.uploadhandler.css" rel="stylesheet"/>
    <link href="../assets/css/qcubed.gallery.css" rel="stylesheet"/>
    <link href="../assets/css/toastr.css" rel="stylesheet"/>
    <link href="../assets/css/toastr.fontawesome.css" rel="stylesheet"/>
    <link href="../../select2/assets/css/select2.css" rel="stylesheet"/>
    <link href="../../select2/assets/css/select2-bootstrap.css" rel="stylesheet"/>
    <link href="../assets/css/select2-web-vauu.css" rel="stylesheet"/>
</head>

<body>