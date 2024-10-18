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
			return $this->_eksporSemuaProvinsi();
		}

		return $this->_eksporProvinsiByKabupaten();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _eksporSemuaProvinsi(): View
  {
	try {
		$provinsi = $this->_ambilSemuaProvinsi();
		return view('exports.provinsi.semua-provinsi', compact('provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _eksporProvinsiByKabupaten(): View
  {
	try {
		$kabupaten = $this->_ambilKabupatenById();
		$provinsi = $this->_ambilProvinsiByKabupatenId();
		return view('exports.provinsi.provinsi', compact('kabupaten', 'provinsi'));
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _ambilSemuaProvinsi(): Collection
  {
	try {
		return Provinsi::selectRaw('UPPER(nama) AS nama')->get();
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _ambilKabupatenById(): Kabupaten
  {
	try {
		return Kabupaten::selectRaw('UPPER(nama) AS nama')->find($this->kabupatenId);
	} catch (Exception $exception) {
		throw $exception;
	}
  }

  private function _ambilProvinsiByKabupatenId(): Collection
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
		$this->_setBorderNamaKabupaten($sheet, $index, $styleArray);

		if ($index > 1) {
			$this->_setBorderProvinsi($sheet, $index, $styleArray);
		}

		$index++;
	}
  }
  
  private function _setBorderNamaKabupaten(Worksheet $sheet, int $index, array $styleArray): void
  {
	$namaKabupaten = $sheet->getCell('A1');
	$namaKabupaten->getStyle()->getFont()->setBold(true);
  }

  private function _setBorderProvinsi(Worksheet $sheet, int $index, array $styleArray): void
  {
	$cellA = $sheet->getCell("A$index");
	$cellA->getStyle()->applyFromArray($styleArray);
  }
}