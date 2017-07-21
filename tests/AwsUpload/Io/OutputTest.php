<?php

namespace AwsUpload\Tests\Settings;

use AwsUpload\Io\Output;
use AwsUpload\Tests\BaseTestCase;

class OutputTest extends BaseTestCase
{

    public function test_color_allColors_true()
    {
        $redText = Output::color("<r>red</r>");
        $this->assertEquals($redText, "\e[31mred\e[0m");

        $greenText = Output::color("<g>green</g>");
        $this->assertEquals($greenText, "\e[32mgreen\e[0m");

        $yellowText = Output::color("<y>yellow</y>");
        $this->assertEquals($yellowText, "\e[33myellow\e[0m");
    }
}
