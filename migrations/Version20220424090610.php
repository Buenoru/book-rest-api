<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424090610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, amount int not null CHECK (amount >= 0), PRIMARY KEY(id))'
        );
        $this->addSql(
            'CREATE TABLE user_transaction (id SERIAL NOT NULL, user_from_id INT NOT NULL, user_to_id INT NOT NULL, amount int not null CHECK (amount >= 0), is_success BOOLEAN NOT NULL, creted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_DB2CCC4420C3C701 ON user_transaction (user_from_id)');
        $this->addSql('CREATE INDEX IDX_DB2CCC44D2F7B13D ON user_transaction (user_to_id)');
        $this->addSql('COMMENT ON COLUMN user_transaction.creted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql(
            'ALTER TABLE user_transaction ADD CONSTRAINT FK_DB2CCC4420C3C701 FOREIGN KEY (user_from_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE user_transaction ADD CONSTRAINT FK_DB2CCC44D2F7B13D FOREIGN KEY (user_to_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_transaction DROP CONSTRAINT FK_DB2CCC4420C3C701');
        $this->addSql('ALTER TABLE user_transaction DROP CONSTRAINT FK_DB2CCC44D2F7B13D');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_transaction');
    }
}
