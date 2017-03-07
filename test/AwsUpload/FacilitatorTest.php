<?php

use AwsUpload\Facilitator;

require_once __DIR__ . '/BaseTestCase.php';


class FacilitatorTest extends BaseTestCase
{

    public function testColor()
    {
        $redText = Facilitator::color("<r>red</r>");
        $this->assertEquals($redText, "\e[31mred\e[0m");

        $greenText = Facilitator::color("<g>green</g>");
        $this->assertEquals($greenText, "\e[32mgreen\e[0m");

        $yellowText = Facilitator::color("<y>yellow</y>");
        $this->assertEquals($yellowText, "\e[33myellow\e[0m");
    }
}
