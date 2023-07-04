<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230704121641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_of ADD sklbl_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('CREATE INDEX IDX_22C283A5E25CDC ON sklbl_of (sklbl_order_id)');
        $this->addSql('ALTER TABLE sklbl_orders ADD sklbl_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('CREATE INDEX IDX_E48EB92C5E25CDC ON sklbl_orders (sklbl_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A5E25CDC');
        $this->addSql('DROP INDEX IDX_22C283A5E25CDC ON sklbl_of');
        $this->addSql('ALTER TABLE sklbl_of DROP sklbl_order_id');
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C5E25CDC');
        $this->addSql('DROP INDEX IDX_E48EB92C5E25CDC ON sklbl_orders');
        $this->addSql('ALTER TABLE sklbl_orders DROP sklbl_order_id');
    }
}
