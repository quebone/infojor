<?php
namespace simi\tplengine;

class Test extends \fpdf
{
	public function __construct($data)
	{
		parent::__construct();
	}
}

class PDFEngine extends \fpdf
{
	private $data;
	private $pageNumber;
	private $trHeader;	//trimestres columns' headers
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
		$this->trHeader = array(
				'1' => array(
						'start' => 149,
						'offset' => 7,
						'headers' => array('1r t.', '3r t.'),
				),
				'2' => array(
						'start' => 142,
						'offset' => 7,
						'headers' => array('1r t.', '2n t.', '3r t.', 'Final'),
				),
		);
	}
	
	private function placeHeader() {
		$this->Image(current($this->data)['header']['logo_gencat'], 20, 10, 70);
		$this->Image(current($this->data)['header']['logo_jor'], 160, 10, 25);
		$this->SetFont(FONT, '', 14);
	}
	
	private function placeFooter($student, $pageNumber)
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
		$this->Cell(0, 12, $pageNumber, 0, 0, 'R');
	}
	
	private function body($student)
	{
		$this->pageNumber = 1;
		$this->AddPage();
		if (!($this->PageNo() % 2)) {
			$this->AddPage();
			$this->pageNumber = 1;
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
		$this->Write(0, 'Tutor/a: ' . $this->decode($student['tutor']));
		$y = TOP + 25;
		foreach ($student['scopes'] as $scope) {
			$y += 15;
			if ($this->pageBreaksFirstArea($scope, $y)) {
				$this->startNewPage($student);
				$y = TOP + 10;
			}
			$this->SetFont(FONT, 'B', 11);
			$this->SetLineWidth(.5);
			$this->SetXY(LEFT, $y);
			$this->Write(10, MB_strtoupper($this->decode($scope['name']), 'ISO-8859-1'));
			$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
			$y += 4;
			foreach ($scope['areas'] as $area) {
				if ($this->pageBreaksArea($area, $y)) {
					$this->startNewPage($student);
					$y = TOP + 3;
				}
				$y += 6;
				$this->SetFont(FONT, 'B', 11);
				$this->SetLineWidth(.2);
				$this->SetXY(LEFT, $y);
				$this->Write(10, $this->decode($area['name']));
				$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
				$this->SetFont(FONT, '', 6);
				//place dimensions' header
				$degreeId = $student['degree']['id'];
				$x = $this->trHeader[$degreeId]['start'];
				foreach ($this->trHeader[$degreeId]['headers'] as $header) {
					$this->SetXY(LEFT + $x, $y + 10);
					$this->Write(0, $header);
					$x += $this->trHeader[$degreeId]['offset'];
				}
				$y += 10;
				if (isset($area['dimensions'])) {
					foreach ($area['dimensions'] as $dimension) {
						$dimName = $this->decode($dimension['name']);
						$dimDescr = $this->decode($dimension['description']);
						$dimArr = array(
								array(
										'text' => $dimName,
										'font' => FONT,
										'style' => '',
										'size' => 11
								));
						if (strlen($dimDescr) > 0) {
							$dimArr[] = array(
									'text' => "(" . $dimDescr . ")",
									'font' => FONT,
									'style' => 'I',
									'size' => 9
							);
						}
						foreach ($this->split($dimArr, 140) as $line) {
							$y += 4.5;
							$this->setXY(LEFT + 3, $y);
							foreach ($line as $data) {
								$this->SetFont($data['font'], $data['style'], $data['size']);
								$this->Write(0, $data['text']);
							}
						}
						$y += 1.5;
						$this->SetX(LEFT + $this->trHeader[$degreeId]['start'] + 1 - $this->GetStringWidth($dimension['mark']) / 2);
						$this->SetFont(FONT, 'B', 11);
						$this->Write(0, $dimension['mark']);
					}
				}
				if (isset($area['mark'])) {
					$y += 8;
					$this->SetXY(LEFT, $y);
					$this->SetFont(FONT, '', 11);
					$this->Write(0, $this->decode('Qualificació global'));
					$this->SetX(LEFT + $this->trHeader[$degreeId]['start'] + 1 - $this->GetStringWidth($area['mark']) / 2);
					$this->SetFont(FONT, 'B', 11);
					$this->Write(0, $area['mark']);
				}
				$this->SetLineWidth(.1);
				$this->SetDrawColor(0x80);
				$this->Line(LEFT, $y + 3, LEFT + 170, $y + 3);
				$this->SetDrawColor(0);
			}
		}
		if ($student['observation']['text'] != '') {
			$y += 10;
			$this->SetFont(FONT, 'B', 11);
			if ($this->pageBreaksObservation($student['observation'], $y)) {
				$this->startNewPage($student);
				$y = TOP + 3;
			}
			$this->SetLineWidth(.5);
			$this->SetFont(FONT, 'B', 11);
			$this->SetXY(LEFT, $y);
			$this->Write(10, strtoupper($this->decode($student['observation']['title'])));
			$this->Line(LEFT, $y + 7.5, LEFT + 170, $y + 7.5);
			$y += 10;
			$this->SetFont(FONT, '', 11);
			$this->SetXY(LEFT, $y);
			$this->MultiCell(0, 5, $this->decode($student['observation']['text']));
		}
		if ($student['reinforce']['text'] != '') {
			if ($this->pageBreaksObservation($student['reinforce'], $y)) {
				$this->startNewPage($student);
				$y = TOP + 3;
			}
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
		}
		$this->placeFooter($student, $this->pageNumber);
	}

	private function decode($inputText)
	{
		setlocale(LC_ALL, 'es_CA');
		return $str = iconv('UTF-8', 'cp1252', $inputText);
	}
	
	//converts an array of text/font-size to a multiline array of text/font-size/size/style
	private function split($inputs, $maxWidth):array {
		$separator = " ";
		$outputText = array();
		$line = 0;
		$index = 0;
		$width = 0;
		foreach ($inputs as $input) {
			$words = explode($separator, $input['text']);
			$fontSize = $input['size'];
			$outputText[$line][$index]['text'] =  '';
			$this->SetFont($input['font'], $input['style'], $input['size']);
			foreach ($words as $word) {
				$outputText[$line][$index]['font'] = $input['font'];
				$outputText[$line][$index]['style'] = $input['style'];
				$outputText[$line][$index]['size'] = $input['size'];
				$width += $this->GetStringWidth($word . $separator);
				if ($width > $maxWidth) {
					$line++;
					$index = 0;
					$outputText[$line][$index]['font'] = $input['font'];
					$outputText[$line][$index]['style'] = $input['style'];
					$outputText[$line][$index]['size'] = $input['size'];
					$outputText[$line][$index]['text'] =  '';
					$width = 0;
				}
				$outputText[$line][$index]['text'] .=  $word . $separator;
			}
			$index++;
		}
		return $outputText;
	}
	
	//scope and first area must stay in the same page 
	private function pageBreaksFirstArea($scope, $y)
	{
		$y += 16;
		$area = current($scope['areas']);
		return $this->pageBreaksArea($area, $y);
	}
	
	//area must stay entire in the same page
	private function pageBreaksArea($area, $y) {
		$y += 38;
		$maxY = 280;
		if (isset($area['dimensions'])) {
			foreach ((array) $area['dimensions'] as $dimension) {
				$y += 6;
			}
		}
		return $y > $maxY;
	}
	
	//text from observations should not break through different pages
	private function pageBreaksObservation($observation, $y) {
		$y += 10;
		$maxY = 280;
		$height = floor($this->GetStringWidth($observation['text']) / 190);
		return ($y + $height) > $maxY;
	}
	
	//creates new page
	private function startNewPage($student)
	{
		$this->placeFooter($student, $this->pageNumber);
		$this->AddPage();
		$this->pageNumber++;
		$this->placeHeader();
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
}