<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230707125316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE107294869C');
        $this->addSql('DROP INDEX IDX_29E1AE107294869C ON sklbl_fx');
        $this->addSql('ALTER TABLE sklbl_fx ADD sklbl_sku_id VARCHAR(255) NOT NULL, DROP article_id');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE1016D690DE FOREIGN KEY (sklbl_sku_id) REFERENCES sklbl_sku (id)');
        $this->addSql('CREATE INDEX IDX_29E1AE1016D690DE ON sklbl_fx (sklbl_sku_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE1016D690DE');
        $this->addSql('DROP INDEX IDX_29E1AE1016D690DE ON sklbl_fx');
        $this->addSql('ALTER TABLE sklbl_fx ADD article_id INT NOT NULL, DROP sklbl_sku_id');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE107294869C FOREIGN KEY (article_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_29E1AE107294869C ON sklbl_fx (article_id)');
    }
}
