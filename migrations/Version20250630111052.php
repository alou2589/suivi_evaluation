<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250630111052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DBC472C34
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_268B9C9DBC472C34 ON agent
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent DROP service_affecte_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agent ADD service_affecte_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DBC472C34 FOREIGN KEY (service_affecte_id) REFERENCES service (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_268B9C9DBC472C34 ON agent (service_affecte_id)
        SQL);
    }
}
