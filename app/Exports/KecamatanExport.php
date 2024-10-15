<?php

namespace App\Exports;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KecamatanExport implements FromView, WithStyles {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
	$kabupaten = null;

	if ($this->kabupatenId == 0) {
		$kecamatan = Kecamatan::selectRaw('id, UPPER(nama) AS nama')->get();
	} else {
		$kabupaten = Kabupaten::selectRaw('id, UPPER(nama) AS nama')->find($this->kabupatenId);
        $kecamatan = Kecamatan::selectRaw('id, UPPER(nama) AS nama')->whereKabupatenId($this->kabupatenId)->get();
    }
    
    return view('exports.kecamatan', compact('kabupaten', 'kecamatan'));
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

			$cellB = $sheet->getCell("B$index");
			$cellB->getStyle()->applyFromArray($styleArray);
		}

		$index++;
	}
  }
}