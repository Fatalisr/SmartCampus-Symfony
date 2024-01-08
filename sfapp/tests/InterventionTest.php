<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\SA;
use App\Entity\Intervention;

class InterventionTest extends TestCase
{
    public function testInterventionConstruct()
    {
        $interv = new Intervention();
        $sa = new SA();
        date_default_timezone_set('UTC');
        $interv->setStartingDate(date_create(date("m.d.y")));
        $interv->setEndingDate(date_create(date("m.d.y")));
        $interv->setSa($sa);
        $interv->setMessage("Coucou");
        $interv->setReport("Salut");
        $interv->setType("INSTALLATION");

        $this->assertEquals(date_create(date("m.d.y")), $interv->getStartingDate());
        $this->assertEquals(date_create(date("m.d.y")), $interv->getEndingDate());
        $this->assertEquals($sa, $interv->getSa());
        $this->assertEquals("Coucou", $interv->getMessage());
        $this->assertEquals("Salut", $interv->getReport());
        $this->assertEquals("INSTALLATION", $interv->getType());
    }
}