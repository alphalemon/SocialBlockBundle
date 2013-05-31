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

namespace AlphaLemon\Block\SocialBlockBundle\Core\Sdk;

use Symfony\Component\Templating\EngineInterface;

/**
 * Description of SdkFacebook
 *
 * @author alphalemon
 */
abstract class SdkBase implements SdkInterface
{
    protected $templating;
    
    /**
     * Constructor
     * 
     * @param \Symfony\Component\Templating\EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
}