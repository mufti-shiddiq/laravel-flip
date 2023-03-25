<?php
if (env('FLIP_ENV') == 'sandbox') {
    $baseUrlV2 = "https://bigflip.id/big_sandbox_api/v2";
    $baseUrlV3 = "https://bigflip.id/big_sandbox_api/v3";
} else {
    $baseUrlV2 = "https://bigflip.id/api/v2";
    $baseUrlV3 = "https://bigflip.id/api/v3";
}

return [
    'key_auth' => base64_encode(env('FLIP_SECRET_KEY') . ':'),
    'base_url_v2' => $baseUrlV2,
    'base_url_v3' => $baseUrlV3
];
