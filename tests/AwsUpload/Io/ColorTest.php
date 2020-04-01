<?php

namespace AwsUpload\Tests\Io;

use function AwsUpload\Io\color;
use AwsUpload\Tests\BaseTestCase;

class ColorTest extends BaseTestCase
{

    public function test_color_allColors_true()
    {
        $redText = color("<r>red</r>");
        $this->assertEquals($redText, "\e[31mred\e[0m");

        $greenText = color("<g>green</g>");
        $this->assertEquals($greenText, "\e[32mgreen\e[0m");

        $yellowText = color("<y>yellow</y>");
        $this->assertEquals($yellowText, "\e[33myellow\e[0m");
    }
}
