<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* __string_template__bade21e2c31c7c8afb56edf462fff513d1b31193d2a55014b8dd540fc0c0cc2a */
class __TwigTemplate_8bd547203c50628d361d2ef42461f554ec2dbe4262461ed127fe075c7bcd1402 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<a href=\"/my/all-attendance/export/";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["raw_arguments"] ?? null), "uid", [], "any", false, false, true, 1), 1, $this->source), "html", null, true);
        echo "?";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\user_module\CustomTwigExtension']->current_uri_param(), "html", null, true);
        echo "&page&_format=xlsx\" class=\"button button--action button--primary right mr-right-4\">Download Timesheet</a>";
    }

    public function getTemplateName()
    {
        return "__string_template__bade21e2c31c7c8afb56edf462fff513d1b31193d2a55014b8dd540fc0c0cc2a";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__bade21e2c31c7c8afb56edf462fff513d1b31193d2a55014b8dd540fc0c0cc2a", "");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 1);
        static $functions = array("current_uri_param" => 1);

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape'],
                ['current_uri_param']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}