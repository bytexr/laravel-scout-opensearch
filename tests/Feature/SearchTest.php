<?php

use ByteXR\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use ByteXR\LaravelScoutOpenSearch\Services\OpenSearchClient;

beforeEach(function () {
    $this->index = "opensearch_engine_test";

    $this->openSearch = new OpenSearchClient(
        (new \OpenSearch\ClientBuilder())
            ->setHosts(["https://127.0.0.1:9200"])
            ->setSSLVerification(false)
            ->setBasicAuthentication("admin", "admin")
            ->build()
    );
    $this->engine = new OpenSearchEngine($this->openSearch);

    $data = collect(json_decode(file_get_contents("tests/data/documents.json"), true));

    $this->openSearch->bulkUpdate($this->index, $data);
});

it('can search full keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "Greenwood paper Factory"));

    expect($count)->toBe(1);
});

it('can search partial (prefix) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "greenwood"));

    expect($count)->toBe(1);
});

it('can search partial (middle) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "paper"));

    expect($count)->toBe(2);
});

it('can search email keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "blackwood+email@example.com"));

    expect($count)->toBe(1);
});

it('can search partial email (prefix) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "greenwood+email"));

    expect($count)->toBe(1);
});

it('can search partial email (suffix) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "@example.com"));

    expect($count)->toBe(2);
});

it('can search postcode keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "AX1 BT2"));

    expect($count)->toBe(1);
});

it('can search partial postcode (prefix) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "AX1"));

    expect($count)->toBe(1);
});

it('can search partial postcode (suffix) keyword', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "XC3"));

    expect($count)->toBe(1);
});

it('can search keyword with "of" without pulling all results with "of"', function (){
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "Isle of Green"));

    expect($count)->toBe(2);
});

it('can search keyword with "of" without pulling all results with "of" partial (prefix)', function (){
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "Isle of"));

    expect($count)->toBe(3);
});

it('can search by house number', function () {
    $count = $this->engine->getTotalCount($this->openSearch->search($this->index, "456"));

    expect($count)->toBe(1);
});