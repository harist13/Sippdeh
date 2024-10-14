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
    if ($this->kabupatenId == 0) {
        $kecamatan = Kecamatan::all();
    } else {
        $kecamatan = Kecamatan::whereKabupatenId($this->kabupatenId)->get();
    }
    
    return view('exports.kecamatan', compact('kecamatan'));
  }
}