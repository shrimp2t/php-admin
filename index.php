<?php

function get_ext( $file ){
    $array = explode('.', $file );
    $extension = end($array);
    return $extension;
}


$dir    = dirname( __FILE__ );
$base_dir =  basename( $dir );
$folder = isset( $_GET['folder'] ) ? $_GET['folder'] : '';
$view_file =  isset( $_GET['file'] ) ? $_GET['file'] : '';

$root_url = '';
if ( $folder != '' ){
    $scan_dir = $dir.'/'.$folder;
} else {
    $scan_dir = $dir;
}

if ( ! is_dir( $scan_dir ) ) {
    $scan_dir = $dir;
}

$breadcrumbs = array();
$breadcrumbs =  array( '<a href="/">'.$base_dir.'</a>');
if ( $scan_dir != $dir ){
    $_folders = explode( '/', $folder );
    $_f = '';
    foreach ( $_folders as $f ){
        if ( $_f ) {
            $_f .='/'.$f;
        } else {
            $_f .= $f;
        }

        $breadcrumbs[] = '<a href="/?folder='.$_f.''.'">'.$f.'</a>';
    }
}


$breadcrumbs = array_filter( $breadcrumbs );

?><html>
<head>
    <title>Localhost<?php echo $scan_dir ? ' '.$scan_dir : '' ; ?></title>
    <style>
        *{
            margin: 0px;
            padding: 0px;
            box-sizing: border-box;
        }
        body {
            background: #f5f5f5;
            font-family: "Open Sans",sans-serif;
            font-size: 14px;
        }
        a {
            color: rgb(83, 83, 83);
            text-decoration: none;
        }
        a:hover {
            color: rgb(56, 151, 237);
        }
        .body {
           display: block;
           margin: 30px;
        }
        .files {
            margin: 0px -5px;
            display: block;
            max-width: 700px ;
        }
        .breadcrumbs .sep{
            margin-left: 2px;
            margin-right: 2px;
        }
        .files:after {
            clear: both;
            content: " "; display: block;
        }
        .files .file {
           padding: 5px 5px;
        }
        .col {
            width: 50%;
            float: left;
            display: block;
            min-height: 100px;
        }
        .col .view-code {
            float: right;
            font-size: 12px;
            text-transform: uppercase;
        }
        .col .file:after {
            clear: both;
            content: ' ';
            display: block;
        }
        h3 {
            padding: 5px 5px;
            font-size: 18px;
        }
        .file {
            background: #FFFFFF;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.08);
            padding: 10px 15px;
            margin: 5px;
            font-size: 14px;
        }
        .files a {
            display: block;
            font-size: 14px;
            line-height: 20px;
            padding: 5px 10px;
        }
        .files a .ext {
            background: #f5500c;
            color: #FFFFFF;
            padding: 5px 10px;
        }
        .current-dir {
            margin-bottom: 10px;
        }
        .current-dir small {
            font-size: 14px;
            display: block;
            font-weight: normal;
            margin-top: 10px;
            background: #FFFFFF;
            padding: 5px;
        }
        pre {
            width: 100%;
            overflow: auto;
            background: #FFFFFF;
            padding: 15px;
            margin-bottom: 30px;;
        }
        h3 span {
            font-weight: normal;
        }
        .file.folder {
            background: rgba(71, 151, 25, 0.2);
        }
        .body {
            padding-top: 30px;
        }
        .actions {
            height: 30px;
            position: fixed;
            top: 0px;
            left: 0px;
            width: 100%;
            background: #000000;
        }
        .actions a {
            color: #ebebeb;
            font-size: 16px;
            line-height: 30px;
            margin: 0px 10px;
        }
    </style>
</head>
<body>
<div class="actions">
    <a href="http://localhost/phpMyAdmin/">phpMyAdmin</a>
    <a href="http://localhost/MAMP/">MAMP</a>
    <a href="http://localhost/MAMP/index.php?language=English&page=phpinfo">phpinfo</a>
    <a href="http://localhost/phpLiteAdmin/phpliteadmin.php">phpLiteAdmin</a>
</div>
<div class="body">
    <h2 class="current-dir"><?php echo basename( $scan_dir ); ?>
    <small class="breadcrumbs"><?php echo join('<span class="sep">/</span>', $breadcrumbs ); ?></small>
    </h2>
    <?php if ( is_file( $view_file ) ) { ?>
        <h3>Viewing file: <span><?php echo $view_file; ?></span></h3>
        <pre><?php highlight_string( file_get_contents( $view_file ) ) ; ?></pre>
    <?php } ?>
    <div class="files">
        <?php
        $files = scandir( $scan_dir , 1 );

        $cols = array( 'folders'=>array(), 'files' =>array() );

        foreach ( $files as $file ) {
            if ( $file =='.' ||  $file =='..' ) {
                    continue;
            }

            if ( $folder !='' ){
                $test_dir = $folder.'/'. $file;
            } else {
                $test_dir =  $file;
            }

            if ( is_dir( $test_dir ) ) {
                $cols['folders'][] =  '<div class="file folder"><a href="'.$root_url.'/?folder='.$test_dir.'"><span class="name">'.$file.'<span></a></div>';
            } else {

                $cols['files'][] =  '<div class="file '.get_ext( $file ).'"><a class="view-code" href="'.$root_url.'/?folder='.$folder.'&file='.$folder.'/'.$file.'">Code</a><a href="'.$root_url.'/'.$folder.'/'.$file.'"><span class="name">'.$file.'<span></a></div>';
            }
        }

        echo '<div class="col col-folder"><h3>Folders</h3>'.join( ' ', $cols['folders'] ).'</div>';
        echo '<div class="col col-files"><h3>Files</h3>'.join( ' ', $cols['files'] ).'</div>';

        ?>
    </div>
</div>
</body>
</html>