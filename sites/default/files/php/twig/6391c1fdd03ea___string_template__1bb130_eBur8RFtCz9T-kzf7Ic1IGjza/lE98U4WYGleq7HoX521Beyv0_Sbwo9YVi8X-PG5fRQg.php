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

/* __string_template__1bb130fc334b27c14ddcebe0ff8b469c35ce69a3c67ead248c3e764ded20c9a1 */
class __TwigTemplate_b6827b2e867bd12cf59971cc7487e97512969a00f8241c948e322a36c491c705 extends \Twig\Template
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
        $context["dt2"] = "2012-01-01 06:25:00";
        // line 2
        $context["dt1"] = "2012-01-01 05:25:00";
        // line 3
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\user_module\CustomTwigExtension']->work_hours_diff($this->sandbox->ensureToStringAllowed(($context["dt1"] ?? null), 3, $this->source), $this->sandbox->ensureToStringAllowed(($context["dt2"] ?? null), 3, $this->source)), "html", null, true);
    }

    public function getTemplateName()
    {
        return "__string_template__1bb130fc334b27c14ddcebe0ff8b469c35ce69a3c67ead248c3e764ded20c9a1";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 3,  41 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "__string_template__1bb130fc334b27c14ddcebe0ff8b469c35ce69a3c67ead248c3e764ded20c9a1", "");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 1);
        static $filters = array("escape" => 3);
        static $functions = array("work_hours_diff" => 3);

        try {
            $this->sandbox->checkSecurity(
                ['set'],
                ['escape'],
                ['work_hours_diff']
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
