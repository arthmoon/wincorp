<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131012428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE transaction_id INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wallet_id INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE currency_exchange_rates (first_currency VARCHAR(255) NOT NULL, second_currency VARCHAR(255) NOT NULL, rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(first_currency, second_currency))');
        $this->addSql('CREATE TABLE transactions (id BIGINT NOT NULL, wallet_id BIGINT DEFAULT NULL, currency VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4C712520F3 ON transactions (wallet_id)');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, wallet_id BIGINT DEFAULT NULL, api_token_value VARCHAR(255) DEFAULT NULL, api_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9712520F3 ON users (wallet_id)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:user_id)\'');
        $this->addSql('COMMENT ON COLUMN users.api_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE wallets (id BIGINT NOT NULL, amount INT NOT NULL, currency VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C712520F3 FOREIGN KEY (wallet_id) REFERENCES wallets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9712520F3 FOREIGN KEY (wallet_id) REFERENCES wallets (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE transaction_id CASCADE');
        $this->addSql('DROP SEQUENCE wallet_id CASCADE');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C712520F3');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9712520F3');
        $this->addSql('DROP TABLE currency_exchange_rates');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE wallets');
    }
}
