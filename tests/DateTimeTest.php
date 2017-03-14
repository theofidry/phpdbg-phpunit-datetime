<?php declare(strict_types = 1);

namespace App;

use DateTimeImmutable;
use phpmock\phpunit\PHPMock;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \App\DateUtil
 */
class DateTimeTest extends TestCase
{
    use PHPMock;

    /**
     * @runInSeparateProcess
     */
    public function testCreateDateTimeWithoutMilliseconds()
    {
        $time = $this->getFunctionMock(
            (new ReflectionClass(DateUtil::class))->getNamespaceName(),
            'microtime'
        );
        $time->expects($this->once())->willReturnCallback(function ($getAsFloat = null) {
            return true === $getAsFloat ? 1417011228 : '0.38200000 1480947566';
        });

        $expected = DateTimeImmutable::createFromFormat('U.u', '1417011228.0000');
        $actual = DateUtil::nowWithMilliseconds();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(date_default_timezone_get(), $actual->getTimezone()->getName());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateDateTimeWithMilliseconds()
    {
        $time = $this->getFunctionMock(
            (new ReflectionClass(DateUtil::class))->getNamespaceName(),
            'microtime'
        );
        $time->expects($this->once())->willReturn(1480355498.988);

        $expected = DateTimeImmutable::createFromFormat('U.u', '1480355498.9880');
        $actual = DateUtil::nowWithMilliseconds();

        $this->assertEquals($expected, $actual);
        $this->assertEquals(date_default_timezone_get(), $actual->getTimezone()->getName());
    }

    public function testMakeImmutable()
    {
        $time = new DateTimeImmutable('now');
        $immutable = DateUtil::makeImmutable($time);

        $this->assertInstanceOf(DateTimeImmutable::class, $immutable);
        $this->assertSame($time->format('U.u'), $immutable->format('U.u'));
        $this->assertSame($immutable, DateUtil::makeImmutable($immutable));
    }
}