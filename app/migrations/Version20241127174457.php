<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127174457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id UUID NOT NULL, character_id UUID DEFAULT NULL, actor_name VARCHAR(255) NOT NULL, seasons_active JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_447556F91136BE75 ON actor (character_id)');
        $this->addSql('COMMENT ON COLUMN actor.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN actor.character_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character (id UUID NOT NULL, character_name VARCHAR(255) NOT NULL, character_image_thumb VARCHAR(255) DEFAULT NULL, character_image_full VARCHAR(255) DEFAULT NULL, nickname VARCHAR(255) DEFAULT NULL, kingsguard BOOLEAN DEFAULT false NOT NULL, royal BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN character.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_siblings (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_FFFB1EE5FCC8BCE0 ON character_siblings (character_source)');
        $this->addSql('CREATE INDEX IDX_FFFB1EE5E52DEC6F ON character_siblings (character_target)');
        $this->addSql('COMMENT ON COLUMN character_siblings.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_siblings.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_parents (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_94F59D57FCC8BCE0 ON character_parents (character_source)');
        $this->addSql('CREATE INDEX IDX_94F59D57E52DEC6F ON character_parents (character_target)');
        $this->addSql('COMMENT ON COLUMN character_parents.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_parents.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_married_engaged (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_EC2E32D2FCC8BCE0 ON character_married_engaged (character_source)');
        $this->addSql('CREATE INDEX IDX_EC2E32D2E52DEC6F ON character_married_engaged (character_target)');
        $this->addSql('COMMENT ON COLUMN character_married_engaged.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_married_engaged.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_serves (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_8E2826F3FCC8BCE0 ON character_serves (character_source)');
        $this->addSql('CREATE INDEX IDX_8E2826F3E52DEC6F ON character_serves (character_target)');
        $this->addSql('COMMENT ON COLUMN character_serves.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_serves.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_allies (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_6A9A23F3FCC8BCE0 ON character_allies (character_source)');
        $this->addSql('CREATE INDEX IDX_6A9A23F3E52DEC6F ON character_allies (character_target)');
        $this->addSql('COMMENT ON COLUMN character_allies.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_allies.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_killed (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_86F9C8C9FCC8BCE0 ON character_killed (character_source)');
        $this->addSql('CREATE INDEX IDX_86F9C8C9E52DEC6F ON character_killed (character_target)');
        $this->addSql('COMMENT ON COLUMN character_killed.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_killed.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_house (character_id UUID NOT NULL, house_id UUID NOT NULL, PRIMARY KEY(character_id, house_id))');
        $this->addSql('CREATE INDEX IDX_9916DEFF1136BE75 ON character_house (character_id)');
        $this->addSql('CREATE INDEX IDX_9916DEFF6BB74515 ON character_house (house_id)');
        $this->addSql('COMMENT ON COLUMN character_house.character_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_house.house_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE character_guardian (character_source UUID NOT NULL, character_target UUID NOT NULL, PRIMARY KEY(character_source, character_target))');
        $this->addSql('CREATE INDEX IDX_3C4989C4FCC8BCE0 ON character_guardian (character_source)');
        $this->addSql('CREATE INDEX IDX_3C4989C4E52DEC6F ON character_guardian (character_target)');
        $this->addSql('COMMENT ON COLUMN character_guardian.character_source IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN character_guardian.character_target IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE house (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN house.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE actor ADD CONSTRAINT FK_447556F91136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_siblings ADD CONSTRAINT FK_FFFB1EE5FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_siblings ADD CONSTRAINT FK_FFFB1EE5E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_parents ADD CONSTRAINT FK_94F59D57FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_parents ADD CONSTRAINT FK_94F59D57E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_married_engaged ADD CONSTRAINT FK_EC2E32D2FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_married_engaged ADD CONSTRAINT FK_EC2E32D2E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_serves ADD CONSTRAINT FK_8E2826F3FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_serves ADD CONSTRAINT FK_8E2826F3E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_allies ADD CONSTRAINT FK_6A9A23F3FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_allies ADD CONSTRAINT FK_6A9A23F3E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_killed ADD CONSTRAINT FK_86F9C8C9FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_killed ADD CONSTRAINT FK_86F9C8C9E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_house ADD CONSTRAINT FK_9916DEFF1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_house ADD CONSTRAINT FK_9916DEFF6BB74515 FOREIGN KEY (house_id) REFERENCES house (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_guardian ADD CONSTRAINT FK_3C4989C4FCC8BCE0 FOREIGN KEY (character_source) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_guardian ADD CONSTRAINT FK_3C4989C4E52DEC6F FOREIGN KEY (character_target) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE actor DROP CONSTRAINT FK_447556F91136BE75');
        $this->addSql('ALTER TABLE character_siblings DROP CONSTRAINT FK_FFFB1EE5FCC8BCE0');
        $this->addSql('ALTER TABLE character_siblings DROP CONSTRAINT FK_FFFB1EE5E52DEC6F');
        $this->addSql('ALTER TABLE character_parents DROP CONSTRAINT FK_94F59D57FCC8BCE0');
        $this->addSql('ALTER TABLE character_parents DROP CONSTRAINT FK_94F59D57E52DEC6F');
        $this->addSql('ALTER TABLE character_married_engaged DROP CONSTRAINT FK_EC2E32D2FCC8BCE0');
        $this->addSql('ALTER TABLE character_married_engaged DROP CONSTRAINT FK_EC2E32D2E52DEC6F');
        $this->addSql('ALTER TABLE character_serves DROP CONSTRAINT FK_8E2826F3FCC8BCE0');
        $this->addSql('ALTER TABLE character_serves DROP CONSTRAINT FK_8E2826F3E52DEC6F');
        $this->addSql('ALTER TABLE character_allies DROP CONSTRAINT FK_6A9A23F3FCC8BCE0');
        $this->addSql('ALTER TABLE character_allies DROP CONSTRAINT FK_6A9A23F3E52DEC6F');
        $this->addSql('ALTER TABLE character_killed DROP CONSTRAINT FK_86F9C8C9FCC8BCE0');
        $this->addSql('ALTER TABLE character_killed DROP CONSTRAINT FK_86F9C8C9E52DEC6F');
        $this->addSql('ALTER TABLE character_house DROP CONSTRAINT FK_9916DEFF1136BE75');
        $this->addSql('ALTER TABLE character_house DROP CONSTRAINT FK_9916DEFF6BB74515');
        $this->addSql('ALTER TABLE character_guardian DROP CONSTRAINT FK_3C4989C4FCC8BCE0');
        $this->addSql('ALTER TABLE character_guardian DROP CONSTRAINT FK_3C4989C4E52DEC6F');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE character_siblings');
        $this->addSql('DROP TABLE character_parents');
        $this->addSql('DROP TABLE character_married_engaged');
        $this->addSql('DROP TABLE character_serves');
        $this->addSql('DROP TABLE character_allies');
        $this->addSql('DROP TABLE character_killed');
        $this->addSql('DROP TABLE character_house');
        $this->addSql('DROP TABLE character_guardian');
        $this->addSql('DROP TABLE house');
    }
}
