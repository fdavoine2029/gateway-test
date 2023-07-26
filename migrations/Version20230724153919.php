<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724153919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_upload_config (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, column_name VARCHAR(255) NOT NULL, column_csv VARCHAR(3) DEFAULT NULL, status INT NOT NULL, INDEX IDX_631B685F5E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F5E25CDC');
        $this->addSql('DROP TABLE sklbl_upload_config');
    }
}
