<?php
return array(
    'pdf' => array(
        'enabled' => true,
        'binary' => base_path(env('WK_HTML_TO_PDF_BIN_PATH')),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary' => base_path(env('WK_HTML_TO_IMAGE_BIN_PATH')),
        'timeout' => false,
        'options' => array(),
        'env' => array(),
    ),
);
