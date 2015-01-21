<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Create table to store Yubikey ID
 */
class Version20150120005252 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE ttree_yubico_domain_model_key (publicid VARCHAR(12) NOT NULL, account VARCHAR(40) DEFAULT NULL, INDEX IDX_25D26A9A7D3656A4 (account), PRIMARY KEY(publicid))");
		$this->addSql("ALTER TABLE ttree_yubico_domain_model_key ADD CONSTRAINT FK_25D26A9A7D3656A4 FOREIGN KEY (account) REFERENCES typo3_flow_security_account (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("DROP TABLE ttree_yubico_domain_model_key");
	}
}