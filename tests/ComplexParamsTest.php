<?php

use Tests\DslTestCase;
use ElasticKit\DSL\Query;

class ComplexParamsTest extends DslTestCase
{
    // === post_filter ===

    public function testPostFilterWithClosure()
    {
        $query = new Query();
        $query->matchAll();
        $query->postFilter(function ($q) {
            $q->term('status', 'published');
        });

        $expected = '{"query":{"match_all":{}},"post_filter":{"term":{"status":"published"}}}';
        $this->assertQuery($expected, $query);
    }

    public function testPostFilterWithArray()
    {
        $query = new Query();
        $query->matchAll();
        $query->postFilter(['term' => ['status' => 'published']]);

        $expected = '{"query":{"match_all":{}},"post_filter":{"term":{"status":"published"}}}';
        $this->assertQuery($expected, $query);
    }

    // === collapse ===

    public function testCollapseSimple()
    {
        $query = new Query();
        $query->matchAll();
        $query->collapse(function ($c) {
            $c->field('user');
        });

        $expected = '{"query":{"match_all":{}},"collapse":{"field":"user"}}';
        $this->assertQuery($expected, $query);
    }

    public function testCollapseWithInnerHits()
    {
        $query = new Query();
        $query->matchAll();
        $query->collapse(function ($c) {
            $c->field('user')
              ->innerHits('last_tweets', ['size' => 5]);
        });

        $expected = '{"query":{"match_all":{}},"collapse":{"field":"user","inner_hits":{"name":"last_tweets","size":5}}}';
        $this->assertQuery($expected, $query);
    }

    // === rescore ===

    public function testRescoreWithClosure()
    {
        $query = new Query();
        $query->matchAll();
        $query->rescore(function ($r) {
            $r->windowSize(100)
              ->query(function ($q) {
                  $q->match('message', 'elasticsearch');
              })
              ->rescoreQueryWeight(1.5)
              ->queryWeight(0.8)
              ->scoreMode('total');
        });

        $expected = '{"query":{"match_all":{}},"rescore":{"window_size":100,"query":{"rescore_query":{"match":{"message":"elasticsearch"}},"rescore_query_weight":1.5,"query_weight":0.8,"score_mode":"total"}}}';
        $this->assertQuery($expected, $query);
    }

    // === highlight ===

    public function testHighlightWithClosure()
    {
        $query = new Query();
        $query->match('title', 'elasticsearch');
        $query->highlight(function ($h) {
            $h->preTags(['<em>'])
              ->postTags(['</em>'])
              ->field('title')
              ->field('content', ['fragment_size' => 150, 'number_of_fragments' => 3]);
        });

        $expected = '{"query":{"match":{"title":"elasticsearch"}},"highlight":{"pre_tags":["<em>"],"post_tags":["</em>"],"fields":{"title":{},"content":{"fragment_size":150,"number_of_fragments":3}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testHighlightWithHighlightQuery()
    {
        $query = new Query();
        $query->match('content', 'test');
        $query->highlight(function ($h) {
            $h->field('content')
              ->highlightQuery(function ($q) {
                  $q->match('content', 'test');
              });
        });

        $expected = '{"query":{"match":{"content":"test"}},"highlight":{"fields":{"content":{}},"highlight_query":{"match":{"content":"test"}}}}';
        $this->assertQuery($expected, $query);
    }

    // === suggest ===

    public function testSuggestTerm()
    {
        $query = new Query();
        $query->matchAll();
        $query->suggest(function ($s) {
            $s->term('my-suggestion', 'title', 'surprize');
        });

        $expected = '{"query":{"match_all":{}},"suggest":{"my-suggestion":{"text":"surprize","term":{"field":"title"}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testSuggestCompletion()
    {
        $query = new Query();
        $query->matchAll();
        $query->suggest(function ($s) {
            $s->completion('song-suggest', 'suggest', 'nir');
        });

        $expected = '{"query":{"match_all":{}},"suggest":{"song-suggest":{"prefix":"nir","completion":{"field":"suggest"}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testSuggestPhrase()
    {
        $query = new Query();
        $query->matchAll();
        $query->suggest(function ($s) {
            $s->phrase('title-phrase', 'title', 'noble prize');
        });

        $expected = '{"query":{"match_all":{}},"suggest":{"title-phrase":{"text":"noble prize","phrase":{"field":"title"}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testSuggestMultiple()
    {
        $query = new Query();
        $query->matchAll();
        $query->suggest(function ($s) {
            $s->term('suggestion-1', 'title', 'surprize')
              ->completion('song-suggest', 'suggest', 'nir');
        });

        $expected = '{"query":{"match_all":{}},"suggest":{"suggestion-1":{"text":"surprize","term":{"field":"title"}},"song-suggest":{"prefix":"nir","completion":{"field":"suggest"}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testHighlightWithStringShorthand()
    {
        $query = new Query();
        $query->match('description', 'phone');
        $query->highlight('description');

        $expected = '{"query":{"match":{"description":"phone"}},"highlight":{"fields":{"description":{}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testHighlightWithMultipleFields()
    {
        $query = new Query();
        $query->matchAll();
        $query->highlight('title');
        $query->highlight(function ($h) {
            $h->field('description');
        });

        // Both fields are merged
        $expected = '{"query":{"match_all":{}},"highlight":{"fields":{"title":{},"description":{}}}}';
        $this->assertQuery($expected, $query);
    }

    // === combined ===

    public function testComplexParamsDoNotLeakIntoQuery()
    {
        $query = new Query();
        $query->match('title', 'test');
        $query->size(10);
        $query->postFilter(function ($q) {
            $q->term('status', 'published');
        });
        $query->highlight(function ($h) {
            $h->field('title');
        });

        $expected = '{"query":{"match":{"title":"test"}},"size":10,"post_filter":{"term":{"status":"published"}},"highlight":{"fields":{"title":{}}}}';
        $this->assertQuery($expected, $query);
    }

    // === knn ===

    public function testKnnWithArray()
    {
        $query = new Query();
        $query->matchAll();
        $query->knn([
            'field' => 'image-vector',
            'query_vector' => [-5, 9, -12],
            'k' => 10,
            'num_candidates' => 100,
        ]);

        $expected = '{"query":{"match_all":{}},"knn":{"field":"image-vector","query_vector":[-5,9,-12],"k":10,"num_candidates":100}}';
        $this->assertQuery($expected, $query);
    }

    public function testKnnWithClosure()
    {
        $query = new Query();
        $query->matchAll();
        $query->knn(function ($k) {
            $k->field('image-vector')
              ->queryVector([-5, 9, -12])
              ->k(10)
              ->numCandidates(100);
        });

        $expected = '{"query":{"match_all":{}},"knn":{"field":"image-vector","query_vector":[-5,9,-12],"k":10,"num_candidates":100}}';
        $this->assertQuery($expected, $query);
    }

    public function testKnnWithShorthand()
    {
        $query = new Query();
        $query->knn('image-vector', [-5, 9, -12]);

        $expected = '{"knn":{"field":"image-vector","query_vector":[-5,9,-12]}}';
        $this->assertQuery($expected, $query);
    }

    public function testKnnWithFilter()
    {
        $query = new Query();
        $query->knn(function ($k) {
            $k->field('image-vector')
              ->queryVector([54, 10, -2])
              ->k(5)
              ->numCandidates(50)
              ->filter(function ($q) {
                  $q->term('file-type', 'png');
              });
        });

        $expected = '{"knn":{"field":"image-vector","query_vector":[54,10,-2],"k":5,"num_candidates":50,"filter":{"term":{"file-type":"png"}}}}';
        $this->assertQuery($expected, $query);
    }

    public function testKnnMultipleClauses()
    {
        $query = new Query();
        $query->match('title', 'mountain lake');
        $query->knn([
            'field' => 'image-vector',
            'query_vector' => [54, 10, -2],
            'k' => 5,
            'num_candidates' => 50,
            'boost' => 0.1,
        ]);
        $query->knn([
            'field' => 'title-vector',
            'query_vector' => [1, 20, -52, 23, 10],
            'k' => 10,
            'num_candidates' => 10,
            'boost' => 0.5,
        ]);

        $expectedJson = <<<JSON
{
  "query": { "match": { "title": "mountain lake" } },
  "knn": [
    { "field": "image-vector", "query_vector": [54, 10, -2], "k": 5, "num_candidates": 50, "boost": 0.1 },
    { "field": "title-vector", "query_vector": [1, 20, -52, 23, 10], "k": 10, "num_candidates": 10, "boost": 0.5 }
  ]
}
JSON;
        $this->assertQuery($expectedJson, $query);
    }

    public function testKnnCombinedWithQuery()
    {
        $query = new Query();
        $query->match('title', function ($m) {
            $m->query('mountain lake')->boost(0.9);
        });
        $query->knn(function ($k) {
            $k->field('image-vector')
              ->queryVector([54, 10, -2])
              ->k(5)
              ->numCandidates(50)
              ->boost(0.1);
        });
        $query->size(10);

        $expectedJson = <<<JSON
{
  "query": { "match": { "title": { "query": "mountain lake", "boost": 0.9 } } },
  "knn": { "field": "image-vector", "query_vector": [54, 10, -2], "k": 5, "num_candidates": 50, "boost": 0.1 },
  "size": 10
}
JSON;
        $this->assertQuery($expectedJson, $query);
    }
}
