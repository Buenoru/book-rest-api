<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\TransactionWriteOffException;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class TransactService
{
    private EntityManagerInterface $em;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }


    /**
     * @return array{transaction_id: int, write_off_amount: int, enroll_amount: int}
     *
     * @throws Exception
     */
    public function transactByUsersIds(int $idFrom, int $idTo, int $amount): array
    {
        $from = $this->userRepository->findOrFail($idFrom);
        $to = $this->userRepository->findOrFail($idTo);

        return $this->transactByUserEntity($from, $to, $amount);
    }

    /**
     * @return array{transaction_id: int, write_off_amount: int, enroll_amount: int}
     *
     * @throws Exception
     */
    public function transactByUserEntity(User $from, User $to, int $amount): array
    {
        try {
            $sql = <<<SQL
                with write_off as (
                    update "user" set amount = amount - :amount where id = :from returning id, amount
                ),
                enroll as (
                    update "user" set amount = amount + :amount where id = :to returning id, amount
                ),
                transaction as (
                    insert into user_transaction (user_from_id, user_to_id, amount, is_success, creted_at)
                        select write_off.id, enroll.id, :amount, true, now()
                            from write_off, enroll
                    returning user_transaction.id
                )
                select transaction.id transaction_id, write_off.amount write_off_amount, enroll.amount enroll_amount
                from write_off, enroll, transaction
            SQL;

            $conn = $this->em->getConnection();
            $stmt = $conn->prepare($sql);

            $res = $stmt->executeQuery([
                'from' => $from->getId(),
                'to' => $to->getId(),
                'amount' => $amount,
            ]);

            return $res->fetchAssociative();
        } catch (Exception $t) {
            // @todo: Тут должен быть уход в какую-нить фабрику.
            if (false !== strpos($t->getMessage(), 'user_amount_check')) {
                throw new TransactionWriteOffException($from, $amount);
            } else {
                throw $t;
            }
        }
    }
}
