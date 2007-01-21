<?php

// Looks for a cache file with specific identifier newer than $date.
//
// $date is unix timestamp.
//
// Returns disk file name or null if not cached.
function cache_query($cache_id, $date) {
    // get disk file paths
    $cache_file = IA_CACHE_DIR . $cache_id;
    $mtime = @filemtime($cache_file);

    if ($mtime === false || $mtime < $date) {
        // cache is older, delete cache.
        @unlink($cache_file);
        return null;
    } else {
        // cache is up-to-date, return
        return $cache_file;
    }
}

// Sweep the cache
function cache_sweep() {
    log_warn("CACHE: Sweeping not implemented.");
}

// Add something to the cache.
function cache_save($cache_id, $buffer) {
    if (cache_usage() > IA_CACHE_SIZE) {
        // cache is full
        log_warn('Cache is full.');
        cache_sweep();
        return false;
    }

    $filename = IA_CACHE_DIR . $cache_id;
    $ret = @file_put_contents($filename, $buffer, LOCK_EX);
    // A broken cacke is fairly harmless, especially in debug.
    // Throwing up here results in no visible images, which tends to suck.
    if (false === $ret) {
        log_warn('CACHE: Could not create file ' . $filename);
        return false;
    }

    log_print("CACHE: Saved $cache_id");

    return true;
}

// Calculate cache usage.
function cache_usage() {
    // scan all files in image cache directory
    $nodes = scandir(IA_CACHE_DIR);
    $files = array();
    foreach ($nodes as $node) {
        if (!is_dir($node)) {
            $files[] = $node;
        }
    }

    // sum up file size
    $total = 0;
    foreach ($files as $file) {
        $fsize = filesize(IA_CACHE_DIR . $file);
        if (false === $fsize) {
            log_warn('CACHE: Could not determine file size of ' . IA_CACHE_DIR . $file);
        }
        $total += $fsize;
    }

    return $total;
}



?>
