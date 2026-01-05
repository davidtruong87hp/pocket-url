<?php

namespace Tests\Unit\Helpers;

use App\Helpers\ChangeDetector;
use App\Models\ShortenedUrl;
use Tests\TestCase;

class ChangeDetectorTest extends TestCase
{
    public function test_detects_url_change()
    {
        $link = new ShortenedUrl([
            'url' => 'https://old.com',
            'title' => 'Example',
        ]);

        $changes = ChangeDetector::detect($link, [
            'url' => 'https://new.com',
        ]);

        $this->assertCount(1, $changes);
        $this->assertArrayHasKey('url', $changes);
        $this->assertEquals('https://old.com', $changes['url']['old']);
        $this->assertEquals('https://new.com', $changes['url']['new']);
    }

    public function test_detects_multiple_field_changes()
    {
        $link = new ShortenedUrl([
            'url' => 'https://old.com.vn',
            'title' => 'Old Title',
        ]);

        $changes = ChangeDetector::detect($link, [
            'url' => 'https://new.com.vn',
            'title' => 'New Example Title',
        ]);

        $this->assertCount(2, $changes);
        $this->assertArrayHasKey('url', $changes);
        $this->assertArrayHasKey('title', $changes);
        $this->assertEquals('https://old.com.vn', $changes['url']['old']);
        $this->assertEquals('https://new.com.vn', $changes['url']['new']);
        $this->assertEquals('Old Title', $changes['title']['old']);
        $this->assertEquals('New Example Title', $changes['title']['new']);
    }

    public function test_detects_no_change_when_values_same()
    {
        $link = new ShortenedUrl([
            'url' => 'https://example.com',
            'title' => 'Example',
        ]);

        $changes = ChangeDetector::detect($link, [
            'url' => 'https://example.com',
            'title' => 'Example',
        ]);

        $this->assertEmpty($changes);
    }

    public function test_handles_null_values()
    {
        $link = new ShortenedUrl([
            'title' => null,
        ]);

        $changes = ChangeDetector::detect($link, [
            'title' => 'New Title',
        ]);

        $this->assertCount(1, $changes);
        $this->assertArrayHasKey('title', $changes);
        $this->assertEquals(null, $changes['title']['old']);
        $this->assertEquals('New Title', $changes['title']['new']);
        $this->assertTrue(ChangeDetector::areEqual(null, null));
    }

    public function test_are_equal_handles_boolean_and_string_values()
    {
        $this->assertTrue(ChangeDetector::areEqual(true, '1'));
        $this->assertTrue(ChangeDetector::areEqual(false, '0'));

        $this->assertTrue(ChangeDetector::areEqual(true, 'yes'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'no'));

        $this->assertFalse(ChangeDetector::areEqual(true, 'y'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'n'));

        $this->assertTrue(ChangeDetector::areEqual(true, 'true'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'false'));

        $this->assertTrue(ChangeDetector::areEqual(true, 'on'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'off'));

        $this->assertFalse(ChangeDetector::areEqual(true, 'yesss'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'nooo'));

        $this->assertFalse(ChangeDetector::areEqual(true, 'truee'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'falsee'));

        $this->assertFalse(ChangeDetector::areEqual(true, 'onnn'));
        $this->assertTrue(ChangeDetector::areEqual(false, 'offff'));
    }

    public function test_are_equal_handles_array_values()
    {
        $this->assertTrue(ChangeDetector::areEqual([1, 2, 3], [1, 2, 3]));
        $this->assertFalse(ChangeDetector::areEqual([1, 2, 3], [1, 2, 4]));
    }
}
