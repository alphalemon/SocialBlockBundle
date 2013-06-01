<?php
/**
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

namespace AlphaLemon\Block\SocialBlockBundle\Tests\Unit\Core\Sdk;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;

/**
 * LanguagesFormTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
abstract class SdkBase extends TestCase
{    
    protected $templating;
    protected $event;
    
    protected function init($responseContent, $expectedResult, $expectedCall)
    {
        $this->event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;        
        
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $response
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($responseContent))
        ;
        
        $this->event
            ->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response))
        ;
        
        $this->templating = $this->getMock('Symfony\Component\Templating\EngineInterface'); 
        $this->templating
            ->expects($this->exactly($expectedCall))
            ->method('render')
            ->will($this->returnValue($expectedResult))
        ;        
    }
}
