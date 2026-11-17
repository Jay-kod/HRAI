<?php

namespace Tests\Feature;

use Tests\TestCase;

class WelcomeTest extends TestCase
{
    public function test_healthrisk_ai_home_page_is_displayed(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('HealthRisk AI')
            ->assertSee('Create account')
            ->assertSee('educational screening prototype');
    }
}
