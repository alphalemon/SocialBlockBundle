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

use AlphaLemon\Block\SocialBlockBundle\Core\Sdk\SdkTwitter;

/**
 * LanguagesFormTest
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 */
class SdkTwitterTest extends SdkBase
{  
    /**
     * @dataProvider sdkProvider()
     */
    public function testRender($responseContent, $expectedResult)
    {   
        $expectedCall = (empty($expectedResult)) ? 0 : 1;
        $this->init($responseContent, $expectedResult, $expectedCall);
            
        $sdk = new SdkTwitter($this->templating);
        $sdk->render($this->event);
    }
    
    public function sdkProvider()
    {
        return array(
            array(
                'Page contents',
                '',
            ),
            array(
                'Page contents<div class="twitter-share"></div>Page contents',
                'twitter share button',
            ),
        );
    }
}