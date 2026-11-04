<?php


use Tests\DslTestCase;
use ElasticKit\DSL\Query;
use ElasticKit\DSL\Queries\Shape\Shape;

class ShapeQueriesTest extends DslTestCase
{
    public function testShape()
    {
        $exampleJson = <<<'JSON'
{
  "query": {
    "shape": {
      "geometry": {
        "shape": {
          "type": "envelope",
          "coordinates": [ [ 1355.0, 5355.0 ], [ 1400.0, 5200.0 ] ]
        },
        "relation": "within"
      }
    }
  }
}
JSON;
        $query = new Query();
        $query->shape('geometry', function (Shape $shape) {
            $shape->shape(['type' => 'envelope', 'coordinates' => [[1355.0, 5355.0], [1400.0, 5200.0]]]);
            $shape->relation('within');
        });
        $this->assertQuery($exampleJson, $query);
    }

    public function testShapeSiblingParamsSerializeBesideField()
    {
        $exampleJson = <<<'JSON'
{
  "query": {
    "shape": {
      "geometry": {
        "shape": {
          "type": "envelope",
          "coordinates": [ [ 1355.0, 5355.0 ], [ 1400.0, 5200.0 ] ]
        },
        "relation": "within"
      },
      "boost": 2.0,
      "_name": "area"
    }
  }
}
JSON;
        $query = new Query();
        $query->shape('geometry', function (Shape $shape) {
            $shape->shape(['type' => 'envelope', 'coordinates' => [[1355.0, 5355.0], [1400.0, 5200.0]]])
                ->relation('within')
                ->boost(2.0)
                ->_name('area');
        });
        $this->assertQuery($exampleJson, $query);
    }
}
