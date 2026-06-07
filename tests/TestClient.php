<?php

use Elastic\Elasticsearch\ClientInterface;
use Elastic\Transport\Transport;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Test double for Elastic\Elasticsearch\ClientInterface.
 *
 * The 8.x ClientInterface only exposes transport-level methods.
 * API methods (search, indices, bulk, etc.) are generated via traits on the concrete Client class.
 * Since Client is final, we need this stub for PHPUnit mocks.
 */
class TestClient implements ClientInterface
{
    public function getTransport(): Transport
    {
        throw new \LogicException('Not implemented');
    }

    public function getLogger(): LoggerInterface
    {
        throw new \LogicException('Not implemented');
    }

    public function setAsync(bool $async): self
    {
        return $this;
    }

    public function getAsync(): bool
    {
        return false;
    }

    public function setElasticMetaHeader(bool $active): self
    {
        return $this;
    }

    public function getElasticMetaHeader(): bool
    {
        return true;
    }

    public function setResponseException(bool $active): self
    {
        return $this;
    }

    public function getResponseException(): bool
    {
        return true;
    }

    public function sendRequest(RequestInterface $request)
    {
        throw new \LogicException('Not implemented');
    }

    // --- API methods used by ElasticKit Index layer ---

    public function search(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function count(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function scroll(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function clearScroll(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function get(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function getSource(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function exists(array $params = [])
    {
        return new BoolResponse(false);
    }

    public function index(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function update(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function delete(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function bulk(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function indices(): TestIndices
    {
        return new TestIndices();
    }
}

class TestIndices
{
    public function create(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function delete(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function exists(array $params = [])
    {
        return new BoolResponse(false);
    }

    public function existsAlias(array $params = [])
    {
        return new BoolResponse(false);
    }

    public function get(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function getAlias(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function getMapping(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function getSettings(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function putAlias(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function deleteAlias(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function updateAliases(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function putMapping(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function putSettings(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function refresh(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function forcemerge(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function close(array $params = [])
    {
        return new ArrayResponse([]);
    }

    public function open(array $params = [])
    {
        return new ArrayResponse([]);
    }
}

/**
 * Minimal response object that mimics ES8 response's asArray() method.
 */
class ArrayResponse
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function asArray(): array
    {
        return $this->data;
    }
}

/**
 * Minimal response object that mimics ES8 exists-family response's asBool() method.
 */
class BoolResponse
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function asBool(): bool
    {
        return $this->value;
    }
}
