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

/* modules/custom/automail/templates/mail-for-user.html.twig */
class __TwigTemplate_53b23a7e3d673e75d1b5291d03ec86b56d96129847f8527493afdf3ae4a803a5 extends \Twig\Template
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
        echo "<!doctype html>
<html>
  <head>
    <meta charset=\"utf-8\">
    <title>autocaravanalgarve</title>
    <meta name=\"viewport\" content=\"width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\">
    <meta http-equiv=\"Content-Type\" content=\"text/html charset=UTF-8\" />
    <style type=\"text/css\">
      body {
        font-family: Arial;
      }
      img {
        height: auto;
        max-width: 100%;
      }
      .para {
        font-size: 16px;
        color: #282828;
      }
      @media only screen and (max-width:660px) {
        table {
          width: 100% !important;
        }
        .w20 {
          width: 10px !important;
        }
        .d-block{
          display: block !important; 
          width: 100% !important;
        }
        .h10{
          height: 10px;
        }
      }
      @media only screen and (max-width:480px) {
        .h10 {
          height: 10px !important;
        }
        .para {
          font-size: 15px !important;
        }
        .h15 {
          height: 15px !important;
        }
      }
    </style>
   </head>
   <body style=\"background-color:#242424; margin:0;\">
      <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width:100%; text-align:center;  background-color: #282828; margin: 0 auto; border-collapse: collapse;\">
         <tr>
            <td>
               <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\"width: 1000px; background-color: #fff; text-align:center; margin: 0 auto; border-collapse: collapse;\">
                  <tr>
                     <td style=\"height: 4px; border-top: 4px solid #f29000;\" colspan=\"3\"></td>
                  </tr>
                  <tr>
                     <td colspan=\"3\">
                        <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\" text-align:center; margin: 0 auto; border-collapse: collapse; width: 100%;\">
                           <tr>
                              <td style=\"width: 30px;\" class=\"w20\"></td>
                              <td>
                                 <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\" text-align:left; margin: 0 auto; border-collapse: collapse; width: 100%;\">
                                    <tr>
                                       <td style=\"height: 20px;\" class=\"h10\"></td>
                                    </tr>
                                    <tr>
                                       <td style=\"float: right;\">
                                          <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" style=\" text-align:left; margin: 0 auto; border-collapse: collapse; width: 100%;\">
                                             <tr>
                                                <td style=\"font-size: 18px; color: #fff; font-weight: bold; background: #f29000; width: 115px; height: 50px; text-align: center; padding: 0 10px;\">";
        // line 70
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, twig_date_format_filter($this->env, "now", "d-m-Y"), "html", null, true);
        echo "</td>
                                             </tr>
                                             <tr>
                                                <td style=\"height: 20px;\"></td>
                                             </tr>
                                          </table>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                              <td style=\"width: 30px;\" class=\"w20\"></td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td style=\"width: 30px; text-align: left\" class=\"w20\"></td>
                     <td class=\"para\" style=\"font-size: 15px; color: #282828; line-height: 25px; text-align: left; padding: 0 10px;\">
                        ";
        // line 88
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->sandbox->ensureToStringAllowed(($context["mail_data"] ?? null), 88, $this->source));
        echo "
                     </td>
                     <td style=\"width: 30px;\" class=\"w20\"></td>
                  </tr>
                  <tr>
                     <td style=\"height: 50px;\" colspan=\"3\"></td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "modules/custom/automail/templates/mail-for-user.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  131 => 88,  110 => 70,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/automail/templates/mail-for-user.html.twig", "D:\\Projects\\drupal\\hrms\\hrms\\modules\\custom\\automail\\templates\\mail-for-user.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array("escape" => 70, "date" => 70, "raw" => 88);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 'date', 'raw'],
                []
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
