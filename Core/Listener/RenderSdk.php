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

namespace AlphaLemon\Block\SocialBlockBundle\Core\Listener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use AlphaLemon\Block\SocialBlockBundle\Core\SdkCollection\SdkCollection;

/**
 * Renders the used SDKs at the end of the page
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class RenderSdk
{
    private $sdkCollection;
    
    /**
     * Constructor
     */
    public function __construct(SdkCollection $sdkCollection)
    {
        $this->sdkCollection = $sdkCollection;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $skdInterfaces = array();
        foreach($this->sdkCollection as $sdk)
        {
            $skdInterfaces[] = $sdk->render($event);
        }
        
        if ( ! empty($skdInterfaces)) {
            $response = $event->getResponse();
            $content = $response->getContent();
            $content = preg_replace('/\<\/body\>/si', implode(PHP_EOL, $skdInterfaces) . '</body>', $content);
            $response->setContent($content);
        }
    }
}
