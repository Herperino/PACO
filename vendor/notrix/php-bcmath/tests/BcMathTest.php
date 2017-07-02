<?php

require_once __DIR__ . '/../src/BcMath.php';

/**
 * BcMath class tests
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BcMath
     */
    protected $bcMath;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->bcMath = new BcMath(
            BcMath_PositiveNumber::create(6)
        );
    }

    /**
     * Test addition
     *
     * @param BcMath_Number $leftOperand
     * @param BcMath_Number $rightOperand
     * @param BcMath_Number $result
     *
     * @dataProvider additionProvider
     */
    public function testAddition(BcMath_Number $leftOperand, BcMath_Number $rightOperand, BcMath_Number $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->addition($leftOperand, $rightOperand)
        );
    }

    /**
     * Test subtraction
     *
     * @param BcMath_Number $leftOperand
     * @param BcMath_Number $rightOperand
     * @param BcMath_Number $result
     *
     * @dataProvider subtractionProvider
     */
    public function testSubtraction(BcMath_Number $leftOperand, BcMath_Number $rightOperand, BcMath_Number $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->subtraction($leftOperand, $rightOperand)
        );
    }

    /**
     * Test multiplication
     *
     * @param BcMath_Number $leftOperand
     * @param BcMath_Number $rightOperand
     * @param BcMath_Number $result
     *
     * @dataProvider multiplicationProvider
     */
    public function testMultiplication($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->multiplication($leftOperand, $rightOperand)
        );
    }

    /**
     * Test power
     *
     * @param BcMath_Number $leftOperand
     * @param BcMath_Number $rightOperand
     * @param BcMath_Number $result
     *
     * @dataProvider powerProvider
     */
    public function testPower($leftOperand, $rightOperand, $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->power($leftOperand, $rightOperand)
        );
    }

    /**
     * Test division
     *
     * @param BcMath_Number $leftOperand
     * @param BcMath_Number $rightOperand
     * @param BcMath_Number $result
     *
     * @dataProvider divisionProvider
     */
    public function testDivision(BcMath_Number $leftOperand, BcMath_Number $rightOperand, BcMath_Number $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->division($leftOperand, $rightOperand)
        );
    }

    /**
     * Test ceiling
     *
     * @param BcMath_Number $operand
     * @param BcMath_Number $precision
     * @param BcMath_Number $result
     *
     * @dataProvider ceilingProvider
     */
    public function testCeil($operand, $precision, $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->ceiling($operand, $precision)
        );
    }

    /**
     * Test floor
     *
     * @param BcMath_Number $operand
     * @param BcMath_Number $precision
     * @param BcMath_Number $result
     *
     * @dataProvider flooringProvider
     */
    public function testFloor($operand, $precision, $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->flooring($operand, $precision)
        );
    }

    /**
     * Test rounding
     *
     * @param BcMath_Number $operand
     * @param BcMath_Number $precision
     * @param BcMath_Number $result
     *
     * @dataProvider roundingProvider
     */
    public function testRound($operand, $precision, $result)
    {
        $this->assertEquals(
            $result->getValue(),
            $this->bcMath->rounding($operand, $precision)
        );
    }

    /**
     * Addition provider
     *
     * @return array
     */
    public function additionProvider()
    {
        return array(
            array(BcMath_Number::create('0.5'),  BcMath_Number::create('0.5'),  BcMath_Number::create('1')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('1'),    BcMath_Number::create('2')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('-1'),   BcMath_Number::create('0')),
            array(BcMath_Number::create('-0'),   BcMath_Number::create('0'),    BcMath_Number::create('0')),
            array(BcMath_Number::create('0'),    BcMath_Number::create('-0'),   BcMath_Number::create('0')),
            array(BcMath_Number::create('-0.5'), BcMath_Number::create('0'),    BcMath_Number::create('-0.5')),
            array(BcMath_Number::create('-0.5'), BcMath_Number::create('-0.5'), BcMath_Number::create('-1')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('1'),    BcMath_Number::create('0')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('-1'),   BcMath_Number::create('-2')),
        );
    }

    /**
     * Subtraction provider
     *
     * @return array
     */
    public function subtractionProvider()
    {
        return array(
            array(BcMath_Number::create('1'),    BcMath_Number::create('1'),    BcMath_Number::create('0')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('-1'),   BcMath_Number::create('0')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('-1'),   BcMath_Number::create('2')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('1'),    BcMath_Number::create('-2')),
            array(BcMath_Number::create('0.5'),  BcMath_Number::create('0.5'),  BcMath_Number::create('0')),
            array(BcMath_Number::create('-0.5'), BcMath_Number::create('-0.5'), BcMath_Number::create('0')),
            array(BcMath_Number::create('-0.5'), BcMath_Number::create('0'),    BcMath_Number::create('-0.5')),
            array(BcMath_Number::create('-0'),   BcMath_Number::create('0'),    BcMath_Number::create('0')),
        );
    }

    /**
     * Multiplication provider
     *
     * @return array
     */
    public function multiplicationProvider()
    {
        return array(
            array(BcMath_Number::create('5.654789'), BcMath_Number::create('1.55'), BcMath_Number::create('8.764922')),
            array(BcMath_Number::create('3.123456'), BcMath_Number::create('1.77'), BcMath_Number::create('5.528517')),
            array(BcMath_Number::create('1'),        BcMath_Number::create('1'),    BcMath_Number::create('1')),
            array(BcMath_Number::create('1'),        BcMath_Number::create('-1'),   BcMath_Number::create('-1')),
            array(BcMath_Number::create('0.5'),      BcMath_Number::create('0.5'),  BcMath_Number::create('0.25')),
            array(BcMath_Number::create('-0'),       BcMath_Number::create('0'),    BcMath_Number::create('0')),
            array(BcMath_Number::create('-0.5'),     BcMath_Number::create('0'),    BcMath_Number::create('0')),
            array(BcMath_Number::create('-0.5'),     BcMath_Number::create('-0.5'), BcMath_Number::create('0.25')),
            array(BcMath_Number::create('-1'),       BcMath_Number::create('1'),    BcMath_Number::create('-1')),
            array(BcMath_Number::create('-1'),       BcMath_Number::create('-1'),   BcMath_Number::create('1')),
        );
    }

    /**
     * Power provider
     *
     * @return array
     */
    public function powerProvider()
    {
        return array(
            array(BcMath_Number::create('5'), BcMath_Number::create('2'), BcMath_Number::create('25')),
            array(BcMath_Number::create('2'), BcMath_Number::create('3'), BcMath_Number::create('8')),
            array(BcMath_Number::create('-1'), BcMath_Number::create('2'), BcMath_Number::create('1')),
            array(BcMath_Number::create('-1'), BcMath_Number::create('3'), BcMath_Number::create('-1')),
            array(BcMath_Number::create('0.5'), BcMath_Number::create('3'), BcMath_Number::create('0.125')),
            array(BcMath_Number::create('10'), BcMath_Number::create('5'), BcMath_Number::create('100000')),
            array(BcMath_Number::create('3'), BcMath_Number::create('1'), BcMath_Number::create('3')),
        );
    }

    /**
     * Division provider
     *
     * @return array
     */
    public function divisionProvider()
    {
        return array(
            array(BcMath_Number::create('700'),  BcMath_Number::create('3.5'),  BcMath_Number::create('200')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('3'),    BcMath_Number::create('0.333333')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('2'),    BcMath_Number::create('0.5')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('1'),    BcMath_Number::create('1')),
            array(BcMath_Number::create('1'),    BcMath_Number::create('-1'),   BcMath_Number::create('-1')),
            array(BcMath_Number::create('0.5'),  BcMath_Number::create('0.5'),  BcMath_Number::create('1')),
            array(BcMath_Number::create('-0.5'), BcMath_Number::create('-0.5'), BcMath_Number::create('1')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('1'),    BcMath_Number::create('-1')),
            array(BcMath_Number::create('-1'),   BcMath_Number::create('-1'),   BcMath_Number::create('1')),
        );
    }

    /**
     * Ceiling provider
     *
     * @return array
     */
    public function ceilingProvider()
    {
        return array(
            array(BcMath_Number::create('10.5481'),   BcMath_PositiveNumber::create(3), BcMath_Number::create('10.549')),
            array(BcMath_Number::create('35'),        BcMath_PositiveNumber::create(2), BcMath_Number::create('35')),
            array(BcMath_Number::create('-10.548'),   BcMath_PositiveNumber::create(2), BcMath_Number::create('-10.54')),
            array(BcMath_Number::create('8.444444'),  BcMath_PositiveNumber::create(0), BcMath_Number::create('9')),
            array(BcMath_Number::create('3.999999'),  BcMath_PositiveNumber::create(0), BcMath_Number::create('4')),
            array(BcMath_Number::create('26.0000'),   BcMath_PositiveNumber::create(0), BcMath_Number::create('26')),
            array(BcMath_Number::create('12.0001'),   BcMath_PositiveNumber::create(0), BcMath_Number::create('13')),
            array(BcMath_Number::create('1.000001'),  BcMath_PositiveNumber::create(0), BcMath_Number::create('2')),
            array(BcMath_Number::create('5.0000001'), BcMath_PositiveNumber::create(0), BcMath_Number::create('5')),
        );
    }

    /**
     * Floor provider
     *
     * @return array
     */
    public function flooringProvider()
    {
        return array(
            array(BcMath_Number::create('10.5489'),    BcMath_PositiveNumber::create(3), BcMath_Number::create('10.548')),
            array(BcMath_Number::create('35'),         BcMath_PositiveNumber::create(2), BcMath_Number::create('35')),
            array(BcMath_Number::create('-10.548'),    BcMath_PositiveNumber::create(2), BcMath_Number::create('-10.55')),
            array(BcMath_Number::create('9.333333'),   BcMath_PositiveNumber::create(0), BcMath_Number::create('9')),
            array(BcMath_Number::create('7.999999'),   BcMath_PositiveNumber::create(0), BcMath_Number::create('7')),
            array(BcMath_Number::create('8.0000'),     BcMath_PositiveNumber::create(0), BcMath_Number::create('8')),
            array(BcMath_Number::create('3.0009'),     BcMath_PositiveNumber::create(0), BcMath_Number::create('3')),
            array(BcMath_Number::create('12.000009'),  BcMath_PositiveNumber::create(0), BcMath_Number::create('12')),
            array(BcMath_Number::create('99.0000001'), BcMath_PositiveNumber::create(0), BcMath_Number::create('99')),
        );
    }

    /**
     * Rounding provider
     *
     * @return array
     */
    public function roundingProvider()
    {
        return array(
            array(BcMath_Number::create('10.544'),       BcMath_PositiveNumber::create(2), BcMath_Number::create('10.54')),
            array(BcMath_Number::create('-10.544'),      BcMath_PositiveNumber::create(2), BcMath_Number::create('-10.54')),
            array(BcMath_Number::create('10.545'),       BcMath_PositiveNumber::create(2), BcMath_Number::create('10.55')),
            array(BcMath_Number::create('10.444444'),    BcMath_PositiveNumber::create(0), BcMath_Number::create('10')),
            array(BcMath_Number::create('10.488888'),    BcMath_PositiveNumber::create(0), BcMath_Number::create('10')),
            array(BcMath_Number::create('10.588888'),    BcMath_PositiveNumber::create(0), BcMath_Number::create('11')),
            array(BcMath_Number::create('10.000000'),    BcMath_PositiveNumber::create(6), BcMath_Number::create('10.000000')),
            array(BcMath_Number::create('10.0000555'),   BcMath_PositiveNumber::create(6), BcMath_Number::create('10.000056')),
            array(BcMath_Number::create('10.000055565'), BcMath_PositiveNumber::create(8), BcMath_Number::create('10.00005557')),
        );
    }
}
