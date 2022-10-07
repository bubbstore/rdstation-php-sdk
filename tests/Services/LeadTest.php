<?php

namespace bubbstore\RDStation\Services;

use bubbstore\RDStation\TestCase;
use bubbstore\RDStation\RD;
use bubbstore\RDStation\Exceptions\RDException;

class LeadTest extends TestCase
{

    /**
     * @var \bubbstore\RDStation\RD
     */
    protected $rd;

    public function setUp(): void
    {
        parent::setUp();

        $this->rd = new RD(
            'TOKEN'
        );
    }

    public function testIfEmailIsEmpty()
    {

        $this->expectException(RDException::class);

        $lead = $this->rd->lead()->create([
            'foo' => 'bar',
        ]);

    }

    public function testApiResponse()
    {
        $body = __DIR__.'/../ResponseSamples/LeadCreated.json';
        $http = $this->mockHttpClient($body);
        
        $lead = new Lead($http, $this->rd);
        
        $this->assertEquals([
            'result' => 'success',
            'lead' => [
                'email' => 'lucas@bubb.com.br'
            ]
        ], $lead->create(['email' => 'lucas@bubb.com.br']));

    }

}