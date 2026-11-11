<?php

namespace Tests\Feature;

use App\Services\DocumentNumberer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\CreatesVenue;
use Tests\TestCase;

class DocumentNumbererTest extends TestCase
{
    use RefreshDatabase, CreatesVenue;

    public function test_numbers_are_sequential_per_venue_and_type(): void
    {
        $venue = $this->makeVenue();
        $numberer = app(DocumentNumberer::class);
        $year = now()->year;

        $this->assertSame("AVO-{$year}-000001", $numberer->next($venue, 'AVO'));
        $this->assertSame("AVO-{$year}-000002", $numberer->next($venue, 'AVO'));
        $this->assertSame("FAC-{$year}-000001", $numberer->next($venue, 'FAC'));
    }
}