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
        $skdContents = array();
        foreach($this->sdkCollection as $sdk)
        {
            $skdContents[$sdk->getReplacedTag()][] = $sdk->render($event);
        }
        
        if ( ! empty($skdContents)) {
            $response = $event->getResponse();
            $content = $response->getContent(); 
            foreach($skdContents as $tag => $skdContent) {
                $regex = sprintf('/%s/si', preg_quote($tag, '/'));
                $finalContent = implode(PHP_EOL, $skdContent);
                $finalContent = (strpos($tag, '/') === false) ? $tag . $finalContent : $finalContent . $tag;
                $content = preg_replace($regex , $finalContent, $content);
            }
            
            $response->setContent($content);
        }
    }
}
