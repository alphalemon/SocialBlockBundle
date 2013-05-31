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

namespace AlphaLemon\Block\SocialBlockBundle\Core\Form\Twitter;

use AlphaLemon\AlphaLemonCmsBundle\Core\Form\JsonBlock\JsonBlockType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Implements the form to manage the facebook like button attributes
 */
class TwitterShareType extends JsonBlockType
{
    /**
     *  @{inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder->add('url');
        $builder->add('text', 'textarea');
        $builder->add('via');
        $builder->add('related');
        $builder->add('hashtags');
        $builder->add('size', 'choice', array('choices' => array('' => 'small', 'large' => 'large')));
        $builder->add('dnt', 'choice', array('choices' => array('' => 'false', 'true' => 'true')));
        $builder->add('count', 'choice', array('choices' => array('horizontal' => 'horizontal', 'vertical' => 'vertical', 'none' => 'false')));
        $builder->add('lang');                
    }
    
    /**
     *  @{inheritdoc}
     */
    public function getDefaultOptions(array $options)
    {
        $values = parent::getDefaultOptions($options);
        
        return $values;
    }
}
