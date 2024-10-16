<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProvinsiExport implements FromView, WithStyles {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
	$kabupaten = null;

	if ($this->kabupatenId == 0) {
		$provinsi = Provinsi::selectRaw('UPPER(nama) AS nama')->get();
	} else {
		$kabupaten = Kabupaten::selectRaw('UPPER(nama) AS nama')->find($this->kabupatenId);
        $provinsi = Provinsi::selectRaw('UPPER(nama) AS nama')
			->whereHas('kabupaten', fn (Builder $builder) => $builder->where('id', $this->kabupatenId))
			->get();
    }
    
    return view('exports.provinsi', compact('kabupaten', 'provinsi'));
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
		$namaKabupaten = $sheet->getCell('A1');
		$namaKabupaten->getStyle()->getFont()->setBold(true);

		if ($index > 1) {
			$cellA = $sheet->getCell("A$index");
			$cellA->getStyle()->applyFromArray($styleArray);
		}

		$index++;
	}
  }
}