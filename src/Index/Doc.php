<?php

namespace ElasticKit\Index;

/**
 * Lightweight reference to a single document for CRUD operations.
 */
class Doc
{
    /**
     * @var Index
     */
    private $index;

    /**
     * @var string|int
     */
    private $id;

    /**
     * @var int
     */
    private $retryOnConflict = 0;

    /**
     * @var string|null
     */
    private $refresh;

    /**
     * @param Index $index
     * @param string|int $id
     */
    public function __construct(Index $index, $id)
    {
        $this->index = $index;
        $this->id = $id;
    }

    /**
     * Return the document ID.
     *
     * @return string|int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Set retry_on_conflict for the next write operation.
     *
     * @param int $count
     * @return $this
     */
    public function retryOnConflict($count)
    {
        $this->retryOnConflict = $count;

        return $this;
    }

    /**
     * Set refresh for the next write operation (true/false/wait_for).
     *
     * @param string $value
     * @return $this
     */
    public function refresh($value)
    {
        $this->refresh = $value;

        return $this;
    }

    /**
     * Get the full document (with _source, _id, _version, etc).
     *
     * @return array<string, mixed>
     */
    public function get()
    {
        return $this->index->getClient()->get([
            'index' => $this->index->name(),
            'id' => $this->id,
        ])->asArray();
    }

    /**
     * Get the document _source only.
     *
     * @return array<string, mixed>
     */
    public function source()
    {
        return $this->index->getClient()->getSource([
            'index' => $this->index->name(),
            'id' => $this->id,
        ])->asArray();
    }

    /**
     * Check if the document exists.
     *
     * @return bool
     */
    public function exists()
    {
        return $this->index->getClient()->exists([
            'index' => $this->index->name(),
            'id' => $this->id,
        ])->asBool();
    }

    /**
     * Update the document (partial update with optional upsert).
     * Chain retryOnConflict() and refresh() for common options.
     * For other ES options (routing, detect_noop, etc), use getClient()->update() directly.
     *
     * @param array<string, mixed> $data
     * @param bool $upsert
     * @return array<string, mixed>
     */
    public function update($data, $upsert = false)
    {
        $params = [
            'index' => $this->index->name(),
            'id' => $this->id,
            'body' => [
                'doc' => $data,
                'doc_as_upsert' => $upsert,
            ],
        ];

        if ($this->retryOnConflict > 0) {
            $params['retry_on_conflict'] = $this->retryOnConflict;
        }

        if ($this->refresh !== null) {
            $params['refresh'] = $this->refresh;
        }

        $this->resetOptions();

        return $this->index->getClient()->update($params)->asArray();
    }

    /**
     * Index (create or overwrite) the document.
     *
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    public function index($document)
    {
        $params = [
            'index' => $this->index->name(),
            'id' => $this->id,
            'body' => $document,
        ];

        if ($this->refresh !== null) {
            $params['refresh'] = $this->refresh;
        }

        $this->resetOptions();

        return $this->index->getClient()->index($params)->asArray();
    }

    /**
     * Alias for index(). Create or overwrite the document.
     *
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    public function save($document)
    {
        return $this->index($document);
    }

    /**
     * Create the document (fail if already exists).
     *
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    public function create($document)
    {
        $params = [
            'index' => $this->index->name(),
            'id' => $this->id,
            'body' => $document,
            'op_type' => 'create',
        ];

        if ($this->refresh !== null) {
            $params['refresh'] = $this->refresh;
        }

        $this->resetOptions();

        return $this->index->getClient()->index($params)->asArray();
    }

    /**
     * Delete the document.
     *
     * @return array<string, mixed>
     */
    public function delete()
    {
        $params = [
            'index' => $this->index->name(),
            'id' => $this->id,
        ];

        if ($this->refresh !== null) {
            $params['refresh'] = $this->refresh;
        }

        $this->resetOptions();

        return $this->index->getClient()->delete($params)->asArray();
    }

    /**
     * Reset pending options after a write operation.
     */
    private function resetOptions(): void
    {
        $this->retryOnConflict = 0;
        $this->refresh = null;
    }
}
