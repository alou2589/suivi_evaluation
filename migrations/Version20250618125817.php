<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618125817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE nature_depense (id INT AUTO_INCREMENT NOT NULL, action_id INT DEFAULT NULL, nom_nature VARCHAR(255) NOT NULL, budget_cp VARCHAR(255) NOT NULL, budget_ae VARCHAR(255) NOT NULL, INDEX IDX_2C86DC829D32F035 (action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE nature_depense ADD CONSTRAINT FK_2C86DC829D32F035 FOREIGN KEY (action_id) REFERENCES action (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE nature_depense DROP FOREIGN KEY FK_2C86DC829D32F035
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE nature_depense
        SQL);
    }
}
