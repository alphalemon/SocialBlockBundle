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

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerContainer;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\JsonBlock\AlBlockManagerJsonBlock;

/**
 * Implements the Block Manager to manage the facebook like button
 */
class AlBlockManagerFacebookLikeButton extends AlBlockManagerContainer
{
    /**
     *  @{inheritdoc}
     */
    public function getDefaultValue()
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
        
        return array('Content' => $value);
    }
    
    /**
     *  @{inheritdoc}
     */
    protected function renderHtml()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        $options = $items["like"]; 
        
        if ($options['url'] == "") {
            $options['url'] = "{{ app.request.uri }}";
        }
        
        $options = $this->fixBooleanConversion($options);
        
        return array('RenderView' => array(
            'view' => 'SocialBlockBundle:Content:facebook_like.html.twig',
            'options' => array(
                'options' => $options,
            ),
        ));
    }
    
    /**
     *  @{inheritdoc}
     */
    public function getMetaTags()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());
        
        $result = "";
        $metatags = $items["graph"]; 
        foreach ($metatags as $metatag => $value) {
            if ($metatag == 'url' && empty($value)) {
                $value = "{{ app.request.uri }}";
            } 
            
            if ($metatag == 'title' && empty($value) && null !== $this->pageTree) {                
                $value = $this->pageTree->getMetaTitle();
            } 
            
            if (empty($value)) {
                continue;
            }
            
            $prefix = ($metatag != 'admins') ? 'ob' : 'fb';            
            $result .= sprintf('<meta property="%s:%s" content="%s" />' . PHP_EOL, $prefix, $metatag, $value);
        }
        
        return $result;
    }
    
    /**
     *  @{inheritdoc}
     */
    public function editorParameters()
    {
        $items = AlBlockManagerJsonBlock::decodeJsonContent($this->alBlock->getContent());

        $formFactory = $this->container->get('form.factory');
        $likeForm = $formFactory->create($this->container->get('facebook_like.form'), $this->fixBooleanConversion($items["like"]));
        $graphForm = $formFactory->create($this->container->get('facebook_open_graph.form'), $items["graph"]);

        return array(
            "template" => "SocialBlockBundle:Editor:facebook_like_editor.html.twig",
            "title" => "Facebook Like Button Editor",
            "form_name" => "al_facebook_form",
            "form_like" => $likeForm->createView(),
            "form_open_graph" => $graphForm->createView(),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function edit(array $values)
    {
        $fixedValues = $this->convertSerializedDataToJson($values);

        return parent::edit($fixedValues);
    }
    
    /**
     * Fixes the json passed by the ajax transaction
     * 
     * @param array $values
     * @return array
     */
    protected function convertSerializedDataToJson(array $values)
    {
        if (array_key_exists('Content', $values)) {
            $unserializedData = array();
            $serializedData = $values['Content'];
            parse_str($serializedData, $unserializedData);
            
            $like = $unserializedData["al_like"];
            $graph = $unserializedData["al_open_graph"];            
            $content = array(
                "like" => $like,
                "graph" => $graph,
            );

            $values['Content'] = json_encode($content);
        }
        
        return $values;
    }


    /**
     *  Fixes the boolean values handled as strings by json
     */
    protected function fixBooleanConversion($item)
    {
        if ( ! array_key_exists('send', $item)) {
            $item['send'] = false;
        } else {
            $item['send'] = filter_var($item['send'], FILTER_VALIDATE_BOOLEAN);
        }
        
        if ( ! array_key_exists('show_faces', $item)) {
            $item['show_faces'] = false;
        } else {
            $item['show_faces'] = filter_var($item['show_faces'], FILTER_VALIDATE_BOOLEAN);
        }
        
        return $item;
    }
}
