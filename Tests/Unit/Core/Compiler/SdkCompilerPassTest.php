<?php
/*
 * This file is part of the SocialBlockBundle and it is distributed
 * under the MIT LICENSE. To use this application you must leave intact this copyright 
 * notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    MIT LICENSE
 * 
 */

namespace AlphaLemon\Block\SocialBlockBundle\Tests\Unit\Core\Compiler;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\SocialBlockBundle\Core\Compiler\SdkCompilerPass;

/**
 * SdkCompilerPassTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class SdkCompilerPassTest extends TestCase
{
    private $container;

    protected function setUp()
    {
        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->definition = $this->getMock('Symfony\Component\DependencyInjection\Definition');
    }

    public function testNothingIsParsedWhenRequiredDefinitionExists()
    {
        $this->setUpDefinition(false);
        $compiler = new SdkCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    public function testTagIsNotParsedWhenAnyAttributeIsSpecified()
    {
        $this->setUpDefinition();

        $tag = array(
            'service_id' => array()
        );

        $this->container
                ->expects($this->once())
                ->method('findTaggedServiceIds')
                ->will($this->returnValue($tag));

        $this->definition
                ->expects($this->never())
                ->method('addMethodCall');

        $compiler = new SdkCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    public function testTagIsParsed()
    {
        $this->setUpDefinition();

        $tag = array(
            'service_id' => array(
                array(
                    'description' => 'Business slider',
                    'type' => 'BusinessSlider',
                    'group' => 'business_theme_apps',
                )
            )
        );

        $this->container
                ->expects($this->once())
                ->method('findTaggedServiceIds')
                ->will($this->returnValue($tag));

        $this->definition
                ->expects($this->once())
                ->method('addMethodCall')
                ->with('addSdk');

        $compiler = new SdkCompilerPass();
        $this->assertNull($compiler->process($this->container));
    }

    private function setUpDefinition($value = true)
    {
        $this->container
                ->expects($this->once())
                ->method('hasDefinition')
                ->will($this->returnValue($value));

        $expectation = (false === $value) ? $this->never() : $this->once();
        $this->container
                ->expects($expectation)
                ->method('getDefinition')
                ->will($this->returnValue($this->definition));
    }
}
