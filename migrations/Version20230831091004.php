<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230831091004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ART (ART_ID INT AUTO_INCREMENT NOT NULL, DOS VARCHAR(8) NOT NULL, REF VARCHAR(25) NOT NULL, DES VARCHAR(80) NOT NULL, ABCCOD VARCHAR(1) NOT NULL, PRIMARY KEY(ART_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FOU (FOU_ID INT AUTO_INCREMENT NOT NULL, DOS VARCHAR(8) NOT NULL, TIERS VARCHAR(20) NOT NULL, NOM VARCHAR(80) NOT NULL, PAY VARCHAR(3) NOT NULL, TRANSJRNB NUMERIC(3, 0) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(FOU_ID)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apps (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, app_order INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, ref VARCHAR(25) NOT NULL, designation VARCHAR(80) NOT NULL, abccod VARCHAR(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', lot INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bf (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cli (id INT NOT NULL, dos VARCHAR(8) NOT NULL, tiers VARCHAR(20) NOT NULL, nom VARCHAR(80) NOT NULL, pay VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clients (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseurs (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(20) NOT NULL, name VARCHAR(80) NOT NULL, country VARCHAR(3) NOT NULL, trspdays NUMERIC(3, 0) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, description LONGTEXT NOT NULL, executed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, result VARCHAR(255) NOT NULL, progress NUMERIC(3, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouv (id INT AUTO_INCREMENT NOT NULL, dos VARCHAR(8) NOT NULL, ref VARCHAR(25) NOT NULL, sref1 VARCHAR(8) NOT NULL, sref2 VARCHAR(8) NOT NULL, tiers VARCHAR(20) NOT NULL, enrno NUMERIC(14, 0) NOT NULL, cdno NUMERIC(10, 0) NOT NULL, cddt DATE NOT NULL, des VARCHAR(80) NOT NULL, cdqte NUMERIC(12, 3) NOT NULL, refun VARCHAR(4) NOT NULL, mont NUMERIC(13, 2) NOT NULL, dev VARCHAR(4) NOT NULL, blno NUMERIC(10, 0) NOT NULL, blqte NUMERIC(12, 3) NOT NULL, depo VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ofs (id INT NOT NULL, article_id INT NOT NULL, client_id INT DEFAULT NULL, code NUMERIC(10, 0) NOT NULL, dossier VARCHAR(8) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, ref_cli VARCHAR(40) DEFAULT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, libelle VARCHAR(40) DEFAULT NULL, order_num NUMERIC(10, 0) DEFAULT NULL, prevstart_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', conditionnement NUMERIC(9, 2) NOT NULL, INDEX IDX_4661B9A87294869C (article_id), INDEX IDX_4661B9A819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_sup (id VARCHAR(255) NOT NULL, fournisseurs_id INT DEFAULT NULL, articles_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, order_num NUMERIC(10, 0) NOT NULL, record_num NUMERIC(14, 0) NOT NULL, order_date DATE NOT NULL, designation VARCHAR(80) NOT NULL, buyer VARCHAR(20) NOT NULL, order_qte NUMERIC(15, 3) NOT NULL, unit VARCHAR(4) NOT NULL, amount NUMERIC(13, 2) NOT NULL, currency VARCHAR(4) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, delay DATE NOT NULL, trans NUMERIC(3, 0) NOT NULL, delivery_place VARCHAR(3) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', to_deliver_qte NUMERIC(15, 3) NOT NULL, delivery_note NUMERIC(10, 0) DEFAULT NULL, batch_num VARCHAR(30) DEFAULT NULL, receiv_qte NUMERIC(12, 3) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, blmod VARCHAR(4) NOT NULL, delay_trsp DATE NOT NULL, sync INT NOT NULL, etablissement VARCHAR(3) NOT NULL, order_line NUMERIC(4, 0) NOT NULL, bl_line NUMERIC(14, 0) NOT NULL, no_ventilation NUMERIC(14, 0) NOT NULL, INDEX IDX_E8E0BCF427ACDDFD (fournisseurs_id), INDEX IDX_E8E0BCF41EBAF6CC (articles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quality_ctrl (id INT AUTO_INCREMENT NOT NULL, order_sup_id VARCHAR(255) DEFAULT NULL, ofs_id INT DEFAULT NULL, checked_by_id INT DEFAULT NULL, checked_on DATE NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', comment LONGTEXT DEFAULT NULL, INDEX IDX_ADB60D663CF61D18 (order_sup_id), INDEX IDX_ADB60D66BE988D91 (ofs_id), INDEX IDX_ADB60D662199DB86 (checked_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiv_sup_details (id INT AUTO_INCREMENT NOT NULL, order_sup_id VARCHAR(255) DEFAULT NULL, qte_recue NUMERIC(10, 2) NOT NULL, comment LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', batch_num VARCHAR(30) DEFAULT NULL, num_bl_fou VARCHAR(10) NOT NULL, INDEX IDX_DFE6D3313CF61D18 (order_sup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_emballage (id INT NOT NULL, article_id INT NOT NULL, dossier VARCHAR(8) NOT NULL, code VARCHAR(4) NOT NULL, libelle VARCHAR(40) NOT NULL, unite VARCHAR(4) NOT NULL, qte NUMERIC(12, 3) NOT NULL, ordre INT NOT NULL, INDEX IDX_B03EA41B7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_files (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, sklbl_of_id INT DEFAULT NULL, client_filename VARCHAR(255) DEFAULT NULL, categorie VARCHAR(50) DEFAULT NULL, status INT NOT NULL, ligne INT NOT NULL, INDEX IDX_7C0084185E25CDC (sklbl_order_id), INDEX IDX_7C0084182038673D (sklbl_of_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_fx (id INT AUTO_INCREMENT NOT NULL, sklbl_of_id INT NOT NULL, sklbl_sku_id INT NOT NULL, sklbl_file_id INT NOT NULL, sklbl_order_id INT DEFAULT NULL, unique_id VARCHAR(255) DEFAULT NULL, sent_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', received_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, data1 VARCHAR(255) DEFAULT NULL, data2 VARCHAR(255) DEFAULT NULL, data3 VARCHAR(255) DEFAULT NULL, data4 VARCHAR(255) DEFAULT NULL, data5 VARCHAR(255) DEFAULT NULL, data6 VARCHAR(255) DEFAULT NULL, data7 VARCHAR(255) DEFAULT NULL, data8 VARCHAR(255) DEFAULT NULL, data9 VARCHAR(255) DEFAULT NULL, data10 VARCHAR(255) DEFAULT NULL, data11 VARCHAR(255) DEFAULT NULL, data12 VARCHAR(255) DEFAULT NULL, data13 VARCHAR(255) DEFAULT NULL, data14 VARCHAR(255) DEFAULT NULL, data15 VARCHAR(255) DEFAULT NULL, data16 VARCHAR(255) DEFAULT NULL, data17 VARCHAR(255) DEFAULT NULL, data18 VARCHAR(255) DEFAULT NULL, data19 VARCHAR(255) DEFAULT NULL, data20 VARCHAR(255) DEFAULT NULL, INDEX IDX_29E1AE102038673D (sklbl_of_id), INDEX IDX_29E1AE1016D690DE (sklbl_sku_id), INDEX IDX_29E1AE10E6A07BB4 (sklbl_file_id), INDEX IDX_29E1AE105E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_fx2 (id VARBINARY(255) NOT NULL, sklbl_fx_id INT DEFAULT NULL, sklbl_file_id INT DEFAULT NULL, sklbl_of_id INT DEFAULT NULL, sklbl_custfile_id INT DEFAULT NULL, unique_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deals_on DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gen_scalabel_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, sklbl_filename VARCHAR(255) NOT NULL, data1 VARCHAR(255) DEFAULT NULL, data2 VARCHAR(255) DEFAULT NULL, data3 VARCHAR(255) DEFAULT NULL, data4 VARCHAR(255) DEFAULT NULL, data5 VARCHAR(255) DEFAULT NULL, data6 VARCHAR(255) DEFAULT NULL, data7 VARCHAR(255) DEFAULT NULL, data8 VARCHAR(255) DEFAULT NULL, data9 VARCHAR(255) DEFAULT NULL, data10 VARCHAR(255) DEFAULT NULL, data11 VARCHAR(255) DEFAULT NULL, data12 VARCHAR(255) DEFAULT NULL, data13 VARCHAR(255) DEFAULT NULL, data14 VARCHAR(255) DEFAULT NULL, data15 VARCHAR(255) DEFAULT NULL, data16 VARCHAR(255) DEFAULT NULL, data17 VARCHAR(255) DEFAULT NULL, data18 VARCHAR(255) DEFAULT NULL, data19 VARCHAR(255) DEFAULT NULL, data20 VARCHAR(255) DEFAULT NULL, INDEX IDX_74B4FC79DEE25E0 (sklbl_fx_id), INDEX IDX_74B4FC7E6A07BB4 (sklbl_file_id), INDEX IDX_74B4FC72038673D (sklbl_of_id), INDEX IDX_74B4FC71B40BBA6 (sklbl_custfile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_lisage_config (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, sklbl_structure_id INT NOT NULL, categorie VARCHAR(20) NOT NULL, name VARCHAR(50) NOT NULL, label VARCHAR(255) NOT NULL, num INT NOT NULL, value VARCHAR(255) DEFAULT NULL, format VARCHAR(50) DEFAULT NULL, INDEX IDX_5D4F6AAA5E25CDC (sklbl_order_id), INDEX IDX_5D4F6AAA207255EA (sklbl_structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_logs (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, user_id INT DEFAULT NULL, job_name VARCHAR(255) NOT NULL, executed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', message LONGTEXT NOT NULL, status INT NOT NULL, mode VARCHAR(50) NOT NULL, progress INT NOT NULL, INDEX IDX_AF112FD75E25CDC (sklbl_order_id), INDEX IDX_AF112FD7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_model (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_of (id INT NOT NULL, article_id INT DEFAULT NULL, client_id INT DEFAULT NULL, emballage1_id INT DEFAULT NULL, emballage2_id INT DEFAULT NULL, emballage3_id INT DEFAULT NULL, emballage4_id INT DEFAULT NULL, fichier1_id INT DEFAULT NULL, fichier2_id INT DEFAULT NULL, mini_complet_id INT DEFAULT NULL, masque_id INT DEFAULT NULL, fichier_retour_id INT DEFAULT NULL, options_id INT DEFAULT NULL, sklbl_order_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, code NUMERIC(10, 0) NOT NULL, ref_cli VARCHAR(40) DEFAULT NULL, order_qte NUMERIC(12, 3) NOT NULL, launched_qte NUMERIC(12, 3) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, sync INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', of_status INT NOT NULL, sklbl_status INT NOT NULL, planned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', order_num NUMERIC(10, 0) NOT NULL, INDEX IDX_22C283A7294869C (article_id), INDEX IDX_22C283A19EB6921 (client_id), INDEX IDX_22C283A19AEC6B2 (emballage1_id), INDEX IDX_22C283AB1B695C (emballage2_id), INDEX IDX_22C283AB3A70E39 (emballage3_id), INDEX IDX_22C283A2E703680 (emballage4_id), INDEX IDX_22C283A8C6663D4 (fichier1_id), INDEX IDX_22C283A9ED3CC3A (fichier2_id), INDEX IDX_22C283AACF56546 (mini_complet_id), INDEX IDX_22C283AEE50B206 (masque_id), INDEX IDX_22C283ACDAC68F (fichier_retour_id), INDEX IDX_22C283A3ADB05F1 (options_id), INDEX IDX_22C283A5E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_orders (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, article_id INT NOT NULL, sklbl_order_id INT DEFAULT NULL, dossier VARCHAR(8) NOT NULL, order_num NUMERIC(10, 0) NOT NULL, sref1 VARCHAR(8) DEFAULT NULL, sref2 VARCHAR(8) DEFAULT NULL, order_qte NUMERIC(15, 3) NOT NULL, order_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status INT NOT NULL, sklbl_status INT NOT NULL, qte_limit INT DEFAULT NULL, percent_above_limit NUMERIC(3, 1) DEFAULT NULL, INDEX IDX_E48EB92C19EB6921 (client_id), INDEX IDX_E48EB92C7294869C (article_id), INDEX IDX_E48EB92C5E25CDC (sklbl_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_rubrique (id INT NOT NULL, dossier VARCHAR(8) NOT NULL, entite VARCHAR(90) NOT NULL, rubrique VARCHAR(32) NOT NULL, valeur VARCHAR(80) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_sku (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT NOT NULL, sklbl_file_id INT NOT NULL, order_qte INT NOT NULL, produce_qte INT DEFAULT NULL, off_qte INT DEFAULT NULL, status INT NOT NULL, data1 VARCHAR(255) DEFAULT NULL, data2 VARCHAR(255) DEFAULT NULL, data3 VARCHAR(255) DEFAULT NULL, data4 VARCHAR(255) DEFAULT NULL, data5 VARCHAR(255) DEFAULT NULL, data6 VARCHAR(255) DEFAULT NULL, data7 VARCHAR(255) DEFAULT NULL, data8 VARCHAR(255) DEFAULT NULL, data9 VARCHAR(255) DEFAULT NULL, data10 VARCHAR(255) DEFAULT NULL, data11 VARCHAR(255) DEFAULT NULL, data12 VARCHAR(255) DEFAULT NULL, data13 VARCHAR(255) DEFAULT NULL, data14 VARCHAR(255) DEFAULT NULL, data15 VARCHAR(255) DEFAULT NULL, data16 VARCHAR(255) DEFAULT NULL, data17 VARCHAR(255) DEFAULT NULL, data18 VARCHAR(255) DEFAULT NULL, data19 VARCHAR(255) DEFAULT NULL, data20 VARCHAR(255) DEFAULT NULL, INDEX IDX_94F1BBFD5E25CDC (sklbl_order_id), INDEX IDX_94F1BBFDE6A07BB4 (sklbl_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_structure (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sklbl_upload_config (id INT AUTO_INCREMENT NOT NULL, sklbl_order_id INT DEFAULT NULL, sklbl_structure_id INT DEFAULT NULL, sklbl_model_id INT DEFAULT NULL, categorie VARCHAR(50) NOT NULL, label VARCHAR(50) NOT NULL, value VARCHAR(255) DEFAULT NULL, format VARCHAR(50) DEFAULT NULL, order_num INT DEFAULT NULL, customer TINYINT(1) NOT NULL, f1 TINYINT(1) NOT NULL, f2 TINYINT(1) NOT NULL, f3 TINYINT(1) NOT NULL, f4 TINYINT(1) NOT NULL, f5 TINYINT(1) NOT NULL, lisage TINYINT(1) NOT NULL, unique_value TINYINT(1) NOT NULL, customer_csv VARCHAR(3) DEFAULT NULL, f1_csv VARCHAR(3) DEFAULT NULL, f2_csv VARCHAR(3) DEFAULT NULL, f3_csv VARCHAR(3) DEFAULT NULL, f4_csv VARCHAR(3) DEFAULT NULL, f5_csv VARCHAR(3) DEFAULT NULL, lisage_csv VARCHAR(3) DEFAULT NULL, f1_csv_num INT DEFAULT NULL, f2_csv_num INT DEFAULT NULL, f3_csv_num INT DEFAULT NULL, f4_csv_num INT DEFAULT NULL, f5_csv_num INT DEFAULT NULL, customer_csv_num INT DEFAULT NULL, lisage_csv_num INT DEFAULT NULL, INDEX IDX_631B685F5E25CDC (sklbl_order_id), INDEX IDX_631B685F207255EA (sklbl_structure_id), INDEX IDX_631B685FF1088603 (sklbl_model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, categorie VARCHAR(10) NOT NULL, code INT NOT NULL, name VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(150) NOT NULL, is_verified TINYINT(1) NOT NULL, reset_token VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ofs ADD CONSTRAINT FK_4661B9A87294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE ofs ADD CONSTRAINT FK_4661B9A819EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF427ACDDFD FOREIGN KEY (fournisseurs_id) REFERENCES fournisseurs (id)');
        $this->addSql('ALTER TABLE order_sup ADD CONSTRAINT FK_E8E0BCF41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D663CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D66BE988D91 FOREIGN KEY (ofs_id) REFERENCES ofs (id)');
        $this->addSql('ALTER TABLE quality_ctrl ADD CONSTRAINT FK_ADB60D662199DB86 FOREIGN KEY (checked_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE receiv_sup_details ADD CONSTRAINT FK_DFE6D3313CF61D18 FOREIGN KEY (order_sup_id) REFERENCES order_sup (id)');
        $this->addSql('ALTER TABLE sklbl_emballage ADD CONSTRAINT FK_B03EA41B7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE sklbl_files ADD CONSTRAINT FK_7C0084185E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_files ADD CONSTRAINT FK_7C0084182038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE102038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE1016D690DE FOREIGN KEY (sklbl_sku_id) REFERENCES sklbl_sku (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE10E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_fx ADD CONSTRAINT FK_29E1AE105E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC79DEE25E0 FOREIGN KEY (sklbl_fx_id) REFERENCES sklbl_fx (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC7E6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC72038673D FOREIGN KEY (sklbl_of_id) REFERENCES sklbl_of (id)');
        $this->addSql('ALTER TABLE sklbl_fx2 ADD CONSTRAINT FK_74B4FC71B40BBA6 FOREIGN KEY (sklbl_custfile_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_lisage_config ADD CONSTRAINT FK_5D4F6AAA5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_lisage_config ADD CONSTRAINT FK_5D4F6AAA207255EA FOREIGN KEY (sklbl_structure_id) REFERENCES sklbl_structure (id)');
        $this->addSql('ALTER TABLE sklbl_logs ADD CONSTRAINT FK_AF112FD75E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_logs ADD CONSTRAINT FK_AF112FD7A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
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
        $this->addSql('ALTER TABLE sklbl_of ADD CONSTRAINT FK_22C283A5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE sklbl_orders ADD CONSTRAINT FK_E48EB92C5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_sku ADD CONSTRAINT FK_94F1BBFD5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_sku ADD CONSTRAINT FK_94F1BBFDE6A07BB4 FOREIGN KEY (sklbl_file_id) REFERENCES sklbl_files (id)');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F5E25CDC FOREIGN KEY (sklbl_order_id) REFERENCES sklbl_orders (id)');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685F207255EA FOREIGN KEY (sklbl_structure_id) REFERENCES sklbl_structure (id)');
        $this->addSql('ALTER TABLE sklbl_upload_config ADD CONSTRAINT FK_631B685FF1088603 FOREIGN KEY (sklbl_model_id) REFERENCES sklbl_model (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ofs DROP FOREIGN KEY FK_4661B9A87294869C');
        $this->addSql('ALTER TABLE ofs DROP FOREIGN KEY FK_4661B9A819EB6921');
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF427ACDDFD');
        $this->addSql('ALTER TABLE order_sup DROP FOREIGN KEY FK_E8E0BCF41EBAF6CC');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D663CF61D18');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D66BE988D91');
        $this->addSql('ALTER TABLE quality_ctrl DROP FOREIGN KEY FK_ADB60D662199DB86');
        $this->addSql('ALTER TABLE receiv_sup_details DROP FOREIGN KEY FK_DFE6D3313CF61D18');
        $this->addSql('ALTER TABLE sklbl_emballage DROP FOREIGN KEY FK_B03EA41B7294869C');
        $this->addSql('ALTER TABLE sklbl_files DROP FOREIGN KEY FK_7C0084185E25CDC');
        $this->addSql('ALTER TABLE sklbl_files DROP FOREIGN KEY FK_7C0084182038673D');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE102038673D');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE1016D690DE');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE10E6A07BB4');
        $this->addSql('ALTER TABLE sklbl_fx DROP FOREIGN KEY FK_29E1AE105E25CDC');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC79DEE25E0');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC7E6A07BB4');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC72038673D');
        $this->addSql('ALTER TABLE sklbl_fx2 DROP FOREIGN KEY FK_74B4FC71B40BBA6');
        $this->addSql('ALTER TABLE sklbl_lisage_config DROP FOREIGN KEY FK_5D4F6AAA5E25CDC');
        $this->addSql('ALTER TABLE sklbl_lisage_config DROP FOREIGN KEY FK_5D4F6AAA207255EA');
        $this->addSql('ALTER TABLE sklbl_logs DROP FOREIGN KEY FK_AF112FD75E25CDC');
        $this->addSql('ALTER TABLE sklbl_logs DROP FOREIGN KEY FK_AF112FD7A76ED395');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A7294869C');
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A19EB6921');
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
        $this->addSql('ALTER TABLE sklbl_of DROP FOREIGN KEY FK_22C283A5E25CDC');
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C19EB6921');
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C7294869C');
        $this->addSql('ALTER TABLE sklbl_orders DROP FOREIGN KEY FK_E48EB92C5E25CDC');
        $this->addSql('ALTER TABLE sklbl_sku DROP FOREIGN KEY FK_94F1BBFD5E25CDC');
        $this->addSql('ALTER TABLE sklbl_sku DROP FOREIGN KEY FK_94F1BBFDE6A07BB4');
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F5E25CDC');
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685F207255EA');
        $this->addSql('ALTER TABLE sklbl_upload_config DROP FOREIGN KEY FK_631B685FF1088603');
        $this->addSql('DROP TABLE ART');
        $this->addSql('DROP TABLE FOU');
        $this->addSql('DROP TABLE apps');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE bf');
        $this->addSql('DROP TABLE cli');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE fournisseurs');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE mouv');
        $this->addSql('DROP TABLE ofs');
        $this->addSql('DROP TABLE order_sup');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE quality_ctrl');
        $this->addSql('DROP TABLE receiv_sup_details');
        $this->addSql('DROP TABLE sklbl');
        $this->addSql('DROP TABLE sklbl_emballage');
        $this->addSql('DROP TABLE sklbl_files');
        $this->addSql('DROP TABLE sklbl_fx');
        $this->addSql('DROP TABLE sklbl_fx2');
        $this->addSql('DROP TABLE sklbl_lisage_config');
        $this->addSql('DROP TABLE sklbl_logs');
        $this->addSql('DROP TABLE sklbl_model');
        $this->addSql('DROP TABLE sklbl_of');
        $this->addSql('DROP TABLE sklbl_orders');
        $this->addSql('DROP TABLE sklbl_rubrique');
        $this->addSql('DROP TABLE sklbl_sku');
        $this->addSql('DROP TABLE sklbl_structure');
        $this->addSql('DROP TABLE sklbl_upload_config');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
