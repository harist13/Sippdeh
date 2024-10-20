<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Exception;

class KabupatenExport implements FromView, WithStyles {
  private $provinsiId;

  public function __construct(int $provinsiId) {
    $this->provinsiId = $provinsiId;
  }
  
  public function view(): View {
	try {
		if ($this->provinsiId == 0) {
			return $this->exportAllKabupaten();
		}
	
		return $this->exportKabupatenByProvinsi();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportAllKabupaten(): View
  {
	  try {
		$kabupaten = Kabupaten::all();
		return view('exports.kabupaten.semua-kabupaten', compact('kabupaten'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportKabupatenByProvinsi(): View
  {
	try {
		$provinsi = Provinsi::find($this->provinsiId);
		return view('exports.kabupaten.kabupaten-provinsi', compact('provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  public function styles(Worksheet $sheet): void
  {
	$styleArray = [
		'borders' => [
			'bottom' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
			'top' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
			'right' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
			'left' => ['borderStyle' => 'thin', 'color' => ['argb' => '00000000']],
		],
	];

	$index = 1;

	foreach ($sheet->getRowIterator() as $row) {
		$this->setKabupatenBorder($sheet, $index, $styleArray);
		
		// Jika memilih semua provinsi, maka berikan border untuk kolom B
		if ($this->provinsiId == 0) {
			$this->setProvinsiBorder($sheet, $index, $styleArray);
		}

		$index++;
	}
  }

  private function setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellA = $sheet->getCell("A$index");
	$cellA->getStyle()->applyFromArray($styleArray);
  }

  private function setProvinsiBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellB = $sheet->getCell("B$index");
	$cellB->getStyle()->applyFromArray($styleArray);
  }
}