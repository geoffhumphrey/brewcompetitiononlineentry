<?php
include (__DIR__ . '/../paths.php');

/**
* Recursively move files from one directory to another
*
* @param String $src – Source of files being moved
* @param String $dest – Destination of files being moved
* @source https://ben.lobaugh.net/blog/864/php-5-recursively-move-or-copy-files
*/
function rmove(string $src, string $dest): bool{

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

    // If the destination directory does not exist create it
    if(!is_dir($dest)) {
        //mkdir($dest);
        if(!mkdir($dest)) {
            // If the destination directory could not be created stop processing
            return false;
        }
    }

    // Open the source directory to read in files
    $i = new DirectoryIterator($src);
    foreach($i as $f) {
        if($f->isFile()) {
            rename($f->getRealPath(), "$dest/" . $f->getFilename());
        }
    }

    return true;
}

/*
function rdelete($src,$file_ext){
    if (empty($file_ext)) $file_ext = ".pdf";
    // If source is not a directory stop processing
    if(!is_dir($src)) return false;
    array_map('unlink', glob($src."*".$file_ext));
}
*/
/**
 * @param mixed $file_mimes
 */
function rdelete(string $src, $file_mimes): bool{

    if (empty($file_mimes)) $file_mimes = ['image/jpeg','image/jpg','image/gif','image/png','application/pdf','image/bmp','image/tiff','image/svg+xml'];
    else $file_mimes = ['application/pdf'];

    // If source is not a directory stop processing
    if(!is_dir($src)) return false;

    //array_map('unlink', glob($src."*".$file_ext));

    $files = new FilesystemIterator($src);

    foreach($files as $file) {
        $mime = mime_content_type($file->getPathname());
        if (in_array($mime, $file_mimes)) unlink($file);
    }

    return true;
}

$src = USER_DOCS;
$dest = (USER_DOCS."2018");

echo $src."<br>";
echo $dest;

rdelete($src,".pdf");

//rmove($src, $dest);
//rcopy($src, $dest);
?>