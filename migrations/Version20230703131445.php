<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703131445 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sklbl_orders (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, article_id INT NOT NULL, dossier VARCHAR(8) NOT NULL, order_num NUMERIC(10, 0) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, order_qte NUMERIC(15, 3) NOT NULL, order_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E48EB92C19EB6921 (client_id), INDEX IDX_E48EB92C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C19EB6921');
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C7294869C');
        $this->addSql('DROP TABLE sklbl_orders');
    }
}
