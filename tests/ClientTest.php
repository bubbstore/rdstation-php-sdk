<?php

namespace bubbstore\RDStation;

use Mockery;
use bubbstore\RDStation\Contracts\LeadInterface;

class ClientTest extends TestCase
{
    /**
     * @var \bubbstore\RDStation\RD
     */
    protected $rd;

    public function setUp(): void
    {
        parent::setUp();

        $this->rd = new RD(
            'TOKEN',
            Mockery::mock(LeadInterface::class)
        );
    }

    public function testLeadService()
    {
        $this->assertInstanceOf(LeadInterface::class, $this->rd->lead());
    }
}