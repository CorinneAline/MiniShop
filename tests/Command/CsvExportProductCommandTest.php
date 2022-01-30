<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \App\Command\CsvExportProductCommand
 */
class CsvExportProductCommandTest extends KernelTestCase
{
    /**
     * @group command
     */
    public function testExecute()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:export-products-csv');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Début de l\'import============Import des produits  0/12 [>---------------------------]', $output);
        $this->assertStringContainsString('Command exited cleanly !', $output);
    }

}
