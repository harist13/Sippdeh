<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Provinsi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Exception;

class KelurahanExport implements FromView, WithStyles {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
	try {
		if ($this->kabupatenId == 0) {
			return $this->_exportAllKelurahan();
		}
	
		return $this->_exportKelurahanKabupaten();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _exportAllKelurahan(): View
  {
	  try {
		$kelurahan = Kelurahan::all();
		return view('exports.kelurahan.semua-kelurahan', compact('kelurahan'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _exportKelurahanKabupaten(): View
  {
	try {
		$provinsi = Provinsi::whereHas('kabupaten', fn($builder) => $builder->where('id', $this->kabupatenId))->first();
		$kabupaten = Kabupaten::findOrFail($this->kabupatenId);
		$kecamatan = Kecamatan::whereHas('kabupaten', fn($builder) => $builder->where('id', $this->kabupatenId))->first();
		$kelurahan = Kelurahan::whereHas('kecamatan', function($builder) {
			$builder->whereHas('kabupaten', fn($builder) => $builder->where('id', $this->kabupatenId));
		})
		->get();

		return view('exports.kelurahan.kelurahan-kabupaten', compact('provinsi', 'kabupaten', 'kecamatan', 'kelurahan'));
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
			// Skip border untuk header provinsi (indeks ke-1), header kabupaten (indeks ke-2), dan header kecamatan (indeks ke-3)
			if ($index >= 4) {
				$this->_setKelurahanBorder($sheet, $index, $styleArray);
			}
		} else {
			$this->_setKelurahanBorder($sheet, $index, $styleArray);
		}
		
		// Jika memilih semua kabupaten, maka berikan border untuk wilayah-wilayah di atasnya
		if ($this->kabupatenId == 0) {
			$this->_setKecamatanBorder($sheet, $index, $styleArray);
			$this->_setKabupatenBorder($sheet, $index, $styleArray);
			$this->_setProvinsiBorder($sheet, $index, $styleArray);
		}

		$index++;
	}
  }

  private function _setKelurahanBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("A$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }

  private function _setKecamatanBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("B$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }

  private function _setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("C$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }

  private function _setProvinsiBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cell = $sheet->getCell("D$index");
	$cell->getStyle()->applyFromArray($styleArray);
  }
}