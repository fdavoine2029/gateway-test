<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703093832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, category_order INT NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sklbl_of ADD emballage1_id INT DEFAULT NULL, ADD emballage2_id INT DEFAULT NULL, ADD emballage3_id INT DEFAULT NULL, ADD emballage4_id INT DEFAULT NULL, ADD fichier1_id INT DEFAULT NULL, ADD fichier2_id INT DEFAULT NULL, ADD mini_complet_id INT DEFAULT NULL, ADD masque_id INT DEFAULT NULL, ADD fichier_retour_id INT DEFAULT NULL, ADD options_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A19AEC6B2 FOREIGN KEY (emballage1_id) REFERENCES sklbl_emballage (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283AB1B695C FOREIGN KEY (emballage2_id) REFERENCES sklbl_emballage (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283AB3A70E39 FOREIGN KEY (emballage3_id) REFERENCES sklbl_emballage (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A2E703680 FOREIGN KEY (emballage4_id) REFERENCES sklbl_emballage (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A8C6663D4 FOREIGN KEY (fichier1_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A9ED3CC3A FOREIGN KEY (fichier2_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283AACF56546 FOREIGN KEY (mini_complet_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283AEE50B206 FOREIGN KEY (masque_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283ACDAC68F FOREIGN KEY (fichier_retour_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A3ADB05F1 FOREIGN KEY (options_id) REFERENCES sklbl_rubrique (id)');
        $this->addSql('CREATE INDEX IDX_22C283A19AEC6B2 ON sklbl_of (emballage1_id)');
        $this->addSql('CREATE INDEX IDX_22C283AB1B695C ON sklbl_of (emballage2_id)');
        $this->addSql('CREATE INDEX IDX_22C283AB3A70E39 ON sklbl_of (emballage3_id)');
        $this->addSql('CREATE INDEX IDX_22C283A2E703680 ON sklbl_of (emballage4_id)');
        $this->addSql('CREATE INDEX IDX_22C283A8C6663D4 ON sklbl_of (fichier1_id)');
        $this->addSql('CREATE INDEX IDX_22C283A9ED3CC3A ON sklbl_of (fichier2_id)');
        $this->addSql('CREATE INDEX IDX_22C283AACF56546 ON sklbl_of (mini_complet_id)');
        $this->addSql('CREATE INDEX IDX_22C283AEE50B206 ON sklbl_of (masque_id)');
        $this->addSql('CREATE INDEX IDX_22C283ACDAC68F ON sklbl_of (fichier_retour_id)');
        $this->addSql('CREATE INDEX IDX_22C283A3ADB05F1 ON sklbl_of (options_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AA21214B7');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('DROP TABLE categories');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A19AEC6B2');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283AB1B695C');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283AB3A70E39');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A2E703680');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A8C6663D4');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A9ED3CC3A');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283AACF56546');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283AEE50B206');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283ACDAC68F');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A3ADB05F1');
        $this->addSql('DROP INDEX IDX_22C283A19AEC6B2 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283AB1B695C ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283AB3A70E39 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283A2E703680 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283A8C6663D4 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283A9ED3CC3A ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283AACF56546 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283AEE50B206 ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283ACDAC68F ON sklbl_of');
        $this->addSql('DROP INDEX IDX_22C283A3ADB05F1 ON sklbl_of');
        $this->addSql('ALTER TABLE sklbl_of DROP emballage1_id, DROP emballage2_id, DROP emballage3_id, DROP emballage4_id, DROP fichier1_id, DROP fichier2_id, DROP mini_complet_id, DROP masque_id, DROP fichier_retour_id, DROP options_id');
    }
}
