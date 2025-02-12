<?php

namespace HyperfTest\HttpServer;


use Hyperf\HttpServer\Annotation\RequestMapping;
use PHPUnit\Framework\TestCase;

class MappingAnnotationTest extends TestCase
{

    public function testRequestMapping()
    {
        $mapping = new RequestMapping([]);
        // Assert default methods
        $this->assertSame(['GET', 'POST'], $mapping->methods);
        $this->assertNull($mapping->path);

        // Normal case
        $mapping = new RequestMapping([
            'methods' => 'get,post,put',
            'path' => $path = '/foo',
        ]);
        $this->assertSame(['GET', 'POST', 'PUT'], $mapping->methods);
        $this->assertSame($path, $mapping->path);

        // The methods have space
        $mapping = new RequestMapping([
            'methods' => 'get, post,  put',
            'path' => $path,
        ]);
        $this->assertSame(['GET', 'POST', 'PUT'], $mapping->methods);
        $this->assertSame($path, $mapping->path);
    }

    public function testRequestMappingWithArrayMethods()
    {
        $mapping = new RequestMapping([
            'methods' => [
                'GET', 'POST ', 'put'
            ],
            'path' => $path = '/foo',
        ]);
        $this->assertSame(['GET', 'POST', 'PUT'], $mapping->methods);
        $this->assertSame($path, $mapping->path);
    }

    public function testRequestMappingBindMainProperty()
    {
        $mapping = new RequestMapping(['value' => '/foo']);
        $this->assertSame(['GET', 'POST'], $mapping->methods);
        $this->assertSame('/foo', $mapping->path);
    }
}