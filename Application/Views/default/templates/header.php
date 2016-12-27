<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $data['title'] . ' &mdash; ' . SITETITLE; ?></title>

    <!-- Vendor CSS -->
    <?php
    $i = '0';
    while ($i < count($data['styles_vendor'])) {
        echo '<link rel="stylesheet" href="/vendor/' . $data['styles_vendor'][$i] . '">' . "\n";
        $i++;
    }
    unset($i);
    ?>

    <!-- Site CSS -->
    <?php
    $i = '0';
    while ($i < count($data['styles'])) {
        echo '<link rel="stylesheet" href="/css/' . $data['styles'][$i] . '">' . "\n";
        $i++;
    }
    unset($i);
    ?>
</head>

<body style="padding-top: 70px;">

<nav class="navbar navbar-default navbar-fixed-top" style="min-height: 50px; margin-bottom: 20px;">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo DIR; ?>"><?php echo SITETITLE; ?></a>
        </div>
        <!--/.nav-header -->

        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?php echo DIR; ?>error">Error</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->

    </div>
</nav>

<div class="container">
