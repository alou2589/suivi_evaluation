<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119125413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribution ADD materiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE attribution ADD CONSTRAINT FK_C751ED4916880AAF FOREIGN KEY (materiel_id) REFERENCES matos_informatique (id)');
        $this->addSql('CREATE INDEX IDX_C751ED4916880AAF ON attribution (materiel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribution DROP FOREIGN KEY FK_C751ED4916880AAF');
        $this->addSql('DROP INDEX IDX_C751ED4916880AAF ON attribution');
        $this->addSql('ALTER TABLE attribution DROP materiel_id');
    }
}
