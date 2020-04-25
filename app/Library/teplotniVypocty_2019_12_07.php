//teplotniVypocty_2019_12_07<?php
// volání knihovny pro práci s excelem  
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf;



class teplotniVypocetRozvadece
{

	function __construct()
	{
			
	}

	public $dataInput = array(); // data z xml neno excel
	public $dataNenalezenyZtratovyVykon = array(); //nenalezeno v databasi
	public $dataTabulkaZtratovychVykonu  = array();  // tabulka ztrátových výkonů

    public $cisloProjektu;
	public $firma; 
	public $kontaktniOsoba;
	public $vyrobce;		
	public $telefon;
	public $email;	
	public $datum;
	public $cestaLogo;
	public $vyberJazyk;
	public $maxTeplotaVneRozvadece;
	public $maxTeplotaUvnitrRozvadece;
	public $frekvence;	
	public $napeti;
	public $rozvadecObjednaciCislo;
	public $rozvadecSirka;
	public $rozvadecVyska;
	public $rozvadecHlouba;
	public $rozvadecSoucinitelProstupuTeplaK;
	public $rozvadecTypInstalace;
	public $databaseComponentsFileName;
	public $celkovyZtratovyVykon;
	public $tepelnaVymenaPovrchovouPlochou;
	public $stredniTeplotaRozvadeceBezChlazeni;
	public $odvadenyVykon;
	public $zjisteniPotrebyChlazeni;

	public function nactiPromenne()
	{

        $this->cisloProjektu = "Cislo projektu";
		$this->firma = "firma";
		$this->kontaktniOsoba = "kontaktni osoba";
		$this->vyrobce = "výrobce";		
		$this->telefon = "telefonNeboFax";
		$this->email = "email";	
		$this->datum = "2019.12.01";
		$this->cestaLogo = "logo.png";
		$this->vyberJazyk = "CZ";
		$this->maxTeplotaVneRozvadece = 35;
		$this->maxTeplotaUvnitrRozvadece = 50;
		$this->frekvence = "50";
		$this->napeti = "400 V"; // může být nap 400 V
		$this->rozvadecObjednaciCislo = "Rozvadec";
		$this->rozvadecSirka = 2000;
		$this->rozvadecVyska = 4000;
		$this->rozvadecHlouba = 400;
		$this->rozvadecSoucinitelProstupuTeplaK = 5.5;
		$this->rozvadecTypInstalace = "2.Samostatný rozváděč volně stojící.";
		$this->databaseComponentsFileName = './databaseComponents.xlsx';

    }


	public function nactiData()
	{	
		//1.Nahrátí dat z imput na základě typu
		// Rozlišení typu imput souboru .xml
		if (file_exists("input.xml")) 
		{
			// Rozlišení typu imput souboru .xml
			// Import data do Array
			$xmldata = simplexml_load_file("input.xml");


			$i = 1;
			foreach($xmldata->Document->Page->Line as $key => $Line) 
				{
					$this->dataInput[] = array
					(
						(string)$Line->Label->Property[0]->PropertyValue,
						(string)$Line->Label->Property[1]->PropertyValue,
						(string)$Line->Label->Property[2]->PropertyValue,
						(string)$Line->Label->Property[3]->PropertyValue,
						(string)$Line->Label->Property[4]->PropertyValue,
						(string)$Line->Label->Property[5]->PropertyValue,
						(string)$Line->Label->Property[6]->PropertyValue,
					);
				}


			//var_dump($this->dataInput);

			// Import data do Array
			// Rozlišení typu imput souboru .xml
		} 
		// Rozlišení typu imput souboru .xml
		// Rozlišení typu imput souboru .xlsx
		elseif (file_exists("input.xls")) 
		{
			// Rozlišení typu imput souboru .xlsx
			// Import data do Array

			$inputFileName = './input.xls';

			/** Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
			$worksheet = $spreadsheet->getActiveSheet();
			$this->dataInput = $worksheet->toArray();
			/**
			$data = array();

			$data = $spreadsheet->getActiveSheet()
			    ->rangeToArray(
			        'A1:G1',     // The worksheet range that we want to retrieve
			        NULL,        // Value that should be returned for empty cells
			        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
			        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
			        FALSE         // Should the array be indexed by cell row and cell column
			    );

			**/
			//var_dump($this->dataInput);

			// Import data do Array
			// Rozlišení typu imput souboru .xlsx
		}
		// Rozlišení typu imput souboru .xlsx
		else 
		{

			var_dump("Soubor nenalezen.");
			// Rozlišení typu imput souboru .xlsx
		}
	}





	public function vypocti()
	{


		//3.Import Database Ztrátových výkonu

		/** Load $inputFileName to a Spreadsheet Object  **/
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->databaseComponentsFileName);
		$worksheet = $spreadsheet->getActiveSheet();
		$dataDatabaseComponents = $worksheet->toArray();
		/**
		$data = array();

		$data = $spreadsheet->getActiveSheet()
		    ->rangeToArray(
		        'A1:G1',     // The worksheet range that we want to retrieve
		        NULL,        // Value that should be returned for empty cells
		        FALSE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
		        FALSE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
		        FALSE         // Should the array be indexed by cell row and cell column
		    );

		**/
		//var_dump($dataDatabaseComponents);

		//3.Import Database Ztrátových výkonu


		foreach($this->dataInput as $key => $value ) 
		{ //foreach($this->dataInput as $row  ) 

			$dataInputRow = $value;
			$dataInputObjednaciCislo = $dataInputRow[2];
			(float)$dataInputPocetSoucastek = $dataInputRow[5];	
			//var_dump($key); // objednaci číslo
			//var_dump($value); // objednaci číslo
			//var_dump($row[2]); // objednaci číslo


			if (array_search($dataInputObjednaciCislo, array_column($dataDatabaseComponents, '1')) <> "") //NAlezené data
			{ //NAlezené data Database vytvoří nový array s výslednou tabulkou a doplní hodnoty z database, pouze pokud daná součástka má ztrátový výkon číselnou hodnotu
			$keyDatabase = array_search($dataInputObjednaciCislo, array_column($dataDatabaseComponents, '1')); // zjištění řádku s daty $dataDatabaseComponents


			if ($dataDatabaseComponents[$keyDatabase][3]> 0) 
				{ // bere vpotaz pouze data z databaze pokud má v databázi součástka ztrátový výkon větší než 0
				# code...


					(float)$ztratovyVykonJednotkovy = $dataDatabaseComponents[$keyDatabase][3]; // zjištění ztratovyVykonJednotkovy z $dataDatabaseComponents
					(float)$koeficientCasovehoVyuziti = $dataDatabaseComponents[$keyDatabase][4]; // zjištění $koeficientCasovehoVyuziti z $dataDatabaseComponents
					(float)$koeficientProudovehoVyuziti = $dataDatabaseComponents[$keyDatabase][5]; // zjištění $koeficientProudovehoVyuziti z $dataDatabaseComponents


					if (!isset($this->dataTabulkaZtratovychVykonu)) 
						{ //vytvoří nový array pokud není 
						    $this->dataTabulkaZtratovychVykonu = array();
						}

					(string)$VypocetZtratovehoTeplaSoucastky = ($ztratovyVykonJednotkovy * $koeficientCasovehoVyuziti * $koeficientProudovehoVyuziti) * $dataInputPocetSoucastek;
					array_push($dataInputRow,$ztratovyVykonJednotkovy,$koeficientCasovehoVyuziti,$koeficientProudovehoVyuziti, (string)$VypocetZtratovehoTeplaSoucastky );
					array_push($this->dataTabulkaZtratovychVykonu, $dataInputRow);


			    }// bere vpotaz pouze data z databaze pokud má v databázi součástka ztrátový výkon větší než 0


			} //NAlezené data Database vytvoří nový array s výslednou tabulkou a doplní hodnoty z database, pouze pokud daná součástka má ztrátový výkon číselnou hodnotu
			else //NEnalezené data vytvoří array pokud neexistuje a doplňuje do něj nenalezené hodnoty z databaze
			{

				if (!isset($this->dataNenalezenyZtratovyVykon)) 
				{ //vytvoří nový array pokud není 
				    $this->dataNenalezenyZtratovyVykon = array();
				}
				array_push($this->dataNenalezenyZtratovyVykon, $row); // přídá hodnotu na konec array

			} //NEnalezené data vytvoří array pokud neexistuje a doplňuje do něj nenalezené hodnoty z databaze

				//var_dump($ztratovyVykonJednotkovy);

		} //foreach($this->dataInput as $row  ) KONEC

		//var_dump($this->dataInput);


		//4.Vytvoří array a Doplnění ztratových výkonu na základě objednacího čísla. Pokud nenalezne vytvoří array s nenalezenými hodnotami

		//5.Zjištění celkového ztrátového výkonu z dataTabulkaZtratovychVykonu

		foreach($this->dataTabulkaZtratovychVykonu as $key => $value ) 
		{ 
			$dataTabulkaZtratovychVykonuRow = $value;
			(float)$TabulkaZtratovychVykonuRowZtratovyVykonCelkovy = $dataTabulkaZtratovychVykonuRow[9];

			$celkovyZtratovyVykonVypocet +=  $TabulkaZtratovychVykonuRowZtratovyVykonCelkovy;

		} 

		$this->celkovyZtratovyVykon = $celkovyZtratovyVykonVypocet;
		//5.Zjištění celkového ztrátového výkonu z dataTabulkaZtratovychVykonu
		//6.Vypocet 


		$this->tepelnaVymenaPovrchovouPlochou = $this->vypoctiTepelnaVymenaPovrchovouPlochou($this->rozvadecTypInstalace,$this->rozvadecSirka,$this->rozvadecVyska,$this->rozvadecHlouba,
			$this->maxTeplotaVneRozvadece, $this->maxTeplotaUvnitrRozvadece, $this->rozvadecSoucinitelProstupuTeplaK);
		
		$this->stredniTeplotaRozvadeceBezChlazeni = $this->vypoctiStredniTeplotaRozvadeceBezChlazeni($this->celkovyZtratovyVykon,$this->maxTeplotaVneRozvadece,$this->rozvadecSoucinitelProstupuTeplaK,
			$this->rozvadecTypInstalace, $this->rozvadecSirka, $this->rozvadecVyska,$this->rozvadecHlouba);

		$this->odvadenyVykon = $this->vypoctiOdvadenyVykon($this->celkovyZtratovyVykon, $this->rozvadecTypInstalace, $this->rozvadecSirka, $this->rozvadecVyska, 
		$this->rozvadecHlouba,$this->maxTeplotaVneRozvadece, $this->maxTeplotaUvnitrRozvadece, $this->rozvadecSoucinitelProstupuTeplaK);

		if($this->odvadenyVykon > 0)
		{
		$this->zjisteniPotrebyChlazeni = "ANO";
		} 
		elseif($this->odvadenyVykon < 0)
		{
		$this->zjisteniPotrebyChlazeni = "NE";
		} 

/**
		var_dump($this->celkovyZtratovyVykon. 'dataTabulkaZtratovychVykonu'); // W
		var_dump($this->tepelnaVymenaPovrchovouPlochou. 'vypoctiTepelnaVymenaPovrchovouPlochou'); // w
		var_dump($this->stredniTeplotaRozvadeceBezChlazeni. 'vypoctiStredniTeplotaRozvadeceBezChlazeni'); // °C
    	var_dump($this->odvadenyVykon. 'vypoctiOdvadenyVykon'); // pokud je hodnota v záporu je to ok, pokud je v kladných hodnotách tak je to špatně a rozvaděč nedokáže uchladit komponenty
**/
	}



   // Teplotní vypočty mohou bý pouze v rozmezí hosnot 20 °C až 75°C
	public function overeniTeploty($maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece) 
	{

		if ($maxTeplotaVneRozvadece < 20) 
		{
		  $upozorneniPrekroceniTeploty = "Vnější teplota nesmí být nižší než 20°C. Problém s rosným bodem.";
		}

		if ($maxTeplotaVneRozvadece > 75) 
		{
		  $upozorneniPrekroceniTeploty = $upozorneniPrekroceniTeploty + "<br>Vnější teplota nesmí být vyšší než 75°C.";
		}

		if ($maxTeplotaUvnitrRozvadece > 20) 
		{
		  $upozorneniPrekroceniTeploty = $upozorneniPrekroceniTeploty + "<br>Vnitřní teplota nesmí být nižší než 20°C. Problém s rosným bodem.";
		}

		if ($maxTeplotaUvnitrRozvadece > 75) 
		{
		  $upozorneniPrekroceniTeploty = $upozorneniPrekroceniTeploty + "<br>Vnitřní teplota nesmí být vyšší než 75°C.";
		}

		return $upozorneniPrekroceniTeploty; 
	}






	public function ucinaChladiciPlocha($rozvadecTypInstalace,$rozvadecSirka, $rozvadecVyska,$rozvadecHlouba)
	{
	     (float)$W = $rozvadecSirka/1000; 
	     (float)$H = $rozvadecVyska/1000; 
	     (float)$D = $rozvadecHlouba/1000; 
	
		//$rest = substr("abcdef", -3, -1); // returns "de"
		if ($rozvadecTypInstalace[0] == '1') //1.Samostatný rozváděč volně stojící.
		{
		$ucinaChladiciPlocha = 1.8 * $H * ($W + $D) + 1.4 * $W * $D;
		}	
		elseif ($rozvadecTypInstalace[0] == '2') //2.Počáteční nebo koncový rozvaděč volně stojící.
		{			
		$ucinaChladiciPlocha = 1.4 * $D * ($H + $W) + 1.8 * $H * $W;
		}
		elseif ($rozvadecTypInstalace[0] == '3') //3.Vnitřní střední rozvaděč volně stojící.
		{
		$ucinaChladiciPlocha = 1.8 * $W * $H + 1.4 * $W * $D + $D * $H;
		}
		elseif ($rozvadecTypInstalace[0] == '4') //4.Samostatný rozvaděč pro montáž na stěnu.
		{
		$ucinaChladiciPlocha = 1.4 * $W * ($H + $D) + 1.8 * $H * $D;
		}
		elseif ($rozvadecTypInstalace[0] == '5') //5.Počáteční nebo koncový rozvaděč pro montáž na stěnu.
		{
		$ucinaChladiciPlocha = 1.4 * $H * ($W + $D) + 1.4 * $W * $D;
		}
		elseif ($rozvadecTypInstalace[0] == '6') //6.Vnitřní střední rozvaděč pro montáž na stěnu.
		{
		$ucinaChladiciPlocha = 1.4 * $W * ($H + $D) + $D * $H;
		}
		elseif ($rozvadecTypInstalace[0] == '7') //7.Vnitřní střední rozvaděč pro montáž na stěnu se zakrytou střechou.
		{
		$ucinaChladiciPlocha = 1.4 * $W * $H + 0.7 * $W * $D + $D * $H;
		}
		else
		{

		}	
		//var_dump($W. '$W');
		//var_dump($H. '$H');
		//var_dump($D. '$D');	
		//var_dump($ucinaChladiciPlocha. '$ucinaChladiciPlocha');
		//var_dump(round($ucinaChladiciPlocha, 2). '$ucinaChladiciPlocha');	
		return round($ucinaChladiciPlocha, 2);

	}


	public function rozdilTeplot($maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece)
	{
	   	$rozdilTeplot = $maxTeplotaUvnitrRozvadece -$maxTeplotaVneRozvadece;
		return $rozdilTeplot;
	}


	public function vypoctiTepelnaVymenaPovrchovouPlochou($rozvadecTypInstalace,$rozvadecSirka, $rozvadecVyska,$rozvadecHlouba,
		$maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece, $rozvadecSoucinitelProstupuTeplaK)
	{

		$vypoctiTepelnaVymenaPovrchovouPlochou = $this->ucinaChladiciPlocha($rozvadecTypInstalace,$rozvadecSirka, $rozvadecVyska,$rozvadecHlouba) * $rozvadecSoucinitelProstupuTeplaK * $this->rozdilTeplot($maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece);
		return $vypoctiTepelnaVymenaPovrchovouPlochou;
	}

	public function vypoctiStredniTeplotaRozvadeceBezChlazeni($celkovyZtratovyVykon,$maxTeplotaVneRozvadece,
		$rozvadecSoucinitelProstupuTeplaK, $rozvadecTypInstalace, $rozvadecSirka, $rozvadecVyska,$rozvadecHlouba)
	{
	    $vypoctiStredniTeplotaRozvadeceBezChlazeni = ($celkovyZtratovyVykon / ($rozvadecSoucinitelProstupuTeplaK * $this->ucinaChladiciPlocha($rozvadecTypInstalace,$rozvadecSirka, $rozvadecVyska,$rozvadecHlouba))) + $maxTeplotaVneRozvadece;    
	    return round($vypoctiStredniTeplotaRozvadeceBezChlazeni);   
	}

	public function vypoctiOdvadenyVykon($celkovyZtratovyVykon, $rozvadecTypInstalace, $rozvadecSirka, $rozvadecVyska, 
		$rozvadecHlouba,$maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece, $rozvadecSoucinitelProstupuTeplaK)
	{
		$vypoctiOdvadenyVykon = $celkovyZtratovyVykon - $this->vypoctiTepelnaVymenaPovrchovouPlochou($rozvadecTypInstalace,$rozvadecSirka, $rozvadecVyska,$rozvadecHlouba,
		$maxTeplotaVneRozvadece, $maxTeplotaUvnitrRozvadece, $rozvadecSoucinitelProstupuTeplaK); 
	  	return $vypoctiOdvadenyVykon; 
	}


    public function copy()
    {
        $copied = clone $this;
        $worksheetCount = count($this->workSheetCollection);
        for ($i = 0; $i < $worksheetCount; ++$i) {
            $this->workSheetCollection[$i] = $this->workSheetCollection[$i]->copy();
            $this->workSheetCollection[$i]->rebindParent($this);
        }
        return $copied;
    }

	public function vyexportujNovyExcel()
	{

		$outputSablonaFileName = './outputSablona.xlsx';
		$outputFileName = './output.xlsx';

		if (!copy($outputSablonaFileName, $outputFileName)) 
		{
			var_dump("failed to copy $outputSablonaFileName...\n");
		}
		else
		{


				// Load $outputFileName to a Spreadsheet Object  
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($outputFileName);
				$wsHlavniList = $spreadsheet->getSheetByName('hlavniList');
				$wsTabulkaZtratovychVykonu = $spreadsheet->getSheetByName('tabulkaZtratovychVykonu');
				// ini spreadsheat data
				// ini new Spreadsheet

				//var_dump($this->dataTabulkaZtratovychVykonu);
	
				foreach($this->dataTabulkaZtratovychVykonu as $key => $row) 
				{

					$wsTabulkaZtratovychVykonu->setCellValue('J' . ($key+11), $row[0]);
					$wsTabulkaZtratovychVykonu->setCellValue('K' . ($key+11), $row[1]);
					$wsTabulkaZtratovychVykonu->setCellValue('L' . ($key+11), $row[2]);
					$wsTabulkaZtratovychVykonu->setCellValue('M' . ($key+11), $row[3]);
					$wsTabulkaZtratovychVykonu->setCellValue('N' . ($key+11), $row[4]);
					$wsTabulkaZtratovychVykonu->setCellValue('O' . ($key+11), $row[5]);
					$wsTabulkaZtratovychVykonu->setCellValue('P' . ($key+11), $row[6]);
					$wsTabulkaZtratovychVykonu->setCellValue('Q' . ($key+11), $row[7]);
					$wsTabulkaZtratovychVykonu->setCellValue('R' . ($key+11), $row[8]);
					$wsTabulkaZtratovychVykonu->setCellValue('S' . ($key+11), $row[9]);
				}
					$wsHlavniList->setCellValue('B5', $this->cisloProjektu);
					$wsHlavniList->setCellValue('B6', $this->firma);
					$wsHlavniList->setCellValue('B7', $this->kontaktniOsoba);
					$wsHlavniList->setCellValue('B8', $this->vyrobce);
					$wsHlavniList->setCellValue('B9', $this->telefon);
					$wsHlavniList->setCellValue('B10', $this->email);

					$wsHlavniList->setCellValue('F5', $this->maxTeplotaVneRozvadece.' °C');
					$wsHlavniList->setCellValue('F6', $this->maxTeplotaUvnitrRozvadece. ' °C');
					$wsHlavniList->setCellValue('E7', $this->napeti);
					$wsHlavniList->setCellValue('E8', $this->frekvence. ' Hz');

					$wsHlavniList->setCellValue('D13', $this->rozvadecObjednaciCislo);
					$wsHlavniList->setCellValue('D14', $this->rozvadecSirka.' mm');
					$wsHlavniList->setCellValue('D15', $this->rozvadecVyska.' mm');
					$wsHlavniList->setCellValue('D16', $this->rozvadecHlouba.' mm');
					$wsHlavniList->setCellValue('D17', $this->rozvadecTypInstalace);
					$wsHlavniList->setCellValue('D18', $this->email);

					$wsHlavniList->setCellValue('D21', $this->celkovyZtratovyVykon. ' W');
					$wsHlavniList->setCellValue('D22', $this->tepelnaVymenaPovrchovouPlochou. ' W');
					$wsHlavniList->setCellValue('D23', $this->odvadenyVykon. ' W');
					$wsHlavniList->setCellValue('D24', $this->stredniTeplotaRozvadeceBezChlazeni. ' °C');
					$wsHlavniList->setCellValue('D25', $this->zjisteniPotrebyChlazeni);


					//vložení loga
					$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing();
					$drawing->setName('PhpSpreadsheet logo');
					$drawing->setPath($this->cestaLogo);
					$drawing->setHeight(36);
					//$spreadsheet->getActiveSheet()->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
					$wsHlavniList->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
					$wsHlavniList->getHeaderFooter()->setOddHeader('&L&G&');
					$wsTabulkaZtratovychVykonu->getHeaderFooter()->addImage($drawing, \PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter::IMAGE_HEADER_LEFT);
					$wsTabulkaZtratovychVykonu->getHeaderFooter()->setOddHeader('&L&G&');
					//vložení loga

					//uloží excel
					$writer = new Xlsx($spreadsheet);
					$writer->save('output.xlsx');
                    //uloží excel

					//$writer = new \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf($spreadsheet);
					//$writer->writeAllSheets();
					//$writer->save("05featuredemo.pdf");

 
		}
	}

}	

$tvr = new teplotniVypocetRozvadece();
$tvr->nactiPromenne();
$tvr->nactiData();
$tvr->vypocti();
$tvr->vyexportujNovyExcel();
//$tvr->ulo..


