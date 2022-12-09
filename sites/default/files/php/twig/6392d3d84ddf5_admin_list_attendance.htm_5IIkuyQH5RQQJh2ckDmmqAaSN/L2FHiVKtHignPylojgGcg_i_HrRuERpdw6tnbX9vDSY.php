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

/* modules/custom/user_module/templates/admin_list_attendance.html.twig */
class __TwigTemplate_8677b0a940170459f6c01c4fe576f5b5c570125273b323768d23c72c3a02ab48 extends \Twig\Template
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
        echo "<!DOCTYPE html>

<html>
    <head>
      
  <link href=\"https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css\" rel=\"stylesheet\" />
<link href=\"https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css\" rel=\"stylesheet\" />
<link rel=\"stylesheet\" type=\"text/css\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">
<style>
    .dataTables_length {
    position: relative;
    float: none !important;
    text-align: center;
}
</style>
<script src=\"https://code.jquery.com/jquery-3.5.1.js\" type=\"text/javascript\"></script>

<script type=\"application/javascript\" src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>
<script src=\"https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js\"></script>
<script src=\"https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js\"></script>
<script src=\"https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js\"></script>
<script src=\"https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js\"></script>
<script src=\"https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js\"></script>
        
<script src=\"https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js\"></script>

      
    <div class=\"container\">
        <label><span>Start date:</span></label>
        <input id=\"startdate\" type=\"text\">
        <label><span>End date:</span></label>
<input id=\"enddate\" type=\"text\" />
    <div class=\"\">
        <h1>Administrator Attendance Log</h1>
            <div class=\"\">
                <table id=\"table\" >
                <thead>
                    <tr><th>Name</th>
                        <th>Emp Id</th>
                        <th>Date</th>
                        <th>InTime</th>
                        <th>OutTine</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
<script type=\"text/javascript\">

\$(document).ready(function () {

        var mainurl = \"/restapi/admin/attendance\";

        var myDataTable = \$('#table').DataTable({
         \"bProcessing\": true,
         \"serverSide\": true,
         \"lengthMenu\": [10, 25, 50, 75, 100],
         \"ajax\":{
            url :mainurl, // json
            type: \"get\",  // type of method
            \"data\": function (data) {
                var startdate = \$('#startdate').val();
                data.startdate = startdate;
                var enddate = \$('#enddate').val();
                data.enddate = enddate;
            },
            error: function(){  
            }
          },
          columns: [
            {data: 'name', title: 'Name'},
            {data: 'empid', title: 'Emp Id'},
            {data: 'punchDate', title: 'PunchDate'},
            {data: 'InTime', title: 'InTime',
            \"render\": function(data, type, row, meta){
                if(type === 'display' && row.InTime=='00:00:00'){
                    data = '';
                    }
                return data;
                }
            
        },
            {data: 'OutTime', title: 'OutTime'},
            {data: 'status', title: 'Status'}
            
           
        ],
          dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Empoyee Attendance',
                        text:'Export to excel',
                        //Columns to export
                        exportOptions: {
                           columns: [0, 1, 2, 3,4]
                       }
                    }
                ],
          rowReorder: true,
          columnDefs: [
            { orderable: true, className: 'reorder', targets: 2 },
            { orderable: false, targets: '_all' }
            ]
        });

        // I instantiate the two datepickers here, instead of all at once like before.
// I also changed the dateFormat to match the format of the dates in the data table.
\$(\"#startdate\").datepicker({
  \"dateFormat\": \"yy-mm-dd\",
  \"onSelect\": function(date) {  // This handler kicks off the filtering.
    minDateFilter = new Date(date).getTime();
    myDataTable.draw(); // Redraw the table with the filtered data.
  }
}).keyup(function() {
  minDateFilter = new Date(this.value).getTime();
  myDataTable.draw();
});

\$(\"#enddate\").datepicker({
  \"dateFormat\": \"yy-mm-dd\",
  \"onSelect\": function(date) {
    maxDateFilter = new Date(date).getTime();
    myDataTable.draw();
  }
}).keyup(function() {
  maxDateFilter = new Date(this.value).getTime();
  myDataTable.draw();
});

// The below code actually does the date filtering.
minDateFilter = \"\";
maxDateFilter = \"\";

\$.fn.dataTableExt.afnFiltering.push(
  function(oSettings, aData, iDataIndex) {
    if (typeof aData._date == 'undefined') {
      aData._date = new Date(aData[3]).getTime(); // Your date column is 3, hence aData[3].
    }

    if (minDateFilter && !isNaN(minDateFilter)) {
      if (aData._date < minDateFilter) {
        return false;
      }
    }

    if (maxDateFilter && !isNaN(maxDateFilter)) {
      if (aData._date > maxDateFilter) {
        return false;
      }
    }

    return true;
  }
);



});
</script>";
    }

    public function getTemplateName()
    {
        return "modules/custom/user_module/templates/admin_list_attendance.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "modules/custom/user_module/templates/admin_list_attendance.html.twig", "D:\\Projects\\drupal\\hrms\\hrms\\modules\\custom\\user_module\\templates\\admin_list_attendance.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array();
        static $filters = array();
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
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
