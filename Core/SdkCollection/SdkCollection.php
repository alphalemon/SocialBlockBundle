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

namespace AlphaLemon\Block\SocialBlockBundle\Core\SdkCollection;

use AlphaLemon\Block\SocialBlockBundle\Core\Sdk\SdkInterface;

/**
 * A Collection of SdkInterface objects
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class SdkCollection implements \Iterator, \Countable
{
    private $skd = array();
    
    /**
     * Adds a theme to the collections
     *
     * @param AlTheme $theme
     */
    public function addSdk(SdkInterface $sdk)
    {
        $this->skd[] = $sdk;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->skd);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->skd);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->skd);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        return reset($this->skd);
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return (current($this->skd) !== false);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->skd);
    }
}
