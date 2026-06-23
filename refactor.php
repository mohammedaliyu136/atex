<?php

$file = 'c:/wamp64/www/atex/main/resources/views/seller/onboarding/index.blade.php';
$content = file_get_contents($file);

// 1. Add alert at the top if rejected
$alertBlock = <<<'EOD'
    @if(isset($profile) && $profile->verification_status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3 mb-4 flex items-start gap-3">
            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <div>
                <p class="text-sm font-bold text-red-800">Your KYC submission was rejected.</p>
                <p class="text-xs text-red-600 mt-1">Please review the fields below and correct the highlighted issues. You only need to fix the items with red warning messages.</p>
            </div>
        </div>
    @endif
EOD;

$content = str_replace('<form action="{{ route(\'seller.onboarding.store\') }}"', $alertBlock . "\n\n    <form action=\"{{ route('seller.onboarding.store') }}\"", $content);

// 2. Map fields to their data source
$fieldMapping = [
    'business_name' => '$profile->business_name ?? \'\'',
    'seller_brand_name' => '$profile->seller_brand_name ?? \'\'',
    'business_description' => '$profile->business_description ?? \'\'',
    'business_category' => '$profile->business_category ?? \'\'',
    'country' => '$profile->country ?? \'Nigeria\'', // keep default
    'state' => '$profile->state ?? \'\'',
    'lga' => '$profile->lga ?? \'\'',
    'city' => '$profile->city ?? \'\'',
    'business_address' => '$profile->address ?? \'\'',
    'phone' => '$profile->phone ?? \'\'',
    'nin' => '$profile->nin ?? \'\'',
    'full_name' => '$profile->kyc->full_name ?? \'\'',
    'date_of_birth' => '$profile->kyc->date_of_birth ?? \'\'',
    'nationality' => '$profile->kyc->nationality ?? \'\'',
    'residential_address' => '$profile->kyc->residential_address ?? \'\'',
    'id_type' => '$profile->kyc->id_type ?? \'\'',
    'id_number' => '$profile->kyc->id_number ?? \'\'',
];

// Replace old('field') with old('field', $source)
foreach ($fieldMapping as $field => $source) {
    if ($field === 'country') continue; // country has special logic
    $content = str_replace("old('$field')", "old('$field', isset(\$profile) ? $source : '')", $content);
}

// Add error displays
function addErrorDisplay($content, $field) {
    $errorHtml = "\n                        @if(isset(\$rejectedFields) && \$rejectedFields->has('$field'))\n                            <p class=\"text-xs text-red-600 mt-1.5 font-semibold flex items-start\"><i data-lucide=\"alert-circle\" class=\"w-3.5 h-3.5 mr-1 shrink-0 mt-0.5\"></i> <span>{{ \$rejectedFields['$field']->comment }}</span></p>\n                        @endif";
    
    // Attempt to insert after input, select, or textarea
    // We will use regex to find the closing tag or self-closing tag for the specific field
    $pattern = '/(<(input|select|textarea)[^>]*name="' . preg_quote($field, '/') . '"[^>]*(>|<\/(select|textarea)>))/';
    $content = preg_replace($pattern, "$1" . $errorHtml, $content);
    return $content;
}

$allFields = array_keys($fieldMapping);
$allFields = array_merge($allFields, ['id_front', 'id_back', 'selfie', 'proof_of_address', 'cac_certificate']); // add file fields

// The file fields map to 'id_front_path' etc in the database
$fileFieldMapping = [
    'id_front' => 'id_front_path',
    'id_back' => 'id_back_path',
    'selfie' => 'selfie_path',
    'proof_of_address' => 'proof_of_address_path',
    'cac_certificate' => 'cac_certificate_path',
];

foreach ($allFields as $field) {
    $dbField = $fileFieldMapping[$field] ?? $field;
    $content = addErrorDisplay($content, $dbField);
}

// We need to also allow previously uploaded files to be retained visually or marked as ok.
// If a file field is NOT rejected, maybe it shouldn't be required!
// Currently file inputs have `required` on ID front/back/selfie
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="id_front"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'id_front_path\'))) ? \'required\' : \'\' }}$2name="id_front"', $content);
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="id_back"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'id_back_path\'))) ? \'required\' : \'\' }}$2name="id_back"', $content);
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="selfie"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'selfie_path\'))) ? \'required\' : \'\' }}$2name="selfie"', $content);


file_put_contents($file, $content);

echo "Done refactoring.";
