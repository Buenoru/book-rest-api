<?php

declare(strict_types=1);

namespace App\Tests\Integrational;

use App\Exception\TransactionWriteOffException;
use App\Service\TransactService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TransactionTest extends KernelTestCase
{
    public static function setUpBeforeClass(): void
    {
        self::bootKernel();
    }

    public function testResponseSchema(): array
    {
        $transaction = $this->service->transactByUsersIds(1, 2, 10);

        $this->assertArrayHasKey('transaction_id', $transaction);
        $this->assertArrayHasKey('write_off_amount', $transaction);
        $this->assertArrayHasKey('enroll_amount', $transaction);

        return $transaction;
    }

    /**
     * @depends testResponseSchema
     */
    public function testTransactByUsersIdsSuccess(array $transaction)
    {
        unset($transaction['transaction_id']);

        $expected = [
            'write_off_amount' => 90,
            'enroll_amount' => 210,
        ];

        $this->assertEquals($expected, $transaction);
    }

    public function testNotEnoughtAmount()
    {
        $this->expectException(TransactionWriteOffException::class);

        $this->service->transactByUsersIds(1, 2, 101);
    }

    protected function setUp(): void
    {
        $container = static::getContainer();
        $this->service = $container->get(TransactService::class);
    }
}
