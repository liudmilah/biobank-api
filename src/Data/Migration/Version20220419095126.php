<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419095126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE samples (id UUID NOT NULL, specie_id UUID NOT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date VARCHAR(50) DEFAULT NULL, place VARCHAR(255) DEFAULT NULL, material VARCHAR(255) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lon DOUBLE PRECISION DEFAULT NULL, sex VARCHAR(20) DEFAULT NULL, age VARCHAR(20) DEFAULT NULL, responsible VARCHAR(100) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, cs VARCHAR(40) DEFAULT NULL, sr VARCHAR(40) DEFAULT NULL, waterbody VARCHAR(100) DEFAULT NULL, company VARCHAR(100) DEFAULT NULL, dna_code VARCHAR(100) DEFAULT NULL, interior_code VARCHAR(40) DEFAULT NULL, ring_number VARCHAR(40) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_19925777D5436AB7 ON samples (specie_id)');
        $this->addSql('COMMENT ON COLUMN samples.id IS \'(DC2Type:bb_id)\'');
        $this->addSql('COMMENT ON COLUMN samples.specie_id IS \'(DC2Type:bb_id)\'');
        $this->addSql('COMMENT ON COLUMN samples.code IS \'(DC2Type:sample_code)\'');
        $this->addSql('COMMENT ON COLUMN samples.type IS \'(DC2Type:sample_type)\'');
        $this->addSql('CREATE TABLE species (id UUID NOT NULL, name_lat VARCHAR(255) NOT NULL, name_en VARCHAR(255) DEFAULT NULL, name_ru VARCHAR(255) DEFAULT NULL, family VARCHAR(255) DEFAULT NULL, sp_order VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A50FF71291170C7B ON species (name_lat)');
        $this->addSql('COMMENT ON COLUMN species.id IS \'(DC2Type:bb_id)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, password_hash VARCHAR(255) DEFAULT NULL, status VARCHAR(16) NOT NULL, role VARCHAR(16) NOT NULL, signup_token_value VARCHAR(255) DEFAULT NULL, signup_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, password_reset_token_value VARCHAR(255) DEFAULT NULL, password_reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:bb_id)\'');
        $this->addSql('COMMENT ON COLUMN users.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.email IS \'(DC2Type:user_email)\'');
        $this->addSql('COMMENT ON COLUMN users.status IS \'(DC2Type:user_status)\'');
        $this->addSql('COMMENT ON COLUMN users.role IS \'(DC2Type:user_role)\'');
        $this->addSql('COMMENT ON COLUMN users.signup_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.password_reset_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE samples ADD CONSTRAINT FK_19925777D5436AB7 FOREIGN KEY (specie_id) REFERENCES species (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE samples DROP CONSTRAINT FK_19925777D5436AB7');
        $this->addSql('DROP TABLE samples');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE users');
    }
}
