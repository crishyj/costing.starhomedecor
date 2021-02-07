<?php require_once('../Connections/StarCreations.php'); 

if (!isset($_SESSION)) {
  session_start();
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_products = "-1";
if (isset($_GET['rpt'])) {
  $colname_products = $_GET['rpt'];
}
mysql_select_db($database_StarCreations, $StarCreations);
$query_products = sprintf("SELECT p.ProductID, p.ProductCode, p.ProductName, p.PHeight, p.PWidth, p.Dimensions, p.Cost, p.Price, p.Percentage, p.Multiplier, p.PrintPublisher, p.PrintSize, p.Discontinued, p.CustomerIDs, p.CreateDate, p.AlterDate, p.ProductInfo, p.Tags, p.CreatedBy, p.ModifiedBy, p.TotalCost, p.TotalPrice, p.Confirmed, p.PrintNumber, p.PrintTitle, p.PrintArtist, p.Mat1, p.Mat2, p.Mat3, p.Mat4, p.Molding, p.InnerMolding, p.Reports 
FROM Products AS p 
WHERE p.Reports LIKE %s 
ORDER BY p.CreateDate DESC", GetSQLValueString("%".$colname_products."%", "text"));
$products = mysql_query($query_products, $StarCreations) or die(mysql_error());
$row_products = mysql_fetch_assoc($products);
$totalRows_products = mysql_num_rows($products);


/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$rptName = $row_products['Reports'];

$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri')->setSize(8);

$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

$objPHPExcel->getActiveSheet()->getStyle("A1:U1")->getFont()->setBold(true)->applyFromArray($styleArray);

$objPHPExcel->getProperties()->setCreator($_SESSION['MM_UserFullName'])
							 ->setLastModifiedBy($_SESSION['MM_UserFullName'])
							 ->setTitle($rptName)
							 ->setSubject($rptName)
							 ->setDescription($rptName)
							 ->setKeywords($rptName)
							 ->setCategory($rptName);

// Add some data




$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Item Number')
            ->setCellValue('B1', 'Publisher')
            ->setCellValue('C1', 'Print Number')
            ->setCellValue('D1', 'Artist')
			->setCellValue('E1', 'Print Title')
			->setCellValue('F1', 'Component')
			->setCellValue('G1', 'Special')
			->setCellValue('H1', 'Molding')
			->setCellValue('I1', "Mat1")
			->setCellValue('J1', "Mat2")
			->setCellValue('K1', "Mat3")
			->setCellValue('L1', "Mat4")
			->setCellValue('M1', "Art Size")
			->setCellValue('N1', "Glass Size")
			->setCellValue('O1', "O.D.")
			->setCellValue('P1', "Total Images")
			->setCellValue('Q1', "Case Pack")
			->setCellValue('R1', "Min. Sell")
			->setCellValue('S1', "Cost")
			->setCellValue('T1', "Comments");

 $row = 2;
           do {

mysql_select_db($database_StarCreations, $StarCreations);
$query_components = "SELECT ComponentID, ProductID, ComponentName, DimentionsModifier, Quantity, UnitOfMeasure, UnitCost, ExtendedCost, CreateDate, AlterDate, ComponentInfo, Scrap, Report FROM Components WHERE ProductID = ".$row_products['ProductID']." AND Report = '1' ORDER BY ComponentName ASC";
$components = mysql_query($query_components, $StarCreations) or die(mysql_error());
$row_components = mysql_fetch_assoc($components);
$totalRows_components = mysql_num_rows($components); 


$componentsresult = '';
if ($row_components['ComponentName']<>"") {
do {
	$componentsresult .= $row_components['ComponentName'] ." - $".$row_components['UnitCost'];
	if ($row_components['ComponentInfo'] <> "") {
	   $componentsresult .= "\n".$row_components['ComponentInfo'];
	}
	$componentsresult .= "\n\n";
	} while ($row_components = mysql_fetch_assoc($components));  
} else {
	$componentsresult = 'n/a';
}
$molding = '';
if ($row_products['InnerMolding']<>"" && $row_products['Molding']<>"") {
do {
	$molding .= "I: ".$row_products['InnerMolding'] ."\nO: ".$row_products['Molding']. "\n";
	} while ($row_components = mysql_fetch_assoc($components));  
} else {
	$molding = $row_products['InnerMolding'] . $row_products['Molding'];
}
$minsel = '';
if ($row_products['Multiplier']!="") {
	$minsel = $row_products['TotalPrice'];
}else{
	$minsel = $row_products['Price'];
}

	
           	$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, $row_products['ProductCode'])
			->setCellValue('B' . $row, $row_products['PrintPublisher'])
			->setCellValue('C' . $row, $row_products['PrintNumber'])
			->setCellValue('D' . $row, $row_products['PrintArtist'])
			->setCellValue('E' . $row, $row_products['PrintTitle'])
			->setCellValue('F' . $row, $componentsresult)
			->setCellValue('G' . $row, $row_products['ProductInfo'])
			->setCellValue('H' . $row, $molding)
			->setCellValue('I' . $row, $row_products['Mat1'])
			->setCellValue('J' . $row, $row_products['Mat2'])
			->setCellValue('K' . $row, $row_products['Mat3'])
			->setCellValue('L' . $row, $row_products['Mat4'])
			->setCellValue('M' . $row, $row_products['PrintSize'])
			->setCellValue('N' . $row, $row_products['PHeight']."x".$row_products['PWidth'])
			->setCellValue('O' . $row, "")
			->setCellValue('P' . $row, "")
			->setCellValue('Q' . $row, "")
			->setCellValue('R' . $row, $minsel)
			->setCellValue('S' . $row, "")
			->setCellValue('T' . $row, "");
			

$objPHPExcel->getActiveSheet()->getStyle("A".$row.":T".$row."")->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getStyle("A".$row.":T".$row."")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);		
$objPHPExcel->getActiveSheet()->getStyle("A".$row.":T".$row."")->getAlignment()->setWrapText(true);	
$objPHPExcel->getActiveSheet()->getStyle("R".$row)->getNumberFormat()->setFormatCode("$#,##0.00");	
$objPHPExcel->getActiveSheet()->getStyle("A".$row.":Q".$row."")->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
$objPHPExcel->getActiveSheet()->getStyle("S".$row.":T".$row."")->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
	
            $row++;
           } while ($row_products = mysql_fetch_assoc($products));



$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&B'. $rptName . '&RPrinted on &D');
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
foreach(range('A','T') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(False)->setWidth(40);	  


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);



// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $rptName .'.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

mysql_free_result($products);
mysql_free_result($components);
?>





