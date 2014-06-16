<?php
// autoload a class without requiring a class
function __autoload($class_name) {
    $class_name = strtolower($class_name);
    $path = "../core/{$class_name}.php";

    if(file_exists($path)) {
        require_once($path);
    } else {
        die("The file {$class_name}.php could not be found.");
    }
}

function samp() {
    echo "hello from function";
}

function template($page, $title="") {
    include(PATH_TMPL.DS.$page);
}

?>
