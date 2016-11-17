<?php
namespace Simi\TplEngine;

class PDFEngine extends \FPDF
{
	private $data;
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	
	public function __construct($data)
	{
		parent::__construct();
		$this->data = $data;
		$this->init();
		foreach ($this->data as $student) {
			$this->body($student);
		}
	}
	
	private function init()
	{
		define('FONT', 'Helvetica');
		define('LEFT', 20);
		define('TOP', 20);
		$this->SetMargins(LEFT, TOP);
		$this->AliasNbPages();
		$this->SetAutoPageBreak(false);
	}
	
	private function placeHeader() {
		$this->Image(current($this->data)['header']['logo_gencat'], 20, 10, 70);
		$this->Image(current($this->data)['header']['logo_jor'], 160, 10, 25);
		$this->SetFont(FONT, '', 14);
	}
	
	private function placeFooter($student)
	{
		$this->SetLineWidth(.5);
		$this->Line(LEFT, 276, LEFT + 170, 276);
		$this->SetY(276);
		$this->SetFont(FONT,'B',8);
		$this->SetTextColor(0);
		$this->WriteHTML($this->decode($student['footer']['caption']));
		$this->SetFont(FONT, 'I', 8);
		$this->SetTextColor(128);
		$this->Cell(1, 15, $this->decode($student['footer']['student'], 0, 0, 'C'));
		$this->SetFont(FONT, '', 10);
		$this->SetTextColor(0);
		$this->Cell(0, 12, $this->PageNo(), 0, 0, 'R');
	}
	
	private function body($student)
	{
		$this->AddPage();
		if (!($this->PageNo() % 2)) {
			$this->AddPage();
		}
		$this->placeHeader();
		$this->SetY(TOP + 10);
		$this->Write(0, mb_strtoupper($this->decode(($student['title'])), 'ISO-8859-1'));
		$this->SetTextColor(0x80);
		$this->Write(0, MB_strtoupper($this->decode(($student['degree']['name'])), 'ISO-8859-1'));
		$this->SetXY(LEFT, TOP + 25);
		$this->SetFont(FONT, 'B', 14);
		$this->SetTextColor(0);
		$this->Write(0, $this->decode($student['student']));
		$this->SetX(LEFT + 80);
		$this->SetFont(FONT, '', 11);
		$this->Write(0, $student['trimestre']);
		$this->SetX(LEFT + 115);
		$this->Write(0, $this->decode($student['classroom']));
		$this->SetX(LEFT + 140);
		$this->Write(0, $student['course']);
		$this->SetLineWidth(1);
		$this->Line(LEFT, TOP + 28, LEFT + 170, TOP + 28);
		$this->SetXY(LEFT, TOP + 33);
		$this->Write(0, 'Tutor/a: ' . $student['tutor']);
		$y = TOP + 25;
		foreach ($student['scopes'] as $scope) {
			$y += 15;
			if ($this->pageBreaksScope($scope, $y)) {
				$this->placeFooter($student);
				$this->AddPage();
				$this->placeHeader();
				$y = TOP + 10;
			}
			$this->SetFont(FONT, 'B', 11);
			$this->SetLineWidth(.5);
			$this->SetXY(LEFT, $y);
			$this->Write(10, MB_strtoupper($this->decode($scope['name']), 'ISO-8859-1'));
			$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
			$y += 4;
			foreach ($scope['areas'] as $area) {
				$y += 6;
				$this->SetFont(FONT, 'B', 11);
				$this->SetLineWidth(.2);
				$this->SetXY(LEFT, $y);
				$this->Write(10, $this->decode($area['name']));
				$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
				$this->SetFont(FONT, '', 6);
				$start = 142; $offset = 7;
				$this->SetXY(LEFT + $start, $y + 10);
				$this->Write(0, '1r t.');
				$this->SetX(LEFT + $start + $offset);
				$this->Write(0, '2n t.');
				$this->SetX(LEFT + $start + 2 * $offset);
				$this->Write(0, '3r t.');
				$this->SetX(LEFT + $start + 3 * $offset);
				$this->Write(0, 'Final');
				$y += 10;
				foreach ($area['dimensions'] as $dimension) {
					$y += 6;
					$this->setXY(LEFT + 3, $y);
					$this->SetFont(FONT, '', 11);
					$this->Write(0, $this->decode($dimension['name']));
					if (strlen($dimension['description']) > 0) {
						$this->SetFont(FONT, 'I', 9);
						$this->Write(0, " (" . $this->decode($dimension['description']) . ")");
					}
					$this->SetX(LEFT + $start + 1 - $this->GetStringWidth($dimension['mark']) / 2);
					$this->SetFont(FONT, 'B', 11);
					$this->Write(0, $dimension['mark']);
				}
				$y += 8;
				$this->SetXY(LEFT, $y);
				$this->SetFont(FONT, '', 11);
				$this->Write(0, $this->decode('Qualificació global'));
				$this->SetX(LEFT + $start + 1 - $this->GetStringWidth($area['mark']) / 2);
				$this->SetFont(FONT, 'B', 11);
				$this->Write(0, $area['mark']);
				$this->SetLineWidth(.1);
				$this->SetDrawColor(0x80);
				$this->Line(LEFT, $y + 3, LEFT + 170, $y + 3);
				$this->SetDrawColor(0);
			}
		}
		$y += 10;
		$this->SetFont(FONT, 'B', 11);
		$this->SetLineWidth(.5);
		$this->SetXY(LEFT, $y);
		$this->Write(10, strtoupper($this->decode($student['observation']['title'])));
		$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
		$y += 10;
		$this->SetFont(FONT, '', 11);
		$this->SetXY(LEFT, $y);
		$this->MultiCell(0, 5, $this->decode($student['observation']['text']));
		$this->SetFont(FONT, 'B', 11);
		$this->SetLineWidth(.5);
		$this->Ln(10);
		$this->Cell(0, 0, $this->decode($student['reinforce']['title']));
		$y = $this->GetY();
		$this->Line(LEFT, $y + 3, LEFT + 170, $y + 3);
		$y += 4;
		$this->SetFont(FONT, '', 11);
		$this->SetXY(LEFT, $y);
		$this->MultiCell(0, 5, $this->decode($student['reinforce']['text']));
		$this->placeFooter($student);
	}
	
	function WriteHTML($html)
	{
		// HTML parser
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
					else
						$this->Write(4,$e);
			}
			else
			{
				// Tag
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
					else
					{
						// Extract attributes
						$a2 = explode(' ',$e);
						$tag = strtoupper(array_shift($a2));
						$attr = array();
						foreach($a2 as $v)
						{
							if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
								$attr[strtoupper($a3[1])] = $a3[2];
						}
						$this->OpenTag($tag,$attr);
					}
			}
		}
	}
	
	function OpenTag($tag, $attr)
	{
		// Opening tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
			if($tag=='A')
				$this->HREF = $attr['HREF'];
				if($tag=='BR')
					$this->Ln(5);
	}
	
	function CloseTag($tag)
	{
		// Closing tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
			if($tag=='A')
				$this->HREF = '';
	}
	
	function SetStyle($tag, $enable)
	{
		// Modify style and select corresponding font
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}
	
	function PutLink($URL, $txt)
	{
		// Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	
	private function decode($inputText)
	{
		setlocale(LC_ALL, 'es_CA');
		return $str = iconv('UTF-8', 'cp1252', $inputText);
	}
	
	private function pageBreaksScope($scope, $y)
	{
		$maxY = 280;
		foreach ($scope['areas'] as $area) {
			$y += 38;
			foreach ($area['dimensions'] as $dimension) {
				$y += 6;
			}
		}
		return $y > $maxY;
	}
}