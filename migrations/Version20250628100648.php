<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250628100648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD options TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD image_url VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD description VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD question_type VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD key VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ADD caption VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ALTER title DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ALTER is_required DROP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN field.options IS '(DC2Type:array)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template ADD topic VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template ADD tags TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN template.tags IS '(DC2Type:array)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP options
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP image_url
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP description
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP question_type
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP key
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field DROP caption
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ALTER title SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE field ALTER is_required SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template DROP topic
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE template DROP tags
        SQL);
    }
}
