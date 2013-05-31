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

namespace AlphaLemon\Block\SocialBlockBundle\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlockContainer;

/**
 * Implements the Block Manager to manage the twitter share button
 */
class AlBlockManagerTwitterShare extends AlBlockManagerJsonBlockContainer
{
    /**
     *  @{inheritdoc}
     */
    public function getDefaultValue()
    {
        $value =
            '{
                "0" : {
                    "url" : "",
                    "text" : "",
                    "via" : "",
                    "related" : "",
                    "hashtags" : "",
                    "size" : "",
                    "dnt" : "", 
                    "count" : "", 
                    "lang" : ""
                 }
            }';
        
        return array('Content' => $value);
    }
    
    /**
     *  @{inheritdoc}
     */
    public function editorParameters()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $item = $items[0];

        $formClass = $this->container->get('twitter_share.form');
        $shareForm = $this->container->get('form.factory')->create($formClass, $item);

        return array(
            "template" => "SocialBlockBundle:Editor:twitter_share_editor.html.twig",
            "title" => "Twitter Share Button Editor",
            "form" => $shareForm->createView(),
        );
    }
    
    /**
     *  @{inheritdoc}
     */
    protected function renderHtml()
    {
        $items = $this->decodeJsonContent($this->alBlock->getContent());
        $options = $items["0"]; 
        
        $data = array();
        foreach($options as $tag => $value)
        {
            if ( ! empty($value)) {
                $data[] = 'data-' . $tag . '="' . $value . '"';
            }
        }
        $tags = implode(" ", $data);
        
        return array('RenderView' => array(
            'view' => 'SocialBlockBundle:Content:twitter_share.html.twig',
            'options' => array('data' => $tags),
        ));
    }
}
