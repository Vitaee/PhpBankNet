<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class RedisCacheTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the Redis cache connection.
     *
     * @return void
     */
    public function test_redis_connection()
    {
        $cacheKey = 'test_key';
        $cacheValue = 'test_value';

        // Store data in the Redis cache
        Redis::set($cacheKey, $cacheValue);

        // Retrieve data from the Redis cache
        $retrievedValue = Redis::get($cacheKey);

        // Assert that the retrieved value matches the original value
        $this->assertEquals($cacheValue, $retrievedValue);
    }
}
