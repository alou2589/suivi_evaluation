<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704105443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE document_administratif (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, type_doc VARCHAR(255) NOT NULL, nom_doc VARCHAR(255) NOT NULL, document VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_33F0D1213414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document_administratif ADD CONSTRAINT FK_33F0D1213414710B FOREIGN KEY (agent_id) REFERENCES agent (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE document_administratif DROP FOREIGN KEY FK_33F0D1213414710B
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_administratif
        SQL);
    }
}
