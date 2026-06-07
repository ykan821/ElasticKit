<?php

use PHPUnit\Framework\TestCase;
use ElasticKit\Index\Index;
use ElasticKit\Index\Results;

class ResultsTest extends TestCase
{
    public function testTotal()
    {
        $results = new Results([
            'hits' => [
                'total' => ['value' => 42],
                'hits' => [],
            ],
        ]);
        $this->assertEquals(42, $results->total());
    }

    public function testTotalDefaultsToZero()
    {
        $results = new Results([]);
        $this->assertEquals(0, $results->total());
    }

    public function testHits()
    {
        $hits = [
            ['_id' => '1', '_source' => ['title' => 'foo']],
            ['_id' => '2', '_source' => ['title' => 'bar']],
        ];
        $results = new Results([
            'hits' => ['total' => ['value' => 2], 'hits' => $hits],
        ]);
        $this->assertEquals($hits, $results->hits());
    }

    public function testHitsDefaultsToEmpty()
    {
        $results = new Results([]);
        $this->assertEquals([], $results->hits());
    }

    public function testDocs()
    {
        $results = new Results([
            'hits' => [
                'total' => ['value' => 2],
                'hits' => [
                    ['_id' => '1', '_source' => ['title' => 'foo']],
                    ['_id' => '2', '_source' => ['title' => 'bar']],
                ],
            ],
        ]);
        $this->assertEquals([['title' => 'foo'], ['title' => 'bar']], $results->docs());
    }

    public function testDocsDefaultsToEmpty()
    {
        $results = new Results([]);
        $this->assertEquals([], $results->docs());
    }

    public function testAggregations()
    {
        $aggs = ['price_avg' => ['value' => 100.5]];
        $results = new Results([
            'hits' => ['total' => ['value' => 0], 'hits' => []],
            'aggregations' => $aggs,
        ]);
        $this->assertEquals($aggs, $results->aggregations());
    }

    public function testAggregationsDefaultsToEmpty()
    {
        $results = new Results(['hits' => []]);
        $this->assertEquals([], $results->aggregations());
    }

    public function testScrollId()
    {
        $results = new Results([
            '_scroll_id' => 'abc123',
            'hits' => ['total' => ['value' => 0], 'hits' => []],
        ]);
        $this->assertEquals('abc123', $results->scrollId());
    }

    public function testScrollIdDefaultsToNull()
    {
        $results = new Results([]);
        $this->assertNull($results->scrollId());
    }

    public function testHasMore()
    {
        $results = new Results([
            'hits' => [
                'total' => ['value' => 1],
                'hits' => [['_source' => ['title' => 'foo']]],
            ],
        ]);
        $this->assertTrue($results->hasMore());
    }

    public function testHasMoreReturnsFalseWhenEmpty()
    {
        $results = new Results([
            'hits' => ['total' => ['value' => 0], 'hits' => []],
        ]);
        $this->assertFalse($results->hasMore());
    }

    public function testRaw()
    {
        $response = [
            'took' => 5,
            'hits' => ['total' => ['value' => 1], 'hits' => []],
        ];
        $results = new Results($response);
        $this->assertEquals($response, $results->raw());
    }

    public function testPaginateSetsMetadata()
    {
        $results = new Results([
            'hits' => ['total' => ['value' => 50], 'hits' => []],
        ]);
        $results->paginate(3, 10);

        $this->assertEquals(3, $results->page());
        $this->assertEquals(10, $results->perPage());
        $this->assertEquals(5, $results->lastPage());
    }

    public function testLastPageMinimumIsOne()
    {
        $results = new Results([
            'hits' => ['total' => ['value' => 0], 'hits' => []],
        ]);
        $results->paginate(1, 15);

        $this->assertEquals(1, $results->lastPage());
    }

    public function testItemsReturnsDocs()
    {
        $results = new Results([
            'hits' => [
                'total' => ['value' => 2],
                'hits' => [
                    ['_id' => '1', '_source' => ['title' => 'foo']],
                    ['_id' => '2', '_source' => ['title' => 'bar']],
                ],
            ],
        ]);
        $this->assertEquals($results->docs(), $results->items());
    }

    public function testIsEmpty()
    {
        $results = new Results([
            'hits' => ['total' => ['value' => 0], 'hits' => []],
        ]);
        $this->assertTrue($results->isEmpty());

        $results = new Results([
            'hits' => [
                'total' => ['value' => 1],
                'hits' => [['_source' => ['title' => 'foo']]],
            ],
        ]);
        $this->assertFalse($results->isEmpty());
    }

    public function testToPaginatorCallsResolver()
    {
        Index::setPaginatorResolver(function (Results $results) {
            return ['total' => $results->total(), 'page' => $results->page()];
        });

        $results = new Results([
            'hits' => ['total' => ['value' => 50], 'hits' => []],
        ]);
        $results->paginate(2, 10);

        $paginator = $results->toPaginator();
        $this->assertEquals(['total' => 50, 'page' => 2], $paginator);

        // Clean up
        $ref = new ReflectionProperty(Index::class, 'paginatorResolver');
        $ref->setAccessible(true);
        $ref->setValue(null, null);
    }

    public function testToPaginatorThrowsWithoutResolver()
    {
        $results = new Results(['hits' => []]);
        $this->expectException(\RuntimeException::class);
        $results->toPaginator();
    }

    public function testTook()
    {
        $results = new Results([
            'took' => 5,
            'hits' => ['total' => ['value' => 1], 'hits' => []],
        ]);
        $this->assertEquals(5, $results->took());
    }

    public function testTookDefaultsToZero()
    {
        $results = new Results([]);
        $this->assertEquals(0, $results->took());
    }

    public function testTimedOut()
    {
        $results = new Results([
            'timed_out' => true,
            'hits' => ['total' => ['value' => 0], 'hits' => []],
        ]);
        $this->assertTrue($results->timedOut());
    }

    public function testTimedOutDefaultsToFalse()
    {
        $results = new Results([]);
        $this->assertFalse($results->timedOut());
    }
}
