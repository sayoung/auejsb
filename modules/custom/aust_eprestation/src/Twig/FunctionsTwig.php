<?php
namespace Drupal\core_leyton\Twig;

use Drupal\block\Entity\Block;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FunctionsTwig extends \Twig_Extension {



    /**
     * List of all Twig functions
     */
    public function getFunctions() {
        return array(
           new \Twig_SimpleFunction('geteMailTechnicien',
                array($this, 'geteMailTechnicien'),
                array('is_safe' => array('html')
                )),
           new \Twig_SimpleFunction('geteMailcomptable',
                array($this, 'geteMailcomptable'),
                array('is_safe' => array('html')
                ))
        );
    }
	    /**
     * adres mail de comptable 
     */
    public function geteMailcomptable() {
        return 'mourad.dardari@gmail.com';
    }
   	    /**
     * adres mail de technicien 
     */
    public function geteMailTechnicien() {
        return'everyhere010@gmail.com';
    }

}