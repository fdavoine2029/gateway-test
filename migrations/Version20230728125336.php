<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230728125336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config ADD sklbl_structure_id INT DEFAULT NULL, DROP name');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F207255EA FOREIGN KEY (sklbl_structure_id) REFERENCES sklbl_structure (id)');
        $this->addSql('CREATE INDEX IDX_631B685F207255EA ON sklbl_upload_config (sklbl_structure_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F207255EA');
        $this->addSql('DROP INDEX IDX_631B685F207255EA ON sklbl_upload_config');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD name VARCHAR(50) NOT NULL, DROP sklbl_structure_id');
    }
}
