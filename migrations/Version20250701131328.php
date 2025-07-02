<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250701131328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agent ADD identification_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D4DFE3A85 FOREIGN KEY (identification_id) REFERENCES info_perso (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_268B9C9D4DFE3A85 ON agent (identification_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D4DFE3A85
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_268B9C9D4DFE3A85 ON agent
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE agent DROP identification_id
        SQL);
    }
}
