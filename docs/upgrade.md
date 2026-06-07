# 升级指南

## v7.x → v8.x

### 环境要求

| | v7.x | v8.x |
|---|---|---|
| PHP | 7.2+ | **8.1+** |
| Elasticsearch | 7.x | **8.x** |
| elasticsearch-php | ^7.0 | **^8.0** |

```bash
composer require ykan/elastickit:^8
```

### Client 配置

命名空间从 `Elasticsearch\` 变更为 `Elastic\Elasticsearch\`：

```php
// v7.x
$client = \Elasticsearch\ClientBuilder::create()
    ->setHosts(['http://localhost:9200'])
    ->build();

// v8.x
$client = \Elastic\Elasticsearch\ClientBuilder::create()
    ->setHosts(['https://localhost:9200'])
    ->setBasicAuthentication('elastic', 'password')
    ->build();
```

> ES 8.x 默认启用安全认证（TLS + 认证）。需使用 `https://` 并提供凭据。

### 不兼容变更

**1. 自定义 Client**

`Client` 现在是 `final` 类——不可继承。使用 `ClientBuilder` 配置代替：

```php
// v7.x — 继承 Client 自定义
class MyClient extends \Elasticsearch\Client { ... }

// v8.x — 通过 ClientBuilder 配置
$client = \Elastic\Elasticsearch\ClientBuilder::create()
    ->setHosts([...])
    ->setLogger($logger)
    ->setSSLVerification(false)
    ->build();
```

**2. 响应对象**

elasticsearch-php 8.x 返回 Response 对象而非数组。ElasticKit 内部已处理——Index 层所有方法（search、get、bulk 等）仍返回数组。**用户代码无需修改。**

如果你在 ElasticKit 之外直接使用 elasticsearch-php 客户端：

```php
// v7.x — 返回数组
$response = $client->search($params);
$total = $response['hits']['total']['value'];

// v8.x — 返回 Response 对象，使用 asArray()
$response = $client->search($params);
$total = $response['hits']['total']['value'];      // ArrayAccess 仍可读取
$total = $response->asArray()['hits']['total']['value']; // 显式转换
```

**3. GeoPolygon 查询**

`geo_polygon` 查询在 ES 8.x 中已移除。使用 `geo_shape` 代替：

```php
// v7.x
$query->geoPolygon('location', $points);

// v8.x
$query->geoShape('location', function ($s) {
    $s->shape($polygon, 'envelope');
});
```

### 无需修改

以下在 v7.x 和 v8.x 中完全一致：

- DSL 查询构建（match、term、bool、range 等）
- 聚合构建
- Index 层所有 API（Search、Doc、Bulk、Manager、Rebuild）
- 事件系统
- `Query::make()`、`Agg::make()`、`Query::when()`
- Index 子类定义（$name、$mappings、$settings）
