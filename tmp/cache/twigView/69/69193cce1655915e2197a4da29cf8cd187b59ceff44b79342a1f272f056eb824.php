<?php

/* /Applications/MAMP/htdocs/droppert/vendor/cakephp/bake/src/Template/Bake/Layout/default.twig */
class __TwigTemplate_7601d66d8c74e43aaffd24d98de24eb0f17e93376182ccb894bfee89d756334b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_eb2245a927a7e5531a18d5c7920662e86045d3bf0d6f49cf8498b84eec8af7ff = $this->env->getExtension("WyriHaximus\\TwigView\\Lib\\Twig\\Extension\\Profiler");
        $__internal_eb2245a927a7e5531a18d5c7920662e86045d3bf0d6f49cf8498b84eec8af7ff->enter($__internal_eb2245a927a7e5531a18d5c7920662e86045d3bf0d6f49cf8498b84eec8af7ff_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "/Applications/MAMP/htdocs/droppert/vendor/cakephp/bake/src/Template/Bake/Layout/default.twig"));

        // line 16
        echo $this->getAttribute(($context["_view"] ?? null), "fetch", array(0 => "content"), "method");
        
        $__internal_eb2245a927a7e5531a18d5c7920662e86045d3bf0d6f49cf8498b84eec8af7ff->leave($__internal_eb2245a927a7e5531a18d5c7920662e86045d3bf0d6f49cf8498b84eec8af7ff_prof);

    }

    public function getTemplateName()
    {
        return "/Applications/MAMP/htdocs/droppert/vendor/cakephp/bake/src/Template/Bake/Layout/default.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  22 => 16,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{#
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
#}
{{ _view.fetch('content')|raw }}", "/Applications/MAMP/htdocs/droppert/vendor/cakephp/bake/src/Template/Bake/Layout/default.twig", "");
    }
}
