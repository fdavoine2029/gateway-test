<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707124901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_fx (id INT AUTO_INCREMENT NOT NULL, sklbl_of_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_29E1AE102038673D (sklbl_of_id), INDEX IDX_29E1AE107294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE102038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE107294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE102038673D');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE107294869C');
        $this->addSql('DROP TABLE sklbl_fx');
    }
}
