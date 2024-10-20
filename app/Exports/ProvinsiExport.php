<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProvinsiExport implements FromView, WithStyles {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
	try {
		if ($this->kabupatenId == 0) {
			return $this->exportAllProvinsi();
		}

		return $this->exportProvinsiByKabupaten();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportAllProvinsi(): View
  {
	try {
		$provinsi = $this->getAllProvinsi();
		return view('exports.provinsi.semua-provinsi', compact('provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function exportProvinsiByKabupaten(): View
  {
	try {
		$kabupaten = $this->getKabupatenById();
		$provinsi = $this->getProvinsiByKabupatenId();
		return view('exports.provinsi.provinsi', compact('kabupaten', 'provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function getAllProvinsi(): Collection
  {
	try {
		return Provinsi::selectRaw('UPPER(nama) AS nama')->get();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function getKabupatenById(): Kabupaten
  {
	try {
		return Kabupaten::selectRaw('UPPER(nama) AS nama')->find($this->kabupatenId);
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function getProvinsiByKabupatenId(): Collection
  {
	try {
		return Provinsi::selectRaw('UPPER(nama) AS nama')
			->whereHas('kabupaten', fn (Builder $builder) => $builder->where('id', $this->kabupatenId))
			->get();
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
		$this->setKabupatenBorder($sheet, $index, $styleArray);

		if ($index > 1) {
			$this->setProvinsiBorder($sheet, $index, $styleArray);
		}

		$index++;
	}
  }
  
  private function setKabupatenBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$namaKabupaten = $sheet->getCell('A1');
	$namaKabupaten->getStyle()->getFont()->setBold(true);
  }

  private function setProvinsiBorder(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellA = $sheet->getCell("A$index");
	$cellA->getStyle()->applyFromArray($styleArray);
  }
}