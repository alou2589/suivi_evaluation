<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106092650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sous_structure ADD service_rattache_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sous_structure ADD CONSTRAINT FK_7408DCA5253803B5 FOREIGN KEY (service_rattache_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_7408DCA5253803B5 ON sous_structure (service_rattache_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sous_structure DROP FOREIGN KEY FK_7408DCA5253803B5');
        $this->addSql('DROP INDEX IDX_7408DCA5253803B5 ON sous_structure');
        $this->addSql('ALTER TABLE sous_structure DROP service_rattache_id');
    }
}
