<?php
// generate_pdf.php

// Include TCPDF
require_once('TCPDF/src/Tcpdf.php');

// Create TCPDF instance
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Attendance Report');
$pdf->SetHeaderData('', '', 'Attendance Report', '', array(0, 64, 255), array(0, 64, 128));

// Add a page
$pdf->AddPage();

ob_start();  // Start output buffering to capture HTML content
// Include your HTML content here
include 'studentdata.php'; // Change this to the actual name of your attendance page
$html = ob_get_clean();  // Get the captured HTML content

// Write HTML to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF
$pdf->Output('attendance_report.pdf', 'I');  // 'I' sends the file inline to the browser
?>
