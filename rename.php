<?php

$dirs = ['app', 'database', 'resources/views', 'routes', 'config'];
$replacements = [
    'ExporterProfile' => 'SellerProfile',
    'exporterProfile' => 'sellerProfile',
    'exporter_profiles' => 'seller_profiles',
    'exporter_profile' => 'seller_profile',
    'Exporters' => 'Sellers',
    'exporters' => 'sellers',
    'Exporter' => 'Seller',
    'exporter' => 'seller',
];

function processDir($dir, $replacements) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'json', 'txt'])) {
            $path = $file->getPathname();
            $content = file_get_contents($path);
            $originalContent = $content;

            foreach ($replacements as $search => $replace) {
                $content = str_replace($search, $replace, $content);
            }

            if ($content !== $originalContent) {
                file_put_contents($path, $content);
                echo "Updated content: $path\n";
            }

            // Rename file if name contains exporter
            $filename = basename($path);
            $newFilename = $filename;
            foreach ($replacements as $search => $replace) {
                $newFilename = str_replace($search, $replace, $newFilename);
            }

            if ($filename !== $newFilename) {
                $newPath = dirname($path) . DIRECTORY_SEPARATOR . $newFilename;
                rename($path, $newPath);
                echo "Renamed file to: $newPath\n";
            }
        }
    }
}

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        processDir($dir, $replacements);
    }
}

function renameDirs($dir, $replacements) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($iterator as $file) {
        if ($file->isDir() && !in_array($file->getBasename(), ['.', '..'])) {
            $path = $file->getPathname();
            $dirname = basename($path);
            $newDirname = $dirname;
            foreach ($replacements as $search => $replace) {
                $newDirname = str_replace($search, $replace, $newDirname);
            }

            if ($dirname !== $newDirname) {
                $newPath = dirname($path) . DIRECTORY_SEPARATOR . $newDirname;
                rename($path, $newPath);
                echo "Renamed dir to: $newPath\n";
            }
        }
    }
}

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        renameDirs($dir, $replacements);
    }
}

echo "Done\n";
