<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211205220018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, cylinders INT DEFAULT NULL, capacity DOUBLE PRECISION DEFAULT NULL, power INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fuel_record (id INT AUTO_INCREMENT NOT NULL, gas_station_id INT DEFAULT NULL, vehicle_id INT NOT NULL, amount DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION DEFAULT NULL, mileage INT DEFAULT NULL, date DATE DEFAULT NULL, INDEX IDX_AD5D7538916BFF50 (gas_station_id), INDEX IDX_AD5D7538545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gas_station (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D79572D944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_operation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1024) DEFAULT NULL, interval_km INT DEFAULT NULL, interval_time INT DEFAULT NULL, INDEX IDX_41109C2712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_record (id INT AUTO_INCREMENT NOT NULL, operation_id INT DEFAULT NULL, vehicle_id INT NOT NULL, date DATE NOT NULL, mileage INT DEFAULT NULL, note VARCHAR(1024) DEFAULT NULL, price INT DEFAULT NULL, INDEX IDX_A5F39AA744AC3583 (operation_id), INDEX IDX_A5F39AA7545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, sex VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, color_id INT DEFAULT NULL, model_id INT DEFAULT NULL, engine_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, spz VARCHAR(255) DEFAULT NULL, vin VARCHAR(255) DEFAULT NULL, nickname VARCHAR(255) DEFAULT NULL, year INT DEFAULT NULL, fuel VARCHAR(255) DEFAULT NULL, transmition VARCHAR(255) DEFAULT NULL, odometer BIGINT DEFAULT NULL, wheel_drive VARCHAR(255) DEFAULT NULL, stk DATE DEFAULT NULL, INDEX IDX_1B80E48644F5D008 (brand_id), INDEX IDX_1B80E4867ADA1FB5 (color_id), INDEX IDX_1B80E4867975B7E7 (model_id), INDEX IDX_1B80E486E78C9C0A (engine_id), INDEX IDX_1B80E4867E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fuel_record ADD CONSTRAINT FK_AD5D7538916BFF50 FOREIGN KEY (gas_station_id) REFERENCES gas_station (id)');
        $this->addSql('ALTER TABLE fuel_record ADD CONSTRAINT FK_AD5D7538545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE service_operation ADD CONSTRAINT FK_41109C2712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE service_record ADD CONSTRAINT FK_A5F39AA744AC3583 FOREIGN KEY (operation_id) REFERENCES service_operation (id)');
        $this->addSql('ALTER TABLE service_record ADD CONSTRAINT FK_A5F39AA7545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486E78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D944F5D008');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E48644F5D008');
        $this->addSql('ALTER TABLE service_operation DROP FOREIGN KEY FK_41109C2712469DE2');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867ADA1FB5');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486E78C9C0A');
        $this->addSql('ALTER TABLE fuel_record DROP FOREIGN KEY FK_AD5D7538916BFF50');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867975B7E7');
        $this->addSql('ALTER TABLE service_record DROP FOREIGN KEY FK_A5F39AA744AC3583');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9');
        $this->addSql('ALTER TABLE fuel_record DROP FOREIGN KEY FK_AD5D7538545317D1');
        $this->addSql('ALTER TABLE service_record DROP FOREIGN KEY FK_A5F39AA7545317D1');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE fuel_record');
        $this->addSql('DROP TABLE gas_station');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE service_operation');
        $this->addSql('DROP TABLE service_record');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vehicle');
    }
}
