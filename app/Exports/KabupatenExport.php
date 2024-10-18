<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KabupatenExport implements FromView, WithStyles {
  private $provinsiId;

  public function __construct(int $provinsiId) {
    $this->provinsiId = $provinsiId;
  }
  
  public function view(): View {
	try {
		if ($this->provinsiId == 0) {
			return $this->_semuaKabupaten();
		}
	
		return $this->_kabupatenProvinsi();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _semuaKabupaten(): View
  {
	  try {
		$kabupaten = Kabupaten::all();
		return view('exports.kabupaten.semua-kabupaten', compact('kabupaten'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _kabupatenProvinsi(): View
  {
	try {
		$provinsi = Provinsi::find($this->provinsiId);
		return view('exports.kabupaten.kabupaten-provinsi', compact('provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  public function styles(Worksheet $sheet)
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
		// $namaProvinsi = $sheet->getCell('A1');
		// $namaProvinsi->getStyle()->getFont()->setBold(true);

		$cellA = $sheet->getCell("A$index");
		$cellA->getStyle()->applyFromArray($styleArray);
		
		if ($this->provinsiId == 0) {
			$cellB = $sheet->getCell("B$index");
			$cellB->getStyle()->applyFromArray($styleArray);
		}

		$index++;
	}
  }
}