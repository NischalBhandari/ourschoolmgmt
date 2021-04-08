<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407082846 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3367C3403F');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3367C3403F FOREIGN KEY (classteacher_id) REFERENCES staff (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3367C3403F');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3367C3403F FOREIGN KEY (classteacher_id) REFERENCES staff (id) ON DELETE CASCADE');
    }
}
