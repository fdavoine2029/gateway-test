<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731145149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config ADD model_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F7975B7E7 FOREIGN KEY (model_id) REFERENCES sklbl_model (id)');
        $this->addSql('CREATE INDEX IDX_631B685F7975B7E7 ON sklbl_upload_config (model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F7975B7E7');
        $this->addSql('DROP INDEX IDX_631B685F7975B7E7 ON sklbl_upload_config');
        $this->addSql('ALTER TABLE sklbl_upload_config DROP model_id');
    }
}
