<?php

namespace App\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeService
{
    // Tạo QR Code dưới dạng data URI
    public function generate(string $token): string
    {
        $options = new QROptions([
            'eccLevel' => 'M',
            'outputBase64' => true,
            'scale' => 8,
        ]);

        return (new QRCode($options))->render($token);
    }
}
