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

namespace AlphaLemon\Block\ImageBundle\Tests\Unit\Core\Form;

use AlphaLemon\AlphaLemonCmsBundle\Tests\TestCase;
use AlphaLemon\Block\SocialBlockBundle\Core\Listener\RenderSdk;

/**
 * LanguagesFormTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class RenderSdkListenerTest extends TestCase
{
    public function testOnKernelResponseDoesNothingWhenAnySdkIsProvided()
    {
        $sdkCollection = $this->getMockBuilder('AlphaLemon\Block\SocialBlockBundle\Core\SdkCollection\SdkCollection')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;        
        
        $event
            ->expects($this->never())
            ->method('getResponse')
        ;
    
        $listener = new RenderSdk($sdkCollection);
        $listener->onKernelResponse($event);
    }
    
    /**
     * @dataProvider sdkProvider()
     */
    public function testOnKernelResponse($skds, $responseContent, $expectedResult)
    {
        $sdkCollection = $this->getMockBuilder('AlphaLemon\Block\SocialBlockBundle\Core\SdkCollection\SdkCollection')
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->iterate($sdkCollection, $skds);
        
        
        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\FilterResponseEvent')
            ->disableOriginalConstructor()
            ->getMock()
        ;        
        
        $response = $this->getMock('Symfony\Component\HttpFoundation\Response');
        $response
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($responseContent));
        ;
        
        $response
            ->expects($this->once())
            ->method('setContent')
            ->with($expectedResult);
        ;
        
        $event
            ->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($response));
        ;
    
        $listener = new RenderSdk($sdkCollection);
        $listener->onKernelResponse($event);
    }
    
    public function sdkProvider()
    {
        return array(
            array(
                array(
                    $this->initSdk('<script>a rendered sdk</script>'),
                ),
                'Page contents',
                'Page contents',
            ),
            array(
                array(
                    $this->initSdk('<script>a rendered sdk</script>'),
                ),
                '<boby>Page contents</body>',
                '<boby>Page contents<script>a rendered sdk</script></body>',
            ),
            array(
                array(
                    $this->initSdk('<script>a rendered sdk</script>'),
                    $this->initSdk('<script>another rendered sdk</script>'),
                ),
                '<boby>Page contents</body>',
                "<boby>Page contents<script>a rendered sdk</script>\n<script>another rendered sdk</script></body>",
            ),
        );
    }
    
    private function initSdk($content)
    {
        $sdk = $this->getMock('AlphaLemon\Block\SocialBlockBundle\Core\Sdk\SdkInterface');
        $sdk
            ->expects($this->once())
            ->method('render')
            ->will($this->returnValue($content));
        ;
        
        return $sdk;
    }
    
    private function iterate($sdkCollection, $skds)
    {
        $sdkCollection->expects($this->at(0))
             ->method('rewind');

        $sequence = 1;
        foreach($skds as $skd)
        {
            $sdkCollection->expects($this->at($sequence))
                 ->method('valid')
                 ->will($this->returnValue(true));
            $sequence++;
            
            $sdkCollection->expects($this->at($sequence))
                 ->method('current')
                 ->will($this->returnValue($skd));
            $sequence++;
            
            $sdkCollection->expects($this->at($sequence))
                 ->method('next');
            $sequence++;
        }
    }
}
