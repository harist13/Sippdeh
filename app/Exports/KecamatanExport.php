<?php

namespace App\Exports;

use App\Models\Kecamatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KecamatanExport implements FromView {
  private $kabupatenId;

  public function __construct(int $kabupatenId) {
    $this->kabupatenId = $kabupatenId;
  }
  
  public function view(): View {
    $kecamatan = Kecamatan::whereKabupatenId($this->kabupatenId)->get();
    return view('exports.kecamatan', compact('kecamatan'));
  }
}