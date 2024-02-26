<?php

use Illuminate\Support\Facades\Http;
use MoMasoud\Singlewire\Singlewire;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class SinglewireTest extends OrchestraTestCase
{
    public function testGetDeices()
    {
        $data = file_get_contents(__DIR__ . '/textures/devices.json');
        $data = json_decode($data, true);

        Http::fake([
            'https://api.icmobile.singlewire.com/*' => Http::response([
                'data' => $data,
                'next' => null,
            ]),
        ]);

        $singlewire = Singlewire::make('https://api.icmobile.singlewire.com/api/v1', 'fake-token');
        $devices = $singlewire->devices();

        $this->assertNotEmpty($devices);

        $this->assertEquals($data, $devices->toArray());
    }
}
