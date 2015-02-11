<?php // apc_compile_dir.php
function apc_compile_dir($root, $recursively = true){
    $compiled   = true;
    echo 'new';
    switch($recursively){
        case    true:
            foreach(glob($root.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir){
                $compiled   = $compiled && apc_compile_dir($dir, $recursively);
                }
        case    false:
            foreach(glob($root.DIRECTORY_SEPARATOR.'*.php') as $file){
                $compiled   = $compiled && apc_compile_file($file);
                echo $file.' Compiled OK com:'.$compiled.'<br />';
                }
            break;
    }
    return  $compiled;
}





//echo apc_compile_dir('classe',true);

apc_add('var', 'Ma super donne',100);

var_dump(apc_fetch('var'));
echo '<pre>';
print_r(apc_cache_info());
echo '</pre>';



echo phpinfo();

?>


