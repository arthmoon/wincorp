<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131201048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX transactions_reason_index');
        $this->addSql('DROP INDEX transactions_creted_at_index');
        $this->addSql('CREATE INDEX transactions_search_index ON transactions (created_at, reason)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX transactions_search_index');
        $this->addSql('CREATE INDEX transactions_reason_index ON transactions (reason)');
        $this->addSql('CREATE INDEX transactions_creted_at_index ON transactions (created_at)');
    }
}
