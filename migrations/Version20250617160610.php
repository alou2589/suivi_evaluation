<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617160610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE action ADD responsable_action_id INT DEFAULT NULL, ADD cout_action VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', ADD updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE action ADD CONSTRAINT FK_47CC8C926074A123 FOREIGN KEY (responsable_action_id) REFERENCES action (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_47CC8C926074A123 ON action (responsable_action_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE action DROP FOREIGN KEY FK_47CC8C926074A123
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_47CC8C926074A123 ON action
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE action DROP responsable_action_id, DROP cout_action, DROP created_at, DROP updated_at
        SQL);
    }
}
