<?php
$file = 'c:/wamp64/www/atex/main/resources/views/seller/onboarding/index.blade.php';
$content = file_get_contents($file);

$pattern = '/([ \t]*)(@if\(isset\(\$rejectedFields\) && \$rejectedFields->has\(\'[^\']+\'\)\)\s*<p class="text-xs text-red-600 mb-2 font-semibold flex items-start">.*?<\/p>\s*@endif\s*)(<input[^>]+>|<textarea[^>]*>.*?<\/textarea>|<select[^>]*>.*?<\/select>)/s';

$content = preg_replace_callback($pattern, function($matches) {
    $indent = $matches[1];
    $ifBlock = str_replace('mb-2', 'mt-2', trim($matches[2]));
    $inputBlock = trim($matches[3]);
    return $inputBlock . "\n" . $indent . $ifBlock . "\n";
}, $content);

file_put_contents($file, $content);
echo "Replaced successfully!\n";
