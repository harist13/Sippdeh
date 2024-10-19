<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Exception;

class KecamatanExport implements FromView, WithStyles {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
	try {
		if ($this->kabupatenId == 0) {
			return $this->_eksporSemuaKecamatan();
		}
	
		return $this->_eksporKecamatanKabupaten();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _eksporSemuaKecamatan(): View
  {
	  try {
		$kecamatan = Kecamatan::all();
		return view('exports.kecamatan.semua-kecamatan', compact('kecamatan'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _eksporKecamatanKabupaten(): View
  {
	try {
		$kabupaten = Kabupaten::find($this->kabupatenId);
		return view('exports.kecamatan.kecamatan-kabupaten', compact('kabupaten'));
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
		$this->_setKecamatanBorder($sheet, $index, $styleArray);
		
		// Jika memilih semua kabupaten, maka berikan border untuk kolom B
		if ($this->kabupatenId == 0) {
			$this->_setKabupatenBorder($sheet, $index, $styleArray);
		}

		$index++;
	}
  }

  private function _setKecamatanBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellA = $sheet->getCell("A$index");
	$cellA->getStyle()->applyFromArray($styleArray);
  }

  private function _setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellB = $sheet->getCell("B$index");
	$cellB->getStyle()->applyFromArray($styleArray);
  }
}