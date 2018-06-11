<?php
//============================================================+
// File name   : example_006.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 006 for TCPDF class
//               WriteHTML and RTL support
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
// require_once('tcpdf_include.php');
require_once 'TCPDF/tcpdf.php';
$id=1;
if(isset($_GET['id']))
  $id=$_GET['id'];
require_once 'connectMysql2.php';


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 006');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
//
// // set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//
// // set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//
// // set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//
// // set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//
// // set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//
// // set some language-dependent strings (optional)
// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
// 	require_once(dirname(__FILE__).'/lang/eng.php');
// 	$pdf->setLanguageArray($l);
// }

// ---------------------------------------------------------

// set font
$pdf->SetFont('msungstdlight','',16);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content


// output the HTML content
// $pdf->writeHTML($html, true, false, true, false, '');


// output some RTL HTML content
$query_project="SELECT * FROM `project` WHERE id=$id";
$query_pro_task="SELECT * FROM `pro_task` WHERE num=$id";
$query_pro_task_finish="SELECT * FROM `pro_task` WHERE num=$id AND finish=1";
$data=$db_link->query($query_project)->fetch(PDO::FETCH_ASSOC);
$pro_task=$db_link->query($query_pro_task)->fetchAll(PDO::FETCH_ASSOC);
$finish_num=count($db_link->query($query_pro_task_finish)->fetchAll(PDO::FETCH_ASSOC));
$unfinish_num=count($pro_task)-$finish_num;

// test some inline CSS
$html = '
<style>

.information p{
  width: 300px;
}
.task{
  display: inline-block;
  width: 60%;
  margin-left: 15px;
}
.checked{
  text-decoration: line-through;
  color: red;
}
</style>
<div class="right_panel border">
  <div>
    <div style="display: inline-block; width:50%;">
      <div class="information">
        <span class="input">編號:</span>
        <span class="text-left input" >'.$data['id'].'</span>
      </div>

      <div class="information">
        <span class="input">專案名:</span>
        <span class="text-left input" >'.$data['name'].'</span>
      </div>
      <div class="information">
        <span class="input">開始時間:</span>
        <span class="text-left input" id="start_day">'.$data['created'].'</span>
      </div>
      <div class="information">
        <span class="input">預計時間:</span>
        <span class="text-left input" id="due_day">'.$data['due_date'].'</span>
      </div>
      <div class="information">
        <span class="input">完成時間:</span>
        <span class="text-left input" id="finish_day">'.$data['finish_date'].'</span>
      </div>
    </div>

    <div style="display: inline-block;">
      <div class="information">
        <span class="input">完成任務:</span>
        <span class="text-left input" id="complete_task">'.$finish_num.'</span>
      </div>
      <div class="information">
        <span class="input">未完成任務:</span>
        <span class="text-left input" id="uncomplete_task">'.$unfinish_num.'</span>
      </div>
    </div>
  </div>
  <hr>

</div>
';

$pdf->writeHTML($html, true, false, true, false, '');
$i=1;
// $pdf->SetXY(15, 150);
foreach ($pro_task as $task) {
  $content=($task['finish']=='1')?"完成":"未完成";
  $pdf->write(10,"$content","","0","R");
  $content=
  "<style>
  .information p{
    width: 300px;
  }
  .task{
    width: 60%;
  }
  .checked{
    text-decoration: line-through;
    color: red;
  }
  </style>
  <div style=\" width:100%; ".($task['finish']=='1'?"color:red;":'')."\" >
    <span class=\"input\" style=\"text-align:right; width:65px; \">".($i++)."</span>
    <span class=\"input task\" style=\"text-align:right;width: 60%\">".$task['txt']."</span></div><br />";
    $pdf->writeHTML($content, true, false, true, false, '');
    // $content.="<br />";
    // $pdf->writeHTML($content, true, false, true, false, '');
}

// reset pointer to the last page
$pdf->lastPage();



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

//Close and output PDF document
$pdf->Output('example_006.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
