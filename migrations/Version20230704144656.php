<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704144656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_files (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, sklbl_of_id INT DEFAULT NULL, client_filename VARCHAR(255) DEFAULT NULL, categorie VARCHAR(50) DEFAULT NULL, INDEX IDX_7C0084185E25CDC (sklbl_order_id), INDEX IDX_7C0084182038673D (sklbl_of_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_files ADD CONSTRAINT FK_7C0084185E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_files ADD CONSTRAINT FK_7C0084182038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_files DROP FOREIGN KEY FK_7C0084185E25CDC');
        $this->addSql('ALTER TABLE sklbl_files DROP FOREIGN KEY FK_7C0084182038673D');
        $this->addSql('DROP TABLE sklbl_files');
    }
}
