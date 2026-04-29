<?php

namespace Tests;

use Rice\Basic\Infrastructure\PathManager;
use PHPUnit\Framework\TestCase;

class PathManagerTest extends TestCase
{
    public function testSingletonInstance(): void
    {
        $instance1 = PathManager::getInstance();
        $instance2 = PathManager::getInstance();

        $this->assertSame($instance1, $instance2, 'PathManager应该是单例模式，返回相同实例');
    }

    public function testPathPropertiesInitialization(): void
    {
        $pathManager = PathManager::getInstance();

        $this->assertTrue(property_exists($pathManager, 'project'), 'PathManager应该有project属性');
        $this->assertTrue(property_exists($pathManager, 'cache'), 'PathManager应该有cache属性');
        $this->assertTrue(property_exists($pathManager, 'src'), 'PathManager应该有src属性');
        $this->assertTrue(property_exists($pathManager, 'test'), 'PathManager应该有test属性');
        $this->assertTrue(property_exists($pathManager, 'domain'), 'PathManager应该有domain属性');
        $this->assertTrue(property_exists($pathManager, 'infrastructure'), 'PathManager应该有infrastructure属性');
        $this->assertTrue(property_exists($pathManager, 'support'), 'PathManager应该有support属性');
        $this->assertTrue(property_exists($pathManager, 'contracts'), 'PathManager应该有contracts属性');
        $this->assertTrue(property_exists($pathManager, 'lang'), 'PathManager应该有lang属性');

        $this->assertNotEmpty($pathManager->project, 'project路径不应为空');
        $this->assertNotEmpty($pathManager->cache, 'cache路径不应为空');
        $this->assertNotEmpty($pathManager->src, 'src路径不应为空');
        $this->assertNotEmpty($pathManager->test, 'test路径不应为空');
        $this->assertNotEmpty($pathManager->domain, 'domain路径不应为空');
        $this->assertNotEmpty($pathManager->infrastructure, 'infrastructure路径不应为空');
        $this->assertNotEmpty($pathManager->support, 'support路径不应为空');
        $this->assertNotEmpty($pathManager->contracts, 'contracts路径不应为空');
        $this->assertNotEmpty($pathManager->lang, 'lang路径不应为空');
    }

    public function testPathFormat(): void
    {
        $pathManager = PathManager::getInstance();

        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->project, 'project路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->cache, 'cache路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->src, 'src路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->test, 'test路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->domain, 'domain路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->infrastructure, 'infrastructure路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->support, 'support路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->contracts, 'contracts路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->lang, 'lang路径应以目录分隔符结尾');
    }

    public function testPathHierarchy(): void
    {
        $pathManager = PathManager::getInstance();

        $expectedSrcPath = $pathManager->project . 'src' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedSrcPath, $pathManager->src, 'src目录应该是project目录的子目录');

        $expectedTestPath = $pathManager->project . 'tests' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedTestPath, $pathManager->test, 'test目录应该是project目录的子目录');

        $expectedDomainPath = $pathManager->src . 'Domain' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedDomainPath, $pathManager->domain, 'domain目录应该是src目录的子目录');

        $expectedInfrastructurePath = $pathManager->src . 'Infrastructure' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedInfrastructurePath, $pathManager->infrastructure, 'infrastructure目录应该是src目录的子目录');

        $expectedSupportPath = $pathManager->src . 'Support' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedSupportPath, $pathManager->support, 'support目录应该是src目录的子目录');

        $expectedContractsPath = $pathManager->src . 'Contracts' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedContractsPath, $pathManager->contracts, 'contracts目录应该是src目录的子目录');

        $expectedLangPath = $pathManager->project . 'lang' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedLangPath, $pathManager->lang, 'lang目录应该是project目录的子目录');
    }

    public function testPathPropertiesType(): void
    {
        $pathManager = PathManager::getInstance();

        $this->assertIsString($pathManager->project, 'project属性应该是字符串类型');
        $this->assertIsString($pathManager->cache, 'cache属性应该是字符串类型');
        $this->assertIsString($pathManager->src, 'src属性应该是字符串类型');
        $this->assertIsString($pathManager->test, 'test属性应该是字符串类型');
        $this->assertIsString($pathManager->domain, 'domain属性应该是字符串类型');
        $this->assertIsString($pathManager->infrastructure, 'infrastructure属性应该是字符串类型');
        $this->assertIsString($pathManager->support, 'support属性应该是字符串类型');
        $this->assertIsString($pathManager->contracts, 'contracts属性应该是字符串类型');
        $this->assertIsString($pathManager->lang, 'lang属性应该是字符串类型');
    }
}
