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

namespace AlphaLemon\Block\SocialBlockBundle\Tests\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Tests\Unit\Core\Content\Block\Base\AlBlockManagerContainerBase;
use AlphaLemon\Block\SocialBlockBundle\Core\Block\AlBlockManagerFacebookLikeButton;

/**
 * AlBlockManagerTwitterShareTest
 */
class AlBlockManagerFacebookLikeTest extends AlBlockManagerContainerBase
{   
    public function testDefaultValue()
    {
        $expectedValue = array(
            'Content' => '{
                "like" : {
                    "url" : "",
                    "send" : false,
                    "layout" : "standard",
                    "width" : 450,
                    "show_faces" : true,
                    "font" : "arial",
                    "colorscheme" : "light",
                    "action" : "like"
                 },
                 "graph" : {
                    "url" : "",
                    "title" : "",
                    "type" : "",
                    "image" : "",
                    "site_name" : "",
                    "admins" : ""
                }
            }'
        );

        $this->initContainer(); 
        $blockManager = new AlBlockManagerFacebookLikeButton($this->container, $this->validator);
        $this->assertEquals($expectedValue, $blockManager->getDefaultValue());
    }
    
    public function testEditorParameters()
    {
        $value =
        '{
            "like" : {
                "url" : "",
                "send" : false,
                "layout" : "standard",
                "width" : 450,
                "show_faces" : true,
                "font" : "arial",
                "colorscheme" : "light",
                "action" : "like"
             },
             "graph" : {
                "url" : "",
                "title" : "",
                "type" : "",
                "image" : "",
                "site_name" : "",
                "admins" : ""
            }
        }';

        $block = $this->initBlock($value);
        $this->initContainer();
        
        
        
        $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $formFactory->expects($this->at(0))
                    ->method('create')
                    ->will($this->returnValue($this->initForm()))
        ;
        
        $formFactory->expects($this->at(1))
                    ->method('create')
                    ->will($this->returnValue($this->initForm()))
        ;
        
        $this->container->expects($this->at(2))
                        ->method('get')
                        ->with('form.factory')
                        ->will($this->returnValue($formFactory))
        ;
        
        $formType = $this->getMock('Symfony\Component\Form\FormTypeInterface');
        $this->container->expects($this->at(3))
                        ->method('get')
                        ->with('facebook_like.form')
                        ->will($this->returnValue($formType))
        ;
        
        $this->container->expects($this->at(4))
                        ->method('get')
                        ->with('facebook_open_graph.form')
                        ->will($this->returnValue($formType))
        ;
        
        
        $blockManager = new AlBlockManagerFacebookLikeButton($this->container, $this->validator);
        $blockManager->set($block);
        $blockManager->editorParameters();
    }
    
    /**
     * @dataProvider getHtmlProvider 
     */
    public function testGetHtml($value, $expectedOptions, $expectedMetataglResult)
    {
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerFacebookLikeButton($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'SocialBlockBundle:Content:facebook_like.html.twig',
            'options' => array(
                'block_manager' => $blockManager,
                'options' => $expectedOptions,
            ),
        ));
        
        $this->assertEquals($expectedResult, $blockManager->getHtml());
    }
    
    /**
     * @dataProvider getHtmlProvider 
     */
    public function testGetMetaTags($value, $expectedOptions, $expectedMetataglResult)
    {
        $block = $this->initBlock($value);
        $this->initContainer();
        
        $blockManager = new AlBlockManagerFacebookLikeButton($this->container, $this->validator);
        $blockManager->set($block);
        
        $expectedResult = array('RenderView' => array(
            'view' => 'SocialBlockBundle:Content:facebook_like.html.twig',
            'options' => array(
                'block_manager' => $blockManager,
                'options' => $expectedOptions,
            ),
        ));
        
        $this->assertEquals($expectedMetataglResult, $blockManager->getMetaTags());
    }
    
    public function testSave()
    {
        $this->markTestSkipped("Todo");
    }
    
    public function getHtmlProvider()
    {   
        return array(
            array(
                '{
                    "like" : {
                        "url" : "",
                        "send" : false,
                        "layout" : "standard",
                        "width" : 450,
                        "show_faces" : true,
                        "font" : "arial",
                        "colorscheme" : "light",
                        "action" : "like"
                     },
                     "graph" : {
                        "url" : "",
                        "title" : "",
                        "type" : "",
                        "image" : "",
                        "site_name" : "",
                        "admins" : ""
                    }
                }',
                array(
                    'url' => '{{ app.request.uri }}',
                    'send' => false,
                    'layout' => 'standard',
                    'width' => 450,
                    'show_faces' => true,
                    'font' => 'arial',
                    'colorscheme' => 'light',
                    'action' => 'like',
                ),
                '<meta property="ob:url" content="{{ app.request.uri }}" />' . PHP_EOL,
            ),
            array(
                '{
                    "like" : {
                        "url" : "path/to/facebook",
                        "send" : false,
                        "layout" : "standard",
                        "width" : 450,
                        "show_faces" : true,
                        "font" : "arial",
                        "colorscheme" : "light",
                        "action" : "like"
                     },
                     "graph" : {
                        "url" : "path/to/facebook",
                        "title" : "the title",
                        "type" : "",
                        "image" : "",
                        "site_name" : "",
                        "admins" : ""
                    }
                }',
                array(
                    'url' => 'path/to/facebook',
                    'send' => false,
                    'layout' => 'standard',
                    'width' => 450,
                    'show_faces' => true,
                    'font' => 'arial',
                    'colorscheme' => 'light',
                    'action' => 'like',
                ),
                '<meta property="ob:url" content="path/to/facebook" />' . PHP_EOL .
                '<meta property="ob:title" content="the title" />' . PHP_EOL,
            ),
            array(
                '{
                    "like" : {
                        "url" : "",
                        "layout" : "standard",
                        "width" : 450,
                        "font" : "arial",
                        "colorscheme" : "light",
                        "action" : "like"
                     },
                     "graph" : {
                        "url" : "",
                        "title" : "the title",
                        "type" : "the type",
                        "image" : "",
                        "site_name" : "",
                        "admins" : ""
                    }
                }',
                array(
                    'url' => '{{ app.request.uri }}',
                    'send' => false,
                    'layout' => 'standard',
                    'width' => 450,
                    'show_faces' => false,
                    'font' => 'arial',
                    'colorscheme' => 'light',
                    'action' => 'like',
                ),
                '<meta property="ob:url" content="{{ app.request.uri }}" />' . PHP_EOL .
                '<meta property="ob:title" content="the title" />' . PHP_EOL .
                '<meta property="ob:type" content="the type" />' . PHP_EOL,
            ),
            array(
                '{
                    "like" : {
                        "url" : "path/to/facebook",
                        "send" : true,
                        "layout" : "button_count",
                        "width" : 225,
                        "show_faces" : true,
                        "font" : "thaoma",
                        "colorscheme" : "dark",
                        "action" : "reccomend"
                     },
                     "graph" : {
                        "url" : "path/to/facebook",
                        "title" : "the title",
                        "type" : "the type",
                        "image" : "the image",
                        "site_name" : "http://alphalemon.com",
                        "admins" : "123456789"
                    }
                }',
                array(
                    'url' => 'path/to/facebook',
                    'send' => true,
                    'layout' => 'button_count',
                    'width' => 225,
                    'show_faces' => true,
                    'font' => 'thaoma',
                    'colorscheme' => 'dark',
                    'action' => 'reccomend',
                ),
                '<meta property="ob:url" content="path/to/facebook" />' . PHP_EOL .
                '<meta property="ob:title" content="the title" />' . PHP_EOL .
                '<meta property="ob:type" content="the type" />' . PHP_EOL .
                '<meta property="ob:image" content="the image" />' . PHP_EOL .
                '<meta property="ob:site_name" content="http://alphalemon.com" />' . PHP_EOL .
                '<meta property="fb:admins" content="123456789" />' . PHP_EOL,
            ),
        );
    }
    
    private function initBlock($value)
    {
        $block = $this->getMock('AlphaLemon\AlphaLemonCmsBundle\Model\AlBlock');
        $block->expects($this->once())
              ->method('getContent')
              ->will($this->returnValue($value));

        return $block;
    }
    
    private function initForm()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                    ->disableOriginalConstructor()
                    ->getMock();
        $form->expects($this->once())
            ->method('createView')
        ;
        
        return $form;
    }
}
