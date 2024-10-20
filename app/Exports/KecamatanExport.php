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
			return $this->exportAllKecamatan();
		}
	
		return $this->exportKecamatanByKabupaten();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportAllKecamatan(): View
  {
	  try {
		$kecamatan = Kecamatan::all();
		return view('exports.kecamatan.semua-kecamatan', compact('kecamatan'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportKecamatanByKabupaten(): View
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
		if ($this->kabupatenId != 0) {
			// Skip border untuk header provinsi (indeks ke-1) dan header kabupaten (indeks ke-2)
			if ($index >= 3) {
				$this->setKecamatanBorder($sheet, $index, $styleArray);
			}
		} else {
			$this->setKecamatanBorder($sheet, $index, $styleArray);
		}
		
		// Jika memilih semua kabupaten, maka berikan border untuk wilayah-wilayah di atasnya
		if ($this->kabupatenId == 0) {
			$this->setKabupatenBorder($sheet, $index, $styleArray);
			$this->setProvinsiBorder($sheet, $index, $styleArray);
		}

		$index++;
	}
  }

  private function setKecamatanBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("A$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }

  private function setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("B$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }

  private function setProvinsiBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("C$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }
}