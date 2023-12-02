<?php

declare(strict_types=1);

namespace Whateverthing\Qed;

use PHPUnit\Framework\TestCase;

class QedTest extends TestCase
{
    public function testAdd_WithObject(): void
    {
        $numberOne = new Qed('123');
        $numberTwo = new Qed('456');

        $this->assertEquals('579', $numberOne->add($numberTwo)->value);
    }

    public function testAdd_WithString(): void
    {
        $numberOne = new Qed('123');

        $this->assertEquals('579', $numberOne->add('456')->value);
    }

    public function testSub_WithObject(): void
    {
        $numberOne = new Qed('123');
        $numberTwo = new Qed('456');

        $this->assertEquals('-333', $numberOne->sub($numberTwo)->value);
    }

    public function testSub_WithString(): void
    {
        $numberOne = new Qed('123');

        $this->assertEquals('-333', $numberOne->sub('456')->value);
    }

    public function testMul_WithObject(): void
    {
        $numberOne = new Qed('123');
        $numberTwo = new Qed('456');

        $this->assertEquals('56088', $numberOne->mul($numberTwo)->value);
    }

    public function testMul_WithString(): void
    {
        $numberOne = new Qed('123');

        $this->assertEquals('56088', $numberOne->mul('456')->value);
    }

    public function testDiv_WithObject_AndScale(): void
    {
        $numberOne = new Qed('123', 5);
        $numberTwo = new Qed('456');

        $this->assertEquals('0.26973', $numberOne->div($numberTwo)->value);
    }

    public function testDiv_WithString_AndScale(): void
    {
        $numberOne = new Qed('123', 5);

        $this->assertEquals('0.26973', $numberOne->div('456')->value);
    }

    public function testMod_WithObject(): void
    {
        $numberOne = new Qed('458');
        $numberTwo = new Qed('456');

        $this->assertEquals('2', $numberOne->mod($numberTwo)->value);
    }

    public function testMod_WithString(): void
    {
        $numberOne = new Qed('458');

        $this->assertEquals('2', $numberOne->mod('456')->value);
    }

    public function testPow_WithObject(): void
    {
        $numberOne = new Qed('9');
        $numberTwo = new Qed('2');

        $this->assertEquals('81', $numberOne->pow($numberTwo)->value);
    }

    public function testPow_WithString(): void
    {
        $numberOne = new Qed('9');

        $this->assertEquals('81', $numberOne->pow('2')->value);
    }

    public function testPowMod_WithObject(): void
    {
        $numberOne = new Qed('9');
        $numberTwo = new Qed('2');
        $numberThree = new Qed('6');

        $this->assertEquals('3', $numberOne->powmod($numberTwo, $numberThree)->value);
    }

    public function testPowMod_WithString(): void
    {
        $numberOne = new Qed('9');

        $this->assertEquals('3', $numberOne->powmod('2', '6')->value);
    }

    public function testSqrt_WithObject(): void
    {
        $numberOne = new Qed('81');

        $this->assertEquals('9', $numberOne->sqrt()->value);
    }

    public function testSqrt_WithString(): void
    {
        $numberOne = new Qed('9');

        $this->assertEquals('3', $numberOne->sqrt()->value);
    }

    public function testComp_WithObject_AndScale(): void
    {
        $numberOne = new Qed('123', 5);
        $numberTwo = new Qed('456');

        $this->assertEquals('-1', $numberOne->comp($numberTwo)->value);
    }

    public function testComp_WithString_AndScale(): void
    {
        $numberOne = new Qed('123', 5);

        $this->assertEquals('-1', $numberOne->comp('456')->value);
    }

    public function testScale(): void
    {
        $num = new Qed('1');
        $this->assertSame('0', $num->scale()->value);
    }

    public function testScale_Ten(): void
    {
        $num = new Qed('1', 10);
        $this->assertSame('10', $num->scale()->value);
    }

    public function testScale_Change(): void
    {
        $num = new Qed('1', 10);
        $this->assertSame('10', $num->scale()->value);

        $num = $num->scale(5);
        $this->assertSame('5', $num->scale()->value);
    }

    /**
     * @dataProvider floorProvider
     */
    public function testFloor($number, $expected): void
    {
        $num = new Qed($number);

        $this->assertSame($expected, $num->floor()->value);
    }

    public static function floorProvider()
    {
        yield '123' => ['123.77', '123'];
        yield 'uh oh' => ['.123', '0'];
        yield 'negatory' => ['-123.77', '-123'];
        yield 'negazero' => ['-.77', '-0'];
    }

    /**
     * @dataProvider ceilProvider
     */
    public function testCeil($number, $expected): void
    {
        $num = new Qed($number);

        $this->assertSame($expected, $num->ceil()->value);
    }

    public static function ceilProvider()
    {
        yield '123' => ['123.77', '124'];
        yield 'uh oh' => ['.123', '1'];
        yield 'negatory' => ['-123.77', '-123'];
        yield 'negazero' => ['-.77', '-0'];
    }

    /**
     * @dataProvider roundProvider
     */
    public function testRound($number, $precision, $roundHalf, $expected): void
    {
        $num = new Qed($number);

        $this->assertSame($expected, $num->round($precision, $roundHalf)->value);
    }

    public static function roundProvider()
    {
        $precision = 0;
        yield 'nada' => ['123', $precision, PHP_ROUND_HALF_UP, '123'];
        yield 'nada-0' => ['123.0000', $precision, PHP_ROUND_HALF_UP, '123'];

        yield '123-up' => ['123.37', $precision, PHP_ROUND_HALF_UP, '123'];
        yield '123-down' => ['123.37', $precision, PHP_ROUND_HALF_DOWN, '123'];
        yield '123-even' => ['123.37', $precision, PHP_ROUND_HALF_EVEN, '123'];
        yield '123-odd' => ['123.37', $precision, PHP_ROUND_HALF_ODD, '123'];

        yield '124-p0' => ['123.77', $precision, PHP_ROUND_HALF_UP, '124'];
        yield 'uh oh' => ['.123', $precision, PHP_ROUND_HALF_UP, '0'];
        yield 'negatory' => ['-123.77', $precision, PHP_ROUND_HALF_UP, '-124'];
        yield 'negazero' => ['-.77', $precision, PHP_ROUND_HALF_UP, '-1'];

        $precision = 1;
        yield '123-' . $precision . '-up' => ['123.37', $precision, PHP_ROUND_HALF_UP, '123.4'];
        yield '123-' . $precision . '-down' => ['123.37', $precision, PHP_ROUND_HALF_DOWN, '123.4'];
        yield '123-' . $precision . '-even' => ['123.37', $precision, PHP_ROUND_HALF_EVEN, '123.4'];
        yield '123-' . $precision . '-odd' => ['123.37', $precision, PHP_ROUND_HALF_ODD, '123.4'];

        yield '124-' . $precision => ['123.77', $precision, PHP_ROUND_HALF_UP, '123.8'];
        yield 'uh oh-' . $precision => ['.123', $precision, PHP_ROUND_HALF_UP, '0.1'];
        yield 'negatory-' . $precision => ['-123.77', $precision, PHP_ROUND_HALF_UP, '-123.8'];
        yield 'negazero-' . $precision => ['-.77', $precision, PHP_ROUND_HALF_UP, '-0.8'];

        yield 'qed-example' . $precision => ['2.6973', 2, PHP_ROUND_HALF_UP, '2.70'];
    }
}