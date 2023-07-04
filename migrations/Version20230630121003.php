<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630121003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs ADD client_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE ofs ADD CONSTRAINT FK_4661B9A819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE INDEX IDX_4661B9A819EB6921 ON ofs (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs DROP FOREIGN KEY FK_4661B9A819EB6921');
        $this->addSql('DROP INDEX IDX_4661B9A819EB6921 ON ofs');
        $this->addSql('ALTER TABLE ofs DROP client_id, DROP created_at, DROP updated_at, DROP status');
    }
}
