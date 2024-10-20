<?php

namespace App\Exports;

use App\Models\Kabupaten;
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
				return $this->exportAllKelurahan();
			}

			return $this->exportKelurahanKabupaten();
		} catch (Exception $exception) {
			throw $exception;
		}
	}

	private function exportAllKelurahan(): View
	{
		try {
			$kelurahan = Kelurahan::all();
			return view('exports.kelurahan.semua-kelurahan', compact('kelurahan'));
		} catch (Exception $exception) {
			throw $exception;
		}
	}
  
	private function exportKelurahanKabupaten(): View
	{
		try {
			$provinsi = $this->getProvinsi();
			$kabupaten = $this->getKabupaten();
			$kelurahan = $this->getKelurahan();

			return view('exports.kelurahan.kelurahan-kabupaten', compact('provinsi', 'kabupaten', 'kelurahan'));
		} catch (Exception $exception) {
			throw $exception;
		}
	}

	private function getProvinsi()
	{
		try {
			return Provinsi::whereHas('kabupaten', fn($builder) => $builder->where('id', $this->kabupatenId))->first();
		} catch (Exception $exception) {
			throw new Exception("Error fetching Provinsi: " . $exception->getMessage());
		}
	}

	private function getKabupaten()
	{
		try {
			return Kabupaten::findOrFail($this->kabupatenId);
		} catch (Exception $exception) {
			throw new Exception("Error fetching Kabupaten: " . $exception->getMessage());
		}
	}

	private function getKelurahan()
	{
		try {
			return Kelurahan::whereHas('kecamatan', function($builder) {
				$builder->whereHas('kabupaten', fn($builder) => $builder->where('id', $this->kabupatenId));
			})->get();
		} catch (Exception $exception) {
			throw new Exception("Error fetching Kelurahan: " . $exception->getMessage());
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
					$this->setKelurahanBorder($sheet, $index, $styleArray);
					$this->setKecamatanBorder($sheet, $index, $styleArray);
				}
			} else {
				$this->setKelurahanBorder($sheet, $index, $styleArray);
				$this->setKecamatanBorder($sheet, $index, $styleArray);
			}
			
			// Jika memilih semua kabupaten, maka berikan border untuk wilayah-wilayah di atasnya
			if ($this->kabupatenId == 0) {
				$this->setKecamatanBorder($sheet, $index, $styleArray);
				$this->setKabupatenBorder($sheet, $index, $styleArray);
				$this->setProvinsiBorder($sheet, $index, $styleArray);
			}

			$index++;
		}
	}

	private function setKelurahanBorder(Worksheet $sheet, int $index, array $styleArray): void
	{
		$cell = $sheet->getCell("A$index");
		$cell->getStyle()->applyFromArray($styleArray);
	}

	private function setKecamatanBorder(Worksheet $sheet, int $index, array $styleArray): void
	{
		$cell = $sheet->getCell("B$index");
		$cell->getStyle()->applyFromArray($styleArray);
	}

	private function setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
	{
		$cell = $sheet->getCell("C$index");
		$cell->getStyle()->applyFromArray($styleArray);
	}

	private function setProvinsiBorder(Worksheet $sheet, int $index, array $styleArray): void
	{
		$cell = $sheet->getCell("D$index");
		$cell->getStyle()->applyFromArray($styleArray);
	}
}