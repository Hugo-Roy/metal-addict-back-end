<?php

namespace App\DataFixtures\Provider;

class ShareOMetalProvider 
{
    private $setlistIds = [
        '7bea6a34',
        '3bea6ccc',
        '5bfbfb50',
        '1398a5f9',
        '6b876a6e',
        '43e2c3ff',
        '43fe770b',
        '5b95b738',
        '395b9c7',
        '73e75e0d',
        '1b9a0d60',
        '23e8e86b',
        '43e8839f',
        '3b9048a8',
        '3b9b9024',
        '5b9b9b20',
        '4b9137ae',
        '73ea6a4d',
        '1391211d',
        'b957d0a',
        '3e4e9fb',
        '13949501',
    ];

    public function getSetlistIds()
    {
        return $this->setlistIds;
    }
}