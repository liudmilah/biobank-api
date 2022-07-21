<?php

declare(strict_types=1);

namespace App\Data\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220519110316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE species ALTER name_lat TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_lat DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_en DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_en SET NOT NULL');
        $this->addSql('ALTER TABLE species ALTER name_ru TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_ru DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_ru SET NOT NULL');
        $this->addSql('ALTER TABLE species ALTER family TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER family DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER family SET NOT NULL');
        $this->addSql('ALTER TABLE species ALTER sp_order TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER sp_order DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER sp_order SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN species.name_lat IS \'(DC2Type:specie_name_type)\'');
        $this->addSql('COMMENT ON COLUMN species.name_en IS \'(DC2Type:specie_name_type)\'');
        $this->addSql('COMMENT ON COLUMN species.name_ru IS \'(DC2Type:specie_name_type)\'');
        $this->addSql('COMMENT ON COLUMN species.family IS \'(DC2Type:specie_name_type)\'');
        $this->addSql('COMMENT ON COLUMN species.sp_order IS \'(DC2Type:specie_name_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE species ALTER name_lat TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_lat DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_en TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_en DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_en DROP NOT NULL');
        $this->addSql('ALTER TABLE species ALTER name_ru TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER name_ru DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER name_ru DROP NOT NULL');
        $this->addSql('ALTER TABLE species ALTER family TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER family DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER family DROP NOT NULL');
        $this->addSql('ALTER TABLE species ALTER sp_order TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE species ALTER sp_order DROP DEFAULT');
        $this->addSql('ALTER TABLE species ALTER sp_order DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN species.name_lat IS NULL');
        $this->addSql('COMMENT ON COLUMN species.name_en IS NULL');
        $this->addSql('COMMENT ON COLUMN species.name_ru IS NULL');
        $this->addSql('COMMENT ON COLUMN species.family IS NULL');
        $this->addSql('COMMENT ON COLUMN species.sp_order IS NULL');
    }
}
