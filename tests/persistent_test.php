<?php
/**
 * @copyright Copyright (C) 2005, 2006 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version //autogentag//
 * @filesource
 * @package DatabaseSchema
 * @subpackage Tests
 */

/**
 * @package DatabaseSchema
 * @subpackage Tests
 */
class ezcDatabaseSchemaPersistentTest extends ezcTestCase
{
    public function setUp()
    {
        $this->testFilesDir = dirname( __FILE__ ) . '/testfiles';
        $this->tempDir = $this->createTempDir( 'ezcDatabasePersistentTest' );
    }

    public function tearDown()
    {
        $this->removeTempDir();
    }

    public function getSchema()
    {
        $schema = ezcDbSchema::createFromFile( 'xml', $this->testFilesDir . '/webbuilder.schema.xml' );
        return $schema;
    }

    public function testPersistentGenerationSuccess()
    {
        $schema = $this->getSchema();
        $schema->writeToFile( 'persistent', $this->tempDir );

        $d = dir( $this->testFilesDir . '/persistent' );
        while ( ( $entry = $d->read() ) !== false )
        {
            if ( $entry == '.'|| $entry == '..' )
            {
                continue;
            }
            if ( !file_exists( $this->tempDir . '/' . $entry ) )
            {
                $this->fail( "PersistentObject definition <{$entry}> not created!" );
            }
            $this->assertEquals( 
                md5_file( $this->testFilesDir . '/persistent/' . $entry ),
                md5_file( $this->tempDir . '/' . $entry ),
                "PersistentObject definition for file <$entry> differs"
            );
        }
    }

    public function testPersistentGenerationFailureMissingDir()
    {
        $schema = $this->getSchema();
        try
        {
            $schema->writeToFile( 'persistent', $this->tempDir . '/unavailable' );
        }
        catch ( ezcBaseFileException $e )
        {
            return;
        }
        $this->fail( "Expected ezcBaseFileException not thrown on saving PersistentObject definitions to non-existent directory." );
    }

    public function testPersistentGenerationFailureNonDir()
    {
        $schema = $this->getSchema();
        try
        {
            $schema->writeToFile( 'persistent', __FILE__ );
        }
        catch ( ezcBaseFileException $e )
        {
            return;
        }
        $this->fail( "Expected ezcBaseFileException not thrown on saving PersistentObject definitions to a non-directory." );
    }

    public function testPersistentReading()
    {
        $schema = ezcDbSchema::createFromFile( 'persistent', $this->testFilesDir . '/persistent' );
        $fakeSchema = include( $this->testFilesDir . '/persistent_schema.php' );

        $this->assertEquals( $fakeSchema, $schema->getSchema(), "Schema not correctly generated from PersistentObject definition." );
    }

    public static function suite()
    {
        return new ezcTestSuite( 'ezcDatabaseSchemaPersistentTest' );
    }
}
?>