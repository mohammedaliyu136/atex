<?php

$dirs = ['app', 'database', 'resources', 'routes', 'config'];
$replacements = [
    'FieldOfficerProfile' => 'AdminProfile',
    'fieldOfficerProfile' => 'adminProfile',
    'field_officer_profiles' => 'admin_profiles',
    'field_officer_profile' => 'admin_profile',
    'field-officer' => 'admin',
    'field_officer' => 'admin',
    'Field Officer' => 'Admin',
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

            // Rename file if name contains
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

echo "Done\n";
