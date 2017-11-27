<?php

/**
 * This file is part of Laradiff -
 *
 * @see         https://github.com/FBnil/laradiff
 * @copyright   2017 Laradiff contributors
 * @license     http://www.gnu.org/licenses/mit.txt
 */

namespace Laradiff;

use PHPUnit\Framework\TestCase;

final class DiffTest extends TestCase
{
    public function testParse() {
        $this->assertTrue(true);
    }

    /**
     * Construct test
     *
     * @covers ::__construct
     *
     * @test
     */
    public function testTheConstruct()
    {
        $object = new Diff();

        $this->assertInstanceOf('Laradiff\\Diff', $object);
    }

    /**
     * Diff text test
     *
     * @covers compareText
     * @covers getInsertedCount
     * @covers getDeletedCount
     *
     * @test
     */
    public function testCompareText()
    {
        $diff = new Diff();

        $comp = $diff->compareText("a\nb", "A\nb");
        $this->assertEquals(
            $comp->toString(),
            "- a\n+ A\n  b\n"
        );

        $comp = $diff->compareText("same here\nbee hive", "same here\nBee hive");
        $this->assertEquals(
            $comp->toString(),
            "  same here\n- bee hive\n+ Bee hive\n"
        );

        $comp = $diff->compareText("same here\nbee hive", "same here\nBee hive");
        $this->assertEquals(
            $comp->toString(),
            "  same here\n- bee hive\n+ Bee hive\n"
        );
		$this->assertEquals( $comp->getInsertedCount(), 1);
        $this->assertEquals( $comp->getDeletedCount(), 1);

        $comp = $diff->compareText("same here\nbee hive", "same here\nbehave said the\nBee hive");
		$this->assertEquals( $comp->getInsertedCount(), 2);
        $this->assertEquals( $comp->getDeletedCount(), 1);
    }

    /**
     * Diff text test
     *
     * @covers compareFiles
     * @covers toHtml
     *
     * @test
     */
    public function testCompareFiles()
    {
        $diff = new Diff();
        $comp = $diff->compareFiles(__DIR__ . '/_files/compare_before.txt', __DIR__ . '/_files/compare_after.txt');
        $this->assertEquals(
            $comp->toHtml(),
            '<span>The quick brown</span><br/><del>fox jumed iver </del><br/><del>the lasy dogg.</del><br/><ins>fox jumped over </ins><br/><ins>the lazy dog.</ins><br/><span></span><br/>'
        );
	}

    /**
     * Diff text test
     *
     * @covers compareFiles
     * @covers toStruct
     * @covers getInsertedCount
     * @covers getDeletedCount
     *
     * @test
     */
    public function testToStruct()
    {
        $comp = Diff::compareFiles(__DIR__ . '/_files/compare_before.txt', __DIR__ . '/_files/compare_after.txt');
		$struct = $comp->toStruct();
        $this->assertEquals(
            $struct[0],
            ['The quick brown', 0]
        );
        $this->assertEquals(
            $struct[1],
            ['fox jumed iver ', 1]
        );
        $this->assertEquals(
            $struct[3],
            ['fox jumped over ', 2]
        );
        $this->assertEquals( $comp->getInsertedCount(), 2);
        $this->assertEquals( $comp->getDeletedCount(), 2);
	}

}
