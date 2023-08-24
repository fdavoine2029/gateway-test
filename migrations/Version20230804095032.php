<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230804095032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_logs ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_logs ADD CONSTRAINT FK_AF112FD7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_AF112FD7A76ED395 ON sklbl_logs (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_logs DROP FOREIGN KEY FK_AF112FD7A76ED395');
        $this->addSql('DROP INDEX IDX_AF112FD7A76ED395 ON sklbl_logs');
        $this->addSql('ALTER TABLE sklbl_logs DROP user_id');
    }
}
