<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731150451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_model (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F7975B7E7');
        $this->addSql('DROP INDEX IDX_631B685F7975B7E7 ON sklbl_upload_config');
        $this->addSql('ALTER TABLE sklbl_upload_config CHANGE model_id sklbl_model_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685FF1088603 FOREIGN KEY (sklbl_model_id) REFERENCES sklbl_model (id)');
        $this->addSql('CREATE INDEX IDX_631B685FF1088603 ON sklbl_upload_config (sklbl_model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685FF1088603');
        $this->addSql('DROP TABLE sklbl_model');
        $this->addSql('DROP INDEX IDX_631B685FF1088603 ON sklbl_upload_config');
        $this->addSql('ALTER TABLE sklbl_upload_config CHANGE sklbl_model_id model_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F7975B7E7 FOREIGN KEY (model_id) REFERENCES sklbl_model (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_631B685F7975B7E7 ON sklbl_upload_config (model_id)');
    }
}
