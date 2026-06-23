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

if (!str_contains($content, 'Your KYC submission was rejected')) {
    $content = str_replace('<form action="{{ route(\'seller.onboarding.store\') }}"', $alertBlock . "\n\n    <form action=\"{{ route('seller.onboarding.store') }}\"", $content);
}

// 2. Add error displays AFTER the <label>
$fieldMapping = [
    'business_name' => '$profile->business_name ?? \'\'',
    'seller_brand_name' => '$profile->seller_brand_name ?? \'\'',
    'business_description' => '$profile->business_description ?? \'\'',
    'business_category' => '$profile->business_category ?? \'\'',
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

$allFields = array_keys($fieldMapping);
$allFields = array_merge($allFields, ['country', 'id_front', 'id_back', 'selfie', 'proof_of_address', 'cac_certificate']);

$fileFieldMapping = [
    'id_front' => 'id_front_path',
    'id_back' => 'id_back_path',
    'selfie' => 'selfie_path',
    'proof_of_address' => 'proof_of_address_path',
    'cac_certificate' => 'cac_certificate_path',
];

foreach ($allFields as $field) {
    $dbField = $fileFieldMapping[$field] ?? $field;
    
    $errorHtml = "\n                        @if(isset(\$rejectedFields) && \$rejectedFields->has('$dbField'))\n                            <p class=\"text-xs text-red-600 mb-2 font-semibold flex items-start\"><i data-lucide=\"alert-circle\" class=\"w-3.5 h-3.5 mr-1 shrink-0 mt-0.5\"></i> <span>{{ \$rejectedFields['$dbField']->comment }}</span></p>\n                        @endif\n                        ";
    
    // Safely insert between </label> and <input/select/textarea>
    $pattern = '/(<label[^>]*>.*?<\/label>)\s*(<(input|select|textarea)[^>]+name="' . preg_quote($field, '/') . '"[^>]*>)/is';
    
    $content = preg_replace($pattern, "$1" . $errorHtml . "$2", $content);
}

// 3. Now replace old('field') with old('field', $source)
foreach ($fieldMapping as $field => $source) {
    // avoid double replacing if already done
    if (!str_contains($content, "old('$field', isset(\$profile)")) {
        $content = str_replace("old('$field')", "old('$field', isset(\$profile) ? $source : '')", $content);
    }
}
// Special case for country
$content = str_replace("old('country', 'Nigeria')", "old('country', isset(\$profile) ? (\$profile->country ?? 'Nigeria') : 'Nigeria')", $content);

// 4. Update file inputs to not be required if already exists
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="id_front"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'id_front_path\'))) ? \'required\' : \'\' }}$2name="id_front"', $content);
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="id_back"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'id_back_path\'))) ? \'required\' : \'\' }}$2name="id_back"', $content);
$content = preg_replace('/<input type="file"([^>]*)required([^>]*)name="selfie"/', '<input type="file"$1{{ (!isset($profile) || (isset($rejectedFields) && $rejectedFields->has(\'selfie_path\'))) ? \'required\' : \'\' }}$2name="selfie"', $content);

file_put_contents($file, $content);
echo "Done refactoring cleanly.";
