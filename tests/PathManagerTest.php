<?php

namespace Tests;

use Rice\Basic\PathManager;
use PHPUnit\Framework\TestCase;

class PathManagerTest extends TestCase
{
    /**
     * 测试PathManager的单例模式.
     */
    public function testSingletonInstance(): void
    {
        $instance1 = PathManager::getInstance();
        $instance2 = PathManager::getInstance();

        $this->assertSame($instance1, $instance2, 'PathManager应该是单例模式，返回相同实例');
    }

    /**
     * 测试PathManager的所有路径属性是否正确初始化.
     */
    public function testPathPropertiesInitialization(): void
    {
        $pathManager = PathManager::getInstance();

        // 测试主要路径属性是否存在且不为空
        $this->assertTrue(property_exists($pathManager, 'project'), 'PathManager应该有project属性');
        $this->assertTrue(property_exists($pathManager, 'cache'), 'PathManager应该有cache属性');
        $this->assertTrue(property_exists($pathManager, 'src'), 'PathManager应该有src属性');
        $this->assertTrue(property_exists($pathManager, 'test'), 'PathManager应该有test属性');
        $this->assertTrue(property_exists($pathManager, 'components'), 'PathManager应该有components属性');
        $this->assertTrue(property_exists($pathManager, 'support'), 'PathManager应该有support属性');

        // 测试路径属性值是否不为空
        $this->assertNotEmpty($pathManager->project, 'project路径不应为空');
        $this->assertNotEmpty($pathManager->cache, 'cache路径不应为空');
        $this->assertNotEmpty($pathManager->src, 'src路径不应为空');
        $this->assertNotEmpty($pathManager->test, 'test路径不应为空');
        $this->assertNotEmpty($pathManager->components, 'components路径不应为空');
        $this->assertNotEmpty($pathManager->support, 'support路径不应为空');
    }

    /**
     * 测试PathManager的路径格式是否正确（以DIRECTORY_SEPARATOR结尾）.
     */
    public function testPathFormat(): void
    {
        $pathManager = PathManager::getInstance();

        // 所有路径属性都应该以DIRECTORY_SEPARATOR结尾
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->project, 'project路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->cache, 'cache路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->src, 'src路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->test, 'test路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->components, 'components路径应以目录分隔符结尾');
        $this->assertStringEndsWith(DIRECTORY_SEPARATOR, $pathManager->support, 'support路径应以目录分隔符结尾');
    }

    /**
     * 测试PathManager的路径层次关系是否正确.
     */
    public function testPathHierarchy(): void
    {
        $pathManager = PathManager::getInstance();

        // 测试src目录是否是project目录的子目录
        $expectedSrcPath = $pathManager->project . 'src' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedSrcPath, $pathManager->src, 'src目录应该是project目录的子目录');

        // 测试test目录是否是project目录的子目录
        $expectedTestPath = $pathManager->project . 'tests' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedTestPath, $pathManager->test, 'test目录应该是project目录的子目录');

        // 测试components目录是否是src目录的子目录
        $expectedComponentsPath = $pathManager->src . 'Components' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedComponentsPath, $pathManager->components, 'components目录应该是src目录的子目录');

        // 测试support目录是否是src目录的子目录
        $expectedSupportPath = $pathManager->src . 'Support' . DIRECTORY_SEPARATOR;
        $this->assertEquals($expectedSupportPath, $pathManager->support, 'support目录应该是src目录的子目录');
    }

    /**
     * 测试PathManager的所有路径属性是否都是字符串类型.
     */
    public function testPathPropertiesType(): void
    {
        $pathManager = PathManager::getInstance();

        $this->assertIsString($pathManager->project, 'project属性应该是字符串类型');
        $this->assertIsString($pathManager->cache, 'cache属性应该是字符串类型');
        $this->assertIsString($pathManager->src, 'src属性应该是字符串类型');
        $this->assertIsString($pathManager->test, 'test属性应该是字符串类型');
        $this->assertIsString($pathManager->components, 'components属性应该是字符串类型');
        $this->assertIsString($pathManager->support, 'support属性应该是字符串类型');
    }
}
