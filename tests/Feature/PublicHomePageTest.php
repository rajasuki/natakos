<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicHomePageTest extends TestCase
{
    public function test_homepage_shows_location_section_fallback(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Lokasi & sekitar kos');
        $response->assertSeeText('Map embed belum diatur');
        $response->assertSeeText('Belum ada daftar tempat sekitar yang ditampilkan.');
    }
}
