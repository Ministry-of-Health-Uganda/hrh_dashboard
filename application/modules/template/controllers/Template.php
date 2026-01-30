<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'template_model'
		));
		$this->watermark=FCPATH."assets/images/watermark.png";
	}
 
	public function layout($data)
	{  
		$id = $this->session->userdata('id');
		
		$data['setting'] = $this->template_model->setting();
		$this->load->view('layout', $data);
	}
 
	public function login($data)
	{ 
		$data['setting'] = $this->template_model->setting();
		$this->load->view('login', $data);
	}


public function makePdf($html,$filename,$action)
	{	

	 $this->load->library('ML_pdf');  //or i used ML_pdf for landscape
    
     ob_clean();
     
	$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
	$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
    $this->ml_pdf->pdf->showWatermarkImage = true;

	$this->ml_pdf->pdf->WriteHTML($PDFContent); 
	 
	//download it D save F.
	ob_end_clean();
	$this->ml_pdf->pdf->Output($filename,$action);

	}

	/**
	 * Stream PDF to browser without holding full content in memory.
	 * Writes PDF to a temp file then streams it in chunks for better performance on large reports.
	 */
	public function streamPdf($html, $filename)
	{
		$this->load->library('ML_pdf');
		ob_clean();

		$PDFContent = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
		$this->ml_pdf->pdf->SetWatermarkImage($this->watermark);
		$this->ml_pdf->pdf->showWatermarkImage = true;
		$this->ml_pdf->pdf->WriteHTML($PDFContent);

		$tempDir = sys_get_temp_dir();
		$tempFile = $tempDir . '/' . uniqid('audit_pdf_', true) . '.pdf';
		$this->ml_pdf->pdf->Output($tempFile, 'F');
		unset($this->ml_pdf->pdf);
		ob_end_clean();

		if (!is_file($tempFile) || !is_readable($tempFile)) {
			show_error('Failed to generate PDF.', 500);
			return;
		}

		$filesize = filesize($tempFile);
		header('Content-Type: application/pdf');
		header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename) . '"');
		header('Content-Length: ' . $filesize);
		header('Cache-Control: private, max-age=0, must-revalidate');
		header('Pragma: public');

		$chunkSize = 8192;
		$handle = fopen($tempFile, 'rb');
		if ($handle) {
			while (!feof($handle)) {
				echo fread($handle, $chunkSize);
				if (ob_get_level()) {
					ob_flush();
				}
				flush();
			}
			fclose($handle);
		}
		@unlink($tempFile);
		exit;
	}

	
	
    public function exportToExcel($exportData,$filename) {
        
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        if(is_array($exportData))
         $exportData=json_decode(json_encode($exportData), true);
        
        $isPrintHeader = false;
        
        foreach ($exportData as $row) {
            if (! $isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
        }
        exit();
    }
 
}
