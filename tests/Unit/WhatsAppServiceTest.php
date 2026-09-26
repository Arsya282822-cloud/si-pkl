<?php

namespace Tests\Unit;

use App\Services\WhatsAppService;
use PHPUnit\Framework\TestCase;

class WhatsAppServiceTest extends TestCase
{
    public function test_it_formats_indonesian_phone_numbers_correctly(): void
    {
        // 0812xxxx -> 62812xxxx
        $this->assertEquals('628123456789', WhatsAppService::formatPhoneNumber('08123456789'));

        // 812xxxx -> 62812xxxx
        $this->assertEquals('628123456789', WhatsAppService::formatPhoneNumber('8123456789'));

        // +62 812-3456-7890 -> 6281234567890
        $this->assertEquals('6281234567890', WhatsAppService::formatPhoneNumber('+62 812-3456-7890'));

        // Already 62812xxxx
        $this->assertEquals('628123456789', WhatsAppService::formatPhoneNumber('628123456789'));

        // Null / Empty
        $this->assertNull(WhatsAppService::formatPhoneNumber(null));
        $this->assertNull(WhatsAppService::formatPhoneNumber(''));
    }
}
