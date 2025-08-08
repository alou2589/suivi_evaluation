<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250806160637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribution (id INT AUTO_INCREMENT NOT NULL, affectaire_id INT DEFAULT NULL, materiel_id INT DEFAULT NULL, date_attribution DATE DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C751ED49CDF58C8A (affectaire_id), INDEX IDX_C751ED4916880AAF (materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribution ADD CONSTRAINT FK_C751ED49CDF58C8A FOREIGN KEY (affectaire_id) REFERENCES affectation (id)');
        $this->addSql('ALTER TABLE attribution ADD CONSTRAINT FK_C751ED4916880AAF FOREIGN KEY (materiel_id) REFERENCES matos_informatique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribution DROP FOREIGN KEY FK_C751ED49CDF58C8A');
        $this->addSql('ALTER TABLE attribution DROP FOREIGN KEY FK_C751ED4916880AAF');
        $this->addSql('DROP TABLE attribution');
    }
}
